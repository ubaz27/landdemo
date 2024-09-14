<?php

namespace Database\Seeders;

use App\Models\Mission;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use File;

class MissionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $json = File::get("database/data/missions.json");
        $data = json_decode($json,true);
        foreach ($data as $obj)
        {
            Mission::query()->updateOrCreate([
                "id"=>$obj['id'],
                "mission"=>$obj['mission'],
                "vision"=>$obj['vision'],
            ]);
            
        }
    }
}
