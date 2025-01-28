<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    //
    public function register(Request $request)
    {
        try {
            //code...
            $validatedData = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'password' => 'required|string|min:8',
                'accepted_terms' => 'required|boolean',
            ]);
        } catch (ValidationException $e) {
            $errors = $e->validator->errors();

            $firstErrorMessage = collect(
                $errors->messages(),
            )->flatten()->first();

            return response()->json([
                'success' => false,
                'message' => $firstErrorMessage,
            ]);
        }

        try {
            //code...
            User::firstOrCreate([
                'name' => $validatedData['name'],
                'email' => $validatedData['email'],
                'password' => Hash::make($validatedData['password']),
                'accepted_terms' => $validatedData['accepted_terms'],
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Usuário criado com sucesso.',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Ocorreu um erro ao criar o usuário.',
            ]);
        }
    }

    public function login(Request $request)
    {
        try {
            //code...
            $user = User::where('email', $request['email'])->firstOrFail();
        } catch (\Exception $e) {
            //
            return response()->json([
                'success' => false,
                'message' => 'E-mail ou senha incorretos.',
            ]);
        }

        try {
            //code...
            $tokenData = [];
            $cretendials = $request->only('email', 'password');

            if (Auth::attempt($cretendials)) {
                $token = $user->createToken('auth_token')->plainTextToken;

                $tokenData = [
                    'access_token' => $token,
                    'token_type' => 'Bearer',
                ];

                return response()->json([
                    'success' => true,
                    'data' => $tokenData,
                    'message' => 'Login realizado com sucesso.',
                ]);
            } else {
                throw new \Exception('E-mail ou senha incorretos.');
            }
        } catch (\Exception $e) {
            //
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ]);
        }
    }

    public function update(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'name' => 'string|max:255|nullable',
                'email' => 'string|email|nullable|max:255|unique:users,email,' . $request->user()->id,
                'phone_number' => 'string|max:255|nullable',
                'gender' => 'string|max:255|nullable',
                'age' => 'integer|nullable',
                'bio' => 'string|max:255|nullable',
            ]);
        } catch (ValidationException $e) {
            $errors = $e->validator->errors();
            $firstErrorMessage = collect($errors->messages())->flatten()->first();

            return response()->json([
                'success' => false,
                'message' => $firstErrorMessage,
            ]);
        }

        try {
            $validatedData = array_filter($validatedData, fn($value) => !is_null($value));

            if (isset($validatedData['age'])) {
                $yearOfBirth = now()->year - $validatedData['age'];
                $validatedData['date_of_birth'] = "{$yearOfBirth}-01-01";
                unset($validatedData['age']);
            }

            $user = $request->user();
            $user->update($validatedData);

            return response()->json([
                'success' => true,
                'data' => $user,
                'message' => 'Perfil atualizado com sucesso',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function profile(Request $request)
    {
        $user = $request->user()->load(
            'userDenomination.denomination',
            'address',
            'photos',
            'interests'
        );

        $denomination = $user->userDenomination->denomination->name ?? null;

        $storageUrl = env('SUPABASE_STORAGE_URL');

        $photos = $user->photos->pluck('photo_name');

        $photos = $photos->map(function ($photoName) use ($storageUrl) {
            return "{$storageUrl}/{$photoName}";
        });

        $interests = $user->interests->pluck('name');

        $age = $user->date_of_birth
            ? floor(abs(now()->diffInSeconds(\Carbon\Carbon::parse($user->date_of_birth)) / (60 * 60 * 24 * 365)))
            : null;

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'age' => $age,
                'gender' => $user->gender,
                'bio' => $user->bio,
                'denomination' => $denomination,
                'address' => $user->address,
                'photos' => $photos,
                'interests' => $interests,
            ],
            'message' => '',
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'success' => true,
            'message' => 'Logout realizado com sucesso!',
        ]);
    }
}
