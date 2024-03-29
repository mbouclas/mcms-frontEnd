<?php

namespace Mcms\FrontEnd\Services;
use Carbon\Carbon;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Redis;
use Mcms\FrontEnd\Models\SsgBuildHistoryModel;


class SsgService
{
    public $id = 'local';
    public $name = 'Local Builder';
    public $cacheKeyName = 'ssg.build';
    protected $model;

    public function __construct()
    {
        $this->model = new SsgBuildHistoryModel();
    }

    public function all()
    {

        return $this->model
            ->orderBy('run_at', 'desc')
            ->get();

    }

    public function generateToken() {
        return str_random(30);
    }

    public function storeBuildToken($token) {
        Cache::add($this->cacheKeyName, $token, 2);

        return $this;
    }

    public function getFromCache($token)
    {
        return Cache::get($this->cacheKeyName) == $token;
    }

    public function store($userId, $provider = 'astro')
    {

        $token = $this->generateToken();
        $this->storeBuildToken($token);

        $this->model->provider = $provider;
        $this->model->user_id = $userId;
        $this->model->status = 'started';
        $this->model->token = $token;
        $this->model->run_at = Carbon::now();
        $this->model->save();
        return $this->model;
    }

    public function update($jobId, $data)
    {
        $model = $this->model->where(['id' => $jobId]);
        $model->update($data);
        return $model;
    }

    public function startBuild()
    {
        $client = new Client();
        $model = $this->store(auth()->id());

        $client->post(env('SSG_BUILDER_URL'), [
            'json' => [
                'token' => $model->token,
                'id' => env('SSG_ID'),
                'jobId' => $model->id,
            ]
        ]);

        return $model;
    }

    public function getDeploymentUpdates($id)
    {
        $data = Redis::get($id);

        return json_decode($data);
    }
}
