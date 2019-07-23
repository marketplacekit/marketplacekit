<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Nicolaslopezj\Searchable\SearchableTrait;
use Grimzy\LaravelMysqlSpatial\Eloquent\SpatialTrait;
use DB;
use ChristianKuri\LaravelFavorite\Traits\Favoriteable;
use Illuminate\Database\Eloquent\SoftDeletes;
use PhpUnitsOfMeasure\PhysicalQuantity\Length;
use App\Traits\Commentable;
use App\Traits\HashId;

class ListingAdditionalOption extends Model
{
    use \Spiritix\LadaCache\Database\LadaCacheTrait;
	protected $casts = [
          'meta' => 'json',
    ];


    protected $fillable = [
        'position', 'name', 'price', 'listing_id', 'min_quantity', 'max_quantity'
    ];
   
}
