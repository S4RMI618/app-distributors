<?php

namespace Database\Seeders;

use App\Models\Company;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CompanySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Company::create([
            'nit' => '123456789',
            'name' => 'Sarmiento S.A.S.',
            'phone' => '123456789',
            'address' => 'Calle Falsa 123',
            'department' => 'Tolima',
            'municipality' => 'Ibagué',
        ]);
    }
}
