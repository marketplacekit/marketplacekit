<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\SchemalessAttributes\SchemalessAttributes;

class CheckoutSession extends Model
{
    //
    protected $fillable = [
        'listing_id', 'user_id', 'request', 'payment_provider_key'
    ];

    public $casts = [
        'request' => 'json',
        'extra_attributes' => 'array',
    ];

    public function getExtraAttributesAttribute(): SchemalessAttributes
    {
        return SchemalessAttributes::createForModel($this, 'extra_attributes');
    }

    public function scopeWithExtraAttributes(): Builder
    {
        return SchemalessAttributes::scopeWithSchemalessAttributes('extra_attributes');
    }

    public function listing()
    {
        return $this->belongsTo('App\Models\Listing');
    }

    public function payment_provider()
    {
        return $this->belongsTo('App\Models\PaymentProvider', 'payment_provider_key', 'key');
    }

    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

}
