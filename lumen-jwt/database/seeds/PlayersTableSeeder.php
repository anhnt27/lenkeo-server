<?php

use Illuminate\Database\Seeder;

class PlayersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\Models\Player::class)->create([
            'email' => 'lenkeoapp@gmail.com',
            'is_admin' => true,
            'name' => 'Admin'
        ]);

        factory(App\Models\Player::class)->create([
            'email' => 'player1@lenkeo.com',
        ]);

        factory(App\Models\Player::class)->create([
            'email' => 'player2@lenkeo.com',
        ]);

        factory(App\Models\Player::class)->create([
            'email' => 'player3@lenkeo.com',
        ]);
    }
}
