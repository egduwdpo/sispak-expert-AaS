<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Consultation;
use App\Models\ExpertSystem;
use App\Services\CertaintyFactorService;
use Illuminate\Http\Request;

class ConsultationController extends Controller
{
    protected $cfService;

    public function __construct(CertaintyFactorService $cfService)
    {
        $this->cfService = $cfService;
    }

    public function index(Request $request)
    {
        $consultations = Consultation::where('user_id', $request->user()->id)
            ->with('expertSystem')
            ->latest()
            ->get();

        return response()->json([
            'success' => true,
            'data' => $consultations,
        ]);
    }

    public function diagnose(Request $request, $expertSystemId)
    {
        $expertSystem = ExpertSystem::findOrFail($expertSystemId);

        $request->validate([
            'symptoms' => 'required|array',
            'symptoms.*' => 'numeric|min:0|max:1',
        ]);

        // Perform diagnosis using CF calculation
        $results = $this->cfService->diagnose($expertSystemId, $request->symptoms);

        // Save consultation
        $consultation = Consultation::create([
            'user_id' => $request->user()->id,
            'expert_system_id' => $expertSystemId,
            'symptoms_data' => $request->symptoms,
            'results' => $results,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Diagnosis completed successfully',
            'data' => [
                'consultation_id' => $consultation->id,
                'results' => $results,
            ],
        ]);
    }

    public function show($id)
    {
        $consultation = Consultation::with('expertSystem')
            ->findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => $consultation,
        ]);
    }

    public function history(Request $request, $expertSystemId)
    {
        $consultations = Consultation::where('user_id', $request->user()->id)
            ->where('expert_system_id', $expertSystemId)
            ->latest()
            ->get();

        return response()->json([
            'success' => true,
            'data' => $consultations,
        ]);
    }
}
