<?php

namespace App\Observers;

use App\Models\Cloud;

class CloudObserver
{
    public function updated(Cloud $cloud)
    {
        if ($cloud->exp >= 100) {
            $cloud->level = $cloud->level + 1;
            $cloud->exp = $cloud->exp - 100;
            $cloud->save();
        }
    }
}
