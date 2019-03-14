<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class SendEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $user;
    private $Mail;

    public function __construct($user, $Mail)
    {
        $this->user = $user;
        $this->Mail = $Mail;
    }

    public function handle()
    {
        \Mail::to($this->user)->send($this->Mail); 
    }
}
