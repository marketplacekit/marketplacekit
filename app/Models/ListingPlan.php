<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\SchemalessAttributes\SchemalessAttributes;

class ListingPlan extends Model
{
    use \Spiritix\LadaCache\Database\LadaCacheTrait;
    use SoftDeletes;

    protected $fillable = [
        "name", "description", "price", "credits", "duration_units", "duration_period", "images", "spotlight", "priority", "bold", "additional_price", "category_id", "min_price", "max_price", "group", 
    ];
    protected $dates = ['deleted_at'];
public $casts = [
        'meta' => 'array',
    ];
	
	 public function getMetaAttribute(): SchemalessAttributes
    {
        return SchemalessAttributes::createForModel($this, 'meta');
    }

    public function scopeWithMeta(): Builder
    {
        return SchemalessAttributes::scopeWithSchemalessAttributes('meta');
    }

    public function additional_prices()
    {
        return $this->hasMany('\App\Models\ListingPlanAdditionalPrice');
    }

    public function category()
    {
        return $this->belongsTo('\App\Models\Category');
    }
}
