<?php

namespace App\Traits;

use Hashids;

trait OrderId
{

    /**
	 * Get the value of the model's route key.
	 *
	 * @return mixed
	 */
	public function getRouteKey()
	{
		return Hashids::connection('order')->encode($this->getKey());
	}

}
