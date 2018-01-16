<?php

namespace App\Auth\Traits;

use Mail;
use App\Mail\MagicLoginRequested;

use App\UserLoginToken;

trait MagicallyAuthenticatable{
    /*
     *This is the method that generates and stores a token to the users_login_tokens table
     */

    public function storeToken(){
        // Deleting any existing token...
        $this->token()->delete();

        // Generating a new token...
        $this->token()->create([
            'token' => str_random(255)
        ]);
        
        return $this;
    }

    public function sendMagicLink(array $options){
        Mail::to($this)->send(new MagicLoginRequested($this, $options));
    }

    public function token(){
        return $this->hasOne(UserLoginToken::class);
    }
}