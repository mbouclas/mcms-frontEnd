<?php

namespace FrontEnd\Jobs;
use App\Jobs\Job;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Mail;

class SendMail extends Job implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    protected $view;
    protected $message;
    protected $data;

    public function __construct($view, $data)
    {
        $this->view = $view;
        $this->data = $data;
    }

    public function handle()
    {
        Mail::send($this->view, ['messageData' => $this->data['message']], function ($m) {
            $m->from($this->data['from']['email'], $this->data['from']['name']);

            $m->to($this->data['to']['email'], $this->data['to']['name'])
                ->subject($this->data['subject']);
        });
    }
}
