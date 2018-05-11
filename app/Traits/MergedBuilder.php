<?php

namespace App\Traits;

use Sofa\Eloquence\Builder as EloquenceBuilder;

class MergedBuilder extends EloquenceBuilder
{
    use SpatialBuilderTrait;
}