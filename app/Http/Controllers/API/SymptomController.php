<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Symptom;
use App\Models\ExpertSystem;
use Illuminate\Http\Request;

class SymptomController extends Controller
{
    public function index($expertSystemId)
    {
        $symptoms = Symptom::where('expert_system_id', $expertSystemId)->get();

        return response()->json([
            'success' => true,
            'data' => $symptoms,
        ]);
    }

    public function store(Request $request, $expertSystemId)
    {
        $expertSystem = ExpertSystem::findOrFail($expertSystemId);

        // Check ownership
        if ($expertSystem->expert_id !== $request->user()->id) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized',
            ], 403);
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $symptom = Symptom::create([
            'expert_system_id' => $expertSystemId,
            'name' => $request->name,
            'description' => $request->description,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Symptom created successfully',
            'data' => $symptom,
        ], 201);
    }

    public function update(Request $request, $expertSystemId, $id)
    {
        $symptom = Symptom::where('expert_system_id', $expertSystemId)
            ->findOrFail($id);

        $expertSystem = $symptom->expertSystem;

        // Check ownership
        if ($expertSystem->expert_id !== $request->user()->id) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized',
            ], 403);
        }

        $request->validate([
            'name' => 'string|max:255',
            'description' => 'nullable|string',
        ]);

        $symptom->update($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Symptom updated successfully',
            'data' => $symptom,
        ]);
    }

    public function destroy(Request $request, $expertSystemId, $id)
    {
        $symptom = Symptom::where('expert_system_id', $expertSystemId)
            ->findOrFail($id);

        $expertSystem = $symptom->expertSystem;

        // Check ownership
        if ($expertSystem->expert_id !== $request->user()->id) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized',
            ], 403);
        }

        $symptom->delete();

        return response()->json([
            'success' => true,
            'message' => 'Symptom deleted successfully',
        ]);
    }
}
