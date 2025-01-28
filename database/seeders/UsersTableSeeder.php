<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Address;
use App\Models\Photo;
use App\Models\User;
use App\Models\UserDenomination;
use App\Models\UserInterest;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $faker = \Faker\Factory::create('pt_BR');

        for ($i = 0; $i < 10; $i++) {
            $user = User::create([
                'name' => $faker->name(),
                'email' => $faker->unique()->safeEmail(),
                'password' => bcrypt('password'),
                'phone_number' => $faker->phoneNumber(),
                'gender' => $i < 5 ? 'male' : 'female',
                'bio' => $faker->sentence(),
                'is_active' => false,
                'accepted_terms' => true,
                'date_of_birth' => $faker->date('Y-m-d', '-18 years'),
            ]);

            UserDenomination::create([
                'user_id' => $user->id,
                'denomination_id' => 7,
            ]);

            UserInterest::create([
                'user_id' => $user->id,
                'interest_id' => 1,
            ]);

            Photo::create([
                'user_id' => $user->id,
                'photo_name' => '1733099452247-ffeb7e87-8c6d-426b-bcdb-764fc365bd95',
            ]);

            Address::create([
                'user_id' => $user->id,
                'state' => 'RJ',
                'city' => $faker->randomElement([
                    'Belford Roxo',
                    'Rio de Janeiro',
                    'Niterói',
                    'Duque de Caxias',
                    'Nova Iguaçu',
                ]),
                'lat' => $faker->randomFloat(6, -23.0, -22.0),
                'long' => $faker->randomFloat(6, -44.0, -42.0),
            ]);
        }
    }
}
