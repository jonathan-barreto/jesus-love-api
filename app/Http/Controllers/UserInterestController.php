<?php

namespace App\Http\Controllers;

use App\Models\Interest;
use App\Models\User;
use App\Models\UserInterest;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class UserInterestController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        //
        try {
            $interests = Interest::all();

            $userInterests = $request->user()->load('interests');

            $userInterestIds = $userInterests->interests->pluck('id')->toArray();

            $interests = $interests->map(function ($interest) use ($userInterestIds) {
                $interest->isSelected = in_array($interest->id, $userInterestIds);
                unset($interest->pivot);
                return $interest;
            });

            return response()->json([
                'success' => true,
                'data' => $interests,
                'message' => 'Interesses recuperados com sucesso.',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Não foi possível recuperar os interesses no momento.',
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
            $validated = $request->validate([
                'interest_ids' => 'required|array',
                'interest_ids.*' => 'integer|exists:interests,id',
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
            $user = User::findOrFail($request->user()->id);
            $user->interests()->detach();
            $user->interests()->attach($validated['interest_ids']);

            $userInterests = $request->user()->load('interests');
            $userInterestIds = $userInterests->interests->pluck('name')->toArray();

            return response()->json([
                'success' => true,
                'data' => $userInterestIds,
                'message' => 'Interesses atualizados com sucesso.',
            ], 200);
        } catch (\Exception $e) {
            //throw $th;
            return response()->json([
                'success' => false,
                'message' => 'Erro ao atualizar interesses.',
            ], 200);
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
    public function show(UserInterest $userInterest)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(UserInterest $userInterest)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, UserInterest $userInterest)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(UserInterest $userInterest)
    {
        //
    }
}
