<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

class AuthController extends Controller
{
    public function login(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'email'    => ['required'],
            'password' => ['required']
        ]);

        if ($validator->fails()) {
            return response()->json(
                [
                    'success' => false,
                    'errors'  => $validator->errors()
                ]
            );
        }

        $attributes = [
            'email'    => $request->get('email'),
            'password' => $request->get('password')
        ];

        if (!Auth::attempt($attributes, $request->get('remember', false))) {
            return response()->json(
                [
                    'success' => false,
                    'errors'  => ['email' => ['Неверный адрес эл. почты или пароль.']]
                ]
            );
        }

        $request->session()->regenerate();
        return response()->json(['success' => true]);
    }

    public function logout(): JsonResponse
    {
        Auth::logout();
        return response()->json(['success' => true]);
    }
}
