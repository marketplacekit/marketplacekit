<?php namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

#use LaravelBook\Ardent\Ardent;
use GeneaLabs\LaravelModelCaching\Traits\Cachable;

class PageTranslation extends Model {
    use \Spiritix\LadaCache\Database\LadaCacheTrait;
	public $timestamps = true;
    protected $fillable = ['title', 'slug', 'content', 'seo_title', 'locale', 'seo_meta_description', 'seo_meta_keywords', 'status', 'visibility'];
    protected $hidden = ['content'];
	protected $appends = array('intro', 'last_modified');


	public function page()
    {
        return $this->belongsTo('\App\Models\Page');
    }

	public function translations()
    {
        return $this->hasMany('\App\Models\PageTranslation', 'page_id', 'page_id');
    }

	public function getIntroAttribute()
    {
        return str_limit(strip_tags($this->content), 150);
    }

    public function getLastModifiedAttribute()
    {
        $updated_at = $this->updated_at;
        if(!$this->updated_at)
            $updated_at = Carbon::now();

        return $updated_at->lt(Carbon::minValue()) ? "Never" : $updated_at->format('jS M Y, h:i');
    }

}
