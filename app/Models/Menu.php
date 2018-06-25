<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    //
    protected $casts = [
        'items' => 'json',
    ];
    public $translatable = ['title', 'url'];
    public $fillable = ['locale'];
}
