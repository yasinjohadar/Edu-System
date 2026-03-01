<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\ParentModel;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;

class ParentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $parentRole = Role::firstOrCreate(['name' => 'parent']);

        $parents = [
            [
                'name' => 'محمد عبدالله السعيد',
                'email' => 'mohammed.parent@example.com',
                'phone' => '0501111111',
                'parent_code' => 'P001',
                'relationship' => 'father',
                'occupation' => 'مهندس',
                'workplace' => 'شركة التقنية',
            ],
            [
                'name' => 'فاطمة أحمد محمد',
                'email' => 'fatima.parent@example.com',
                'phone' => '0501111112',
                'parent_code' => 'P002',
                'relationship' => 'mother',
                'occupation' => 'معلمة',
                'workplace' => 'مدرسة الأمل',
            ],
            [
                'name' => 'خالد حسن علي',
                'email' => 'khalid.parent@example.com',
                'phone' => '0501111113',
                'parent_code' => 'P003',
                'relationship' => 'father',
                'occupation' => 'طبيب',
                'workplace' => 'مستشفى المدينة',
            ],
            [
                'name' => 'نورا إبراهيم أحمد',
                'email' => 'nora.parent@example.com',
                'phone' => '0501111114',
                'parent_code' => 'P004',
                'relationship' => 'mother',
                'occupation' => 'محاسبة',
                'workplace' => 'مكتب محاسبة',
            ],
            [
                'name' => 'عبدالرحمن سعد الدوسري',
                'email' => 'abdulrahman.parent@example.com',
                'phone' => '0501111115',
                'parent_code' => 'P005',
                'relationship' => 'father',
                'occupation' => 'تاجر',
                'workplace' => 'متجر الأجهزة',
            ],
            [
                'name' => 'مريم علي حسن',
                'email' => 'mariam.parent@example.com',
                'phone' => '0501111116',
                'parent_code' => 'P006',
                'relationship' => 'mother',
                'occupation' => 'ربة منزل',
                'workplace' => null,
            ],
            [
                'name' => 'يوسف محمد خالد',
                'email' => 'youssef.parent@example.com',
                'phone' => '0501111117',
                'parent_code' => 'P007',
                'relationship' => 'father',
                'occupation' => 'محامي',
                'workplace' => 'مكتب محاماة',
            ],
            [
                'name' => 'لينا أحمد فهد',
                'email' => 'lina.parent@example.com',
                'phone' => '0501111118',
                'parent_code' => 'P008',
                'relationship' => 'mother',
                'occupation' => 'ممرضة',
                'workplace' => 'مستشفى النور',
            ],
        ];

        foreach ($parents as $parentData) {
            $user = User::firstOrCreate(
                ['email' => $parentData['email']],
                [
                    'name' => $parentData['name'],
                    'phone' => $parentData['phone'],
                    'password' => Hash::make('123456789'),
                    'status' => 'active',
                    'is_active' => true,
                    'email_verified_at' => now(),
                ]
            );

            if (!$user->hasRole('parent')) {
                $user->assignRole($parentRole);
            }

            ParentModel::firstOrCreate(
                ['parent_code' => $parentData['parent_code']],
                [
                    'user_id' => $user->id,
                    'parent_code' => $parentData['parent_code'],
                    'relationship' => $parentData['relationship'],
                    'occupation' => $parentData['occupation'],
                    'workplace' => $parentData['workplace'],
                ]
            );
        }
    }
}
