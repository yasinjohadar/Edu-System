@extends('student.layouts.master')

@section('page-title')
    الجدول الدراسي
@stop

@section('content')
    <!-- Start::app-content -->
    <div class="main-content app-content">
        <div class="container-fluid">
            <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
                <div class="my-auto">
                    <h5 class="page-title fs-21 mb-1">الجدول الدراسي</h5>
                </div>
            </div>

            <div class="row">
                <div class="col-xl-12">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title">جدول {{ $student->section->name ?? 'الفصل الدراسي' }}</h5>
                        </div>
                        <div class="card-body">
                            @if($scheduleByDay->count() > 0)
                                <div class="table-responsive">
                                    <table class="table table-bordered table-hover">
                                        <thead class="table-light">
                                            <tr>
                                                <th style="width: 120px;">الوقت</th>
                                                @foreach($days as $dayNum => $dayName)
                                                    <th>{{ $dayName }}</th>
                                                @endforeach
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php
                                                // تجميع الحصص حسب الوقت
                                                $timeSlots = [];
                                                foreach ($scheduleByDay as $day => $schedules) {
                                                    foreach ($schedules as $schedule) {
                                                        $timeKey = $schedule->start_time . '-' . $schedule->end_time;
                                                        if (!isset($timeSlots[$timeKey])) {
                                                            $timeSlots[$timeKey] = [
                                                                'start' => $schedule->start_time,
                                                                'end' => $schedule->end_time,
                                                                'schedules' => []
                                                            ];
                                                        }
                                                        $timeSlots[$timeKey]['schedules'][$day] = $schedule;
                                                    }
                                                }
                                                ksort($timeSlots);
                                            @endphp

                                            @foreach($timeSlots as $timeSlot)
                                                <tr>
                                                    <td class="text-center fw-bold">
                                                        {{ \Carbon\Carbon::parse($timeSlot['start'])->format('H:i') }}<br>
                                                        -<br>
                                                        {{ \Carbon\Carbon::parse($timeSlot['end'])->format('H:i') }}
                                                    </td>
                                                    @foreach($days as $dayNum => $dayName)
                                                        <td>
                                                            @if(isset($timeSlot['schedules'][$dayNum]))
                                                                @php $schedule = $timeSlot['schedules'][$dayNum]; @endphp
                                                                <div class="p-2 bg-light rounded">
                                                                    <strong>{{ $schedule->subject->name }}</strong><br>
                                                                    <small class="text-muted">{{ $schedule->teacher->user->name ?? '-' }}</small><br>
                                                                    @if($schedule->room)
                                                                        <small class="text-info">قاعة: {{ $schedule->room }}</small>
                                                                    @endif
                                                                </div>
                                                            @endif
                                                        </td>
                                                    @endforeach
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <div class="alert alert-info text-center">
                                    <p class="mb-0">لا يوجد جدول دراسي متاح</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End::app-content -->
@stop

