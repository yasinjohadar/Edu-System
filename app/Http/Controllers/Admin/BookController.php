<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\BookCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BookController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:book-list')->only('index', 'show');
        $this->middleware('permission:book-create')->only('create', 'store');
        $this->middleware('permission:book-edit')->only('edit', 'update');
        $this->middleware('permission:book-delete')->only('destroy');
    }

    public function index(Request $request)
    {
        $booksQuery = Book::with('category')->orderBy('title');

        if ($request->filled('query')) {
            $search = $request->input('query');
            $booksQuery->where(function ($q) use ($search) {
                $q->where('title', 'like', "%$search%")
                  ->orWhere('title_en', 'like', "%$search%")
                  ->orWhere('author', 'like', "%$search%")
                  ->orWhere('isbn', 'like', "%$search%");
            });
        }

        if ($request->filled('category_id')) {
            $booksQuery->where('category_id', $request->input('category_id'));
        }

        if ($request->filled('is_active')) {
            $booksQuery->where('is_active', $request->input('is_active'));
        }

        $books = $booksQuery->paginate(15);
        $categories = BookCategory::where('is_active', true)->orderBy('name')->get();

        return view('admin.pages.books.index', compact('books', 'categories'));
    }

    public function create()
    {
        $categories = BookCategory::where('is_active', true)->orderBy('name')->get();
        return view('admin.pages.books.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'category_id' => 'required|exists:book_categories,id',
            'isbn' => 'nullable|string|max:255|unique:books,isbn',
            'title' => 'required|string|max:255',
            'title_en' => 'nullable|string|max:255',
            'author' => 'required|string|max:255',
            'publisher' => 'nullable|string|max:255',
            'publication_year' => 'nullable|integer|min:1000|max:' . date('Y'),
            'language' => 'nullable|string|max:10',
            'total_copies' => 'required|integer|min:1',
            'description' => 'nullable|string',
            'cover_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'location' => 'nullable|string|max:255',
            'price' => 'nullable|numeric|min:0',
            'pages' => 'nullable|integer|min:1',
            'edition' => 'nullable|string|max:255',
            'is_active' => 'boolean',
        ]);

        $data = $request->except('cover_image');
        $data['available_copies'] = $request->total_copies;

        if ($request->hasFile('cover_image')) {
            $image = $request->file('cover_image');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $data['cover_image'] = $image->storeAs('books/covers', $imageName, 'public');
        }

        Book::create($data);

        return redirect()->route('admin.books.index')->with('success', 'تم إنشاء الكتاب بنجاح');
    }

    public function show(string $id)
    {
        $book = Book::with(['category', 'borrowings.student.user'])->findOrFail($id);
        return view('admin.pages.books.show', compact('book'));
    }

    public function edit(string $id)
    {
        $book = Book::findOrFail($id);
        $categories = BookCategory::where('is_active', true)->orderBy('name')->get();
        return view('admin.pages.books.edit', compact('book', 'categories'));
    }

    public function update(Request $request, string $id)
    {
        $book = Book::findOrFail($id);

        $request->validate([
            'category_id' => 'required|exists:book_categories,id',
            'isbn' => 'nullable|string|max:255|unique:books,isbn,' . $id,
            'title' => 'required|string|max:255',
            'title_en' => 'nullable|string|max:255',
            'author' => 'required|string|max:255',
            'publisher' => 'nullable|string|max:255',
            'publication_year' => 'nullable|integer|min:1000|max:' . date('Y'),
            'language' => 'nullable|string|max:10',
            'total_copies' => 'required|integer|min:1',
            'description' => 'nullable|string',
            'cover_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'location' => 'nullable|string|max:255',
            'price' => 'nullable|numeric|min:0',
            'pages' => 'nullable|integer|min:1',
            'edition' => 'nullable|string|max:255',
            'is_active' => 'boolean',
        ]);

        $data = $request->except('cover_image');

        // تحديث عدد النسخ المتاحة
        $borrowedCount = $book->borrowings()->whereIn('status', ['borrowed', 'overdue'])->count();
        $data['available_copies'] = max(0, $request->total_copies - $borrowedCount);

        if ($request->hasFile('cover_image')) {
            // حذف الصورة القديمة
            if ($book->cover_image) {
                Storage::disk('public')->delete($book->cover_image);
            }
            $image = $request->file('cover_image');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $data['cover_image'] = $image->storeAs('books/covers', $imageName, 'public');
        }

        $book->update($data);

        return redirect()->route('admin.books.index')->with('success', 'تم تحديث الكتاب بنجاح');
    }

    public function destroy(string $id)
    {
        $book = Book::findOrFail($id);

        if ($book->borrowings()->whereIn('status', ['borrowed', 'overdue'])->count() > 0) {
            return redirect()->route('admin.books.index')
                ->with('error', 'لا يمكن حذف الكتاب لأنه مستعار حالياً');
        }

        if ($book->cover_image) {
            Storage::disk('public')->delete($book->cover_image);
        }

        $book->delete();

        return redirect()->route('admin.books.index')->with('success', 'تم حذف الكتاب بنجاح');
    }
}
