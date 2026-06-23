@extends('admin.layouts.master')

@section('page-title')
    تعديل مسار حافلة
@stop

@section('content')
    <div class="main-content app-content">
        <div class="container-fluid">
            @include('admin.partials.flash-alerts')

            <div class="admin-page-header">
                <div class="page-title-wrap">
                    <h1>تعديل مسار الحافلة</h1>
                    <p>{{ $busRoute->route_number }}</p>
                </div>
                <a href="{{ route('admin.bus-routes.index') }}" class="admin-btn admin-btn-secondary">
                    <i class="ri-arrow-right-line"></i> العودة
                </a>
            </div>

            <div class="admin-page-card">
                <form action="{{ route('admin.bus-routes.update', $busRoute) }}" method="POST" class="admin-form" id="bus-route-form">
                    @csrf
                    @method('PUT')
                    <div class="admin-form-body">
                        @include('admin.pages.transport.bus-routes._form', ['busRoute' => $busRoute])
                    </div>
                    <div class="admin-form-footer">
                        <button type="submit" class="admin-btn admin-btn-primary"><i class="ri-save-line"></i> حفظ</button>
                        <a href="{{ route('admin.bus-routes.index') }}" class="admin-btn admin-btn-secondary">إلغاء</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@stop

@push('scripts')
<script>document.addEventListener('DOMContentLoaded', () => AdminTables.initAdminForm('#bus-route-form'));</script>
@endpush
