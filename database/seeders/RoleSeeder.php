<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // إنشاء دور الطالب
        $studentRole = Role::firstOrCreate(['name' => 'student']);
        $studentPermissions = [
            'student-dashboard-view',
            'student-grades-view',
            'student-attendance-view',
            'student-schedule-view',
            'student-assignments-view',
            'student-invoices-view',
            'student-profile-edit',
        ];
        $studentRole->syncPermissions($studentPermissions);

        // إنشاء دور ولي الأمر
        $parentRole = Role::firstOrCreate(['name' => 'parent']);
        $parentPermissions = [
            'parent-dashboard-view',
            'parent-children-view',
            'parent-grades-view',
            'parent-attendance-view',
            'parent-schedule-view',
            'parent-assignments-view',
            'parent-invoices-view',
            'parent-messages-send',
            'parent-notifications-view',
        ];
        $parentRole->syncPermissions($parentPermissions);

        // إنشاء دور المعلم
        $teacherRole = Role::firstOrCreate(['name' => 'teacher']);
        
        // إنشاء دور المحاسب
        $accountantRole = Role::firstOrCreate(['name' => 'accountant']);
        
        // إنشاء دور أمين المكتبة
        $librarianRole = Role::firstOrCreate(['name' => 'librarian']);
        
        // إنشاء دور موظف إداري
        $staffRole = Role::firstOrCreate(['name' => 'staff']);
        
        // إضافة صلاحيات الحضور والغياب لدور المعلم
        $teacherPermissions = [
            'attendance-list',
            'attendance-create',
            'attendance-edit',
            'attendance-view',
        ];
        $teacherRole->syncPermissions(array_merge($teacherRole->permissions->pluck('name')->toArray(), $teacherPermissions));
    }
}
