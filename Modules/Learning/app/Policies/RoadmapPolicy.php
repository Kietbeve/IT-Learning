<?php

namespace Modules\Learning\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;

class RoadmapPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     */
    public function __construct()
    {
        //
    }
}
