<?php

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\User::class)->create([
            'email' => 'user1@lenkeo.com',
            'password' => app('hash')->make('1234')
        ]);

        factory(App\User::class)->create([
            'email' => 'user2@lenkeo.com',
            'password' => app('hash')->make('1234')
        ]);

        factory(App\User::class)->create([
            'email' => 'user3@lenkeo.com',
            'password' => app('hash')->make('1234')
        ]);
    }
}
