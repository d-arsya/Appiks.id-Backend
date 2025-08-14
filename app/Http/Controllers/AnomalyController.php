<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateAnomalyRequest;
use App\Http\Resources\AnomalyResource;
use App\Models\Anomaly;
use App\Traits\ApiResponderTrait;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Auth;

class AnomalyController extends Controller
{
    use ApiResponderTrait, AuthorizesRequests;
    public function __construct()
    {
        $this->authorizeResource(Anomaly::class, 'anomaly');
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();
        if ($user->role == 'student') {
            $anomalies = $user->anomalies;
        } elseif ($user->role == 'teacher') {
            $anomalies = Anomaly::whereIn('user_id', $user->room->students->pluck('id'))->get();
        }
        $anomalies = Anomaly::whereIn('user_id', $user->school->students->pluck('id'))->get();
        return $this->success(AnomalyResource::collection($anomalies));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateAnomalyRequest $request)
    {
        $payload = $request->validated();
        $payload["user_id"] = Auth::user()->id;
        $anomaly = Anomaly::create($payload);
        return $this->success(new AnomalyResource($anomaly), 'Success', 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Anomaly $anomaly)
    {
        return $this->success($anomaly);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CreateAnomalyRequest $request, Anomaly $anomaly)
    {
        $anomaly->update($request->validated());
        return $this->success($anomaly);
    }
}
