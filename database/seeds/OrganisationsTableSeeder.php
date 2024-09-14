<?php

namespace Database\Seeders;

use App\Models\Organisation;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class OrganisationsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Organisation::updateOrCreate(
            [
                'id' => 1,
            ],
            [
                'name' => 'MAINTROP PROPERTIES LIMITED',
                'short_name' => 'MAINTROP PROPERTIES LIMITED',
                'abbreviation' => 'MAINTROP',
                'state_id' => 1,
            ]
        );
    }
}
