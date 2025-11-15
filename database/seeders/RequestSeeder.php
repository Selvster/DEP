<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Request;

class RequestSeeder extends Seeder
{
    public function run(): void
    {
        $requests = [
            [
                'name' => 'أحمد محمد علي',
                'national_id' => '29501011234567',
                'phone' => '01012345678',
                'email' => 'ahmed@example.com',
                'center_id' => 1,
                'request_type_id' => 1,
                'status_id' => 1,
                'description' => 'طلب استفسار عن الخدمات المتاحة بالمركز',
                'documents' => null,
                'created_by' => null,
            ],
            [
                'name' => 'فاطمة حسن محمود',
                'national_id' => '29601021234567',
                'phone' => '01123456789',
                'email' => null,
                'center_id' => 2,
                'request_type_id' => 2,
                'status_id' => 2,
                'description' => 'طلب الحصول على مساعدة اجتماعية',
                'documents' => null,
                'created_by' => null,
            ],
            [
                'name' => 'محمود سعيد إبراهيم',
                'national_id' => '29701031234567',
                'phone' => '01234567890',
                'email' => 'mahmoud@example.com',
                'center_id' => 3,
                'request_type_id' => 4,
                'status_id' => 4,
                'description' => 'طلب الاستفسار عن وحدات سكنية',
                'documents' => null,
                'created_by' => 1,
            ],
        ];

        foreach ($requests as $request) {
            Request::create($request);
        }
    }
}

