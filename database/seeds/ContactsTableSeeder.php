<?php

namespace Database\Seeders;

use App\Models\Contact;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use File;

class ContactsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $json = File::get("database/data/contacts.json");
        $data =json_decode($json,true);
        foreach ($data as $obj)
        {
            Contact::query()->updateOrCreate([
                "id"=>$obj['id'],
                "email"=>$obj['email'],
                "phone"=>$obj['phone'],
                "address"=>$obj['address'],
                
            ]);
        }
        

    }
}
