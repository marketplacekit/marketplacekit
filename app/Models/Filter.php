<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Filter extends Model
{
    use \Spiritix\LadaCache\Database\LadaCacheTrait;

    protected $casts = [
          'form_input_meta' => 'array',
          'categories' => 'array',
          'is_hidden' => 'boolean',
      ];
}
