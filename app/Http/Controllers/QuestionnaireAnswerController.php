<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateQuestionnaireAnswerRequest;
use App\Http\Resources\QuestionnaireAnswerResource;
use App\Models\Questionnaire;
use App\Models\QuestionnaireAnswer;
use App\Traits\ApiResponderTrait;
use Http\Discovery\Exception\NotFoundException;

class QuestionnaireAnswerController extends Controller
{
    use ApiResponderTrait;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return $this->success(QuestionnaireAnswerResource::collection(QuestionnaireAnswer::all()));
    }

    /**
     * Store a newly created resource in storage.
     */

    public function store(CreateQuestionnaireAnswerRequest $request)
    {
        $data = $request->validated();

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
    public function show(string $id)
    {
        $answer = QuestionnaireAnswer::find($id);
        if (!$answer) {
            throw new NotFoundException();
        }
        return $this->success($answer);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CreateQuestionnaireAnswerRequest $request, string $id)
    {
        $answer = QuestionnaireAnswer::find($id);
        if (!$answer) {
            throw new NotFoundException();
        }
        $answer->update($request->validated());
        return $this->success($answer);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $answer = QuestionnaireAnswer::find($id);
        if (!$answer) {
            throw new NotFoundException();
        }
        $answer->delete();
        return $this->success(null);
    }
}
