<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class UserController extends Controller
{
    public function getUsers(Request $request): JsonResponse
    {
        return response()->json(User::all());
    }

    public function addUser(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'email'      => ['required', 'email'],
            'first_name' => ['required', 'string'],
            'last_name'  => ['string', 'nullable'],
            'password'   => ['required', Password::min(8)],
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
        User::create($attributes);

        return response()->json(['success' => true]);
    }

    public function updateUser(Request $request, User $user): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'email'      => ['required', 'email', Rule::unique(User::class)->ignore($user)],
            'first_name' => ['required', 'string'],
            'last_name'  => ['string', 'nullable'],
            'password'   => [Password::min(8)],
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
        $user->update($attributes);

        // После этого, если пользователь поменял самому себе пароль, его выкидывает из аккаунта.
        // Я перепробовал все, что предлагают в инете, чтоб исправить это
        // (установить новый 'password_hash_web' в сессии; залогинить пользователя заново и т.п.),
        // ничего не помогло. Похоже на то, что это проблема Sanctum, или же системы авторизации
        // на основе cookie сессии.

        return response()->json(['success' => true]);
    }

    public function deleteUser(User $user): JsonResponse
    {
        $user->delete();
        return response()->json(['success' => true]);
    }
}
