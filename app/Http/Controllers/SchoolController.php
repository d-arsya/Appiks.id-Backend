<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateSchoolRequest;
use App\Http\Resources\SchoolResource;
use App\Models\School;
use App\Traits\ApiResponderTrait;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class SchoolController extends Controller
{
    use ApiResponderTrait, AuthorizesRequests;

    public function __construct()
    {
        $this->authorizeResource(School::class, 'school');
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return $this->success(SchoolResource::collection(School::all()));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateSchoolRequest $request)
    {
        $school = School::create($request->validated());
        return $this->success(new SchoolResource($school), 'Success', 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(School $school)
    {
        return $this->success($school);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CreateSchoolRequest $request, School $school)
    {
        $school->update($request->validated());
        return $this->success($school);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(School $school)
    {
        $school->delete();
        return $this->success(null);
    }
}
