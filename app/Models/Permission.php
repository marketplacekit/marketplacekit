<?php

namespace App\Models;

class Permission extends \Spatie\Permission\Models\Permission
{
    use \Spiritix\LadaCache\Database\LadaCacheTrait;

}
