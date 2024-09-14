<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(LgasTableSeeder::class);
        $this->call(StatesTableSeeder::class);
        $this->call(PositionsTableSeeder::class);
        $this->call(AdminTableSeeder::class);
        $this->call(OrganisationsTableSeeder::class);
        $this->call(ContactsTableSeeder::class);
        $this->call(MissionsTableSeeder::class);
        $this->call(VisionsTableSeeder::class);
        $this->call(AgentTableSeeder::class);
    }
}
