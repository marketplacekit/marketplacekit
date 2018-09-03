<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class CustomFile extends Model
{
    use \Spiritix\LadaCache\Database\LadaCacheTrait;

    protected $fillable = [
        'path', 'contents',
    ];

}
