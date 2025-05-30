<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ScoreSetting;
use App\Http\Resources\ApiResource;

class ScoreSettingController extends Controller
{
    /**
     * GET /api/classes/{class_id}/score-settings
     * 
     * Response:
     * {
     *   "class_id": 42,
     *   "settings": [
     *     { "score_type_id": 1, "percent_value": 10 },
     *     { "score_type_id": 2, "percent_value": 20 },
     *     …
     *   ]
     * }
     */
    public function index($class_id)
    {
        $settings = ScoreSetting::where('class_id', $class_id)
            ->whereIn('score_type_id', [1,2,3,4,5])
            ->with('scoreType')->get(['id','score_type_id', 'percent_value']);

        return new ApiResource(200, 'List Data Bobot Nilai Dalam Kelas', $settings);

    }

    /**
     * POST /api/classes/{class_id}/score-settings
     * 
     * Request JSON:
     * {
     *   "percent_value": {
     *     "1": 10,
     *     "2": 20,
     *     "3": 30,
     *     "4": 25,
     *     "5": 15
     *   }
     * }
     * 
     * Response:
     * {
     *   "message": "Settings saved successfully",
     *   "class_id": 42,
     *   "settings": [
     *     { "score_type_id": 1, "percent_value": 10 },
     *     …
     *   ]
     * }
     */
    public function store(Request $request, $class_id)
    {
        // validasi: wajib array berisi tepat 5 elemen (keys 1–5), masing-masing numeric 0–100
        $data = $request->validate([
            'percent_value'   => 'required|array|size:5',
            'percent_value.*' => 'required|numeric|min:0|max:100',
        ]);

        // loop tiap score_type_id 1–5, update atau create
        foreach (range(1,5) as $type) {
            ScoreSetting::updateOrCreate(
                [
                    'class_id'      => $class_id,
                    'score_type_id' => $type,
                ],
                [
                    'percent_value' => $data['percent_value'][$type],
                ]
            );
        }

        // ambil ulang untuk response
        $settings = ScoreSetting::where('class_id', $class_id)
            ->whereIn('score_type_id', [1,2,3,4,5])
            ->get(['score_type_id', 'percent_value']);

        return response()->json([
            'message'   => 'Settings saved successfully',
            'class_id'  => (int) $class_id,
            'settings'  => $settings,
        ], 200);
    }
}