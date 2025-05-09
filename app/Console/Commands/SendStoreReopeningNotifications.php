<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Models\StoreHours;
use App\Notifications\StoreReopenedNotification;
use Carbon\Carbon;
use Illuminate\Console\Command;

class SendStoreReopeningNotifications extends Command
{
    protected $signature = 'notifications:store-reopening';
    protected $description = 'Send store reopening notifications to users for today';

    public function handle()
    {
        $today = Carbon::now();
        $dayOfWeek = strtolower($today->format('l')); // Get the day of the week

        $storeHours = StoreHours::where('day', $dayOfWeek)->first();

        if (!$storeHours) {
            $this->error("No store hours found for {$dayOfWeek}.");
            return;
        }

        $openingHours = $this->parseHours($storeHours->hours);

        if (empty($openingHours)) {
            $this->error("Invalid store hours format for {$dayOfWeek}.");
            return;
        }

        foreach ($openingHours as $period) {
            $openingTime = $today->copy()->setTimeFromTimeString($period['start']);

            // Only send notifications if the opening time is in the future
            if ($openingTime->isFuture()) {
                User::whereHas('notificationPreferences', function ($query) {
                    $query->where('is_enabled', true);
                })->chunk(100, function ($users) use ($openingTime) {
                    foreach ($users as $user) {
                        $user->notify(new StoreReopenedNotification($openingTime->format('D, F d, Y, g:i A')));
                    }
                });

                $this->info("Notifications sent for opening time: {$openingTime->format('D, F d, Y, g:i A')}");
            } else {
                $this->info("Skipped past opening time: {$openingTime->format('D, F d, Y, g:i A')}");
            }
        }
    }

    private function parseHours($hoursString)
    {
        $periods = explode(',', $hoursString);
        $parsedHours = [];

        foreach ($periods as $period) {
            $times = explode('-', trim($period));
            if (count($times) === 2) {
                $parsedHours[] = [
                    'start' => trim($times[0]),
                    'end' => trim($times[1])
                ];
            }
        }

        return $parsedHours;
    }
}
