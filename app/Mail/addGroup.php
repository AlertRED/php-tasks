<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class addGroup extends Mailable
{
    use Queueable, SerializesModels;

    private $group;

    public function __construct($group)
    {
    	$this->group = $group;
    }

    public function build()
    {
        return $this->view('emails.layouts.MailAddGroup', ['name' => $this->group['name']]);
    }
}
