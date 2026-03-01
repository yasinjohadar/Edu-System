@extends('admin.layouts.master')

@section('title', 'تعديل معيار التقييم')

@section('content')
<div class="page-header">
    <h1 class="page-title">تعديل معيار التقييم</h1>
    <div class="page-subtitle">تعديل معيار تقييم للأسئلة المقالية</div>
</div>

<div class="card">
    <div class="card-body">
        <form action="{{ route('admin.rubrics.update', $rubric) }}" method="POST" id="rubricForm">
            @csrf
            @method('PUT')
            
            <!-- معلومات المعيار -->
            <div class="form-group">
                <label>اسم المعيار <span class="text-danger">*</span></label>
                <input type="text" name="name" class="form-control" value="{{ old('name', $rubric->name) }}" required>
            </div>
            
            <div class="form-group">
                <label>الوصف</label>
                <textarea name="description" class="form-control" rows="3">{{ old('description', $rubric->description) }}</textarea>
            </div>
            
            <div class="form-group">
                <label>الدرجة الإجمالية <span class="text-danger">*</span></label>
                <input type="number" name="total_points" class="form-control" value="{{ old('total_points', $rubric->total_points) }}" required min="0" step="0.5">
            </div>
            
            <div class="form-group">
                <label>تعليمات للطالب</label>
                <textarea name="instructions" class="form-control" rows="3">{{ old('instructions', $rubric->instructions) }}</textarea>
            </div>
            
            <div class="form-group">
                <div class="custom-control custom-checkbox">
                    <input type="checkbox" name="is_active" class="custom-control-input" id="isActive" {{ $rubric->is_active ? 'checked' : '' }}>
                    <label class="custom-control-label" for="isActive">نشط</label>
                </div>
            </div>
            
            <!-- معايير التقييم -->
            <div class="form-group">
                <label>معايير التقييم</label>
                <div id="criteriaContainer">
                    @forelse($rubric->criteria ?? [] as $index => $criterion)
                        <div class="criteria-item" data-index="{{ $index }}">
                            <div class="card criteria-card">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <h5 class="card-title mb-0">المعيار #{{ $index + 1 }}</h5>
                                        <button type="button" class="btn btn-sm btn-danger" onclick="removeCriteria({{ $index }})">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label>اسم المعيار <span class="text-danger">*</span></label>
                                        <input type="text" name="criteria[{{ $index }}][name]" class="form-control" value="{{ old("criteria.$index.name", $criterion->name) }}" required>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label>الوصف</label>
                                        <textarea name="criteria[{{ $index }}][description]" class="form-control" rows="2">{{ old("criteria.$index.description", $criterion->description) }}</textarea>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label>الدرجة <span class="text-danger">*</span></label>
                                        <input type="number" name="criteria[{{ $index }}][points]" class="form-control" value="{{ old("criteria.$index.points", $criterion->points) }}" required min="0" step="0.5">
                                    </div>
                                    
                                    <div class="form-group">
                                        <label>مستويات الأداء</label>
                                        <div class="performance-levels">
                                            <div class="performance-level">
                                                <label>مستوى ممتاز</label>
                                                <textarea name="criteria[{{ $index }}][excellent]" class="form-control" rows="2">{{ old("criteria.$index.excellent", $criterion->excellent ?? '') }}</textarea>
                                            </div>
                                            <div class="performance-level">
                                                <label>مستوى جيد</label>
                                                <textarea name="criteria[{{ $index }}][good]" class="form-control" rows="2">{{ old("criteria.$index.good", $criterion->good ?? '') }}</textarea>
                                            </div>
                                            <div class="performance-level">
                                                <label>مستوى مقبول</label>
                                                <textarea name="criteria[{{ $index }}][satisfactory]" class="form-control" rows="2">{{ old("criteria.$index.satisfactory", $criterion->satisfactory ?? '') }}</textarea>
                                            </div>
                                            <div class="performance-level">
                                                <label>مستوى ضعيف</label>
                                                <textarea name="criteria[{{ $index }}][poor]" class="form-control" rows="2">{{ old("criteria.$index.poor", $criterion->poor ?? '') }}</textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="criteria-item" data-index="0">
                            <div class="card criteria-card">
                                <div class="card-body">
                                    <h5 class="card-title">المعيار #1</h5>
                                    
                                    <div class="form-group">
                                        <label>اسم المعيار <span class="text-danger">*</span></label>
                                        <input type="text" name="criteria[0][name]" class="form-control" required>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label>الوصف</label>
                                        <textarea name="criteria[0][description]" class="form-control" rows="2"></textarea>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label>الدرجة <span class="text-danger">*</span></label>
                                        <input type="number" name="criteria[0][points]" class="form-control" required min="0" step="0.5">
                                    </div>
                                    
                                    <div class="form-group">
                                        <label>مستويات الأداء</label>
                                        <div class="performance-levels">
                                            <div class="performance-level">
                                                <label>مستوى ممتاز</label>
                                                <textarea name="criteria[0][excellent]" class="form-control" rows="2"></textarea>
                                            </div>
                                            <div class="performance-level">
                                                <label>مستوى جيد</label>
                                                <textarea name="criteria[0][good]" class="form-control" rows="2"></textarea>
                                            </div>
                                            <div class="performance-level">
                                                <label>مستوى مقبول</label>
                                                <textarea name="criteria[0][satisfactory]" class="form-control" rows="2"></textarea>
                                            </div>
                                            <div class="performance-level">
                                                <label>مستوى ضعيف</label>
                                                <textarea name="criteria[0][poor]" class="form-control" rows="2"></textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforelse
                </div>
                
                <button type="button" class="btn btn-success" onclick="addCriteria()">
                    <i class="fas fa-plus"></i> إضافة معيار
                </button>
            </div>
            
            <!-- أزرار الإجراءات -->
            <div class="form-actions">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> حفظ التعديلات
                </button>
                <a href="{{ route('admin.rubrics.index') }}" class="btn btn-secondary">
                    <i class="fas fa-times"></i> إلغاء
                </a>
            </div>
        </form>
    </div>
</div>

<style>
.criteria-card {
    margin-bottom: 15px;
    border: 1px solid #dee2e6;
}

.performance-level {
    margin-bottom: 10px;
    padding: 10px;
    background-color: #f8f9fa;
    border-radius: 4px;
}

.performance-level label {
    font-weight: bold;
    margin-bottom: 5px;
    display: block;
}

.form-actions {
    margin-top: 20px;
    padding-top: 20px;
    border-top: 1px solid #dee2e6;
}
</style>

<script>
let criteriaCount = {{ count($rubric->criteria ?? []) }};

function addCriteria() {
    const container = document.getElementById('criteriaContainer');
    const index = criteriaCount;
    
    const html = `
        <div class="criteria-item" data-index="${index}">
            <div class="card criteria-card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="card-title mb-0">المعيار #${index + 1}</h5>
                        <button type="button" class="btn btn-sm btn-danger" onclick="removeCriteria(${index})">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                    
                    <div class="form-group">
                        <label>اسم المعيار <span class="text-danger">*</span></label>
                        <input type="text" name="criteria[${index}][name]" class="form-control" required>
                    </div>
                    
                    <div class="form-group">
                        <label>الوصف</label>
                        <textarea name="criteria[${index}][description]" class="form-control" rows="2"></textarea>
                    </div>
                    
                    <div class="form-group">
                        <label>الدرجة <span class="text-danger">*</span></label>
                        <input type="number" name="criteria[${index}][points]" class="form-control" required min="0" step="0.5">
                    </div>
                    
                    <div class="form-group">
                        <label>مستويات الأداء</label>
                        <div class="performance-levels">
                            <div class="performance-level">
                                <label>مستوى ممتاز</label>
                                <textarea name="criteria[${index}][excellent]" class="form-control" rows="2"></textarea>
                            </div>
                            <div class="performance-level">
                                <label>مستوى جيد</label>
                                <textarea name="criteria[${index}][good]" class="form-control" rows="2"></textarea>
                            </div>
                            <div class="performance-level">
                                <label>مستوى مقبول</label>
                                <textarea name="criteria[${index}][satisfactory]" class="form-control" rows="2"></textarea>
                            </div>
                            <div class="performance-level">
                                <label>مستوى ضعيف</label>
                                <textarea name="criteria[${index}][poor]" class="form-control" rows="2"></textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    `;
    
    container.insertAdjacentHTML('beforeend', html);
    criteriaCount++;
}

function removeCriteria(index) {
    const item = document.querySelector(`.criteria-item[data-index="${index}"]`);
    if (item) {
        item.remove();
    }
}
</script>
@endsection
