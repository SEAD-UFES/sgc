<?php

namespace App\Http\Controllers;

use App\Events\NotificationDismissed;
use Illuminate\Http\RedirectResponse;
use Illuminate\Notifications\DatabaseNotification;

class NotificationController extends Controller
{
    /**
     * Mark notification as read.
     *
     * @param  DatabaseNotification  $notification
     *
     * @return RedirectResponse
     */
    public function dismiss(DatabaseNotification $notification): RedirectResponse
    {
        try {
            $notification->markAsRead();
        } catch (\Exception $e) {
            return back()->withErrors(['noDismiss' => 'Não foi possível dispensar a notificação: ' . $e->getMessage()]);
        }

        NotificationDismissed::dispatch($notification);

        return redirect()->route('home')->with('success', 'Notificação dispensada.');
    }
}
