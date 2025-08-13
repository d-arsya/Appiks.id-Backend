<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateAnomalyRequest;
use App\Http\Resources\AnomalyResource;
use App\Models\Anomaly;
use App\Traits\ApiResponderTrait;
use Http\Discovery\Exception\NotFoundException;

class AnomalyController extends Controller
{
    use ApiResponderTrait;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return $this->success(AnomalyResource::collection(Anomaly::all()));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateAnomalyRequest $request)
    {
        $anomaly = Anomaly::create($request->validated());
        return $this->success(new AnomalyResource($anomaly), 'Success', 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $anomaly = Anomaly::find($id);
        if (!$anomaly) {
            throw new NotFoundException();
        }
        return $this->success($anomaly);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CreateAnomalyRequest $request, string $id)
    {
        $anomaly = Anomaly::find($id);
        if (!$anomaly) {
            throw new NotFoundException();
        }
        $anomaly->update($request->validated());
        return $this->success($anomaly);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $anomaly = Anomaly::find($id);
        if (!$anomaly) {
            throw new NotFoundException();
        }
        $anomaly->delete();
        return $this->success(null);
    }
}
