<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateSharingRequest;
use App\Http\Requests\ReplySharingRequest;
use App\Http\Resources\SharingResource;
use App\Models\Sharing;
use App\Traits\ApiResponder;
use Dedoc\Scramble\Attributes\Group;
use Illuminate\Support\Facades\Auth;

class SharingController extends Controller
{
    use ApiResponder;

    /**
     * Get all sharing data
     * 
     * Get latest sharings data belongs to student for student or belongs to all student of counselored for counselor
     */
    #[Group('Sharing')]
    public function index()
    {
        $user = Auth::user();
        if ($user->role == 'student') {
            $sharings = $user->sharing;
        } else if ($user->role == 'counselor') {
            $sharings = Sharing::with(['user', 'user.room'])->whereIn('user_id', $user->counselored->pluck('id'))->get();
        }
        return $this->success(SharingResource::collection($sharings));
    }

    /**
     * Create new sharing
     */
    #[Group('Sharing')]
    public function store(CreateSharingRequest $request)
    {
        $sharing = Sharing::create($request->all());
        return $this->created(new SharingResource($sharing));
    }

    /**
     * Reply to the sharing
     */
    #[Group('Sharing')]
    public function reply(ReplySharingRequest $request, Sharing $sharing)
    {
        $sharing->update($request->all());
        return $this->success(new SharingResource($sharing));
    }

    /**
     * Reply to the sharing
     */
    #[Group('Sharing')]
    public function close(Sharing $sharing)
    {
        $sharing->update(["status" => "selesai"]);
        return $this->success(new SharingResource($sharing));
    }
}
