<?php

namespace App\Mail;

use App\User;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class MagicLoginRequested extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $options;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(User $user, array $options)
    {
        $this->user     = $user;
        $this->options  = $options;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject("Your magic login link")->view('email.auth.magic.link')->with([
            'link' => $this->buildLink(),
        ]);
    }

    /* 
     * This is the method that builds the magic link using 
     * the token Generated in the MagicallyAuthenticatable trait.
     */

    protected function buildLink(){
        return url('/login/magic/'. $this->user->token->token ."?". http_build_query($this->options));
    }
}
