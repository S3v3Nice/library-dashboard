<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\JobPosition;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Exists;

class EmployeeController
{
    public function getEmployees(Request $request): JsonResponse
    {
        return response()->json(Employee::with('position')->get());
    }

    public function addEmployee(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'first_name' => ['required', 'string'],
            'last_name'  => ['required', 'string'],
            'patronymic' => ['required', 'string'],
            'position_id' => ['required', 'int', new Exists(JobPosition::class, 'id')],
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

        $employee = Employee::make($attributes);
        $employee->position()->associate(JobPosition::find($request->get('position_id')));
        $employee->save();

        return response()->json(['success' => true]);
    }

    public function updateEmployee(Request $request, Employee $employee): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'first_name' => ['required', 'string'],
            'last_name'  => ['required', 'string'],
            'patronymic' => ['required', 'string'],
            'position_id' => ['required', 'int', new Exists(JobPosition::class, 'id')],
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

        $employee->update($attributes);
        $employee->position()->associate(JobPosition::find($request->get('position_id')));
        $employee->save();

        return response()->json(['success' => true]);
    }

    public function deleteEmployee(Employee $employee): JsonResponse
    {
        $employee->delete();
        return response()->json(['success' => true]);
    }
}