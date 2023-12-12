<?php

namespace App\Http\Controllers;

use App\Models\Reader;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ReaderController
{
    public function getReaders(Request $request): JsonResponse
    {
        return response()->json(Reader::all());
    }

    public function addReader(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'first_name'               => ['required', 'string'],
            'last_name'                => ['required', 'string'],
            'patronymic'               => ['required', 'string'],
            'library_card_issue_date'  => ['required', 'date_format:Y-m-d'],
            'library_card_expiry_date' => ['date_format:Y-m-d', 'nullable'],
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

        Reader::create($attributes);

        return response()->json(['success' => true]);
    }

    public function updateReader(Request $request, Reader $reader): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'first_name'               => ['required', 'string'],
            'last_name'                => ['required', 'string'],
            'patronymic'               => ['required', 'string'],
            'library_card_issue_date'  => ['required', 'date_format:Y-m-d'],
            'library_card_expiry_date' => ['date_format:Y-m-d', 'nullable'],
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
        $reader->update($attributes);


        return response()->json(['success' => true]);
    }

    public function deleteReader(Reader $reader): JsonResponse
    {
        $reader->delete();
        return response()->json(['success' => true]);
    }
}