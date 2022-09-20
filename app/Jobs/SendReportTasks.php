<?php

namespace App\Jobs;

use Illuminate\Support\Facades\Log;
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
            if ($overDueDateTasks->count() > 0) {
                // Send email if user has overdue date task
                try {
                    Mail::to($user->email)
                        ->cc(env('MAIL_CC_ADDRESS'))
                        ->bcc(env('MAIL_BCC_ADDRESS'))
                        ->send(new ReportTaskEmail($overDueDateTasks));
                } catch (\Exception $e) {
                    Log::info($e->getMessage());
                }
            }
        }
    }
}
