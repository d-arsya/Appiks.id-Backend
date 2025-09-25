<?php

namespace App\Http\Controllers;

use App\Models\Location;
use App\Traits\ApiResponder;
use Dedoc\Scramble\Attributes\Group;

class LocationController extends Controller
{
    use ApiResponder;

    /**
     * Get all provinces
     */
    #[Group('Location')]
    public function province()
    {
        $provinces = Location::distinct()->pluck('province');

        return $this->success($provinces);
    }

    /**
     * Get all cities at province (name)
     */
    #[Group('Location')]
    public function city(string $province)
    {
        $cities = Location::where('province', $province)->distinct()->pluck('city');

        return $this->success($cities);
    }

    /**
     * Get all disticts at city (name)
     */
    #[Group('Location')]
    public function district(string $city)
    {
        $districts = Location::where('city', $city)->distinct()->pluck('district');

        return $this->success($districts);
    }

    /**
     * Get all villages at district (name)
     */
    #[Group('Location')]
    public function village(string $district)
    {
        $villages = Location::where('district', $district)->distinct()->pluck('village');

        return $this->success($villages);
    }
}
