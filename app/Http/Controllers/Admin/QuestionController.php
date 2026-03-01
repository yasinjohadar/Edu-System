<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Question;
use App\Models\QuestionOption;
use App\Models\QuestionBooleanAnswer;
use App\Models\EssayQuestion;
use App\Models\QuestionBlank;
use App\Models\MatchingPair;
use App\Models\ClassificationItem;
use App\Models\OrderingItem;
use App\Models\HotspotZone;
use App\Models\DragDropItem;
use App\Models\AudioQuestion;
use App\Models\VideoQuestion;
use App\Models\QuestionCategory;
use App\Models\Subject;
use App\Models\Grade;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class QuestionController extends Controller
{
    /**
     * Display a listing of resource.
     */
    public function index(Request $request)
    {
        $query = Question::query();
        
        // Filter by type
        if ($request->has('type')) {
            $query->where('type', $request->type);
        }
        
        // Filter by subject
        if ($request->has('subject_id')) {
            $query->where('subject_id', $request->subject_id);
        }
        
        // Filter by grade
        if ($request->has('grade_id')) {
            $query->where('grade_id', $request->grade_id);
        }
        
        // Filter by difficulty
        if ($request->has('difficulty')) {
            $query->where('difficulty', $request->difficulty);
        }
        
        // Filter by tags
        if ($request->has('tags')) {
            $query->where('tags', 'like', '%' . $request->tags . '%');
        }
        
        // Filter by active status
        if ($request->has('is_active')) {
            $query->where('is_active', $request->is_active);
        }
        
        // Filter by search
        if ($request->has('search') && $request->search) {
            $query->where('content', 'like', '%' . $request->search . '%');
        }
        
        $questions = $query->with(['subject', 'grade', 'creator'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);
        
        // Get subjects and grades for filters
        $subjects = Subject::where('is_active', true)->orderBy('name')->get();
        $grades = Grade::where('is_active', true)->orderBy('order')->get();
        
        return view('admin.questions.index', compact('questions', 'subjects', 'grades'));
    }

    /**
     * Show the specified resource.
     */
    public function show(Question $question)
    {
        $question->load(['subject', 'grade', 'creator']);
        
        return view('admin.questions.show', compact('question'));
    }

    /**
     * Show form for creating a new resource.
     */
    public function create()
    {
        return view('admin.questions.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'type' => 'required|in:multiple_choice,true_false,essay,fill_blanks,matching,ordering,classification,drag_drop,hotspot,audio,video',
            'content' => 'required|string',
            'explanation' => 'nullable|string',
            'difficulty' => 'required|in:easy,medium,hard',
            'subject_id' => 'nullable|exists:subjects,id',
            'grade_id' => 'nullable|exists:grades,id',
            'tags' => 'nullable|string',
            'points' => 'required|numeric|min:0',
            'time_limit' => 'nullable|integer|min:0',
            'is_active' => 'boolean',
        ]);

        // Generate unique question code
        $validated['question_code'] = 'Q-' . strtoupper(uniqid());

        $question = Question::create($validated);
        
        // Create question type specific data
        $this->createQuestionTypeData($question, $request);

        return redirect()
            ->route('admin.questions.index')
            ->with('success', 'تم إنشاء السؤال بنجاح');
    }

    /**
     * Show form for editing the specified resource.
     */
    public function edit(Question $question)
    {
        $question->load(['subject', 'grade', 'creator']);
        
        return view('admin.questions.edit', compact('question'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Question $question)
    {
        $validated = $request->validate([
            'type' => 'required|in:multiple_choice,true_false,essay,fill_blanks,matching,ordering,classification,drag_drop,hotspot,audio,video',
            'content' => 'required|string',
            'explanation' => 'nullable|string',
            'difficulty' => 'required|in:easy,medium,hard',
            'subject_id' => 'nullable|exists:subjects,id',
            'grade_id' => 'nullable|exists:grades,id',
            'tags' => 'nullable|string',
            'points' => 'required|numeric|min:0',
            'time_limit' => 'nullable|integer|min:0',
            'is_active' => 'boolean',
        ]);

        $question->update($validated);
        
        // Update question type specific data
        $this->updateQuestionTypeData($question, $request);

        return redirect()
            ->route('admin.questions.index')
            ->with('success', 'تم تحديث السؤال بنجاح');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Question $question)
    {
        $question->delete();
        
        return redirect()
            ->route('admin.questions.index')
            ->with('success', 'تم حذف السؤال بنجاح');
    }

    /**
     * Create question type specific data based on question type
     */
    private function createQuestionTypeData(Question $question, Request $request)
    {
        switch ($question->type) {
            case 'multiple_choice':
                // Create options
                if ($request->has('options') && is_array($request->options)) {
                    foreach ($request->options as $index => $option) {
                        QuestionOption::create([
                            'question_id' => $question->id,
                            'option_text' => $option['text'],
                            'is_correct' => $option['is_correct'] ?? false,
                            'option_order' => $index,
                            'explanation' => $option['explanation'] ?? null,
                        ]);
                    }
                }
                break;

            case 'true_false':
                // Create boolean answer
                QuestionBooleanAnswer::create([
                    'question_id' => $question->id,
                    'is_correct' => $request->is_correct ?? false,
                    'explanation' => $request->explanation ?? null,
                ]);
                break;

            case 'essay':
                // Create essay question details
                EssayQuestion::create([
                    'question_id' => $question->id,
                    'min_words' => $request->min_words ?? 0,
                    'max_words' => $request->max_words ?? 0,
                    'allow_attachments' => $request->allow_attachments ?? true,
                    'rubric_id' => $request->rubric_id ?? null,
                ]);
                break;

            case 'fill_blanks':
                // Create blanks
                if ($request->has('blanks') && is_array($request->blanks)) {
                    foreach ($request->blanks as $index => $blank) {
                        QuestionBlank::create([
                            'question_id' => $question->id,
                            'blank_order' => $index,
                            'answer' => $blank['answer'],
                            'case_sensitive' => $blank['case_sensitive'] ?? false,
                        ]);
                    }
                }
                break;

            case 'matching':
                // Create matching pairs
                if ($request->has('pairs') && is_array($request->pairs)) {
                    foreach ($request->pairs as $index => $pair) {
                        MatchingPair::create([
                            'question_id' => $question->id,
                            'left_item' => $pair['left_item'],
                            'right_item' => $pair['right_item'],
                            'pair_order' => $index,
                        ]);
                    }
                }
                break;

            case 'ordering':
                // Create ordering items
                if ($request->has('items') && is_array($request->items)) {
                    foreach ($request->items as $index => $item) {
                        OrderingItem::create([
                            'question_id' => $question->id,
                            'item_text' => $item['text'],
                            'correct_order' => $item['order'],
                        ]);
                    }
                }
                break;

            case 'classification':
                // Create categories and items
                if ($request->has('categories') && is_array($request->categories)) {
                    foreach ($request->categories as $index => $category) {
                        $cat = QuestionCategory::create([
                            'name' => $category['name'],
                            'question_id' => $question->id,
                        ]);
                        
                        if ($request->has('items') && is_array($request->items)) {
                            foreach ($request->items as $itemIndex => $item) {
                                ClassificationItem::create([
                                    'question_id' => $question->id,
                                    'category_id' => $cat->id,
                                    'item_text' => $item['text'],
                                    'item_order' => $itemIndex,
                                ]);
                            }
                        }
                    }
                }
                break;

            case 'drag_drop':
                // Create hotspot zones and drag drop items
                if ($request->has('zones') && is_array($request->zones)) {
                    foreach ($request->zones as $index => $zone) {
                        HotspotZone::create([
                            'question_id' => $question->id,
                            'zone_name' => $zone['name'],
                            'coordinates_x' => $zone['x'] ?? null,
                            'coordinates_y' => $zone['y'] ?? null,
                            'width' => $zone['width'] ?? null,
                            'height' => $zone['height'] ?? null,
                            'shape' => $zone['shape'] ?? 'rect',
                        ]);
                    }
                }
                
                if ($request->has('items') && is_array($request->items)) {
                    foreach ($request->items as $index => $item) {
                        DragDropItem::create([
                            'question_id' => $question->id,
                            'item_text' => $item['text'],
                            'target_zone_id' => $item['zone_id'],
                            'item_order' => $index,
                        ]);
                    }
                }
                break;

            case 'hotspot':
                // Create hotspot zones
                if ($request->has('zones') && is_array($request->zones)) {
                    foreach ($request->zones as $index => $zone) {
                        HotspotZone::create([
                            'question_id' => $question->id,
                            'zone_name' => $zone['name'],
                            'coordinates_x' => $zone['x'] ?? null,
                            'coordinates_y' => $zone['y'] ?? null,
                            'width' => $zone['width'] ?? null,
                            'height' => $zone['height'] ?? null,
                            'shape' => $zone['shape'] ?? 'rect',
                        ]);
                    }
                }
                break;

            case 'audio':
                // Create audio question details
                AudioQuestion::create([
                    'question_id' => $question->id,
                    'audio_url' => $request->audio_url,
                    'transcript' => $request->transcript ?? null,
                    'duration' => $request->duration ?? null,
                    'allow_replay' => $request->allow_replay ?? true,
                ]);
                break;

            case 'video':
                // Create video question details
                VideoQuestion::create([
                    'question_id' => $question->id,
                    'video_url' => $request->video_url,
                    'thumbnail_url' => $request->thumbnail_url ?? null,
                    'duration' => $request->duration ?? null,
                    'auto_play' => $request->auto_play ?? false,
                    'allow_download' => $request->allow_download ?? false,
                    'transcript' => $request->transcript ?? null,
                    'start_time' => $request->start_time ?? null,
                    'end_time' => $request->end_time ?? null,
                ]);
                break;
        }
    }

    /**
     * Update question type specific data based on question type
     */
    private function updateQuestionTypeData(Question $question, Request $request)
    {
        switch ($question->type) {
            case 'multiple_choice':
                // Update options
                if ($request->has('options') && is_array($request->options)) {
                    // Delete existing options
                    $question->options()->delete();
                    
                    // Create new options
                    foreach ($request->options as $index => $option) {
                        QuestionOption::create([
                            'question_id' => $question->id,
                            'option_text' => $option['text'],
                            'is_correct' => $option['is_correct'] ?? false,
                            'option_order' => $index,
                            'explanation' => $option['explanation'] ?? null,
                        ]);
                    }
                }
                break;

            case 'true_false':
                // Update boolean answer
                $question->booleanAnswer()->delete();
                
                QuestionBooleanAnswer::create([
                    'question_id' => $question->id,
                    'is_correct' => $request->is_correct ?? false,
                    'explanation' => $request->explanation ?? null,
                ]);
                break;

            case 'essay':
                // Update essay question details
                $question->essayQuestion()->delete();
                
                EssayQuestion::create([
                    'question_id' => $question->id,
                    'min_words' => $request->min_words ?? 0,
                    'max_words' => $request->max_words ?? 0,
                    'allow_attachments' => $request->allow_attachments ?? true,
                    'rubric_id' => $request->rubric_id ?? null,
                ]);
                break;

            case 'fill_blanks':
                // Update blanks
                $question->blanks()->delete();
                
                if ($request->has('blanks') && is_array($request->blanks)) {
                    foreach ($request->blanks as $index => $blank) {
                        QuestionBlank::create([
                            'question_id' => $question->id,
                            'blank_order' => $index,
                            'answer' => $blank['answer'],
                            'case_sensitive' => $blank['case_sensitive'] ?? false,
                        ]);
                    }
                }
                break;

            case 'matching':
                // Update matching pairs
                $question->matchingPairs()->delete();
                
                if ($request->has('pairs') && is_array($request->pairs)) {
                    foreach ($request->pairs as $index => $pair) {
                        MatchingPair::create([
                            'question_id' => $question->id,
                            'left_item' => $pair['left_item'],
                            'right_item' => $pair['right_item'],
                            'pair_order' => $index,
                        ]);
                    }
                }
                break;

            case 'ordering':
                // Update ordering items
                $question->orderingItems()->delete();
                
                if ($request->has('items') && is_array($request->items)) {
                    foreach ($request->items as $index => $item) {
                        OrderingItem::create([
                            'question_id' => $question->id,
                            'item_text' => $item['text'],
                            'correct_order' => $item['order'],
                        ]);
                    }
                }
                break;

            case 'classification':
                // Update categories and items
                $question->categories()->delete();
                
                if ($request->has('categories') && is_array($request->categories)) {
                    foreach ($request->categories as $index => $category) {
                        $cat = QuestionCategory::create([
                            'name' => $category['name'],
                            'question_id' => $question->id,
                        ]);
                        
                        if ($request->has('items') && is_array($request->items)) {
                            foreach ($request->items as $itemIndex => $item) {
                                ClassificationItem::create([
                                    'question_id' => $question->id,
                                    'category_id' => $cat->id,
                                    'item_text' => $item['text'],
                                    'item_order' => $itemIndex,
                                ]);
                            }
                        }
                    }
                }
                break;

            case 'drag_drop':
                // Update hotspot zones and drag drop items
                $question->hotspotZones()->delete();
                
                if ($request->has('zones') && is_array($request->zones)) {
                    foreach ($request->zones as $index => $zone) {
                        HotspotZone::create([
                            'question_id' => $question->id,
                            'zone_name' => $zone['name'],
                            'coordinates_x' => $zone['x'] ?? null,
                            'coordinates_y' => $zone['y'] ?? null,
                            'width' => $zone['width'] ?? null,
                            'height' => $zone['height'] ?? null,
                            'shape' => $zone['shape'] ?? 'rect',
                        ]);
                    }
                }
                
                if ($request->has('items') && is_array($request->items)) {
                    foreach ($request->items as $index => $item) {
                        DragDropItem::create([
                            'question_id' => $question->id,
                            'item_text' => $item['text'],
                            'target_zone_id' => $item['zone_id'],
                            'item_order' => $index,
                        ]);
                    }
                }
                break;

            case 'hotspot':
                // Update hotspot zones
                $question->hotspotZones()->delete();
                
                if ($request->has('zones') && is_array($request->zones)) {
                    foreach ($request->zones as $index => $zone) {
                        HotspotZone::create([
                            'question_id' => $question->id,
                            'zone_name' => $zone['name'],
                            'coordinates_x' => $zone['x'] ?? null,
                            'coordinates_y' => $zone['y'] ?? null,
                            'width' => $zone['width'] ?? null,
                            'height' => $zone['height'] ?? null,
                            'shape' => $zone['shape'] ?? 'rect',
                        ]);
                    }
                }
                break;

            case 'audio':
                // Update audio question details
                $question->audioQuestion()->delete();
                
                AudioQuestion::create([
                    'question_id' => $question->id,
                    'audio_url' => $request->audio_url,
                    'transcript' => $request->transcript ?? null,
                    'duration' => $request->duration ?? null,
                    'allow_replay' => $request->allow_replay ?? true,
                ]);
                break;

            case 'video':
                // Update video question details
                $question->videoQuestion()->delete();
                
                VideoQuestion::create([
                    'question_id' => $question->id,
                    'video_url' => $request->video_url,
                    'thumbnail_url' => $request->thumbnail_url ?? null,
                    'duration' => $request->duration ?? null,
                    'auto_play' => $request->auto_play ?? false,
                    'allow_download' => $request->allow_download ?? false,
                    'transcript' => $request->transcript ?? null,
                    'start_time' => $request->start_time ?? null,
                    'end_time' => $request->end_time ?? null,
                ]);
                break;
        }
    }

    /**
     * Get question types
     */
    public function getQuestionTypes()
    {
        return [
            'multiple_choice' => 'اختيار من متعدد',
            'true_false' => 'صواب وخطأ',
            'essay' => 'مقال',
            'fill_blanks' => 'ملء الفراغات',
            'matching' => 'مطابقة',
            'ordering' => 'ترتيب',
            'classification' => 'تصنيف',
            'drag_drop' => 'سحب وإفلات',
            'hotspot' => 'نقاط ساخنة',
            'audio' => 'صوتي',
            'video' => 'فيديو',
        ];
    }

    /**
     * Get difficulty levels
     */
    public function getDifficultyLevels()
    {
        return [
            'easy' => 'سهل',
            'medium' => 'متوسط',
            'hard' => 'صعب',
        ];
    }
}
