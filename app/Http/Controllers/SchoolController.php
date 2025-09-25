<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateSchoolRequest;
use App\Http\Resources\SchoolResource;
use App\Models\School;
use App\Traits\ApiResponder;
use Dedoc\Scramble\Attributes\Group;
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
    public function update(CreateSchoolRequest $request, School $school)
    {
        $school->update($request->validated());

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
