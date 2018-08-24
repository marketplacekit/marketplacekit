<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class Comment extends Model
{
    use \Spiritix\LadaCache\Database\LadaCacheTrait;
	protected $fillable = [
        'comment',
        'rate',
        'approved',
        'listing_id',
        'commenter_id',
        'seller_id',
    ];

    protected $casts = [
        'approved' => 'boolean'
    ];
	
    public function listing()
    {
        return $this->belongsTo('App\Models\Listing');
    }	
	
    public function commenter()
    {
        return $this->belongsTo('App\Models\User', 'commenter_id');
    }
	
    public function seller()
    {
        return $this->belongsTo('App\Models\User', 'seller_id');
    }
	
    public function getRatingAttribute()
    {
        return (int) $this->rate;
    }

    /**
     * @return $this
     */
    public function approve()
    {
        $this->approved = true;
        $this->save();

        return $this;
    }
}
