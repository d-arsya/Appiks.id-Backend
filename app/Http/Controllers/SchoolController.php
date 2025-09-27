<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateSchoolRequest;
use App\Http\Resources\SchoolResource;
use App\Models\School;
use App\Traits\ApiResponder;
use Dedoc\Scramble\Attributes\Group;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class SchoolController extends Controller
{
    use ApiResponder;

    /**
     * Get all schools data
     *
     * Hanya bisa diakses oleh super admin
     */
    #[Group('School')]
    public function index()
    {
        Gate::authorize('viewAny', School::class);
        $schools = School::all();

        return $this->success(SchoolResource::collection($schools));
    }

    /**
     * Create new school
     *
     * Hanya bisa diakses oleh super admin
     */
    #[Group('School')]
    public function store(CreateSchoolRequest $request)
    {
        $school = School::create($request->validated());

        return $this->created(new SchoolResource($school));
    }

    /**
     * Get school detail
     */
    #[Group('School')]
    public function show(School $school)
    {
        Gate::authorize('view', $school);

        return $this->success(new SchoolResource($school));
    }

    /**
     * Update school detail
     */
    #[Group('School')]
    public function update(Request $request, School $school)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'phone' => 'required|digits_between:8,13|unique:schools,phone,'.$school->id,
            'email' => 'required|email|unique:schools,email,'.$school->id.'|max:255',
            'district' => 'required|string|exists:locations,district|max:255',
            'city' => 'required|string|exists:locations,city|max:255',
            'province' => 'required|string|exists:locations,province|max:255',
        ]);
        $school->update($request->all());

        return $this->created(new SchoolResource($school));
    }

    /**
     * Delete school
     *
     * It will delete all rooms and users related to the school
     */
    #[Group('School')]
    public function destroy(School $school)
    {
        $data = $school->toArray();
        $school->delete();

        return $this->delete($data);
    }
}
