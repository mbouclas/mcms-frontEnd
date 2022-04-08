<?php

namespace Mcms\FrontEnd\Http\Controllers;
use Illuminate\Routing\Controller as BaseController;
use Mcms\FrontEnd\Services\SsgService;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Illuminate\Support\Facades\Redis;

class SsgController extends BaseController
{
    protected $service;
    protected $redisKey;

    public function __construct(SsgService $service)
    {
        $this->redisKey = env('APP_NAME') . '_builder';
        $this->service = $service;
        $this->middleware('sse')->only('getDataStream');
    }

    public function index(): \Illuminate\Http\JsonResponse
    {
        return response()->json($this->service->all());
    }

    public function startBuild()
    {
        $client = new Client();
        $model = $this->service->store(auth()->id());

        $client->post(env('SSG_BUILDER_URL'), [
            'json' => [
                'token' => $model->token,
                'id' => env('SSG_ID'),
                'jobId' => $model->id,
            ]
        ]);

        return $model;
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

    public function getDataStream(Request $request){
        $response = new StreamedResponse();

        $response->headers->set('Content-Type', 'text/event-stream');
        $response->headers->set('Cache-Control', 'no-cache');
        $response->headers->set('Connection', 'keep-alive');
        $response->headers->set('X-Accel-Buffering', 'no');

        $response->setCallback(function ()  {
            $data = Redis::get($this->redisKey);
            $data = json_decode($data);

            if (empty($data)) {
                $data = [];
            }

            $event = 'message';
            $id = 1;
            Redis::del($this->redisKey);
            echo 'id: ' . $id . "\n";
            echo 'event: ' . $event . "\n";
            echo 'data: ' . json_encode($data) . "\n\n";
        });

        return $response->send();


    }
}
