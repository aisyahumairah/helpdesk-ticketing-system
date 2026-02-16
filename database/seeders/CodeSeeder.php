<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CodeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $codes = [
            // Categories/Ticket Types (comp_type)
            ['type' => 'comp_type', 'code' => 'SS', 'name' => 'Software Support'],
            ['type' => 'comp_type', 'code' => 'HS', 'name' => 'Hardware Support'],
            ['type' => 'comp_type', 'code' => 'NI', 'name' => 'Network Issue'],
            ['type' => 'comp_type', 'code' => 'AA', 'name' => 'Account Access'],
            ['type' => 'comp_type', 'code' => 'OT', 'name' => 'Other'],

            // Urgency
            ['type' => 'urgency', 'code' => 'LOW', 'name' => 'Low'],
            ['type' => 'urgency', 'code' => 'MED', 'name' => 'Medium'],
            ['type' => 'urgency', 'code' => 'HIGH', 'name' => 'High'],
            ['type' => 'urgency', 'code' => 'CRIT', 'name' => 'Critical'],

            // Status
            ['type' => 'ticket_status', 'code' => 'NEW', 'name' => 'New'],
            ['type' => 'ticket_status', 'code' => 'PEND', 'name' => 'Pending'],
            ['type' => 'ticket_status', 'code' => 'CLOSE', 'name' => 'Closed'],
            ['type' => 'ticket_status', 'code' => 'DONE', 'name' => 'Done'],
            ['type' => 'ticket_status', 'code' => 'REOPEN', 'name' => 'Reopen'],
        ];

        foreach ($codes as $code) {
            \App\Models\Code::firstOrCreate(
                ['type' => $code['type'], 'code' => $code['code']],
                ['name' => $code['name']]
            );
        }
    }
}
