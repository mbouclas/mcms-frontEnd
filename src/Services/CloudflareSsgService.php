<?php

namespace Mcms\FrontEnd\Services;

use GuzzleHttp\Client;
use Mcms\FrontEnd\Models\SsgBuildHistoryModel;

class CloudflareSsgService
{
    public $id = 'cloudflare';
    public $name = 'Cloudlfare Builder';
    public $cacheKeyName = 'ssg.build';
    protected $model;
    protected $baseUrl = 'https://api.cloudflare.com/client/v4/accounts/';
    protected $projectName;

    public function __construct()
    {
        $this->projectName = env('CLOUDFLARE_PROJECT_NAME');
        $this->model = new SsgBuildHistoryModel();
    }

    protected function headers(): array
    {
        return [
            'X-Auth-Email' => env('CLOUDFLARE_EMAIL'),
            'X-Auth-Key' => env('CLOUDFLARE_KEY'),
        ];
    }

    protected function get($url) {
        $client = new Client();
        try {
            $res = $client->request('GET',$this->baseUrl.env('CLOUDFLARE_ACCOUNT_ID').'/'.$url, [
                'headers'=> $this->headers(),
            ]);

            return json_decode($res->getBody());
        }
        catch (\Exception $exception) {
            return ['success'=> false, 'message'=> $exception->getMessage()];
        }
    }

    protected function post($url, $body = [])
    {
        $client = new Client();
        try {
            $res = $client->request('POST',$this->baseUrl.env('CLOUDFLARE_ACCOUNT_ID').'/'.$url,[
                'json' => json_encode($body),
                'headers'=> $this->headers(),
            ]);

            return json_decode($res->getBody());
        }
        catch (\Exception $exception) {
            return ['success'=> false, 'message'=> $exception->getMessage()];
        }
    }

    public function deployments()
    {
        $url = "pages/projects/".$this->projectName ."/deployments";
        $res = $this->get($url);

        return $res->result;
    }

    /**
     * Get all deployments
     * @return mixed
     */
    public function all()
    {
        $collection = collect($this->deployments());
        return $collection->map(function ($deployment) {
            $status = $deployment->latest_stage->status;
            // Make it compatible with local

            switch ($status) {
                case 'success': $status = 'completed';
                break;
                case 'failure': $status = 'failed';
                break;
            }
           return [
               'run_at' => $deployment->created_on,
               'id' => $deployment->id,
               'status' => $status,
               'name' => $deployment->latest_stage->name,
           ];
        });
    }

    public function allProjects()
    {
        $url = 'pages/projects';
        $res = $this->get($url);

        return $res->result;
    }

    public function store($userId, $provider = 'cloudinary')
    {

    }

    public function update($jobId, $data)
    {

    }

    /**
     * Send a build request to cloudlare
     * @return int[]
     */
    public function startBuild()
    {
        $url = "pages/projects/".env('CLOUDFLARE_PROJECT_NAME')."/deployments";

        $res = $this->post($url);

        return $res->result;
    }

    public function getDeployment($id)
    {
        $url = "pages/projects/".env("CLOUDFLARE_PROJECT_NAME")."/deployments/".$id;
        $res = $this->get($url);

        $item = $res->result;

        $state = $item->latest_stage->name;
        /**
         * Map incoming state changes to match our local interface
         */
        switch ($item->latest_stage->name) {
            case 'initialize': $state = 'started';
                break;
            case 'build': $state = 'progress';
                break;
        }

        if ($item->latest_stage->name == 'build' && $item->latest_stage->status == 'failure') {
            $state = 'failed';
        }

        if ($item->latest_stage->name == 'build' && $item->latest_stage->status == 'canceled') {
            $state = 'failed';
        }

        if ($item->latest_stage->name == 'deploy' && $item->latest_stage->status == 'success') {
            $state = 'completed';
        }


        $item->state = $state;

        return $item;
    }

    public function getDeploymentUpdates($id)
    {
        //Hit cloudflare to get the update and return the latest_stage
        // If the latest stage name == 'deploy' then we're done
        return $this->getDeployment($id);

    }
}
