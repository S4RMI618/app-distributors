<?php

namespace Database\Seeders;

use App\Models\User;
use Database\Seeders\CustomerDetailSeeder;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        $this->call(CompanySeeder::class);
        $this->call(RoleSeeder::class);
        $this->call(ProductSeeder::class);
        $this->call(CustomerDetailSeeder::class);

        User::factory()->create([
            'name' => 'Hugo Sarmiento',
            'email' => 'huessabe@gmail.com',
            'password' => Hash::make('sarmi618'),
            'role_id'=> 1,
            'company_id' => 1,
        ]);

        User::factory()->create([
            'name' => 'Usuario Distribuidor',
            'email' => 'distribuidor@gmail.com',
            'password' => Hash::make('sarmi618'),
            'role_id'=> 2,
            'company_id' => 1,
        ]);
    }
}
