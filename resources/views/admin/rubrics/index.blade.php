@extends('admin.layouts.master')

@section('title', 'معايير التقييم')

@section('content')
<div class="page-header">
    <h1 class="page-title">معايير التقييم</h1>
    <div class="page-subtitle">إدارة معايير تقييم الأسئلة المقالية</div>
</div>

<div class="card">
    <div class="card-header">
        <h3 class="card-title">قائمة معايير التقييم</h3>
        <a href="{{ route('admin.rubrics.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> إضافة معيار جديد
        </a>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>اسم المعيار</th>
                        <th>الوصف</th>
                        <th>الدرجة الإجمالية</th>
                        <th>عدد المعايير</th>
                        <th>المستخدم</th>
                        <th>الإجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($rubrics as $rubric)
                        <tr>
                            <td>{{ $rubric->id }}</td>
                            <td>
                                <strong>{{ $rubric->name }}</strong>
                                @if($rubric->is_active)
                                    <span class="badge badge-success">نشط</span>
                                @else
                                    <span class="badge badge-secondary">غير نشط</span>
                                @endif
                            </td>
                            <td>{{ Str::limit($rubric->description, 50) }}</td>
                            <td>{{ $rubric->total_points }}</td>
                            <td>{{ $rubric->criteria_count ?? 0 }}</td>
                            <td>{{ $rubric->creator?->name ?? '-' }}</td>
                            <td>
                                <div class="btn-group">
                                    <a href="{{ route('admin.rubrics.show', $rubric) }}" class="btn btn-sm btn-info" title="عرض">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.rubrics.edit', $rubric) }}" class="btn btn-sm btn-warning" title="تعديل">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('admin.rubrics.destroy', $rubric) }}" method="POST" style="display: inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('هل أنت متأكد من حذف هذا المعيار؟')" title="حذف">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center">
                                <div class="alert alert-info">
                                    <i class="fas fa-info-circle"></i> لا توجد معايير تقييم حالياً
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($rubrics->hasPages())
            <div class="pagination-wrapper">
                {{ $rubrics->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
