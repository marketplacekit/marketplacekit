<?php

namespace App\Models;

class Role extends \Spatie\Permission\Models\Role
{
    use \Spiritix\LadaCache\Database\LadaCacheTrait;

}
