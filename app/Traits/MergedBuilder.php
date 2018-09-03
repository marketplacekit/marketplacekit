<?php

namespace App\Traits;

use Spiritix\LadaCache\Database\QueryBuilder;
use Grimzy\LaravelMysqlSpatial\Eloquent\SpatialExpression;

class MergedBuilder extends QueryBuilder {
    protected function cleanBindings(array $bindings)
    {
        $bindings = array_map(function ($binding) {
            return $binding instanceof SpatialExpression ? $binding->getSpatialValue() : $binding;
        }, $bindings);
        return parent::cleanBindings($bindings);
    }

}