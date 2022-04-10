<?php

namespace Mcms\FrontEnd\Http\Controllers;
use Illuminate\Routing\Controller as BaseController;
use Mcms\FrontEnd\Services\CloudflareSsgService;
use Mcms\FrontEnd\Services\SsgService;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Illuminate\Support\Facades\Redis;

class SsgController extends BaseController
{
    protected $service;
    protected $redisKey;
    protected $provider;

    public function __construct(SsgService $service)
    {
        $providerName = env('SSG_DEFAULT_PROVIDER', 'local');

        switch ($providerName) {
            case 'cloudflare': $this->provider = new CloudflareSsgService();
            break;
            default: $this->provider = new SsgService();
        }

        $this->redisKey = env('APP_NAME') . '_builder';
        $this->service = $service;
        $this->middleware('sse')->only('getDataStream');
    }

    public function index(): \Illuminate\Http\JsonResponse
    {
        return response()->json($this->provider->all());
    }

    public function boot(): \Illuminate\Http\JsonResponse
    {
        return response()->json([
            'provider' => $this->provider->id,
        ]);
    }

    public function startBuild()
    {
        return response()->json($this->provider->startBuild());
    }

    /**
     * This is a webhook
     * @return void
     */
    public function onBuildProgress(Request $request)
    {
        Redis::set($this->redisKey, json_encode(['state' => 'progress', 'data' => $request->all()]));
    }

    /**
     * This is a webhook
     * @return void
     */
    public function onBuildFailed(Request $request)
    {
        Redis::set($this->redisKey, json_encode(['state' => 'failed', 'data' => $request->all()]));
        $data = $request->all();

        return $this->service->update($data['payload']['jobId'], ['status' => 'failed']);
    }

    /**
     * This is a webhook

     * @return void
     */
    public function onBuildSuccess(Request $request)
    {
        //Validate incoming token $request->data->payload->jobId
        // Write the post data somewhere
        Redis::set($this->redisKey, json_encode(['state' => 'completed', 'data' => $request->all()]));
        // set the db
        $data = $request->all();

        $this->service->update($data['payload']['jobId'], ['status' => 'completed', 'output'=> $data['output']]);
        return ['success' => true];
    }

    public function store()
    {

    }

    public function show($id)
    {

    }

    public function update($id, $payload)
    {

    }

    public function getDeployment($id)
    {
        return response()->json($this->provider->getDeployment($id));
    }

    public function getDataStream(Request $request, $id){
        $response = new StreamedResponse();

        $response->headers->set('Content-Type', 'text/event-stream');
        $response->headers->set('Cache-Control', 'no-cache');
        $response->headers->set('Connection', 'keep-alive');
        $response->headers->set('X-Accel-Buffering', 'no');

        $response->setCallback(function () use ($id)  {
            $data = $this->provider->getDeploymentUpdates($id);
            if (empty($data)) {
                $data = [];
            }

            $event = 'message';
//            Redis::del($this->redisKey);
            echo 'id: ' . $id . "\n";
            echo 'event: ' . $event . "\n";
            echo 'data: ' . json_encode($data) . "\n\n";
        });

        return $response->send();


    }
}
