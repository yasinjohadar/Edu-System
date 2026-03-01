<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BookCategory;
use Illuminate\Http\Request;

class BookCategoryController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:book-category-list')->only('index', 'show');
        $this->middleware('permission:book-category-create')->only('create', 'store');
        $this->middleware('permission:book-category-edit')->only('edit', 'update');
        $this->middleware('permission:book-category-delete')->only('destroy');
    }

    public function index(Request $request)
    {
        $categoriesQuery = BookCategory::with('books')->orderBy('name');

        if ($request->filled('query')) {
            $search = $request->input('query');
            $categoriesQuery->where(function ($q) use ($search) {
                $q->where('name', 'like', "%$search%")
                  ->orWhere('name_en', 'like', "%$search%")
                  ->orWhere('code', 'like', "%$search%");
            });
        }

        if ($request->filled('is_active')) {
            $categoriesQuery->where('is_active', $request->input('is_active'));
        }

        $categories = $categoriesQuery->paginate(15);

        return view('admin.pages.book-categories.index', compact('categories'));
    }

    public function create()
    {
        $parentCategories = BookCategory::where('is_active', true)->orderBy('name')->get();
        return view('admin.pages.book-categories.create', compact('parentCategories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'name_en' => 'nullable|string|max:255',
            'code' => 'required|string|max:255|unique:book_categories,code',
            'description' => 'nullable|string',
            'parent_id' => 'nullable|exists:book_categories,id',
            'is_active' => 'boolean',
        ]);

        BookCategory::create($request->all());

        return redirect()->route('admin.book-categories.index')->with('success', 'تم إنشاء التصنيف بنجاح');
    }

    public function show(string $id)
    {
        $category = BookCategory::with(['books', 'parent', 'children'])->findOrFail($id);
        return view('admin.pages.book-categories.show', compact('category'));
    }

    public function edit(string $id)
    {
        $category = BookCategory::findOrFail($id);
        $parentCategories = BookCategory::where('is_active', true)
            ->where('id', '!=', $id)
            ->orderBy('name')
            ->get();
        return view('admin.pages.book-categories.edit', compact('category', 'parentCategories'));
    }

    public function update(Request $request, string $id)
    {
        $category = BookCategory::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'name_en' => 'nullable|string|max:255',
            'code' => 'required|string|max:255|unique:book_categories,code,' . $id,
            'description' => 'nullable|string',
            'parent_id' => 'nullable|exists:book_categories,id',
            'is_active' => 'boolean',
        ]);

        $category->update($request->all());

        return redirect()->route('admin.book-categories.index')->with('success', 'تم تحديث التصنيف بنجاح');
    }

    public function destroy(string $id)
    {
        $category = BookCategory::findOrFail($id);

        if ($category->books()->count() > 0) {
            return redirect()->route('admin.book-categories.index')
                ->with('error', 'لا يمكن حذف التصنيف لأنه يحتوي على كتب');
        }

        $category->delete();

        return redirect()->route('admin.book-categories.index')->with('success', 'تم حذف التصنيف بنجاح');
    }
}
