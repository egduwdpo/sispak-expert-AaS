<?php

namespace App\Services;

class CertaintyFactorService
{
    /**
     * Calculate CF for user symptom
     * CF_user = CF_pakar × tingkat_keyakinan_user
     */
    public function calculateUserCF($cfPakar, $userConfidence)
    {
        return $cfPakar * $userConfidence;
    }

    /**
     * Combine multiple CF values
     * CFcombine = CF1 + CF2 × (1 – CF1)
     */
    public function combineCF($cf1, $cf2)
    {
        return $cf1 + ($cf2 * (1 - $cf1));
    }

    /**
     * Calculate diagnosis for selected symptoms
     */
    public function diagnose($expertSystemId, $selectedSymptoms)
    {
        // selectedSymptoms format: [symptom_id => confidence_value]
        
        $diseases = Disease::where('expert_system_id', $expertSystemId)
            ->with('rules.symptom')
            ->get();

        $results = [];

        foreach ($diseases as $disease) {
            $cfValues = [];

            foreach ($disease->rules as $rule) {
                // Check if user selected this symptom
                if (isset($selectedSymptoms[$rule->symptom_id])) {
                    $userConfidence = $selectedSymptoms[$rule->symptom_id];
                    $cfPakar = $rule->cf;
                    
                    // Calculate CF user
                    $cfUser = $this->calculateUserCF($cfPakar, $userConfidence);
                    $cfValues[] = $cfUser;
                }
            }

            // Combine all CF values for this disease
            if (!empty($cfValues)) {
                $combinedCF = $cfValues[0];
                
                for ($i = 1; $i < count($cfValues); $i++) {
                    $combinedCF = $this->combineCF($combinedCF, $cfValues[$i]);
                }

                $results[] = [
                    'disease_id' => $disease->id,
                    'disease_name' => $disease->name,
                    'category' => $disease->category,
                    'description' => $disease->description,
                    'cf_value' => round($combinedCF, 4),
                    'certainty_percentage' => round($combinedCF * 100, 2),
                ];
            }
        }

        // Sort by CF value descending
        usort($results, function ($a, $b) {
            return $b['cf_value'] <=> $a['cf_value'];
        });

        return $results;
    }
}