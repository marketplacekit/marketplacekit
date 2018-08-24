<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Nestable\NestableTrait;

class Category extends Model
{
    use \Spiritix\LadaCache\Database\LadaCacheTrait;
    use NestableTrait;
    protected $parent = 'parent_id';

    protected $fillable = [
        'name', 'hash', 'order', 'parent_id', 'slug'
    ];

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
