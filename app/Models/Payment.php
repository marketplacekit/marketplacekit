<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use \Spiritix\LadaCache\Database\LadaCacheTrait;

    protected $fillable = [
        'processor', 'user_id', 'amount', 'currency',
    ];

    public function payable()
    {
        return $this->morphTo();
    }

}
