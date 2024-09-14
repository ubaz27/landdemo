<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\State;
use File;

class StatesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $json = File::get("database/data/states.json");
        $data = json_decode($json, true);

        foreach ($data as $obj) {
            State::query()->updateOrCreate([
                'id' =>$obj['id'],
                'state' => $obj['state'],
            ]);
        }
    }
}
