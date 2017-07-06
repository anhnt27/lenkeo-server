<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        // $this->call(UsersTableSeeder::class);
        $this->call(PlayersTableSeeder::class);
        $this->call(DistrictsTableSeeder::class);
        $this->call(StadiumsTableSeeder::class);
        $this->call(TeamsTableSeeder::class);
        $this->call(FindingDataSeeder::class);
        $this->call(PropertiesTableSeeder::class);

        Model::reguard();
    }
}
