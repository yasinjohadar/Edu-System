<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [
            // صلاحيات الأدوار
            "role-list",
            "role-create",
            "role-edit",
            "role-delete",

            // صلاحيات المستخدمين
            "user-list",
            "user-create",
            "user-edit",
            "user-delete",
            "user-show",


            // صلاحيات إضافية للنظام
            "dashboard-view",
            "settings-manage",
            "reports-view",

            // صلاحيات الحضور والغياب
            "attendance-list",
            "attendance-create",
            "attendance-edit",
            "attendance-delete",
            "attendance-view",

            // صلاحيات إدارة الطلاب
            "student-list",
            "student-create",
            "student-edit",
            "student-delete",
            "student-show",

            // صلاحيات الجدول الدراسي
            "schedule-list",
            "schedule-create",
            "schedule-edit",
            "schedule-delete",
            "schedule-view",

            // صلاحيات الدرجات والتقييم
            "grade-list",
            "grade-create",
            "grade-edit",
            "grade-delete",
            "grade-view",

            // صلاحيات النظام المالي
            "fee-type-list",
            "fee-type-create",
            "fee-type-edit",
            "fee-type-delete",
            "invoice-list",
            "invoice-create",
            "invoice-edit",
            "invoice-delete",
            "invoice-view",
            "payment-list",
            "payment-create",
            "payment-edit",
            "payment-delete",
            "payment-view",
            "financial-account-list",
            "financial-account-view",

            // صلاحيات الطلاب
            "student-dashboard-view",
            "student-grades-view",
            "student-attendance-view",
            "student-schedule-view",
            "student-assignments-view",
            "student-invoices-view",
            "student-profile-edit",

            // صلاحيات أولياء الأمور
            "parent-dashboard-view",
            "parent-children-view",
            "parent-grades-view",
            "parent-attendance-view",
            "parent-schedule-view",
            "parent-assignments-view",
            "parent-invoices-view",
            "parent-messages-send",
            "parent-notifications-view",

            // صلاحيات نظام المكتبة
            "book-category-list",
            "book-category-create",
            "book-category-edit",
            "book-category-delete",
            "book-list",
            "book-create",
            "book-edit",
            "book-delete",
            "book-borrowing-list",
            "book-borrowing-create",
            "book-borrowing-edit",
            "book-borrowing-delete",
            "fine-list",
            "fine-create",
            "fine-edit",
            "fine-delete",

            // صلاحيات نظام المحاضرات الإلكترونية
            "lecture-list",
            "lecture-create",
            "lecture-edit",
            "lecture-delete",
            "lecture-material-list",
            "lecture-material-create",
            "lecture-material-edit",
            "lecture-material-delete",
            "lecture-attendance-list",
            "lecture-attendance-create",
            "lecture-attendance-edit",
            "lecture-attendance-delete",

            // صلاحيات نظام الواجبات
            "assignment-list",
            "assignment-create",
            "assignment-edit",
            "assignment-delete",
            "assignment-view",
            "assignment-publish",
            "assignment-submission-list",
            "assignment-submission-view",
            "assignment-submission-grade",
            "assignment-submit",
        ];

        foreach ($permissions as $key => $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }
    }
}
