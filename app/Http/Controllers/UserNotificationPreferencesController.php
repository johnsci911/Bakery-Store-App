<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Notifications\StoreReopenedNotification;

class UserNotificationPreferencesController extends Controller
{
    public function edit(Request $request)
    {
        $user = $request->user();
        $notificationPreferences = $user->notificationPreferences()
            ->where('notification_type', StoreReopenedNotification::TYPE)
            ->first();

        return Inertia::render('profile/NotificationPreferences', [
            'notificationPreferences' => [
                'store_reopen' => $notificationPreferences ? $notificationPreferences->is_enabled : false,
            ],
        ]);
    }

    public function update(Request $request)
    {
        $request->validate([
            'store_reopen_notification' => 'required|boolean',
        ]);

        $user = $request->user();
        $user->notificationPreferences()->updateOrCreate(
            ['notification_type' => StoreReopenedNotification::TYPE],
            ['is_enabled' => $request->store_reopen_notification]
        );

        return back()->with('success', 'Notification preferences updated successfully.');
    }
}
