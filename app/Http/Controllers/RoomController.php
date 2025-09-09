<?php

namespace App\Http\Controllers;

use App\Models\Room;
use App\Traits\ApiResponder;
use Dedoc\Scramble\Attributes\Group;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoomController extends Controller
{
    use ApiResponder;

    /**
     * Get room count
     */
    #[Group('Dashboard')]
    public function getRoomCount()
    {
        $count = Room::where('school_id', Auth::user()->school_id)->count();
        return $this->success(["count" => (int) $count]);
    }
}
