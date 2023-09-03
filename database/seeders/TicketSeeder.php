<?php

namespace Database\Seeders;

use App\Models\Ticket\Ticket;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class TicketSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Ticket::create([
            'title' => 'Posh Poeny Ticket',
            'description' => null,
            'price' => 50,
            'quantity_available' => 100,
            'quantity_sold' => 0,
            'quantity_attended' => 0,
            'start_sale_date' => Carbon::parse('July 10, 2023, 9:00 AM'),
            'end_sale_date' => Carbon::parse('July 10, 2023, 9:00 PM'),
            'is_hidden' => true,
            'user_id' => 1,
            'event_id' => 1
        ]);
        Ticket::create([
            'title' => 'Posh Ticket 2',
            'description' => null,
            'price' => 50,
            'quantity_available' => 100,
            'start_sale_date' => Carbon::parse('July 10, 2023, 9:00 AM'),
            'end_sale_date' => Carbon::parse('July 10, 2023, 9:00 PM'),
            'user_id' => 1,
            'event_id' => 1
        ]);
    }
}
