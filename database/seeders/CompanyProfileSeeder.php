<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CompanyProfileSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('company_profiles')->insert([
            [
                'name' => 'DNA Konsultan',
                'address' => '123 Innovation Blvd, Tech City',
                'phone' => '123-456-7890',
                'website' => 'https://dnakonsultan.com',
                'logo' => '1',
                'description' => 'Jasa Pembuatan Website dan Pembuatan Aplikasi inventori stok barang, dan Service komputer dan laptop',
                'image' => 'backend/img/logo.jpeg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}