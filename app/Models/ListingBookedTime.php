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

class ListingBookedTime extends Model
{
    use \Spiritix\LadaCache\Database\LadaCacheTrait;
    protected $fillable = [
        'listing_id', 'booked_date', 'start_time', 'duration'
    ];

    protected $casts = [
        'booked_date' => 'date'
    ];


    public function getStartTimeAttribute($value) {
        return date('H:i', strtotime($value));
    }
   
}
