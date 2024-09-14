<?php

namespace Database\Seeders;

use App\Models\Agent;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AgentTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Agent::updateOrCreate(
            [
                'id' => 1,
            ],
            [
                'name' => 'No Agent',
                'phone' => '08000000000',
                'lga' => 'No LGA',

            ]
        );
    }
}
