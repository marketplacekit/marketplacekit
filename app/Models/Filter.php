<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class Filter extends Model
{
    protected $casts = [
          'form_input_meta' => 'array',
          'categories' => 'array',
      ];
}
