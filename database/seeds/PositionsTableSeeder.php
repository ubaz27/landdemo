<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use File;
use App\Models\Position;


class PositionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $json = File::get("database/data/positions.json");
        $data = json_decode($json, true);

        foreach ($data as $obj) {
            // dd($obj);
            Position::query()->updateOrCreate(
                [
                    'id' => $obj['id'],
                ],
                [
                    'position' => $obj['position'],
                ]
            );
        }
        //
    }
}
