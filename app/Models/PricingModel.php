<?php

namespace App\Models;

#use Nicolaslopezj\Searchable\SearchableTrait;
use Illuminate\Database\Eloquent\Model;
use App\Traits\MergedBuilder;
use Grimzy\LaravelMysqlSpatial\Eloquent\SpatialTrait;
use DB;
use ChristianKuri\LaravelFavorite\Traits\Favoriteable;
use Illuminate\Database\Eloquent\SoftDeletes;
use PhpUnitsOfMeasure\PhysicalQuantity\Length;
use App\Traits\Commentable;
use App\Traits\HashId;
use Sofa\Eloquence\Eloquence;
use Spatie\SchemalessAttributes\SchemalessAttributes;

class PricingModel extends Model
{
    use \Spiritix\LadaCache\Database\LadaCacheTrait;
    protected $fillable = [
        "name", "widget", "unit_name", "duration_name"
    ];

	public $casts = [
        'meta' => 'array',
    ];
	protected $appends = ['per_label_buyer', 'per_label_buyer_display', 'quantity_label_buyer', 'quantity_label_buyer_display', 'can_seller_enter_per_label'];

    public function getPerLabelBuyerAttribute() {
        return $this->meta->per_label_buyer;
    }
	
    public function getPerLabelBuyerDisplayAttribute() {
		$name = $this->meta->per_label_buyer;
		if(!$name) {
			if($this->widget == 'book_time') {
				$name = $this->duration_name;
			}			
			if($this->widget == 'book_date') {
				$name = ($this->price_display == 'unit')?$this->unit_name:$this->duration_name;
			}
		}
        return $name;
    }
	
    public function getCanSellerEnterPerLabelAttribute() {
        return $this->meta->can_seller_enter_per_label;
    }
	
    public function getQuantityLabelBuyerAttribute() {
        return $this->meta->quantity_label_buyer;
    }
	
    public function getQuantityLabelBuyerDisplayAttribute() {
		$name = $this->meta->quantity_label_buyer;
		if(!$name) {
			if($this->widget == 'book_time') {
				$name = __('Spaces to book');
			}
		}
        return $name;
    }
	
    public function getPricingUnitAttribute() {
        if($this->price_display == 'unit') {
            return $this->unit_name;
        }
        return $this->duration_name;
    }

	public function getMetaAttribute(): SchemalessAttributes
    {
        return SchemalessAttributes::createForModel($this, 'meta');
    }

    public function scopeWithMeta(): Builder
    {
        return SchemalessAttributes::scopeWithSchemalessAttributes('meta');
    }

}
