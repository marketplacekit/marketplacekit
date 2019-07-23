<?php namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use GeneaLabs\LaravelModelCaching\Traits\Cachable;
use Cviebrock\EloquentSluggable\Sluggable;

class Page extends Model {

#	use \Dimsav\Translatable\Translatable;
    use \Spiritix\LadaCache\Database\LadaCacheTrait;
	public $translatedAttributes = ['title', 'slug', 'content', 'seo_title', 'seo_meta_description', 'seo_meta_keywords', 'status', 'visibility', 'locale'];
    protected $fillable = ['title'];
    protected $appends = array('last_modified');

	use Sluggable;
	public function sluggable()
    {
        return [
            'slug' => [
                'source' => 'title'
            ]
        ];
    }
	
    public static function boot()
    {
        parent::boot();

        // cause a delete of a product to cascade to children so they are also deleted
        static::deleted(function($page)
        {
            $page->translations()->delete();
        });
    }

    public function translations()
    {
        return $this->hasMany('\App\Models\PageTranslation');
    }

    public function getLastModifiedAttribute()
    {
        return $this->updated_at->lt(Carbon::minValue()) ? "Never" : $this->updated_at->format('jS M Y, h:i');
    }

	public function getParamsAttribute($value) {
        if(!is_null($value))
            return json_decode($value);
        return [];
    }

}
