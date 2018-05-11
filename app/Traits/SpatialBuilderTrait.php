<?php

namespace App\Traits;

use Grimzy\LaravelMysqlSpatial\Types\GeometryInterface;

trait SpatialBuilderTrait
{
    public function update(array $values)
    {
        foreach ($values as $key => &$value) {
            if ($value instanceof GeometryInterface) {
                $value = $this->asWKT($value);
            }
        }

        return parent::update($values);
    }

    protected function asWKT(GeometryInterface $geometry)
    {
        return $this->getQuery()->raw("ST_GeomFromText('" . $geometry->toWKT() . "')");
    }
}