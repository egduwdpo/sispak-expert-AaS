<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\ExpertSystem;
use Illuminate\Http\Request;

class ExpertSystemController extends Controller
{
    public function index(Request $request)
    {
        $query = ExpertSystem::with('expert');

        // Filter by status for regular users
        if (!$request->user()->isPakar()) {
            $query->where('status', 'active');
        }

        // Filter by expert_id for pakar
        if ($request->user()->isPakar()) {
            $query->where('expert_id', $request->user()->id);
        }

        $expertSystems = $query->latest()->get();

        return response()->json([
            'success' => true,
            'data' => $expertSystems,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'field' => 'required|string|max:255',
            'description' => 'required|string',
            'target_user' => 'nullable|string',
            'status' => 'required|in:draft,active,inactive',
        ]);

        $expertSystem = ExpertSystem::create([
            'expert_id' => $request->user()->id,
            'name' => $request->name,
            'field' => $request->field,
            'description' => $request->description,
            'target_user' => $request->target_user,
            'status' => $request->status,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Expert system created successfully',
            'data' => $expertSystem,
        ], 201);
    }

    public function show($id)
    {
        $expertSystem = ExpertSystem::with([
            'expert',
            'diseases',
            'symptoms',
            'confidenceScales',
        ])->findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => $expertSystem,
        ]);
    }

    public function update(Request $request, $id)
    {
        $expertSystem = ExpertSystem::findOrFail($id);

        // Check ownership
        if ($expertSystem->expert_id !== $request->user()->id) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized',
            ], 403);
        }

        $request->validate([
            'name' => 'string|max:255',
            'field' => 'string|max:255',
            'description' => 'string',
            'target_user' => 'nullable|string',
            'status' => 'in:draft,active,inactive',
        ]);

        $expertSystem->update($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Expert system updated successfully',
            'data' => $expertSystem,
        ]);
    }

    public function destroy(Request $request, $id)
    {
        $expertSystem = ExpertSystem::findOrFail($id);

        // Check ownership
        if ($expertSystem->expert_id !== $request->user()->id) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized',
            ], 403);
        }

        $expertSystem->delete();

        return response()->json([
            'success' => true,
            'message' => 'Expert system deleted successfully',
        ]);
    }

    // Get statistics for dashboard
    public function statistics(Request $request)
    {
        $userId = $request->user()->id;

        $stats = [
            'total_systems' => ExpertSystem::where('expert_id', $userId)->count(),
            'active_systems' => ExpertSystem::where('expert_id', $userId)->where('status', 'active')->count(),
            'total_consultations' => Consultation::whereHas('expertSystem', function ($query) use ($userId) {
                $query->where('expert_id', $userId);
            })->count(),
            'total_diseases' => Disease::whereHas('expertSystem', function ($query) use ($userId) {
                $query->where('expert_id', $userId);
            })->count(),
            'total_symptoms' => Symptom::whereHas('expertSystem', function ($query) use ($userId) {
                $query->where('expert_id', $userId);
            })->count(),
        ];

        return response()->json([
            'success' => true,
            'data' => $stats,
        ]);
    }
}
