<?php

namespace App\Models;

use App\Traits\OrderId;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Sofa\Eloquence\Eloquence; // base trait
use Sofa\Eloquence\Metable; // extension trait

class Order extends Model
{
    //
    use Eloquence, Metable;
    use SoftDeletes;

    use OrderId;
    protected $casts = [
        'token' => 'array',
        'listing_options' => 'array',
        'user_choices' => 'array',
        'customer_details' => 'array',
    ];
	protected $dates = [
        'accepted_at',
        'declined_at',
        'created_at',
        'updated_at',
        'deleted_at'
    ];
    protected $searchableColumns = ['listing.title', 'user.email'];

    protected $searchable = [
        'columns' => [
            'listings.title' => 10,
            'users.name' => 10,
            'users.email' => 10,
        ],
        'joins' => [
            'listings' => ['orders.listing_id','listings.id'],
            'users' => ['orders.user_id','users.id'],
            //'sellers' => ['listings.user_id','listings.id'],
        ],
    ];
    protected $appends = ['hash'];

    public function getHashAttribute($value) {
        return $this->getRouteKey();
    }

    public function listing() {
      return $this->belongsTo('App\Models\Listing');
    }

    public function payment_gateway() {
      return $this->belongsTo('App\Models\PaymentGateway');
    }

    public function seller() {
      return $this->belongsTo('App\Models\User', 'seller_id');
    }
	
    public function user() {
      return $this->belongsTo('App\Models\User');
    }

}
