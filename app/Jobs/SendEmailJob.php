<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class SendEmailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $view;
    protected $data;
    protected $to;
    protected $subject;

    /**
     * Create a new job instance.
     */
    public function __construct($view, $data, $to, $subject)
    {
        $this->view = $view;
        $this->data = $data;
        $this->to = $to;
        $this->subject = $subject;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            Mail::send($this->view, $this->data, function ($message) {
                $message->to($this->to);
                $message->subject($this->subject);
            });
        } catch (\Exception $e) {
            Log::error('SendEmailJob Error: ' . $e->getMessage());
            // We don't throw exception here to avoid infinite retries if the SMTP is broken, 
            // but in a real app we might want to retry a few times.
        }
    }
}
