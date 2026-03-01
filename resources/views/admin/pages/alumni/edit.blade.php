@extends('admin.layouts.master')

@section('page-title')
    تعديل بيانات الخريج
@stop

@section('content')
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="main-content app-content">
        <div class="container-fluid">
            <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
                <div class="my-auto">
                    <h5 class="page-title fs-21 mb-1">تعديل بيانات الخريج</h5>
                </div>
            </div>

            <div class="row">
                <div class="col-xl-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">بيانات الخريج</h3>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('admin.alumni.update', $alumni) }}" method="POST">
                                @csrf
                                @method('PUT')
                                
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">اسم الخريج <span class="text-danger">*</span></label>
                                            <input type="text" name="name" class="form-control" value="{{ old('name', $alumni->name) }}" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">البريد الإلكتروني <span class="text-danger">*</span></label>
                                            <input type="email" name="email" class="form-control" value="{{ old('email', $alumni->email) }}" required>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">رقم الهاتف</label>
                                            <input type="text" name="phone" class="form-control" value="{{ old('phone', $alumni->phone) }}">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">تاريخ التخرج <span class="text-danger">*</span></label>
                                            <input type="date" name="graduation_date" class="form-control" value="{{ old('graduation_date', $alumni->graduation_date->format('Y-m-d')) }}" required>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">الدرجة</label>
                                            <input type="text" name="degree" class="form-control" value="{{ old('degree', $alumni->degree) }}">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">التخصص</label>
                                            <input type="text" name="major" class="form-control" value="{{ old('major', $alumni->major) }}">
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">الوظيفة الحالية</label>
                                            <input type="text" name="current_job" class="form-control" value="{{ old('current_job', $alumni->current_job) }}">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">الشركة</label>
                                            <input type="text" name="company" class="form-control" value="{{ old('company', $alumni->company) }}">
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <label class="form-label">العنوان</label>
                                            <textarea name="address" class="form-control" rows="3">{{ old('address', $alumni->address) }}</textarea>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="is_active" value="1" id="is_active" {{ old('is_active', $alumni->is_active) ? 'checked' : '' }}>
                                                <label class="form-check-label" for="is_active">
                                                    نشط
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-12">
                                        <button type="submit" class="btn btn-primary">حفظ</button>
                                        <a href="{{ route('admin.alumni.index') }}" class="btn btn-secondary">إلغاء</a>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

