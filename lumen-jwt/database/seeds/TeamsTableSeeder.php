<?php

use Illuminate\Database\Seeder;

class TeamsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\Models\Team::class)->create([
            'name' => 'Manchester United',
        ]);
        factory(App\Models\Team::class)->create([
            'name' => 'Real Marid',
        ]);
        factory(App\Models\Team::class)->create([
            'name' => 'Dortmund',
        ]);

    }
}
