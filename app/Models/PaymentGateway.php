<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentGateway extends Model
{
	
	protected $fillable = [
        'name', 'gateway_id', 'token', 'metadata', 'user_id'
    ];
	
	protected $casts = [
		'metadata' => 'json',
    ];

}
