<?php

namespace App\Http\Controllers;

use App\Models\Like;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use App\Http\Controllers\NotificationController;

class MatchController extends Controller
{
    //
    public function profiles(Request $request)
    {
        $user = $request->user();
        $genderToMatch = $user->gender === 'male' ? 'female' : 'male';

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
            'userDenomination.denomination',
            'address',
            'photos',
            'interests'
        ])
            ->where('gender', $genderToMatch)
            ->where('id', '!=', $user->id)
            ->where('is_active', false)
            ->whereRaw('TIMESTAMPDIFF(YEAR, date_of_birth, CURDATE()) BETWEEN ? AND ?', [$ageMin, $ageMax])
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
            ->map(function ($profile) use ($userLat, $userLong) {
                $storageUrl = 'https://epjmiianomyfekdjufod.supabase.co/storage/v1/object/public/photos';

                // Calcula a idade corretamente
                $age = $profile->date_of_birth
                    ? \Carbon\Carbon::parse($profile->date_of_birth)->age
                    : null;

                // Calcula a distância usando a fórmula de Haversine
                $distance = $this->calculateDistance($userLat, $userLong, $profile->address->lat, $profile->address->long);

                // Processa fotos
                $photos = $profile->photos->pluck('photo_name')->map(fn($photoName) => "{$storageUrl}/{$photoName}");

                return [
                    'id' => $profile->id,
                    'name' => $profile->name,
                    'age' => $age,
                    'gender' => $profile->gender,
                    'bio' => $profile->bio,
                    'denomination' => $profile->userDenomination->denomination->name ?? null,
                    'address' => $profile->address,
                    'photos' => $photos,
                    'interests' => $profile->interests->pluck('name'),
                    'distance' => "{$distance} km", // Adiciona a distância ao retorno
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
        //
        try {
            //code...
            $validatedData = $request->validate([
                'user_liked' => 'required|integer',
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
            $user = $request->user();

            Like::firstOrCreate([
                'user_who_liked' => $user->id,
                'user_liked' => $validatedData['user_liked'],
            ]);

            $reciprocalLike = Like::where('user_who_liked', $validatedData['user_liked'])
                ->where('user_liked', $user->id)
                ->exists();

            // $notificationController = new NotificationController();
            // $notificationController->sendLikedNotification($user->id, $validatedData['user_liked']);

            return response()->json([
                'success' => true,
                'is_match' => $reciprocalLike,
                'message' => '',
            ]);
        } catch (\Exception $e) {
            //throw $th;
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ]);
        }
    }
}
