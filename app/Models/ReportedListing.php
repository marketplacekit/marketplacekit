<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
#use App\Traits\MergedBuilder;
use Grimzy\LaravelMysqlSpatial\Eloquent\SpatialTrait;
use DB;
use ChristianKuri\LaravelFavorite\Traits\Favoriteable;
use Illuminate\Database\Eloquent\SoftDeletes;
use PhpUnitsOfMeasure\PhysicalQuantity\Length;
use App\Traits\Commentable;
use App\Traits\HashId;
#use Sofa\Eloquence\Eloquence;
use Date;
use Nicolaslopezj\Searchable\SearchableTrait;
use Sofa\Eloquence\Eloquence;
use \Grimzy\LaravelMysqlSpatial\Eloquent\MergedBuilder as Builder;

class ReportedListing extends Model
{
    use \Spiritix\LadaCache\Database\LadaCacheTrait;

    protected $fillable = [
        'reason', 'notes', 'user_id', 'listing_id'
    ];

    public function user()
    {
        return $this->belongsTo('\App\Models\User');
    }
    public function listing()
    {
        return $this->belongsTo('\App\Models\Listing');
    }

}
