<?php

namespace App\Providers;

use App\Models\Notification;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;

class ViewServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
        View::composer('*', function ($view) {
            $notifications = [];

            // Determine the logged-in user/guard
            $user = Auth::guard('customer')->user() ?? Auth::guard('web')->user();

            if ($user) {
                $notifications = Notification::where('notifiable_id', $user->id)
                    ->where("read_at", null)
                    ->latest()
                    ->take(5)
                    ->get();

                $view->with(
                    'notificationsCount',
                    Notification::where('notifiable_id', $user->id)
                        ->where("read_at", null)->count()
                );
            }


            $view->with('topNotifications', $notifications);
            //all notifications count

        });
    }
}
