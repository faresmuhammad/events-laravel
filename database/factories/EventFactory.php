<?php

namespace Database\Factories;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Event>
 */
class EventFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => 'Test Event',
            'description' => "description",
            'start_date' => now(),
            'end_date' => Carbon::tomorrow(),
            'location' => 'Alexandria,sdsd',
            'location_coordinates' => [
                'latitude' => 32.9878378,
                'longitude' => -113.876467
            ],
            'user_id' => 1,
            'profile_id' => 1,
            'is_live' => true,
            'event_image' => null
        ];
    }
}
