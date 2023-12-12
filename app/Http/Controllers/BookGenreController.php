<?php

namespace App\Http\Controllers;

use App\Models\BookGenre;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BookGenreController extends Controller
{
    public function getGenres(Request $request): JsonResponse
    {
        return response()->json(BookGenre::all());
    }

    public function addGenre(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string'],
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
        BookGenre::create($attributes);

        return response()->json(['success' => true]);
    }

    public function updateGenre(Request $request, BookGenre $genre): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string'],
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
        $genre->update($attributes);

        return response()->json(['success' => true]);
    }

    public function deleteGenre(BookGenre $genre): JsonResponse
    {
        $genre->delete();
        return response()->json(['success' => true]);
    }
}
