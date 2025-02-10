<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use App\Models\EmailVerification;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Carbon\Carbon;

class AuthController extends Controller
{
    //
    public function register(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'password' => 'required|string|min:8',
                'accepted_terms' => 'required|boolean',
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => collect($e->validator->errors()->messages())->flatten()->first(),
            ]);
        }

        try {
            User::firstOrCreate([
                'name' => $validatedData['name'],
                'email' => $validatedData['email'],
                'password' => Hash::make($validatedData['password']),
                'accepted_terms' => $validatedData['accepted_terms'],
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Cadastro realizado com sucesso!',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao criar conta. Tente novamente.',
            ]);
        }
    }

    public function login(Request $request)
    {
        try {
            $request->validate([
                'email' => 'required|string|email',
                'password' => 'required|string|min:6',
                'device_token' => 'nullable|string'
            ]);

            $user = User::where('email', $request->email)->first();

            if (!$user || !Hash::check($request->password, $user->password)) {
                return response()->json([
                    'success' => false,
                    'message' => 'E-mail ou senha incorretos.',
                ]);
            }

            if ($request->has('device_token')) {
                $user->update(['device_token' => $request->device_token]);
            }

            $token = $user->createToken('auth_token')->plainTextToken;

            return response()->json([
                'success' => true,
                'message' => 'Login realizado com sucesso.',
                'data' => [
                    'access_token' => $token,
                    'token_type' => 'Bearer',
                ],
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao tentar fazer login.',
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
        try {
            $user = $request->user();

            $user->update(['last_login' => now()]);

            $user->load(
                'userDenomination.denomination',
                'address',
                'photos',
                'interests'
            );

            $denomination = $user->userDenomination->denomination->name ?? null;
            $storageUrl = 'https://epjmiianomyfekdjufod.supabase.co/storage/v1/object/public/photos';
            $photos = $user->photos->pluck('photo_name')->map(fn($photoName) => "{$storageUrl}/{$photoName}");
            $interests = $user->interests->pluck('name');
            $age = $user->date_of_birth ? \Carbon\Carbon::parse($user->date_of_birth)->age : null;

            return response()->json([
                'success' => true,
                'data' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'email_verified_at' => $user->email_verified_at,
                    'age' => $age,
                    'gender' => $user->gender,
                    'bio' => $user->bio,
                    'denomination' => $denomination,
                    'address' => $user->address,
                    'photos' => $photos,
                    'interests' => $interests,
                ],
                'message' => 'Perfil carregado com sucesso.',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao carregar perfil.',
            ]);
        }
    }

    public function sendEmailCodeConfirmation(Request $request)
    {
        try {
            $user = $request->user();

            // Gerar um novo código de 4 dígitos numérico
            $verificationCode = str_pad(mt_rand(0, 9999), 4, '0', STR_PAD_LEFT);

            // Criar ou atualizar código de verificação no banco
            EmailVerification::updateOrCreate(
                ['user_id' => $user->id],
                [
                    'code' => $verificationCode,
                    'expires_at' => Carbon::now()->addMinutes(5),
                ]
            );

            // Conteúdo do e-mail
            $htmlContent = "
        <div style='font-family: Arial, sans-serif; text-align: center; padding: 20px; background-color: #ffffff;'>
            <div style='max-width: 500px; background-color: white; padding: 20px; border-radius: 10px; 
                        box-shadow: 0 0 10px rgba(0,0,0,0.1); margin: auto;'>
                <h1 style='color: #6E0000;'>Seja muito bem-vindo(a) ao Jesus Love! 🙏</h1>
                <p style='font-size: 16px; color: #333;'>Estamos muito felizes por ter você aqui! Para começar essa jornada,
                basta confirmar seu e-mail utilizando o código abaixo:</p>
                <div style='font-size: 28px; font-weight: bold; color: white; background-color: #6E0000; 
                            display: block; padding: 15px 30px; border-radius: 5px; margin: 10px auto; 
                            letter-spacing: 2px; text-align: center; max-width: 150px;'>
                    {$verificationCode}
                </div>
                <p style='color: #777;'>O código expira em <strong>5 minutos</strong>.</p>
                <p style='font-size: 14px; color: #6E0000;'>Que Deus abençoe sua caminhada e traga muitas bênçãos para sua vida! ❤️</p>
            </div>
        </div>
        ";

            $textContent = "Seja muito bem-vindo(a) ao Jesus Love! 🙏\n\n"
                . "Estamos muito felizes por ter você aqui! Para começar essa jornada, basta confirmar seu e-mail utilizando o código abaixo:\n\n"
                . "CÓDIGO: {$verificationCode}\n\n"
                . "O código expira em 5 minutos.\n\n"
                . "Que Deus abençoe sua caminhada e traga muitas bênçãos para sua vida! ❤️";

            // Enviar o e-mail
            Mail::send([], [], function ($message) use ($user, $htmlContent, $textContent) {
                $message->to($user->email)
                    ->subject('Seja bem-vindo(a) ao Jesus Love! Confirme seu e-mail 🙏')
                    ->text($textContent)
                    ->html($htmlContent);
            });

            return response()->json([
                'success' => true,
                'message' => 'Código de verificação enviado para o seu e-mail! Estamos felizes por ter você aqui. 😊',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Ops! Ocorreu um erro ao enviar o código de verificação. Tente novamente.',
            ], 500);
        }
    }

    public function verifyEmailCode(Request $request)
    {
        try {
            // Validação do código antes de consultar o banco
            $validatedData = $request->validate([
                'code' => 'required|numeric|digits:4', // Garante que o código foi enviado e é numérico com 4 dígitos
            ]);

            $user = $request->user();
            $code = $validatedData['code']; // Pegando o código validado

            // Buscar o código na tabela EmailVerification
            $verification = EmailVerification::where('user_id', $user->id)
                ->where('code', $code)
                ->first();

            // Se o código não existir, retorna erro
            if (!$verification) {
                return response()->json([
                    'success' => false,
                    'message' => 'Código inválido. Verifique e tente novamente.',
                ], 200);
            }

            // Verificar se o código está expirado
            if (Carbon::now()->gt($verification->expires_at)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Código expirado. Solicite um novo código.',
                ], 200);
            }

            // Atualizar o usuário para indicar que o e-mail foi confirmado
            $user->email_verified_at = Carbon::now();
            $user->save();

            // Remover o registro da tabela EmailVerification
            $verification->delete();

            return response()->json([
                'success' => true,
                'message' => 'E-mail confirmado com sucesso!',
            ]);
        } catch (ValidationException $e) {
            // Capturar erro de validação e retornar mensagem clara
            $errors = $e->validator->errors();
            $firstErrorMessage = collect($errors->messages())->flatten()->first();

            return response()->json([
                'success' => false,
                'message' => $firstErrorMessage,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao verificar o código.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function updateEmail(Request $request)
    {
        try {
            // Validação do e-mail
            $validatedData = $request->validate([
                'email' => 'required|email|unique:users,email', // Garante que o e-mail é válido e único
            ]);

            $user = $request->user();
            $newEmail = $validatedData['email'];

            // Atualizar o e-mail do usuário
            $user->email = $newEmail;
            $user->email_verified_at = null; // Invalida a verificação anterior
            $user->save();

            return response()->json([
                'success' => true,
                'message' => 'E-mail atualizado com sucesso! Por favor, verifique seu novo e-mail.',
            ]);
        } catch (ValidationException $e) {
            // Capturar erro de validação e retornar mensagem clara
            $errors = $e->validator->errors();
            $firstErrorMessage = collect($errors->messages())->flatten()->first();

            return response()->json([
                'success' => false,
                'message' => $firstErrorMessage,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao atualizar o e-mail.',
            ], 500);
        }
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
