<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class patchUser extends Mailable
{
    use Queueable, SerializesModels;

    private $emailAdmin;
	private $oldUser;
	private $newUser;

    public function __construct($emailAdmin, $oldUser, $newUser)
    {
    	$this->emailAdmin = $emailAdmin;
    	$this->oldUser = $oldUser;
    	$this->newUser = $newUser;
    }

    public function build()
    {
        return $this->view('emails.layouts.MailPatchUser', ['email' => $this->emailAdmin,
    														'oldUser' => $this->oldUser,
    														'newUser' => $this->newUser]);
    }
}
