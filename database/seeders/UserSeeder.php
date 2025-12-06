<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $firstNames = ['Emma', 'Sophia', 'Olivia', 'Ava', 'Isabella', 'Mia', 'Charlotte', 'Amelia', 'Harper', 'Evelyn',
                       'Abigail', 'Emily', 'Elizabeth', 'Avery', 'Sofia', 'Ella', 'Madison', 'Scarlett', 'Victoria', 'Grace'];
        $lastNames = ['Johnson', 'Williams', 'Brown', 'Davis', 'Martinez', 'Garcia', 'Rodriguez', 'Wilson', 'Anderson', 'Taylor',
                      'Thomas', 'Moore', 'Jackson', 'Martin', 'Lee', 'Harris', 'Clark', 'Lewis', 'Walker', 'Hall'];
        
        $cities = [
            ['latitude' => 40.7128, 'longitude' => -74.0060],   // New York
            ['latitude' => 34.0522, 'longitude' => -118.2437],  // Los Angeles
            ['latitude' => 41.8781, 'longitude' => -87.6298],   // Chicago
            ['latitude' => 29.7604, 'longitude' => -95.3698],   // Houston
            ['latitude' => 33.7490, 'longitude' => -84.3880],   // Atlanta
            ['latitude' => 39.7392, 'longitude' => -104.9903],  // Denver
            ['latitude' => 47.6062, 'longitude' => -122.3321],  // Seattle
            ['latitude' => 37.7749, 'longitude' => -122.4194],  // San Francisco
            ['latitude' => 42.3601, 'longitude' => -71.0589],   // Boston
            ['latitude' => 33.4484, 'longitude' => -112.0742],  // Phoenix
        ];

        for ($i = 0; $i < 100; $i++) {
            $firstName = $firstNames[array_rand($firstNames)];
            $lastName = $lastNames[array_rand($lastNames)];
            $location = $cities[array_rand($cities)];
            
            User::create([
                'name' => $firstName . ' ' . $lastName,
                'email' => strtolower($firstName . '.' . $lastName . $i . '@example.com'),
                'password' => Hash::make('password'),
                'age' => rand(18, 45),
                'pictures' => [
                    'https://via.placeholder.com/400x500?text=' . urlencode($firstName) . '+1',
                    'https://via.placeholder.com/400x500?text=' . urlencode($firstName) . '+2',
                ],
                'location' => $location,
            ]);
        }
    }
}
