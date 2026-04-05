<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::updateOrCreate(
            ['email' => 'test@example.com'],
            [
                'name' => 'Test User',
                'password' => 'password',
            ]
        );

        $this->call(PropertyDummyDataSeeder::class);
        $this->call(BlogDummyDataSeeder::class);
        $this->call(GalleryDummyDataSeeder::class);
        $this->call(ProjectDummyDataSeeder::class);
        $this->call(ExclusiveResaleDummyDataSeeder::class);
    }
}
