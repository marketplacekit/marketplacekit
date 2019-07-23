<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
#use Grimzy\LaravelMysqlSpatial\Eloquent\SpatialTrait;
use App\Traits\MergedTrait;
use DB;
use ChristianKuri\LaravelFavorite\Traits\Favoriteable;
use Illuminate\Database\Eloquent\SoftDeletes;
use PhpUnitsOfMeasure\PhysicalQuantity\Length;
use App\Traits\Commentable;
use App\Traits\HashId;
use Date;
use Nicolaslopezj\Searchable\SearchableTrait;
#use Sofa\Eloquence\Eloquence; keep for later


class Listing extends Model
{
    #use Eloquence;
    use MergedTrait;
    use SearchableTrait;
    use Favoriteable;
    use Commentable;
    use SoftDeletes;
	use HashId;
    use \App\Traits\HasTags;

    protected $canBeRated = true;
    protected $mustBeApproved = false;

    protected $searchable = [
        'columns' => [
            'listings.title' => 20,
            'listings.tags' => 10,
            'listings.description' => 10,
            'listings.tags_string' => 10,
            'users.display_name' => 10,
        ],
        'joins' => [
            'users' => ['users.id','listings.user_id'],
        ],
    ];
    protected $searchableColumns = ['title', 'tags', 'description'];
    protected $appends = ['thumbnail', 'price_formatted', 'url', 'short_description', 'slug', 'hash', 'media'];
    protected $hidden = ['location'];

    protected $fillable = [
        'key', 'title', 'price', 'stock', 'unit', 'category_id', 'user_id', 'short_address', 'description', 'spotlight', 'staff_pick', 'is_hidden', 'location', 'lat', 'lng', 'pricing_model_id', 'photos', 'city', 'region',  'country', 'currency', 'is_draft', 'session_duration', 'min_duration', 'max_duration'
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
    protected $dates = ['expires_at', 'spotlight', 'bold_until', 'priority_until', 'deleted_at', 'ends_at'];

    /*protected function newBaseQueryBuilder()
    {
        $conn = $this->getConnection();
        $grammar = $conn->getQueryGrammar();
        return new QueryBuilder(
            $conn,
            $grammar,
            $conn->getPostProcessor(),
            app()->make('lada.handler')
        );
    }*/

    public function toggleSpotlight()
    {
        $this->spotlight = ($this->spotlight)?null:Carbon::now();
        $this->save();
    }

    public function getIsNewAttribute()
    {
        return !$this->is_published;
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

    public function getPhotosLimitAttribute($value) {
        if(is_null($value)) {
            return setting('photos_per_listing', 20);
        }
        return $value;
    }

    public function getCountryAttribute($value) {
        return _l($value);
    }
	
    public function getBoldAttribute() {
        if($this->bold_until && $this->bold_until->gt(Carbon::now())) {
            return true;
        }
        return false;
    }
	
    public function getImagesAttribute() {
        if(!$this->photos) {
            return ["http://via.placeholder.com/680x460?text=No%20Image"];
        }
        return $this->photos;
    }

    public function getCarouselAttribute() {
        $images = [];
        $this->media_items = collect($this->media)->slice(0, setting('photos_per_listing', 20));
        if($this->media_items) {
            foreach($this->media_items as $item) {
				#dev_dd($item);
                $images[] = $item;
            }
        }
        return $images;
    }

	public function getHashAttribute(): string
    {
        return $this->getRouteKey();
    }
	
	public function getSlugAttribute(): string
    {
        return str_slug($this->title);
    }

    public function getShortDescriptionAttribute() {
		$description = "";
		try {
			$html = new \Html2Text\Html2Text($this->description);
			$description = $html->getText();
			$description = str_limit($description, 160);
			#dev_dd($this->description);
		} catch(\Exception $e) {
		
		}
		
		return $description;
		#$truncateService = new \Urodoz\Truncate\TruncateService();
		#return $truncateService->truncate($this->description, 255);
    }
	
    public function getEditUrlAttribute() {
        return route('create.edit', [$this]);
    }

    public function getUrlAttribute() {
        return route('listing', [$this, $this->slug]);
    }

    public function getMediaAttribute() {
		$photos = $this->photos;
		#dev_dd('photos');
		#dev_dd($photos);
		if(is_array($photos)) {
			$items = [];
			foreach($photos as $photo) {
				if(is_array($photo)) {
					$items[] = $photo;
				} else {
					$items[] = ['photo' => $photo];
				}
				#dev_dd($photo);
            }
			return $items;
		}
		return [];
	}
	
    public function getThumbnailAttribute() {
        #var_dump($this->photos);die();
        if($this->media) {
            foreach($this->media as $item) {
                return $item['photo'];
            }
        }

        return url("/images/no_image.png");
    }

    public function getCoverImageAttribute() {
        #var_dump($this->photos);die();
        if($this->photos) {
            foreach($this->photos as $photo) {
				if(is_array($photo))
					return $photo['photo'];
				else
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
            $price .= __(" per ").$this->pricing_model->per_label_buyer_display;
		}
		if($this->pricing_model && $this->pricing_model->widget == 'book_time') {
			if($this->pricing_model->can_seller_enter_per_label && $this->price_per_unit_display) {
				$price .= __(" per ").$this->price_per_unit_display;
			} else {
				$price .= __(" per ").$this->pricing_model->per_label_buyer_display;
			}
		}

		if($price) {
            return $price;
        }
        return null;
    }

	public function getPerLabelBuyerDisplayAttribute() {
		$name = $this->pricing_model->per_label_buyer_display;
		if($this->pricing_model->can_seller_enter_per_label && $this->price_per_unit_display) {
			$name = $this->price_per_unit_display;
		}
        return $name;
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
        return $query->where('is_published', 1)->where('is_draft', 0)->whereNotNull('is_admin_verified')->whereNull('is_disabled')
            ->where(function ($query) {
                $query->whereDate('expires_at', '>=', Carbon::now())
                    ->orWhereNull('expires_at');
            })
			->where(function ($query) {
                $query->whereDate('ends_at', '>=', Carbon::now())
                    ->orWhereNull('ends_at');
            });
    }

}
