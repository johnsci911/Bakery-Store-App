<?php

use App\Models\StoreHours;

const API_ENDPOINT = '/api/store-hours';
const MONDAY_HOURS = '8:00 AM - 12:00 PM, 12:45 PM - 4:00 PM';
const WEDNESDAY_HOURS = '9:00 AM - 5:00 PM';
const FRIDAY_HOURS = '8:00 AM - 12:00 PM, 1:00 PM - 5:00 PM';
const SATURDAY_HOURS = '10:00 AM - 3:00 PM';

beforeEach(function () {
    $this->sampleStoreHours = [
        'monday' => MONDAY_HOURS,
        'wednesday' => WEDNESDAY_HOURS,
        'friday' => FRIDAY_HOURS,
        'saturday' => SATURDAY_HOURS,
    ];
});

test('store hours can be retrieved', function () {
    foreach ($this->sampleStoreHours as $day => $hours) {
        StoreHours::create([
            'day' => $day,
            'hours' => $hours,
        ]);
    }

    $response = $this->getJson(API_ENDPOINT);

    $response->assertStatus(200)
        ->assertJson($this->sampleStoreHours);
});

test('empty array is returned when no store hours exist', function () {
    $response = $this->getJson(API_ENDPOINT);

    $response->assertStatus(200)
        ->assertJson([]);
});

test('store hours are correctly formatted with multiple time ranges', function () {
    StoreHours::create([
        'day' => 'monday',
        'hours' => MONDAY_HOURS,
    ]);

    $response = $this->getJson(API_ENDPOINT);

    $response->assertStatus(200)
        ->assertJsonStructure(['monday'])
        ->assertJson([
            'monday' => MONDAY_HOURS,
        ]);
});

test('only existing days are returned', function () {
    StoreHours::create([
        'day' => 'monday',
        'hours' => MONDAY_HOURS,
    ]);

    $response = $this->getJson(API_ENDPOINT);

    $response->assertStatus(200)
        ->assertJsonCount(1)
        ->assertJsonMissing(['tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday']);
});

test('store hours with single and multiple time ranges are handled correctly', function () {
    foreach ($this->sampleStoreHours as $day => $hours) {
        StoreHours::create([
            'day' => $day,
            'hours' => $hours,
        ]);
    }

    $response = $this->getJson(API_ENDPOINT);

    $response->assertStatus(200)
        ->assertJson($this->sampleStoreHours)
        ->assertJsonCount(4);
});
