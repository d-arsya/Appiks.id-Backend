<?php

namespace App\Http\Controllers;

use App\Http\Resources\QuestionnaireResource;
use App\Models\Questionnaire;
use App\Traits\ApiResponderTrait;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class QuestionnaireController extends Controller
{
    use ApiResponderTrait, AuthorizesRequests;
    public function __construct()
    {
        $this->authorizeResource(Questionnaire::class, 'questionnaire');
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return $this->success(QuestionnaireResource::collection(Questionnaire::all()));
    }

    /**
     * Display the specified resource.
     */
    public function show(Questionnaire $questionnaire)
    {
        return $this->success($questionnaire);
    }
}
