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
        \App\User::query()->create([
            'email' => 'user@devolon.fi',
            'name' => 'A Devolon user',
            'password' => \Illuminate\Support\Facades\Hash::make("123456"),
            'email_verified_at' => \Illuminate\Support\Carbon::now(),
        ]);
    }
}
