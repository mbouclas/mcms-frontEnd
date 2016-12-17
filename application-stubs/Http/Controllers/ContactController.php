<?php

namespace FrontEnd\Http\Controllers;


use Config;
use FrontEnd\Jobs\SendMail;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Lang;
use Mail;

class ContactController extends BaseController
{
    use DispatchesJobs;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {

    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('contact');
    }

    public function post(Request $request)
    {
        //validate and send mail
        $data = $request->all();
        $job = (new SendMail('emails.contact', [
            'from' => [
                'name' => $data['name'],
                'email' => $data['email']
            ],
            'to' => [
                'name' => Config::get('mail.from.name'),
                'email' => Config::get('mail.from.address')
            ],
            'subject' => Lang::get('emails.contactForm.subject', [
                'siteName' => Config::get('core.siteName')
            ]),
            'message' => $data
        ]))
            ->onQueue('emails');


        $this->dispatch($job);

        return ['message' => true ];
    }
}
