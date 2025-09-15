<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateSharingRequest;
use App\Http\Requests\ReplySharingRequest;
use App\Http\Resources\SharingResource;
use App\Models\Sharing;
use App\Models\User;
use App\Traits\ApiResponder;
use Dedoc\Scramble\Attributes\Group;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class SharingController extends Controller
{
    use ApiResponder;

    /**
     * Get all sharing data
     * 
     * Mendapatkan semua data curhatan milik siswa tersebut atau siswa yang dibawahi oleh BK tersebut. Hanya bisa diakses oleh BK dan siswa
     */
    #[Group('Sharing')]
    public function index()
    {
        Gate::allowIf(function (User $user) {
            return in_array($user->role, ['student', 'counselor']);
        });
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
     * 
     * Membuat curhatan baru dan hanya bisa dilakukan oleh siswa. Secara default prioritasnya adalah rendah
     */
    #[Group('Sharing')]
    public function store(CreateSharingRequest $request)
    {
        $sharing = Sharing::create($request->all());
        return $this->created(new SharingResource($sharing));
    }

    /**
     * Get sharing detail
     * 
     * Mendapatkan detail curhatan. Hanya bisa diakses oleh murid atau BK dari murid tersebut
     */
    #[Group('Sharing')]
    public function show(Sharing $sharing)
    {
        Gate::allowIf(function (User $authUser) use ($sharing) {
            return ($authUser->role == 'counselor' && $authUser->id === $sharing->user->counselor_id) || $authUser->role == 'student' && $authUser->id === $sharing->user_id;
        });
        return $this->success(new SharingResource($sharing));
    }

    /**
     * Reply to the sharing
     * 
     * Membalas curhatan siswa dan hanya bisa dilakukan oleh Guru BK siswa tersebut
     */
    #[Group('Sharing')]
    public function reply(ReplySharingRequest $request, Sharing $sharing)
    {
        $sharing->update($request->all());
        return $this->success(new SharingResource($sharing));
    }

    /**
     * Get sharing count
     */
    #[Group('Dashboard')]
    public function getSharingCount()
    {
        Gate::authorize('dashboard-data');
        $count = Sharing::whereIn('user_id', Auth::user()->counselored->pluck('id'))->count();
        return $this->success(["count" => (int) $count]);
    }
}
