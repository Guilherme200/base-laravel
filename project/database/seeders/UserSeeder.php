<?php

namespace Database\Seeders;

use App\Domain\User\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        User::query()->create([
           'name' => 'Admin',
           'email' => 'admin@email.com',
           'password' => '12345678'
        ]);
    }
}
