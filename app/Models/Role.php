<?php

namespace App\Models;
use Sofa\Eloquence\Eloquence; // base trait
use Sofa\Eloquence\Metable; // extension trait
use Setting;
use Phoenix\EloquentMeta\MetaTrait;

class Role extends \Spatie\Permission\Models\Role
{
    #use \Spiritix\LadaCache\Database\LadaCacheTrait;
#use Eloquence, Metable;
	use MetaTrait;

	public function setSelectableAttribute($value) {
		$selectable_roles = Setting::get('selectable_roles', []);
		$selectable_roles[] = $this->id;
		$selectable_roles = array_unique($selectable_roles);
		
		if(!$value) {
			$selectable_roles = array_diff( $selectable_roles, [$this->id] ) ;
		}
		#dd($selectable_roles);
		if(count($selectable_roles))
			Setting::set('selectable_roles', $selectable_roles);
		else
			Setting::forget('selectable_roles');
		Setting::save();
    }
	
	public function getSelectableAttribute($value) {
		$selectable_roles = Setting::get('selectable_roles', []);
		return in_array($this->id, $selectable_roles);
		
		#$selectable_roles = array_unique($selectable_roles);
		#Setting::set('selectable_roles', $selectable_roles);
		#Setting::save();
    }

}
