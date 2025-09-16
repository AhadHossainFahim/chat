<?php

namespace Database\Seeders;

use App\Models\Chat;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\User;
use App\Models\Ticket;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->createMany([
            [
                'name' => 'Ahad Hossain',
                'email' => 'ahadh32@gmail.com',
                'password' => bcrypt('12345678'),
                'role' => 'developer',
            ],
            [
                'name' => 'Jakia Sultana Joya Moni',
                'email' => 'jakia.joya7@gmail.com',
                'password' => bcrypt('12345678'),
                'role' => 'client',
            ],
        ]);

        Ticket::factory(1)->create();

        Chat::factory(1)->create();
    }
}
