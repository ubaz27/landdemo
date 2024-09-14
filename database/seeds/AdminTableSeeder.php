<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\Admin;
use File;

class AdminTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $json = File::get("database/data/admins.json");
        $data = json_decode($json, true);

        foreach ($data as $obj) {
            Admin::query()->updateOrCreate([
                'id' => $obj['id'],
                'name' => $obj['name'],
                'email' => $obj['email'],
                'position_id' => $obj['position_id'],
                'password' => Hash::make($obj['password']),
            ]);
        }
    }
}
