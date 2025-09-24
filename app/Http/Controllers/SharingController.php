<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateSharingRequest;
use App\Http\Requests\ReplySharingRequest;
use App\Http\Resources\SharingResource;
use App\Models\Sharing;
use App\Models\User;
use App\Traits\ApiResponder;
use Carbon\Carbon;
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
            $sharings = $user->sharing()->with(['user'])->orderBy('replied_at')->get();
        } elseif ($user->role == 'counselor') {
            $sharings = Sharing::with(['user', 'user.room'])->whereIn('user_id', $user->counselored->pluck('id'))->get();
        }

        return $this->success(SharingResource::collection($sharings));
    }

    /**
     * Get sharing count by types
     *
     * Mendapatkan jumlah curhatan hari itu berdasarkan tipe. Hanya bisa diakses oleh BK
     */
    #[Group('Sharing')]
    public function getSharingCount()
    {
        Gate::allowIf(function (User $user) {
            return $user->role == 'counselor';
        });
        $user = Auth::user();
        $sharings = Sharing::whereDate('created_at', Carbon::today())
            ->whereIn('user_id', $user->counselored->pluck('id'));

        $received = (clone $sharings)->whereNull('reply')->count();
        $replied = (clone $sharings)->whereNotNull('reply')->count();

        return $this->success([
            'received' => $received,
            'replied' => $replied,
            'total' => $replied + $received,
        ]);
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
}
