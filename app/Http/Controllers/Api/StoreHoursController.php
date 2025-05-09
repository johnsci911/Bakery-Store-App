<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\StoreHours;

class StoreHoursController extends Controller
{
    public function index()
    {
        $storeHours = StoreHours::pluck('hours', 'day')
            ->toArray();

        return response()->json($storeHours);
    }
}
