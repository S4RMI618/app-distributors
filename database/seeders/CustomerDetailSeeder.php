<?php

namespace Database\Seeders;

use App\Models\CustomerDetail;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CustomerDetailSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        CustomerDetail::create([
            'identification' => '1110225587',
            'full_name' => 'John Doe',
            'email' => 'john.doe@example.com',
            'phone' => '1234567890',
            'address' => '123 Elm St, Springfield',
            'company_id' => 1,
        ]);

        CustomerDetail::create([
            'identification' => '1110225588',
            'full_name' => 'Jane Smith',
            'email' => 'jane.smith@example.com',
            'phone' => '0987654321',
            'address' => '456 Oak St, Springfield',
            'company_id' => 1,
        ]);

        CustomerDetail::create([
            'identification' => '1110225589',
            'full_name' => 'Emily Johnson',
            'email' => 'emily.johnson@example.com',
            'phone' => '2345678901',
            'address' => '789 Pine St, Springfield',
            'company_id' => 1,
        ]);

        CustomerDetail::create([
            'identification' => '1110225590',
            'full_name' => 'Michael Brown',
            'email' => 'michael.brown@example.com',
            'phone' => '3456789012',
            'address' => '321 Maple St, Springfield',
            'company_id' => 1,
        ]);

        CustomerDetail::create([
            'identification' => '1110225591',
            'full_name' => 'Lisa White',
            'email' => 'lisa.white@example.com',
            'phone' => '4567890123',
            'address' => '654 Cedar St, Springfield',
            'company_id' => 1,
        ]);
    }
}
