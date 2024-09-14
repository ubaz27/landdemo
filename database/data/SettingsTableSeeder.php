<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SettingsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Setting::updateOrCreate(
            [
                'key' => 'organisation_name',
            ],
            [
                'value' => 'MAINTROP PROPERTIES LIMITED',
            ]
        );

        Setting::updateOrCreate(
            [
                'key' => 'organisation_short_name',
            ],
            [
                'value' => 'MAINTROP PROPERTIES LIMITED',
            ]
        );

        Setting::updateOrCreate(
            [
                'key' => 'organisation_abbreviation',
            ],
            [
                'value' => 'MAINTROP',
            ]
        );
    }
}
