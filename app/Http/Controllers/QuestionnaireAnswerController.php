<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateQuestionnaireAnswerRequest;
use App\Http\Resources\QuestionnaireAnswerResource;
use App\Models\Questionnaire;
use App\Models\QuestionnaireAnswer;
use App\Traits\ApiResponderTrait;
use Http\Discovery\Exception\NotFoundException;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Auth;

class QuestionnaireAnswerController extends Controller
{
    use ApiResponderTrait, AuthorizesRequests;
    public function __construct()
    {
        $this->authorizeResource(QuestionnaireAnswer::class, 'questionnaireAnswer');
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $answers = Auth::user()->room->students->pluck('id');

        return $this->success(QuestionnaireAnswerResource::collection(QuestionnaireAnswer::whereIn('user_id', $answers)->get()));
    }

    /**
     * Store a newly created resource in storage.
     */

    public function store(CreateQuestionnaireAnswerRequest $request)
    {
        $data = $request->validated();
        $data["user_id"] = Auth::user()->id;

        if ($data['type'] === 'help') {
            $data['answers'] = [$data['answers']];
            $data['questionnaire_id'] = null;
        } else {
            $questionnaire = Questionnaire::findOrFail($data['questionnaire_id']);

            $structuredAnswers = collect($questionnaire->answers)
                ->map(function ($ans) use ($data) {
                    return [
                        $ans === $data['answers'], // true if matches chosen answer
                        $ans
                    ];
                })
                ->toArray();

            $data['answers'] = $structuredAnswers;
        }
        unset($data["questionnaire_id"]);
        $answer = QuestionnaireAnswer::create($data);

        return $this->success(new QuestionnaireAnswerResource($answer), 'Success', 201);
    }


    /**
     * Display the specified resource.
     */
    public function show(QuestionnaireAnswer $answer)
    {
        return $this->success($answer);
    }
}
