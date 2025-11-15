<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Center;

class CenterSeeder extends Seeder
{
    public function run(): void
    {
        $centers = [
            ['name' => 'مركز المحلة الكبرى', 'is_active' => true, 'created_by' => 1],
            ['name' => 'مركز طنطا', 'is_active' => true, 'created_by' => 1],
            ['name' => 'مركز كفر الزيات', 'is_active' => true, 'created_by' => 1],
            ['name' => 'مركز زفتى', 'is_active' => true, 'created_by' => 1],
            ['name' => 'مركز السنطة', 'is_active' => true, 'created_by' => 1],
        ];

        foreach ($centers as $center) {
            Center::create($center);
        }
    }
}

