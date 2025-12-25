<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Disease;
use App\Models\ExpertSystem;
use Illuminate\Http\Request;

class DiseaseController extends Controller
{
    public function index($expertSystemId)
    {
        $diseases = Disease::where('expert_system_id', $expertSystemId)->get();

        return response()->json([
            'success' => true,
            'data' => $diseases,
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
            'category' => 'nullable|string|max:255',
            'description' => 'nullable|string',
        ]);

        $disease = Disease::create([
            'expert_system_id' => $expertSystemId,
            'name' => $request->name,
            'category' => $request->category,
            'description' => $request->description,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Disease created successfully',
            'data' => $disease,
        ], 201);
    }

    public function update(Request $request, $expertSystemId, $id)
    {
        $disease = Disease::where('expert_system_id', $expertSystemId)
            ->findOrFail($id);

        $expertSystem = $disease->expertSystem;

        // Check ownership
        if ($expertSystem->expert_id !== $request->user()->id) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized',
            ], 403);
        }

        $request->validate([
            'name' => 'string|max:255',
            'category' => 'nullable|string|max:255',
            'description' => 'nullable|string',
        ]);

        $disease->update($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Disease updated successfully',
            'data' => $disease,
        ]);
    }

    public function destroy(Request $request, $expertSystemId, $id)
    {
        $disease = Disease::where('expert_system_id', $expertSystemId)
            ->findOrFail($id);

        $expertSystem = $disease->expertSystem;

        // Check ownership
        if ($expertSystem->expert_id !== $request->user()->id) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized',
            ], 403);
        }

        $disease->delete();

        return response()->json([
            'success' => true,
            'message' => 'Disease deleted successfully',
        ]);
    }
}
