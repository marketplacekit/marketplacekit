<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    //
    use \Spiritix\LadaCache\Database\LadaCacheTrait;
    protected $casts = [
        'items' => 'json',
    ];
    public $translatable = ['title', 'url'];
    public $fillable = ['locale'];
}
