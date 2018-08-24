<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ListingService extends Model
{
    use \Spiritix\LadaCache\Database\LadaCacheTrait;
    protected $casts = [
        'meta' => 'json',
    ];


    protected $fillable = [
        'position', 'name', 'duration', 'price', 'listing_id'
    ];
}
