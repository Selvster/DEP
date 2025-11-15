<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\RequestStatus;

class RequestStatusSeeder extends Seeder
{
    public function run(): void
    {
        $statuses = [
            ['name' => 'جاري المراجعة', 'color' => '#3B82F6', 'is_active' => true, 'created_by' => 1],
            ['name' => 'تحت الإجراء', 'color' => '#F59E0B', 'is_active' => true, 'created_by' => 1],
            ['name' => 'تم التحويل', 'color' => '#8B5CF6', 'is_active' => true, 'created_by' => 1],
            ['name' => 'تم الرد', 'color' => '#10B981', 'is_active' => true, 'created_by' => 1],
            ['name' => 'مرفوض', 'color' => '#EF4444', 'is_active' => true, 'created_by' => 1],
        ];

        foreach ($statuses as $status) {
            RequestStatus::create($status);
        }
    }
}

