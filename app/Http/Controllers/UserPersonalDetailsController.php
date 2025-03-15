<?php

namespace App\Http\Controllers;

use App\Models\ChildrenPreference;
use App\Models\Education;
use App\Models\MaritalStatus;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class UserPersonalDetailsController extends Controller
{
    //
    public function getEducations(Request $request)
    {
        try {
            // Obter todas as educações
            $educations = Education::all();

            // Carregar a educação do usuário autenticado
            $userEducation = $request->user()->load('userPersonalDetail.education');

            // Capturar a education_id do usuário, se existir
            $educationId = $userEducation->userPersonalDetail->education->id ?? null;

            // Mapear educations e marcar a selecionada
            $educations = $educations->map(function ($education) use ($educationId) {
                $education->isSelected = $education->id === $educationId;
                return $education;
            });

            return response()->json([
                'success' => true,
                'data' => $educations,
                'message' => 'Lista de níveis de escolaridade carregada com sucesso.',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao carregar níveis de escolaridade.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function updateEducation(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'education_id' => 'required|exists:educations,id',
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->validator->errors()->first(),
            ]);
        }

        try {
            $user = $request->user();

            $userPersonalDetail = $user->userPersonalDetail()->firstOrCreate([
                'user_id' => $user->id
            ]);

            $userPersonalDetail->update([
                'education_id' => $validatedData['education_id']
            ]);

            $education = Education::find($validatedData['education_id'])->name;

            return response()->json([
                'success' => true,
                'data' => $education,
                'message' => 'Nível de escolaridade atualizado com sucesso!',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao atualizar nível de escolaridade.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    //
    public function getMaritalStatuses(Request $request)
    {
        try {
            // Obter todos os estados civis
            $statuses = MaritalStatus::all();

            // Carregar o estado civil do usuário autenticado
            $userMaritalStatus = $request->user()->load('userPersonalDetail.maritalStatus');

            // Capturar o marital_status_id do usuário, se existir
            $maritalStatusId = $userMaritalStatus->userPersonalDetail->maritalStatus->id ?? null;

            // Mapear os estados civis e marcar o selecionado
            $statuses = $statuses->map(function ($status) use ($maritalStatusId) {
                $status->isSelected = $status->id === $maritalStatusId;
                return $status;
            });

            return response()->json([
                'success' => true,
                'data' => $statuses,
                'message' => 'Lista de estados civis carregada com sucesso.',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao carregar estados civis.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function updateMaritalStatus(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'marital_status_id' => 'required|exists:marital_statuses,id',
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->validator->errors()->first(),
            ]);
        }

        try {
            $user = $request->user();

            // Garante que o registro `user_personal_details` exista antes de atualizar
            $userPersonalDetail = $user->userPersonalDetail()->firstOrCreate([
                'user_id' => $user->id
            ]);

            // Atualiza o estado civil
            $userPersonalDetail->update([
                'marital_status_id' => $validatedData['marital_status_id']
            ]);

            $maritalStatus = MaritalStatus::find($validatedData['marital_status_id'])->name;

            return response()->json([
                'success' => true,
                'data' => $maritalStatus,
                'message' => 'Estado civil atualizado com sucesso!',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao atualizar estado civil.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    //
    public function getChildrenPreferences(Request $request)
    {
        try {
            // Obter todas as preferências sobre filhos
            $preferences = ChildrenPreference::all();

            // Carregar a preferência sobre filhos do usuário autenticado
            $userChildrenPreference = $request->user()->load('userPersonalDetail.childrenPreference');

            // Capturar o children_preference_id do usuário, se existir
            $childrenPreferenceId = $userChildrenPreference->userPersonalDetail->childrenPreference->id ?? null;

            // Mapear as preferências sobre filhos e marcar a selecionada
            $preferences = $preferences->map(function ($preference) use ($childrenPreferenceId) {
                $preference->isSelected = $preference->id === $childrenPreferenceId;
                return $preference;
            });

            return response()->json([
                'success' => true,
                'data' => $preferences,
                'message' => 'Lista de preferências sobre filhos carregada com sucesso.',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao carregar preferências sobre filhos.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function updateChildrenPreference(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'children_preference_id' => 'required|exists:children_preferences,id',
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->validator->errors()->first(),
            ]);
        }

        try {
            $user = $request->user();

            // Garante que o registro `user_personal_details` exista antes de atualizar
            $userPersonalDetail = $user->userPersonalDetail()->firstOrCreate([
                'user_id' => $user->id
            ]);

            // Atualiza a preferência sobre filhos
            $userPersonalDetail->update([
                'children_preference_id' => $validatedData['children_preference_id']
            ]);

            $childrenPreference = ChildrenPreference::find($validatedData['children_preference_id'])->name;

            return response()->json([
                'success' => true,
                'data' => $childrenPreference,
                'message' => 'Preferência sobre filhos atualizada com sucesso!',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao atualizar preferência sobre filhos.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
