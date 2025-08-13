<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateQuestionnaireRequest;
use App\Http\Resources\QuestionnaireResource;
use App\Models\Questionnaire;
use App\Traits\ApiResponderTrait;
use Http\Discovery\Exception\NotFoundException;

class QuestionnaireController extends Controller
{
    use ApiResponderTrait;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return $this->success(QuestionnaireResource::collection(Questionnaire::all()));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateQuestionnaireRequest $request)
    {
        $questionnaire = Questionnaire::create($request->validated());
        return $this->success(new QuestionnaireResource($questionnaire), 'Success', 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $questionnaire = Questionnaire::find($id);
        if (!$questionnaire) {
            throw new NotFoundException();
        }
        return $this->success($questionnaire);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CreateQuestionnaireRequest $request, string $id)
    {
        $questionnaire = Questionnaire::find($id);
        if (!$questionnaire) {
            throw new NotFoundException();
        }
        $questionnaire->update($request->validated());
        return $this->success($questionnaire);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $questionnaire = Questionnaire::find($id);
        if (!$questionnaire) {
            throw new NotFoundException();
        }
        $questionnaire->delete();
        return $this->success(null);
    }
}
