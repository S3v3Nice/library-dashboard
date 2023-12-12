<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\BookAuthor;
use App\Models\BookGenre;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Exists;

class BookController
{
    public function getBooks(Request $request): JsonResponse
    {
        return response()->json(Book::with('author')->with('genre')->get());
    }

    public function addBook(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string'],
            'year' => ['required', 'int'],
            'count' => ['required', 'int', 'min:1'],
            'author_id' => ['required', 'int', new Exists(BookAuthor::class, 'id')],
            'genre_id' => ['required', 'int', new Exists(BookGenre::class, 'id')],
        ]);

        if ($validator->fails()) {
            return response()->json(
                [
                    'success' => false,
                    'errors'  => $validator->errors(),
                ]
            );
        }

        $attributes = $validator->valid();

        $book = Book::make($attributes);
        $book->author()->associate(BookAuthor::find($request->get('author_id')));
        $book->genre()->associate(BookGenre::find($request->get('genre_id')));
        $book->save();

        return response()->json(['success' => true]);
    }

    public function updateBook(Request $request, Book $book): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string'],
            'year' => ['required', 'int'],
            'count' => ['required', 'int', 'min:1'],
            'author_id' => ['required', 'int', new Exists(BookAuthor::class, 'id')],
            'genre_id' => ['required', 'int', new Exists(BookGenre::class, 'id')],
        ]);

        if ($validator->fails()) {
            return response()->json(
                [
                    'success' => false,
                    'errors'  => $validator->errors(),
                ]
            );
        }

        $attributes = $validator->valid();

        $book->update($attributes);
        $book->author()->associate(BookAuthor::find($request->get('author_id')));
        $book->genre()->associate(BookGenre::find($request->get('genre_id')));
        $book->save();

        return response()->json(['success' => true]);
    }

    public function deleteBook(Book $book): JsonResponse
    {
        $book->delete();
        return response()->json(['success' => true]);
    }
}