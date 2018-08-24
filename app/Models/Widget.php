<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Widget extends Model
{
    use \Spiritix\LadaCache\Database\LadaCacheTrait;
    protected $fillable = [
        'name', 'hash', 'order', 'parent_id', 'slug'
    ];

    protected $casts = [
        'metadata' => 'array',
    ];

    protected $appends = ['meta_values'];

    public function getMetaValuesAttribute()
    {
        dd($this->metadata);
        return json_decode($this->metadata);
    }

}
