<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Customer;
use App\Models\Car;
use App\Models\Rental;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        // Seed Customers (minimal 3)
        $customers = [
            [
                'name' => 'Ahmad Rizki',
                'email' => 'ahmad@gmail.com',
                'phone' => '081234567890',
                'address' => 'Jl. Merdeka No. 123, Jakarta',
                'driver_license' => 'SIM123456'
            ],
            [
                'name' => 'Siti Nurhaliza',
                'email' => 'siti@gmail.com',
                'phone' => '082345678901',
                'address' => 'Jl. Sudirman No. 45, Bandung',
                'driver_license' => 'SIM234567'
            ],
            [
                'name' => 'Budi Santoso',
                'email' => 'budi@gmail.com',
                'phone' => '083456789012',
                'address' => 'Jl. Thamrin No. 67, Surabaya',
                'driver_license' => 'SIM345678'
            ],
            [
                'name' => 'Dewi Lestari',
                'email' => 'dewi@gmail.com',
                'phone' => '084567890123',
                'address' => 'Jl. Gatot Subroto No. 89, Medan',
                'driver_license' => 'SIM456789'
            ]
        ];

        foreach ($customers as $customer) {
            Customer::create($customer);
        }

        // Seed Cars (minimal 3)
        $cars = [
            [
                'brand' => 'Toyota',
                'model' => 'Avanza',
                'license_plate' => 'B 1234 ABC',
                'year' => 2022,
                'daily_rate' => 350000,
                'status' => 'available',
                'color' => 'Silver'
            ],
            [
                'brand' => 'Honda',
                'model' => 'Brio',
                'license_plate' => 'B 5678 DEF',
                'year' => 2021,
                'daily_rate' => 300000,
                'status' => 'rented',
                'color' => 'Red'
            ],
            [
                'brand' => 'Mitsubishi',
                'model' => 'Xpander',
                'license_plate' => 'B 9012 GHI',
                'year' => 2023,
                'daily_rate' => 450000,
                'status' => 'rented',
                'color' => 'White'
            ],
            [
                'brand' => 'Suzuki',
                'model' => 'Ertiga',
                'license_plate' => 'B 3456 JKL',
                'year' => 2022,
                'daily_rate' => 400000,
                'status' => 'maintenance',
                'color' => 'Black'
            ]
        ];

        foreach ($cars as $car) {
            Car::create($car);
        }

        // Seed Rentals (minimal 3)
        $rentals = [
            [
                'customer_id' => 1,
                'car_id' => 1,
                'start_date' => Carbon::now()->subDays(5),
                'end_date' => Carbon::now()->addDays(2),
                'total_days' => 7,
                'total_cost' => 2450000,
                'status' => 'completed  '
            ],
            [
                'customer_id' => 2,
                'car_id' => 2,
                'start_date' => Carbon::now()->subDays(10),
                'end_date' => Carbon::now()->subDays(3),
                'total_days' => 7,
                'total_cost' => 2100000,
                'status' => 'active'
            ],
            [
                'customer_id' => 3,
                'car_id' => 3,
                'start_date' => Carbon::now()->addDays(1),
                'end_date' => Carbon::now()->addDays(5),
                'total_days' => 4,
                'total_cost' => 1800000,
                'status' => 'active'
            ]
        ];

        foreach ($rentals as $rental) {
            Rental::create($rental);
        }

        $this->command->info('Database seeded successfully!');
    }
}