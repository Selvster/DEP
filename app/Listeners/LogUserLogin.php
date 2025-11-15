<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Login;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class LogUserLogin
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(Login $event): void
    {
        // Log user login activity
        activity()
            ->causedBy($event->user)
            ->performedOn($event->user)
            ->withProperties([
                'action' => 'login',
                'user_name' => $event->user->name,
                'user_email' => $event->user->email,
                'login_time' => now()->toDateTimeString(),
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
            ])
            ->log('تم تسجيل دخول المستخدم: ' . $event->user->name);
    }
}
