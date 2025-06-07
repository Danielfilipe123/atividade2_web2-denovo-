<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Faker\Factory as FakerFactory;

class DatabaseSeeder extends Seeder
{
    
    public function run()
    {

        User::create([
            'name' => 'Admin',
            'email' => 'admin@admin.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
        ]);

        User::create([
            'name' => 'Bibliotecário',
            'email' => 'bibliotecario@bibliotecario.com',
            'password' => bcrypt('password'),
            'role' => 'bibliotecario',
        ]);

        User::create([
            'name' => 'Cliente',
            'email' => 'cliente@gmail.com',
            'password' => bcrypt('password'),
            'role' => 'cliente',
        ]);

        // User::factory(10)->create();
        FakerFactory::create()->unique(true);
        $this->call([
            CategorySeeder::class,
            AuthorPublisherBookSeeder::class,
            UserBorrowingSeeder::class,
        ]);


    }

    }


