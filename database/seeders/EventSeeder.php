<?php

namespace Database\Seeders;

use App\Models\Event;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class EventSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Event::create([
            'title' => 'Posh Peony Events',
            'description' => 'Study in USA - Spot Admission Assessment by Dr Pau Foster, MSUB, is the must go event for Study in USA aspirants. In this event students and parents will have an opportunity to meet Dr Paul Foster from MSUB and discuss the career opportunities. MSUB has wide range of Degree programs with affordable Tuition Fee and Scholarships.',
            'start_date' => Carbon::parse('July 25, 2023, 09:00 AM'),
            'end_date' => Carbon::parse('July 25, 2023, 02:00 PM'),
            'location' => 'Amazon USA',
            'location_coordinates' => [
                'latitude' => 37.05606690938613,
                'longitude' => -114.81010944415826
            ],
//            'user_id' => auth()->id(),
//            'organization_id' => request('organization_id'),
        ]);
        Event::create([
            'title' => 'Slate & Crystal Events',
            'description' => "The Leicester Mercury Champion and multi award-winner, Jack Gleadowreturns with more old-school comedy for the modern age. He brings jokes,gags, stories and funny sentences. The best thing to come out of Hull since theM62. 'Mainstream appeal, he'll be as big as Lee Evans oneday' (BeyondTheJoke.co.uk). 'An absolute natural' (Chortle.co.uk). Winner:Amused Moose National New Comic, Great Yorkshire Fringe Comedian of TheYear, Hull Comedian of The Year. Nominated: Chortle Best Newcomer, LeicesterComedy Fest Best Debut Show, Edinburgh TV Festival Future Presenter.",
            'start_date' => Carbon::parse('October 7, 2023, 10:00 AM'),
            'end_date' => Carbon::parse('October 13, 2023, 01:00 PM'),
            'location' => 'Amazon USA',
            'location_coordinates' => [
                'latitude' => 37.05606690938613,
                'longitude' => -114.81010944415826
            ],
//            'user_id' => auth()->id(),
//            'organization_id' => request('organization_id'),
        ]);
    }
}
