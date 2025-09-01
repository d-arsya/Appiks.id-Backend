<?php

namespace App\Http\Controllers;

use App\Http\Resources\QuestionnaireResource;
use App\Models\Questionnaire;
use App\Traits\ApiResponder;
use Dedoc\Scramble\Attributes\Group;
use Illuminate\Http\Request;

class QuestionnaireController extends Controller
{
    use ApiResponder;
    /**
     * Get questionnaire by type
     */
    #[Group('Questionnaire')]
    public function getAllQuestionnaires(Request $request, string $type)
    {
        $request->validate([
            'type' => 'string|in:insecure,secure',
        ]);
        $questionnaires = Questionnaire::where('type', $type)->get();
        return $this->success(QuestionnaireResource::collection($questionnaires));
    }

    /**
     * Get questionnaire by type and order
     */
    #[Group('Questionnaire')]
    public function getOneQuestionnaire(Request $request, string $type, int $order)
    {
        $request->validate([
            'type' => 'string|  in:insecure,secure',
            'order' => 'integer|min:1|max:10',
        ]);
        $questionnaire = Questionnaire::whereType($type)->whereOrder($order)->first();
        return $this->success(new QuestionnaireResource($questionnaire));
    }
}
