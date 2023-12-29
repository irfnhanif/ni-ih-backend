<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;

class BookController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $books = Book::where('user_id', auth()->id())->paginate(10);
            return response()->json(['books' => $books]);
        } catch (\Exception) {
            return response()->json(['message' => 'Server error'], 500);
        }
    }

    
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'isbn' => 'required|string|unique:books',
            'title' => 'required|string|max:255',
            'subtitle' => 'string|nullable',
            'author' => 'string|nullable',
            'published' => 'datetime|nullable',
            'publisher' => 'string|nullable',
            'pages' => 'int|nullable',
            'description' => 'string|nullable',
            'website' => 'string|nullable',
        ]);

        $validatedData['user_id'] = auth()->id();
        try {
            $book = Book::create($validatedData);
            return response()->json(['message' => 'Book created', 'book' => $book]);
        } catch (\Exception) {
            return response()->json(['message' => 'Server error'], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request)
    {
        $book = Book::where('id', (int)$request->book_id)->first();

        if (!$book) {
            return response()->json(['message' => 'Book not found'], 404);
        }

        if ($book->user_id !== auth()->id()) {
            return response()->json(['message' => 'Forbidden to access book'], 403);
        }

        return response()->json(['book' => $book]);
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $book = Book::where('id', (int)$request->book_id)->first();
        
        if (!$book) {
            return response()->json(['message' => 'Book not found'], 404);
        }

        if ($book->user_id !== auth()->id()) {
            return response()->json(['message' => 'Forbidden to access book'], 403);
        }

        $validatedData = $request->validate([
            'isbn' => 'required|string|unique:books,isbn,' . $book->id,
            'title' => 'required|string|max:255',
            'subtitle' => 'string|nullable',
            'author' => 'string|nullable',
            'published' => 'datetime|nullable',
            'publisher' => 'string|nullable',
            'pages' => 'int|nullable',
            'description' => 'string|nullable',
            'website' => 'string|nullable',
        ]);

        try {
            $book->update($validatedData);
            return response()->json(['message' => 'Book updated', 'book' => $book]);
        } catch (\Exception) {
            return response()->json(['message' => 'Server error'], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Book $book)
    {
        //
    }
}
