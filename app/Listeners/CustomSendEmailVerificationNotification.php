<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Registered;
use Filament\Notifications\Auth\VerifyEmail;

class CustomSendEmailVerificationNotification
{
    public function handle(Registered $event)
    {
        $user = $event->user;
        $notification = new VerifyEmail;
        $notification->toUser($user);
    }
}