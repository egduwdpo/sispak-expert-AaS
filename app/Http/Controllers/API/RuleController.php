<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Rule;
use App\Models\ExpertSystem;
use App\Models\Disease;
use Illuminate\Http\Request;

class RuleController extends Controller
{
    public function index($expertSystemId)
    {
        $rules = Rule::whereHas('disease', function ($query) use ($expertSystemId) {
            $query->where('expert_system_id', $expertSystemId);
        })->with(['disease', 'symptom'])->get();

        return response()->json([
            'success' => true,
            'data' => $rules,
        ]);
    }

    public function store(Request $request, $expertSystemId)
    {
        $request->validate([
            'disease_id' => 'required|exists:diseases,id',
            'symptom_id' => 'required|exists:symptoms,id',
            'mb' => 'required|numeric|min:0|max:1',
            'md' => 'required|numeric|min:0|max:1',
        ]);

        // Check if disease belongs to expert system
        $disease = Disease::where('id', $request->disease_id)
            ->where('expert_system_id', $expertSystemId)
            ->firstOrFail();

        // Check ownership
        if ($disease->expertSystem->expert_id !== $request->user()->id) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized',
            ], 403);
        }

        $rule = Rule::create([
            'disease_id' => $request->disease_id,
            'symptom_id' => $request->symptom_id,
            'mb' => $request->mb,
            'md' => $request->md,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Rule created successfully',
            'data' => $rule->load(['disease', 'symptom']),
        ], 201);
    }

    public function update(Request $request, $expertSystemId, $id)
    {
        $rule = Rule::findOrFail($id);

        // Check ownership
        if ($rule->disease->expertSystem->expert_id !== $request->user()->id) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized',
            ], 403);
        }

        $request->validate([
            'mb' => 'numeric|min:0|max:1',
            'md' => 'numeric|min:0|max:1',
        ]);

        $rule->update($request->only(['mb', 'md']));

        return response()->json([
            'success' => true,
            'message' => 'Rule updated successfully',
            'data' => $rule->load(['disease', 'symptom']),
        ]);
    }

    public function destroy(Request $request, $expertSystemId, $id)
    {
        $rule = Rule::findOrFail($id);

        // Check ownership
        if ($rule->disease->expertSystem->expert_id !== $request->user()->id) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized',
            ], 403);
        }

        $rule->delete();

        return response()->json([
            'success' => true,
            'message' => 'Rule deleted successfully',
        ]);
    }
}
