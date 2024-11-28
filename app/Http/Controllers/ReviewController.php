<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use App\Models\Review;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReviewController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        // Only apply reviewer check on create/store/edit/update/delete
        $this->middleware('can:manage-reviews')->only(['create', 'store', 'edit', 'update', 'destroy']);
    }

    public function index()
    {
        $reviews = Review::with(['book', 'user', 'tags'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        
        return view('reviews.index', compact('reviews'));
    }

    public function create()
    {
        $books = Buku::all();
        return view('reviews.create', compact('books'));
    }

    public function store(Request $request)
    {
        // Validate request
        $validated = $request->validate([
            'buku_id' => 'required|exists:books,id',
            'content' => 'required|string|min:10',
            'tags' => 'required|string'
        ]);

        try {
            DB::transaction(function () use ($request) {
                // Create review
                $review = Review::create([
                    'buku_id' => $request->buku_id,
                    'user_id' => auth()->id(),
                    'content' => $request->content
                ]);

                // Handle tags
                $tagNames = array_map('trim', explode(',', $request->tags));
                $tags = collect($tagNames)->map(function ($tagName) {
                    return Tag::firstOrCreate(['name' => $tagName]);
                });

                // Attach tags to review
                $review->tags()->attach($tags->pluck('id'));
            });

            return redirect()
                ->route('reviews.index')
                ->with('success', 'Review created successfully');

        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->withErrors(['error' => 'Failed to save review. Please try again.']);
        }
    }

    public function byReviewer($userId)
    {
        $reviews = Review::where('user_id', $userId)
            ->with(['book', 'tags'])
            ->orderBy('created_at', 'desc')
            ->paginate(10); // Changed from get() to paginate()
            
        return view('reviews.by-reviewer', compact('reviews'));
    }

    public function byTag($tagName)
    {
        $tag = Tag::where('name', $tagName)->firstOrFail();
        $reviews = $tag->reviews()
            ->with(['book', 'user'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);  // Change from get() to paginate()
            
        return view('reviews.by-tag', compact('reviews', 'tag'));
    }
}
