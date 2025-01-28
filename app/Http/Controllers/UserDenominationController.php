<?php

namespace App\Http\Controllers;

use App\Models\Denomination;
use App\Models\UserDenomination;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class UserDenominationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        //
        try {
            $denominations = Denomination::all();

            $userDenomination = $request->user()->load('userDenomination.denomination');

            $denominationId = $userDenomination->userDenomination->denomination->id ?? null;

            $denominations = $denominations->map(function ($denomination) use ($denominationId) {
                $denomination->isSelected = $denomination->id === $denominationId;
                return $denomination;
            });

            return response()->json([
                'success' => true,
                'data' => $denominations,
                'message' => 'Denominações recuperadas com sucesso.',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Não foi possível recuperar os denominações no momento.',
                'error' => $e->getMessage(),
            ], 500);
        }
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
                'denomination_id' => 'required|exists:denominations,id',
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
            $userId = $request->user()->id;
            $denominationId = $validatedData['denomination_id'];

            $userDenomination = UserDenomination::where('user_id', $userId)->first();

            $denomination = Denomination::where('id', $validatedData['denomination_id'])->first();

            if ($userDenomination) {
                $userDenomination->update([
                    'denomination_id' => $denominationId
                ]);

                return response()->json([
                    'success' => true,
                    'data' => $denomination->name,
                    'message' => 'Denominação atualizada com sucesso!',
                ]);
            } else {
                $userDenomination = UserDenomination::create([
                    'user_id' => $userId,
                    'denomination_id' => $denominationId,
                ]);

                return response()->json([
                    'success' => true,
                    'data' => $denomination->name,
                    'message' => 'Denominação associada com sucesso!',
                ]);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Ocorreu um erro ao tentar associar a denominação.',
            ]);
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
    public function show(UserDenomination $userDenomination)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(UserDenomination $userDenomination)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, UserDenomination $userDenomination)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(UserDenomination $userDenomination)
    {
        //
    }
}
