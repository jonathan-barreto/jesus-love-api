<?php

namespace App\Http\Controllers;

use App\Models\Photo;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class PhotoController extends Controller
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
            $validatedData = $request->validate([
                'photo_names' => 'required|array',
                'photo_names.*' => 'string|max:255',
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
            $user = $request->user();

            foreach ($validatedData['photo_names'] as $photoName) {
                $user->photos()->create(['photo_name' => $photoName]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Fotos adicionadas com sucesso',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao adicionar fotos: ' . $e->getMessage(),
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
    public function show(Photo $photo)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Photo $photo)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Photo $photo)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        //
        try {
            $request->validate([
                'photo_name' => 'required|string',
            ]);

            $photoName = $request->input('photo_name');

            $photo = Photo::where('photo_name', $photoName)->first();

            if (!$photo) {
                return response()->json([
                    'success' => false,
                    'message' => 'Foto não encontrada.',
                ], 404);
            }

            $photo->delete();

            return response()->json([
                'success' => true,
                'message' => 'Foto deletada com sucesso.',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao deletar a foto.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
