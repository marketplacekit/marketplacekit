<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Avatar;
use App\Traits\CanComment;
use App\Traits\Commentable;
use Cviebrock\EloquentSluggable\Sluggable;
use ChristianKuri\LaravelFavorite\Traits\Favoriteability;
use App\Notifications\ResetPassword as ResetPasswordNotification;
use Kodeine\Metable\Metable;
use Cog\Contracts\Ban\Bannable as BannableContract;
use Cog\Laravel\Ban\Traits\Bannable;
use Spatie\Permission\Traits\HasRoles;
use Overtrue\LaravelFollow\Traits\CanFollow;
use Overtrue\LaravelFollow\Traits\CanBeFollowed;
use Overtrue\LaravelFollow\Traits\CanLike;
use Overtrue\LaravelFollow\Traits\CanBeLiked;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Gerardojbaez\Laraplans\Contracts\PlanSubscriberInterface;
use Gerardojbaez\Laraplans\Traits\PlanSubscriber;
use Depsimon\Wallet\HasWallet;
use Spatie\SchemalessAttributes\SchemalessAttributes;

class User extends Authenticatable implements BannableContract, JWTSubject, PlanSubscriberInterface
{
    use Notifiable;
    use SoftDeletes;
    use CanComment;
    use Commentable;
	use Sluggable;
	use Favoriteability;
	use Metable;
    use Bannable;
    use HasRoles;
    use CanFollow, CanLike, CanBeFollowed, CanBeLiked;
    use \Spiritix\LadaCache\Database\LadaCacheTrait;
    use PlanSubscriber;
    use HasWallet;

    protected $canBeRated = true;
    protected $mustBeApproved = false;

	public function getRouteKey()
	{
		return $this->slug;
	}

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }

	/**
     * Return the sluggable configuration array for this model.
     *
     * @return array
     */
    public function sluggable()
    {
        return [
            'slug' => [
                'source' => 'username'
            ]
        ];
    }
	
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'avatar', 'bio', 'display_name', 'gender', 'phone', 'city', 'region', 'country', 'unread_messages', 'username', 'provider', 'provider_id'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token', 'facebook_app_key', 'facebook_app_secret', 'provider', 'provider_id', 'email', 'location'
    ];
    protected $append = [
        'first_name'
    ];
	
	public $casts = [
        'filters' => 'array',
    ];
	
	public function getFiltersAttribute(): SchemalessAttributes
    {
        return SchemalessAttributes::createForModel($this, 'filters');
    }

    public function scopeWithFilters(): Builder
    {
        return SchemalessAttributes::scopeWithSchemalessAttributes('filters');
    }
	
	/*public function getSlugAttribute(): string
    {
        if(!$this->slug)
            return str_slug($this->display_name);
        return $this->slug;
    }*/
	
	public function getUrlAttribute() {
		return route('profile', [$this]);
    }

	public function payment_gateways()
    {
        return $this->hasMany('App\Models\PaymentGateway');
    }
	
	public function getShortBioAttribute() {
		$description = "";
		try {
			$html = new \Html2Text\Html2Text($this->bio);
			$description = $html->getText();
			$description = str_limit($description, 160);
		} catch(\Exception $e) {
		
		}
		
		return $description;
    }
	
	public function payment_gateway($gateway) {
		return $this->payment_gateways()->where('name', $gateway)->orderBy('created_at', 'DESC')->first();
	}
	
	public function comments()
    {
        return $this->hasMany(Comment::class, 'seller_id');
    }

    public function getCanAcceptPaymentsAttribute($value) {
		return $value;
	}
	
    public function getDisplayNameAttribute($value) {
		if(!$value)
			$value = $this->name;
		return $value;
	}
    public function getIsBannedAttribute()
    {
        return $this->isBanned();
    }

    public function getPathAttribute() {
        return store(getDir($this->attributes['id'].'.jpg', 4), $this->attributes['id'].'.jpg');
    }
    public function getAvatarAttribute() {
        if(!$this->attributes['avatar']) {
            $colors = ['E91E63', '9C27B0', '673AB7', '3F51B5', '0D47A1', '01579B', '00BCD4', '009688', '33691E', '1B5E20', '33691E', '827717', 'E65100',  'E65100', '3E2723', 'F44336', '212121'];
            $background = $colors[$this->id%count($colors)];
            return "https://ui-avatars.com/api/?size=256&background=".$background."&color=fff&name=".urlencode($this->display_name);
            #return "https://www.gravatar.com/avatar/".md5($this->email).'?d=mm&s=300&d=mm&';
        }
        return $this->attributes['avatar'];
    }

    public function first_name() {
        try {
            $nameparser = new \HumanNameParser\Parser();
            $name = $nameparser->parse($this->attributes['name']);
            return (string) $name->getFirstName();
        } catch (\Exception $e) {
            return $this->attributes['name'];
        }
    }

    public function groups() {
      return $this->hasMany('App\Models\Group');
    }

    public function listings() {
      return $this->hasMany('App\Models\Listing');
    }

    /*public function comments() {
      return $this->hasMany('App\Models\Comment', 'seller_id');
    }*/

    public function avg_rating() {
      return number_format($this->comments->avg('rating'), 1);
    }

    public function getVerifiedAttribute($value) {
		if($this->is_admin)
			return true;
		return $value;
    }

    public function count_reviews() {
      return $this->comments()->whereNotNull('rate')->count('rate');
    }
    /*
    public function getUnreadMessagesAttribute($value) {
        return max($value, 0);
    }
    */
    /**
     * Send the password reset notification.
     *
     * @param  string  $token
     * @return void
     */
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new ResetPasswordNotification($token, $this));
    }
	
	
    public function orders() {
        return $this->hasMany('App\Models\Order', 'seller_id');
    }

    public function getOpenOrdersAttribute() {
        return $this->orders()->whereStatus('open')->count();
    }

	public function getRequiresPaymentInformationAttribute() {
        if($this->listings()->count() > 0) {
            if(!$this->can_accept_payments) {
                return true;
            }
        }
        return false;
    }
}
