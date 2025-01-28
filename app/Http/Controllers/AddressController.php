<?php

namespace App\Http\Controllers;

use App\Models\Address;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Validation\ValidationException;

class AddressController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        //
        try {
            //code...
            $validatedData = $request->validate([
                'user_ip' => 'required|string|max:255',
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
            //code...
            $userIp = $validatedData['user_ip'];
            $response = Http::get("http://ip-api.com/json/{$userIp}");

            if ($response->successful()) {
                $user = $request->user();
                $locationData = $response->json();

                $address = Address::firstOrCreate(
                    ['user_id' => $user->id],
                    [
                        'state' => $locationData['region'] ?? null,
                        'city' => $locationData['city'] ?? null,
                        'lat' => $locationData['lat'] ?? null,
                        'long' => $locationData['lon'] ?? null,
                    ]
                );

                return response()->json([
                    'success' => true,
                    'data' => $address,
                ]);
            }
        } catch (\Exception $e) {
            //
            return response()->json([
                'success' => false,
                'message' => 'Falha ao obter informações de localização.',
            ], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Address $address)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Address $address)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Address $address)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Address $address)
    {
        //
    }
}
