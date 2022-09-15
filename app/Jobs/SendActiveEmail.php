<?php

namespace App\Jobs;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Mail\ActiveUserEmail;

class SendActiveEmail extends Job
{
    protected $user;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($user) {
        $this->user = $user;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $user = $this->user;
        //Send registered email here
        Mail::to($user->email)
            ->cc('peter.c@webprovise.com')
            ->bcc('cody.t@webprovise.com')
            ->send(new ActiveUserEmail($user));
    }
}
