<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class patchProfile extends Mailable
{
    use Queueable, SerializesModels;
    
    private $oldProfile;
	private $newProfile;

    public function __construct($oldProfile, $newProfile)
    {
        $this->oldProfile = $oldProfile;
		$this->newProfile = $newProfile;
    }

    public function build()
    {
        return $this->view('emails.layouts.MailPatchProfile', ['id'=>$this->newProfile['id'],
        													   'newName'=>$this->newProfile['name'],
        													   'oldName'=>$this->oldProfile['name']]);
    }
}
