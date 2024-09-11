<?php

namespace App\Filament\Widgets;

use Filament\Widgets\Widget;
use Illuminate\Support\Facades\Auth;

class UserNotificationsWidget extends Widget
{
    protected static ?int $sort = 6;
    protected static string $view = 'filament.widgets.user-notifications-widget';

    public function getNotifications()
    {
        return Auth::user()->unreadNotifications()->take(5)->get();
    }

    public function markAsRead($notificationId)
    {
        Auth::user()->notifications()->find($notificationId)->markAsRead();
    }
}
