<?php

namespace App\Traits;

use Grimzy\LaravelMysqlSpatial\Eloquent\SpatialTrait;
use Spiritix\LadaCache\Database\LadaCacheTrait;

trait MergedTrait
{

    use SpatialTrait, LadaCacheTrait {
        SpatialTrait::newBaseQueryBuilder insteadof LadaCacheTrait;
    }

    protected function newBaseQueryBuilder()
    {
        $connection = $this->getConnection();
        return new MergedBuilder(
            $connection,
            $connection->getQueryGrammar(),
            $connection->getPostProcessor(),
            app()->make('lada.handler')
        );
    }

}
