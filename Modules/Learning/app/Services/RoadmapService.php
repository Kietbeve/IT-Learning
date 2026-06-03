<?php

namespace Modules\Learning\Services;

use Modules\Learning\Models\Roadmap;

class RoadmapService
{
    public function __construct(
        //inject model
    )
    {
    }
    public function getAllRoadmaps()
    {
        return $this->roadmap->all();
    }
}