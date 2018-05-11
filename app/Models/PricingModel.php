<?php

namespace App\Models;

#use Nicolaslopezj\Searchable\SearchableTrait;
use Illuminate\Database\Eloquent\Model;
use App\Traits\MergedBuilder;
use Grimzy\LaravelMysqlSpatial\Eloquent\SpatialTrait;
use DB;
use ChristianKuri\LaravelFavorite\Traits\Favoriteable;
use Illuminate\Database\Eloquent\SoftDeletes;
use PhpUnitsOfMeasure\PhysicalQuantity\Length;
use App\Traits\Commentable;
use App\Traits\HashId;
use Sofa\Eloquence\Eloquence;

class PricingModel extends Model
{
    protected $fillable = [
        "name", "widget", "unit_name", "duration_name"
    ];

    public function getPricingUnitAttribute() {
        if($this->price_display == 'unit') {
            return $this->unit_name;
        }
        return $this->duration_name;
    }


}
