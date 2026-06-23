@extends('admin.layouts.master')

@section('page-title')
    مسارات الحافلات
@stop

@section('content')
    <div class="main-content app-content">
        <div class="container-fluid">
            @include('admin.partials.flash-alerts')

            <div class="admin-page-header">
                <div class="page-title-wrap">
                    <h1>مسارات الحافلات</h1>
                    <p>إدارة مسارات النقل المدرسي</p>
                </div>
                @can('bus-route-create')
                    <a href="{{ route('admin.bus-routes.create') }}" class="admin-btn admin-btn-primary">
                        <i class="ri-add-line"></i> إضافة مسار
                    </a>
                @endcan
            </div>

            <div class="admin-page-card">
                <div class="admin-table-wrap">
                    <div class="table-responsive">
                        <table class="admin-data-table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>اسم المسار</th>
                                    <th>رقم المسار</th>
                                    <th>المسافة</th>
                                    <th>الرسوم</th>
                                    <th>الحالة</th>
                                    <th>العمليات</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($routes as $route)
                                    <tr>
                                        <th scope="row" class="row-number">{{ $routes->firstItem() + $loop->index }}</th>
                                        <td><strong>{{ $route->route_name }}</strong></td>
                                        <td><span class="admin-badge admin-badge-muted">{{ $route->route_number }}</span></td>
                                        <td>{{ $route->distance ? $route->distance . ' كم' : '—' }}</td>
                                        <td>{{ number_format($route->fee, 2) }} ر.س</td>
                                        <td>
                                            <span class="admin-badge {{ $route->is_active ? 'admin-badge-success' : 'admin-badge-danger' }}">
                                                {{ $route->is_active ? 'نشط' : 'غير نشط' }}
                                            </span>
                                        </td>
                                        <td>
                                            <div class="admin-action-group">
                                                @can('bus-route-show')
                                                    <a href="{{ route('admin.bus-routes.show', $route) }}" class="admin-action-btn admin-action-view" title="عرض"><i class="ri-eye-line"></i></a>
                                                @endcan
                                                @can('bus-route-edit')
                                                    <a href="{{ route('admin.bus-routes.edit', $route) }}" class="admin-action-btn admin-action-edit" title="تعديل"><i class="ri-edit-line"></i></a>
                                                @endcan
                                                @can('bus-route-delete')
                                                    <button type="button" class="admin-action-btn admin-action-delete" title="حذف"
                                                            data-delete-url="{{ route('admin.bus-routes.destroy', $route) }}"
                                                            data-delete-message="هل أنت متأكد من حذف المسار <strong>{{ $route->route_name }}</strong>؟">
                                                        <i class="ri-delete-bin-line"></i>
                                                    </button>
                                                @endcan
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="7"><div class="admin-empty-state"><i class="ri-route-line"></i> لا توجد مسارات</div></td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="admin-table-footer mt-3">
                    <div class="admin-pagination">{{ $routes->links() }}</div>
                </div>
            </div>
        </div>
    </div>
    @include('admin.components.delete-modal')
@stop
