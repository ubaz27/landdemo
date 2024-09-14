<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Lga;
use File;



class LgasTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $json = File::get("database/data/lga.json");
        $data = json_decode($json, true);

        foreach ($data as $obj) {
            Lga::query()->updateOrCreate([
                'id' => $obj['id'],
                'lga' => $obj['lga'],
                'state_id' => $obj['state_id'],
            ]);
        }
    }
}
