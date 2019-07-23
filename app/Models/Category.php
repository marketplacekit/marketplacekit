<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Nestable\NestableTrait;
use Spatie\SchemalessAttributes\SchemalessAttributes;

class Category extends Model
{
    use \Spiritix\LadaCache\Database\LadaCacheTrait;
    use NestableTrait;
    protected $parent = 'parent_id';

    protected $fillable = [
        'name', 'hash', 'order', 'parent_id', 'slug', 'description'
    ];
	
	public $casts = [
        'request' => 'json',
        'extra_attributes' => 'array',
    ];
	
	public function metatags()
    {
        return $this->morphOne(Metatag::class, 'metatagable')->byLocale(current_locale())->withDefault();
    }

    public function getExtraAttributesAttribute(): SchemalessAttributes
    {
        return SchemalessAttributes::createForModel($this, 'extra_attributes');
    }

    public function scopeWithExtraAttributes(): Builder
    {
        return SchemalessAttributes::scopeWithSchemalessAttributes('extra_attributes');
    }

	public function setDescriptionAttribute($value)
	{
        $this->extra_attributes['description'] = $value;
	}

	public function getDescriptionAttribute()
	{
        return $this->extra_attributes['description'];
	}

	public function child()
	{
		return $this->hasOne('App\Models\Category', 'id', 'parent_id');
	}

    public function listings() {
        return $this->hasMany('App\Models\Listing');
    }

    public function categories() {
        return $this->belongsTo('App\Models\Category', 'parent_id');
    }

    public function pricing_models() {
        return $this->belongsToMany('App\Models\PricingModel');
    }

    public function parent() {
        return $this->belongsTo('App\Models\Category', 'parent_id');
    }


}
