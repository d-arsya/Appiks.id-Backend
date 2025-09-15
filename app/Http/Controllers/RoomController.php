<?php

namespace App\Http\Controllers;

use App\Models\Room;
use App\Traits\ApiResponder;
use Dedoc\Scramble\Attributes\Group;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class RoomController extends Controller
{
    use ApiResponder;

    /**
     * Get room count
     * 
     * Digunakan untuk mendapatkan jumlah kelas didalam sekolah user tersebut. Bisa diakses oleh selain murid
     */
    #[Group('Dashboard')]
    public function getRoomCount()
    {
        Gate::authorize('dashboard-data');
        $count = Room::where('school_id', Auth::user()->school_id)->count();
        return $this->success(["count" => (int) $count]);
    }
}
