<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        // إنشاء دور المدير (إذا لم يكن موجوداً)
        $adminRole = Role::firstOrCreate(['name' => 'admin']);

        // منح جميع الصلاحيات لدور المدير
        $permissions = Permission::all();
        if ($permissions->count() > 0) {
            $adminRole->syncPermissions($permissions);
        }

        // إنشاء مستخدم مدير افتراضي
        $adminUser = User::firstOrCreate(
            ['email' => 'admin@gmail.com'],
            [
                'name' => 'مدير النظام',
                'password' => Hash::make('123456789'),
                'email_verified_at' => now(),
                'status' => 'active',
                'is_active' => true,
            ]
        );

        // تحديث كلمة المرور في حالة كان المستخدم موجوداً
        if (!$adminUser->wasRecentlyCreated) {
            $adminUser->update([
                'password' => Hash::make('123456789'),
                'status' => 'active',
                'is_active' => true,
            ]);
        }

        // تعيين دور المدير للمستخدم
        if (!$adminUser->hasRole('admin')) {
            $adminUser->assignRole($adminRole);
        }
    }
}
