<?php

namespace App\Http\Controllers;

use App\Models\JobPosition;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class JobPositionController
{
    public function getJobPositions(Request $request): JsonResponse
    {
        return response()->json(JobPosition::all());
    }

    public function addJobPosition(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string'],
            'salary' => ['required', 'numeric'],
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
        JobPosition::create($attributes);

        return response()->json(['success' => true]);
    }

    public function updateJobPosition(Request $request, JobPosition $jobPosition): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string'],
            'salary' => ['required', 'numeric'],
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
        $jobPosition->update($attributes);

        return response()->json(['success' => true]);
    }

    public function deleteJobPosition(JobPosition $jobPosition): JsonResponse
    {
        $jobPosition->delete();
        return response()->json(['success' => true]);
    }
}