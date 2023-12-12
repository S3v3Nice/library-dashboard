<?php

namespace App\Http\Controllers;

use App\Models\BookAuthor;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BookAuthorController extends Controller
{
    public function getAuthors(Request $request): JsonResponse
    {
        return response()->json(BookAuthor::all());
    }

    public function addAuthor(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'first_name' => ['required', 'string'],
            'last_name'  => ['required', 'string'],
            'patronymic' => ['required', 'string'],
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
        BookAuthor::create($attributes);

        return response()->json(['success' => true]);
    }

    public function updateAuthor(Request $request, BookAuthor $author): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'first_name' => ['required', 'string'],
            'last_name'  => ['required', 'string'],
            'patronymic' => ['required', 'string'],
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
        $author->update($attributes);

        return response()->json(['success' => true]);
    }

    public function deleteAuthor(BookAuthor $author): JsonResponse
    {
        $author->delete();
        return response()->json(['success' => true]);
    }
}
