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

class ListingBookedDate extends Model
{
    use \Spiritix\LadaCache\Database\LadaCacheTrait;
	   protected $fillable = [
          'listing_id', 'booked_date', 'is_available'
    ];

	   protected $casts = [
          'booked_date' => 'date'
    ];

	   public function getBookedDateStringAttribute() {
	       return $this->booked_date->format("d-m-Y");
       }
   
}
