<?php

namespace Database\Seeders;

use App\Models\BusRoute;
use App\Models\BusStop;
use App\Models\Driver;
use App\Models\Student;
use App\Models\StudentTransport;
use App\Models\Supervisor;
use Illuminate\Database\Seeder;

class TransportSeeder extends Seeder
{
    public function run(): void
    {
        $routeNorth = BusRoute::updateOrCreate(
            ['route_number' => 'R-001'],
            [
                'route_name' => 'مسار الشمال',
                'description' => 'يغطي الأحياء الشمالية',
                'distance' => 12.5,
                'start_time' => '06:30',
                'end_time' => '07:15',
                'fee' => 200.00,
                'is_active' => true,
            ]
        );

        $routeSouth = BusRoute::updateOrCreate(
            ['route_number' => 'R-002'],
            [
                'route_name' => 'مسار الجنوب',
                'description' => 'يغطي الأحياء الجنوبية',
                'distance' => 15.0,
                'start_time' => '06:45',
                'end_time' => '07:30',
                'fee' => 250.00,
                'is_active' => true,
            ]
        );

        $northStops = [
            ['stop_name' => 'محطة الميدان', 'address' => 'شارع الملك فهد', 'order' => 1, 'arrival_time' => '06:35'],
            ['stop_name' => 'محطة النخيل', 'address' => 'حي النخيل', 'order' => 2, 'arrival_time' => '06:50'],
            ['stop_name' => 'محطة المدرسة', 'address' => 'بوابة المدرسة الرئيسية', 'order' => 3, 'arrival_time' => '07:15'],
        ];

        $southStops = [
            ['stop_name' => 'محطة الوادي', 'address' => 'حي الوادي', 'order' => 1, 'arrival_time' => '06:50'],
            ['stop_name' => 'محطة الربوة', 'address' => 'حي الربوة', 'order' => 2, 'arrival_time' => '07:05'],
            ['stop_name' => 'محطة المدرسة', 'address' => 'بوابة المدرسة الرئيسية', 'order' => 3, 'arrival_time' => '07:30'],
        ];

        $northStopModels = $this->seedStops($routeNorth, $northStops);
        $southStopModels = $this->seedStops($routeSouth, $southStops);

        $driver = Driver::updateOrCreate(
            ['driver_code' => 'DRV-001'],
            [
                'license_number' => 'LIC-10001',
                'license_expiry' => now()->addYear(),
                'phone' => '0500000001',
                'address' => 'الرياض',
                'status' => 'active',
            ]
        );

        $supervisor = Supervisor::updateOrCreate(
            ['supervisor_code' => 'SUP-001'],
            [
                'phone' => '0500000002',
                'status' => 'active',
            ]
        );

        $students = Student::where('status', 'active')->orderBy('id')->limit(3)->get();

        if ($students->isNotEmpty()) {
            StudentTransport::updateOrCreate(
                ['student_id' => $students[0]->id, 'route_id' => $routeNorth->id],
                [
                    'stop_id' => $northStopModels[0]->id,
                    'driver_id' => $driver->id,
                    'supervisor_id' => $supervisor->id,
                    'start_date' => now()->startOfMonth(),
                    'end_date' => null,
                    'status' => 'active',
                ]
            );
        }

        if ($students->count() > 1) {
            StudentTransport::updateOrCreate(
                ['student_id' => $students[1]->id, 'route_id' => $routeSouth->id],
                [
                    'stop_id' => $southStopModels[0]->id,
                    'driver_id' => $driver->id,
                    'supervisor_id' => $supervisor->id,
                    'start_date' => now()->startOfMonth(),
                    'end_date' => null,
                    'status' => 'active',
                ]
            );
        }
    }

    /**
     * @param  array<int, array<string, mixed>>  $stops
     * @return array<int, BusStop>
     */
    private function seedStops(BusRoute $route, array $stops): array
    {
        $models = [];

        foreach ($stops as $stop) {
            $models[] = BusStop::updateOrCreate(
                ['route_id' => $route->id, 'stop_name' => $stop['stop_name']],
                [
                    'address' => $stop['address'],
                    'order' => $stop['order'],
                    'arrival_time' => $stop['arrival_time'],
                ]
            );
        }

        return $models;
    }
}
