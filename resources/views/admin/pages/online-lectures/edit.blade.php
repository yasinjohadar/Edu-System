@extends('admin.layouts.master')

@section('page-title')
    تعديل محاضرة
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

    <!-- Start::app-content -->
    <div class="main-content app-content">
        <div class="container-fluid">
            <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
                <div class="my-auto">
                    <h5 class="page-title fs-21 mb-1">تعديل محاضرة</h5>
                </div>
            </div>

            <div class="row">
                <div class="col-xl-12">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title">معلومات المحاضرة</h5>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('admin.online-lectures.update', $lecture->id) }}" method="POST">
                                @csrf
                                @method('PUT')
                                
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">المادة <span class="text-danger">*</span></label>
                                        <select name="subject_id" class="form-select" required>
                                            <option value="">اختر المادة</option>
                                            @foreach($subjects as $subject)
                                                <option value="{{ $subject->id }}" {{ old('subject_id', $lecture->subject_id) == $subject->id ? 'selected' : '' }}>{{ $subject->name }}</option>
                                            @endforeach
                                        </select>
                                        @error('subject_id')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">الفصل <span class="text-danger">*</span></label>
                                        <select name="section_id" class="form-select" required>
                                            <option value="">اختر الفصل</option>
                                            @foreach($sections as $section)
                                                <option value="{{ $section->id }}" {{ old('section_id', $lecture->section_id) == $section->id ? 'selected' : '' }}>{{ $section->class->name }} - {{ $section->name }}</option>
                                            @endforeach
                                        </select>
                                        @error('section_id')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">المعلم <span class="text-danger">*</span></label>
                                        <select name="teacher_id" class="form-select" required>
                                            <option value="">اختر المعلم</option>
                                            @foreach($teachers as $teacher)
                                                <option value="{{ $teacher->id }}" {{ old('teacher_id', $lecture->teacher_id) == $teacher->id ? 'selected' : '' }}>{{ $teacher->user->name }}</option>
                                            @endforeach
                                        </select>
                                        @error('teacher_id')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">النوع <span class="text-danger">*</span></label>
                                        <select name="type" class="form-select" required id="lecture_type">
                                            <option value="recorded" {{ old('type', $lecture->type) == 'recorded' ? 'selected' : '' }}>مسجلة</option>
                                            <option value="live" {{ old('type', $lecture->type) == 'live' ? 'selected' : '' }}>مباشرة</option>
                                            <option value="material" {{ old('type', $lecture->type) == 'material' ? 'selected' : '' }}>مواد</option>
                                        </select>
                                        @error('type')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-12 mb-3">
                                        <label class="form-label">العنوان <span class="text-danger">*</span></label>
                                        <input type="text" name="title" class="form-control" value="{{ old('title', $lecture->title) }}" required>
                                        @error('title')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-12 mb-3">
                                        <label class="form-label">الوصف</label>
                                        <textarea name="description" class="form-control" rows="3">{{ old('description', $lecture->description) }}</textarea>
                                        @error('description')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-12 mb-3">
                                        <label class="form-label">المحتوى</label>
                                        <textarea name="content" class="form-control" rows="5">{{ old('content', $lecture->content) }}</textarea>
                                        @error('content')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6 mb-3" id="video_url_div">
                                        <label class="form-label">رابط الفيديو</label>
                                        <input type="url" name="video_url" class="form-control" value="{{ old('video_url', $lecture->video_url) }}" placeholder="https://example.com/video">
                                        @error('video_url')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6 mb-3" id="audio_url_div">
                                        <label class="form-label">رابط الصوت</label>
                                        <input type="url" name="audio_url" class="form-control" value="{{ old('audio_url', $lecture->audio_url) }}" placeholder="https://example.com/audio">
                                        @error('audio_url')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6 mb-3" id="scheduled_at_div" style="display: none;">
                                        <label class="form-label">تاريخ ووقت المحاضرة المباشرة</label>
                                        <input type="datetime-local" name="scheduled_at" class="form-control" value="{{ old('scheduled_at', $lecture->scheduled_at ? $lecture->scheduled_at->format('Y-m-d\TH:i') : '') }}">
                                        @error('scheduled_at')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6 mb-3" id="meeting_link_div" style="display: none;">
                                        <label class="form-label">رابط الاجتماع</label>
                                        <input type="url" name="meeting_link" class="form-control" value="{{ old('meeting_link', $lecture->meeting_link) }}" placeholder="https://meet.example.com/...">
                                        @error('meeting_link')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6 mb-3" id="meeting_id_div" style="display: none;">
                                        <label class="form-label">معرف الاجتماع</label>
                                        <input type="text" name="meeting_id" class="form-control" value="{{ old('meeting_id', $lecture->meeting_id) }}">
                                        @error('meeting_id')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6 mb-3" id="meeting_password_div" style="display: none;">
                                        <label class="form-label">كلمة مرور الاجتماع</label>
                                        <input type="text" name="meeting_password" class="form-control" value="{{ old('meeting_password', $lecture->meeting_password) }}">
                                        @error('meeting_password')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">المدة (بالدقائق)</label>
                                        <input type="number" name="duration" class="form-control" min="1" value="{{ old('duration', $lecture->duration) }}">
                                        @error('duration')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="is_published" value="1" {{ old('is_published', $lecture->is_published) ? 'checked' : '' }}>
                                            <label class="form-check-label">منشورة</label>
                                        </div>
                                    </div>
                                </div>

                                <div class="d-flex gap-2">
                                    <button type="submit" class="btn btn-primary">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-left: 5px; vertical-align: middle;">
                                            <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path>
                                            <polyline points="17 21 17 13 7 13 7 21"></polyline>
                                            <polyline points="7 3 7 8 15 8"></polyline>
                                        </svg>
                                        حفظ
                                    </button>
                                    <a href="{{ route('admin.online-lectures.index') }}" class="btn btn-secondary">إلغاء</a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End::app-content -->
@stop

@push('scripts')
<script>
document.getElementById('lecture_type').addEventListener('change', function() {
    const type = this.value;
    const scheduledDiv = document.getElementById('scheduled_at_div');
    const meetingLinkDiv = document.getElementById('meeting_link_div');
    const meetingIdDiv = document.getElementById('meeting_id_div');
    const meetingPasswordDiv = document.getElementById('meeting_password_div');
    const videoDiv = document.getElementById('video_url_div');
    const audioDiv = document.getElementById('audio_url_div');

    if (type === 'live') {
        scheduledDiv.style.display = 'block';
        meetingLinkDiv.style.display = 'block';
        meetingIdDiv.style.display = 'block';
        meetingPasswordDiv.style.display = 'block';
        videoDiv.style.display = 'none';
        audioDiv.style.display = 'none';
    } else if (type === 'recorded') {
        scheduledDiv.style.display = 'none';
        meetingLinkDiv.style.display = 'none';
        meetingIdDiv.style.display = 'none';
        meetingPasswordDiv.style.display = 'none';
        videoDiv.style.display = 'block';
        audioDiv.style.display = 'block';
    } else {
        scheduledDiv.style.display = 'none';
        meetingLinkDiv.style.display = 'none';
        meetingIdDiv.style.display = 'none';
        meetingPasswordDiv.style.display = 'none';
        videoDiv.style.display = 'none';
        audioDiv.style.display = 'none';
    }
});

// تشغيل عند التحميل
document.getElementById('lecture_type').dispatchEvent(new Event('change'));
</script>
@endpush

