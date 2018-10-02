<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ListingPlanPayment extends Model
{
    use \Spiritix\LadaCache\Database\LadaCacheTrait;
    use SoftDeletes;

    protected $fillable = [
        "user_id", "listing_id", "listing_plan_id", "started_at", "ends_at"
    ];

    public function payments()
    {
        return $this->morphMany('App\Models\Payment', 'payable');
    }

    public function listing()
    {
        return $this->belongsTo('\App\Models\Listing');
    }

    public function listing_plan()
    {
        return $this->belongsTo('\App\Models\ListingPlan');
    }

}
