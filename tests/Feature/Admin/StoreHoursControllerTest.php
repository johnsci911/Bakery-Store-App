<?php

use App\Models\StoreHours;
use App\Models\User;
use Inertia\Testing\AssertableInertia as Assert;

beforeEach(function () {
    $this->user = User::factory()->create(['is_admin' => true]);
});

test('admin can view store hours configuration page', function () {
    $storeHours = [
        'monday' => '8:00 AM - 12:00 PM, 12:45 PM - 4:00 PM',
        'wednesday' => '9:00 AM - 5:00 PM',
        'friday' => '8:00 AM - 12:00 PM, 1:00 PM - 5:00 PM',
        'saturday' => '10:00 AM - 3:00 PM',
    ];

    foreach ($storeHours as $day => $hours) {
        StoreHours::create(['day' => $day, 'hours' => $hours]);
    }

    $response = $this->actingAs($this->user)
        ->get(route('admin.store-hours.edit'));

    $response->assertStatus(200);
    $response->assertInertia(fn (Assert $page) => $page
        ->component('admin/ConfigureStoreHours')
        ->has('storeHours', 4)
        ->where('storeHours', $storeHours)
    );
});

test('admin can update store hours', function () {
    $updatedHours = [
        'monday' => '9:00 AM - 1:00 PM, 2:00 PM - 6:00 PM',
        'wednesday' => '8:00 AM - 4:00 PM',
        'friday' => '10:00 AM - 2:00 PM, 3:00 PM - 7:00 PM',
        'saturday' => '11:00 AM - 4:00 PM',
        'isEveryOtherSaturday' => true,
    ];

    $response = $this->actingAs($this->user)
        ->post(route('admin.store-hours.update'), $updatedHours);

    $response->assertRedirect();
    $response->assertSessionHas('success', 'Store hours updated successfully.');

    // Check if the database was updated correctly
    foreach (['monday', 'wednesday', 'friday', 'saturday'] as $day) {
        $this->assertDatabaseHas('store_hours', [
            'day' => $day,
            'hours' => $updatedHours[$day],
            'is_every_other' => $day === 'saturday' ? true : false,
        ]);
    }
});

test('non-admin users cannot access store hours configuration', function () {
    $regularUser = User::factory()->create(['is_admin' => false]);

    $response = $this->actingAs($regularUser)
        ->get(route('admin.store-hours.edit'));

    $response->assertStatus(403);
});
