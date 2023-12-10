<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function users(Request $request): JsonResponse
    {
        return response()->json(User::all());
    }
}
