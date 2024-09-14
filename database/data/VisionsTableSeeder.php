<?php

namespace Database\Seeders;

use App\Models\Vision;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use File;
class VisionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
       
   
        $json = File::get("database/data/visions.json");
        $data = json_decode($json,true);
        foreach($data as $obj)
        {
            Vision::query()->updateOrCreate([
                "id"=>$obj['id'],
                "vision"=>$obj['vision'],
            ]);
    
        }
       
    }
}
