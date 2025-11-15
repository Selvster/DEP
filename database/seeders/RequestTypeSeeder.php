<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\RequestType;

class RequestTypeSeeder extends Seeder
{
    public function run(): void
    {
        $types = [
            ['name' => 'شكوى', 'is_active' => true, 'created_by' => 1],
            ['name' => 'تضامن اجتماعي', 'is_active' => true, 'created_by' => 1],
            ['name' => 'صحة', 'is_active' => true, 'created_by' => 1],
            ['name' => 'إسكان', 'is_active' => true, 'created_by' => 1],
            ['name' => 'توظيف', 'is_active' => true, 'created_by' => 1],
            ['name' => 'تعليم', 'is_active' => true, 'created_by' => 1],
        ];

        foreach ($types as $type) {
            RequestType::create($type);
        }
    }
}

