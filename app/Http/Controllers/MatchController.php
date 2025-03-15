<?php

namespace App\Http\Controllers;

use App\Models\Like;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use App\Http\Controllers\NotificationController;
use App\Models\UserLike;
use Kreait\Firebase\Contract\Messaging;

class MatchController extends Controller
{
    //
    public function profiles(Request $request)
    {
        $user = $request->user();

        $genderToMatch = $user->userProfile->gender === 'male' ? 'female' : 'male';

        $request->validate([
            'distance' => 'required|integer|min:0',
            'age_min' => 'required|integer|min:0|max:100',
            'age_max' => 'required|integer|min:0|max:100',
        ]);

        $ageMin = $request->input('age_min');
        $ageMax = $request->input('age_max');
        $maxDistance = (int) $request->input('distance');

        // Pegue a latitude e longitude do usuário autenticado
        $userLat = $user->address->lat;
        $userLong = $user->address->long;

        // Busca perfis correspondentes (somente usuários ativos)
        $profiles = User::with([
            'userProfile',
            'userAccount',
            'userPersonalDetail.maritalStatus',
            'userPersonalDetail.childrenPreference',
            'userPersonalDetail.education',
            'userDenomination.denomination',
            'address',
            'photos',
            'interests'
        ])
            ->whereHas('userProfile', function ($query) use ($genderToMatch) {
                $query->where('gender', $genderToMatch);
            })
            ->where('id', '!=', $user->id)
            ->whereHas('userAccount', function ($query) {
                $query->where('is_active', true);
            })
            ->whereHas('userProfile', function ($query) use ($ageMin, $ageMax) {
                $query->whereRaw('TIMESTAMPDIFF(YEAR, date_of_birth, CURDATE()) BETWEEN ? AND ?', [$ageMin, $ageMax]);
            })
            ->whereHas('address', function ($query) use ($userLat, $userLong, $maxDistance) {
                $query->whereRaw("
                    6371 * acos(
                        cos(radians(?)) *
                        cos(radians(lat)) *
                        cos(radians(`long`) - radians(?)) +
                        sin(radians(?)) *
                        sin(radians(lat))
                    ) <= ?
                ", [$userLat, $userLong, $userLat, $maxDistance]);
            })
            ->get()
            ->map(function ($profile) use ($userLat, $userLong, $user) {
                $storageUrl = 'https://epjmiianomyfekdjufod.supabase.co/storage/v1/object/public/photos';

                // Calcula a idade
                $age = optional($profile->userProfile)->date_of_birth
                    ? \Carbon\Carbon::parse($profile->userProfile->date_of_birth)->age
                    : null;

                // Calcula a distância entre os usuários
                $distance = $this->calculateDistance($userLat, $userLong, $profile->address->lat, $profile->address->long);

                // Processa fotos
                $photos = $profile->photos->pluck('photo_name')->map(fn($photoName) => "{$storageUrl}/{$photoName}");

                // Verifica se o usuário autenticado já curtiu esse perfil
                $like = UserLike::where('user_who_liked', $user->id)
                    ->where('user_liked', $profile->id)
                    ->first();

                $alreadyLiked = $like && $like->like_type_id == 1;
                $superLiked = $like && $like->like_type_id == 2;

                return [
                    'id' => $profile->id,
                    'name' => $profile->name,
                    'email' => $profile->email,
                    'email_verified_at' => $profile->email_verified_at,
                    'age' => $age,
                    'gender' => optional($profile->userProfile)->gender,
                    'bio' => optional($profile->userProfile)->bio,
                    'phone_number' => optional($profile->userProfile)->phone_number,
                    'denomination' => $profile->userDenomination->denomination->name ?? null,
                    'marital_status' => optional($profile->userPersonalDetail)->maritalStatus->name ?? null,
                    'children_preference' => optional($profile->userPersonalDetail)->childrenPreference->name ?? null,
                    'education' => optional($profile->userPersonalDetail)->education->name ?? null,
                    'is_active' => optional($profile->userAccount)->is_active ?? false,
                    'last_login' => optional($profile->userAccount)->last_login,
                    'address' => [
                        'state' => $profile->address->state,
                        'city' => $profile->address->city,
                        'lat' => $profile->address->lat,
                        'long' => $profile->address->long
                    ],
                    'photos' => $photos,
                    'interests' => $profile->interests->pluck('name'),
                    'distance' => "{$distance} km",
                    'liked' => $alreadyLiked, // Se foi curtido com like normal
                    'superliked' => $superLiked, // Se foi curtido com superlike
                ];
            })->toArray();

        shuffle($profiles);

        return response()->json([
            'success' => true,
            'data' => $profiles,
            'message' => 'Perfis carregados com sucesso.',
        ]);
    }

    /**
     * Calcula a distância entre dois pontos geográficos usando a fórmula de Haversine.
     *
     * @param float $lat1
     * @param float $long1
     * @param float $lat2
     * @param float $long2
     * @return float
     */
    private function calculateDistance($lat1, $long1, $lat2, $long2)
    {
        $earthRadius = 6371; // Raio da Terra em quilômetros

        $lat1 = deg2rad($lat1);
        $long1 = deg2rad($long1);
        $lat2 = deg2rad($lat2);
        $long2 = deg2rad($long2);

        $deltaLat = $lat2 - $lat1;
        $deltaLong = $long2 - $long1;

        $a = sin($deltaLat / 2) * sin($deltaLat / 2) +
            cos($lat1) * cos($lat2) *
            sin($deltaLong / 2) * sin($deltaLong / 2);

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        return round($earthRadius * $c, 2); // Distância arredondada para 2 casas decimais
    }

    public function like(Request $request)
    {
        try {
            // Validação dos dados
            $validatedData = $request->validate([
                'like_type_id' => 'required|integer',
                'user_liked' => 'required|integer',
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
            $user = $request->user();
            $likedUserId = $validatedData['user_liked'];

            // Tenta enviar a notificação
            // $notificationController = new NotificationController();
            // $notificationController->sendLikedNotification($user->id, $likedUserId);

            // Atualiza o like caso já exista, senão cria um novo
            UserLike::updateOrCreate(
                [
                    'user_who_liked' => $user->id,
                    'user_liked' => $likedUserId,
                ],
                [
                    'like_type_id' => $validatedData['like_type_id']
                ]
            );

            // Verifica se há um match recíproco
            $reciprocalLike = UserLike::where('user_who_liked', $likedUserId)
                ->where('user_liked', $user->id)
                ->exists();

            return response()->json([
                'success' => true,
                'data' => $reciprocalLike,
                'message' => '',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ]);
        }
    }
}
