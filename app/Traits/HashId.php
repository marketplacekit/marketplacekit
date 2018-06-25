<?php

namespace App\Traits;

use Hashids;

trait HashId
{

    /**
	 * Get the value of the model's route key.
	 *
	 * @return mixed
	 */
	public function getRouteKey()
	{
		return Hashids::encode($this->getKey());
	}

}
