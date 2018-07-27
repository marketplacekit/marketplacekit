<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
#use App\Traits\MergedBuilder;
use Grimzy\LaravelMysqlSpatial\Eloquent\SpatialTrait;
use DB;
use ChristianKuri\LaravelFavorite\Traits\Favoriteable;
use Illuminate\Database\Eloquent\SoftDeletes;
use PhpUnitsOfMeasure\PhysicalQuantity\Length;
use App\Traits\Commentable;
use App\Traits\HashId;
#use Sofa\Eloquence\Eloquence;
use Date;
use Nicolaslopezj\Searchable\SearchableTrait;
use Sofa\Eloquence\Eloquence;
use \Grimzy\LaravelMysqlSpatial\Eloquent\MergedBuilder as Builder;

class Listing extends Model
{
    #use SearchableTrait;

    #use SpatialTrait, Eloquence;
    use SpatialTrait;
    /*use SpatialTrait{
        SpatialTrait::newEloquentBuilder as spatialNewEloquentBuilder;
        Eloquence::newEloquentBuilder insteadof SpatialTrait;
    }*/
    #use Eloquence;
    use SearchableTrait;
    use Favoriteable;
    use Commentable;
    use SoftDeletes;
	use HashId;

    protected $canBeRated = true;
    protected $mustBeApproved = false;

    protected $searchable = [
        'columns' => [
            'listings.tags' => 5,
            'listings.title' => 10,
        ],
    ];
    protected $searchableColumns = ['tags', 'title', 'description'];
    protected $appends = ['thumbnail', 'price_formatted', 'url'];

    protected $fillable = [
        'key', 'title', 'price', 'stock', 'unit', 'category_id', 'user_id', 'short_address', 'description', 'spotlight', 'staff_pick', 'is_hidden', 'location', 'lat', 'lng', 'pricing_model_id', 'photos', 'city', 'country', 'currency', 'is_draft', 'session_duration', 'min_duration', 'max_duration'
    ];
    protected $casts = [
          'photos' => 'array',
          'meta' => 'json',
          'tags' => 'array',
          'shipping_options' => 'json',
          'variant_options' => 'json',
          'timeslots' => 'json',
    ];
    protected $spatialFields = [
        'location',
    ];
    protected $dates = ['deleted_at'];

    public function toggleSpotlight()
    {
        $this->spotlight = ($this->spotlight)?null:Carbon::now();
        $this->save();
    }

    public function getIsVerifiedAttribute()
    {
        return ($this->is_admin_verified && !$this->is_disabled);
    }

    public function getDaysAgoAttribute($value) {
		return Date::parse($this->created_at)->ago();
	}
    public function getHumanDistanceAttribute($value) {
		$distance = new Length($this->distance, 'meters');
		return __(":value miles", ['value' => number_format($distance->toUnit('miles'), 2)]);
	}

    public function getImagesAttribute() {
        if(!$this->photos) {
            return ["http://via.placeholder.com/680x460?text=No%20Image"];
        }
        return $this->photos;
    }
    public function getCarouselAttribute() {
        $images = [];
        if($this->photos) {
            foreach($this->photos as $item) {
                $images[] = $item;
            }
        }
        return $images;
    }

	public function getSlugAttribute(): string
    {
        return str_slug($this->title);
    }

    public function getEditUrlAttribute() {
        return route('create.edit', [$this]);
    }

    public function getUrlAttribute() {
        return route('listing', [$this, $this->slug]);
    }

    public function getThumbnailAttribute() {
        #var_dump($this->photos);die();
        if($this->photos) {
            foreach($this->photos as $photo) {
                return $photo;
            }
        }

        return "/images/no_image.png";
    }

    public function getCoverImageAttribute() {
        #var_dump($this->photos);die();
        if($this->photos) {
            foreach($this->photos as $photo) {
                return $photo;
            }
        }

        return "/images/no_image.png";
    }

    public function getPriceFormattedAttribute() {
        $price = null;
        if($this->price) {
            $price = format_money($this->price, $this->currency);
		}

		if($this->pricing_model && $this->pricing_model->widget == 'book_date') {
            $price .= " per ".$this->pricing_model->duration_name;
		}
		if($this->pricing_model && $this->pricing_model->widget == 'book_time') {
            $price .= " per ".$this->pricing_model->duration_name;
		}

		if($price) {
            return $price;
        }
        return null;
    }

    public function getCoverImagePathAttribute() {
        $hash = md5($this->id);
        return 'cover-images/'.$hash[0].'/'.$hash[1].'/'.$hash.'.png';
    }

    public function getShortAddressAttribute() {
        return $this->city . ", " . $this->country;
    }

    public function shipping_options()
    {
        return $this->hasMany('\App\Models\ListingShippingOption');
    }
    public function additional_options()
    {
        return $this->hasMany('\App\Models\ListingAdditionalOption');
    }

    public function booked_dates()
    {
        return $this->hasMany('\App\Models\ListingBookedDate');
    }

    public function booked_times()
    {
        return $this->hasMany('\App\Models\ListingBookedTime');
    }

    public function variants()
    {
        return $this->hasMany('\App\Models\ListingVariant');
    }

    public function category()
    {
        return $this->belongsTo('\App\Models\Category');
    }

    public function user()
    {
        return $this->belongsTo('\App\Models\User');
    }

    public function pricing_model() {
        return $this->belongsTo('\App\Models\PricingModel');
    }

    public function services() {
        return $this->hasMany('\App\Models\ListingService');
    }

    public function scopeActive($query)
    {
        return $query->where('is_published', 1)->whereNotNull('is_admin_verified')->whereNull('is_disabled');
    }

}
