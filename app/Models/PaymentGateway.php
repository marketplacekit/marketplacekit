<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentGateway extends Model
{
    use \Spiritix\LadaCache\Database\LadaCacheTrait;

    protected $fillable = [
        'name', 'gateway_id', 'token', 'metadata', 'user_id'
    ];
	
	protected $casts = [
		'metadata' => 'json',
    ];

    public function payment_provider() {
        return $this->belongsTo('App\Models\PaymentProvider');
    }

}
