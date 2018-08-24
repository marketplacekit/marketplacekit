<?php

namespace App\Traits;

use Sofa\Eloquence\Eloquence;
use Grimzy\LaravelMysqlSpatial\Eloquent\SpatialTrait;
use Spiritix\LadaCache\Database\LadaCacheTrait;

trait MergedTrait
{

    use SpatialTrait, LadaCacheTrait {
        LadaCacheTrait::newBaseQueryBuilder insteadof SpatialTrait;
    }

}
