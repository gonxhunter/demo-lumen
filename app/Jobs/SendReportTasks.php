<?php

namespace App\Jobs;

use App\Models\Task;
use Illuminate\Support\Facades\Log;
use App\Services\TaskService;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use App\Mail\ReportTaskEmail;

class SendReportTasks extends Job
{

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct() {
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $users = User::all();
        foreach ($users as $user) {
            $overDueDateTasks = $user->task()->where('due_date', '<', date('Y-m-d'))->get();
            if ($number = $overDueDateTasks->count()) {
                // Send email if user has overdue date task
                Mail::to($user->email)
                    ->cc('peter.c@webprovise.com')
                    ->bcc('cody.t@webprovise.com')
                    ->send(new ReportTaskEmail($overDueDateTasks));
            }
        }
    }
}
