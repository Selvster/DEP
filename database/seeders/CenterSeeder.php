<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Center;

class CenterSeeder extends Seeder
{
    public function run(): void
    {
        $centers = [
            ['name' => 'أشمون', 'is_active' => true, 'created_by' => 1],
            ['name' => 'الباجور', 'is_active' => true, 'created_by' => 1],
            ['name' => 'بركة السبع', 'is_active' => true, 'created_by' => 1],
            ['name' => 'تلا', 'is_active' => true, 'created_by' => 1],
            ['name' => 'الشهداء', 'is_active' => true, 'created_by' => 1],
            ['name' => 'شبين الكوم', 'is_active' => true, 'created_by' => 1],
            ['name' => 'قويسنا', 'is_active' => true, 'created_by' => 1],
            ['name' => 'منوف', 'is_active' => true, 'created_by' => 1],
            ['name' => 'السادات', 'is_active' => true, 'created_by' => 1],
        ];

        foreach ($centers as $center) {
            Center::create($center);
        }
    }
}

