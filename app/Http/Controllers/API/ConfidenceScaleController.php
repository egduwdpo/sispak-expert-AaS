<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\ConfidenceScale;
use App\Models\ExpertSystem;
use Illuminate\Http\Request;

class ConfidenceScaleController extends Controller
{
    public function index($expertSystemId)
    {
        $scales = ConfidenceScale::where('expert_system_id', $expertSystemId)
            ->orderBy('order')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $scales,
        ]);
    }

    public function bulkUpsert(Request $request, $expertSystemId)
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
            'scales' => 'required|array',
            'scales.*.label' => 'required|string',
            'scales.*.value' => 'required|numeric|min:0|max:1',
            'scales.*.order' => 'required|integer',
        ]);

        // Delete existing scales
        ConfidenceScale::where('expert_system_id', $expertSystemId)->delete();

        // Insert new scales
        $scales = [];
        foreach ($request->scales as $scale) {
            $scales[] = ConfidenceScale::create([
                'expert_system_id' => $expertSystemId,
                'label' => $scale['label'],
                'value' => $scale['value'],
                'order' => $scale['order'],
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Confidence scales updated successfully',
            'data' => $scales,
        ]);
    }
}
