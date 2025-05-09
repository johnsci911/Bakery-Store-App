<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\StoreHours;
use Illuminate\Http\Request;
use Inertia\Inertia;

class StoreHoursController extends Controller
{
    public function edit()
    {
        $storeHours = StoreHours::pluck('hours', 'day')
            ->toArray();

        return Inertia::render('admin/ConfigureStoreHours', [
            'storeHours' => $storeHours,
        ]);
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'monday' => 'required|string',
            'wednesday' => 'required|string',
            'friday' => 'required|string',
            'saturday' => 'required|string',
            'isEveryOtherSaturday' => 'boolean',
        ]);

        $days = ['monday', 'wednesday', 'friday', 'saturday'];

        foreach ($days as $day) {
            StoreHours::updateOrCreate(
                ['day' => $day],
                [
                    'hours' => $validated[$day],
                    'is_every_other' => $day === 'saturday' ? $validated['isEveryOtherSaturday'] : false,
                ]
            );
        }

        return back()->with('success', 'Store hours updated successfully.');
    }
}
