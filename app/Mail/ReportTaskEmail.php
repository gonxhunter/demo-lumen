<?php
namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ReportTaskEmail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $tasks;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($tasks)
    {
        $this->tasks = $tasks;
    }

    public function build()
    {
        return $this->view('mail.task');
    }
}
