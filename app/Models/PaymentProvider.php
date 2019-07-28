<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Route;
use Spatie\SchemalessAttributes\SchemalessAttributes;

class PaymentProvider extends Model
{
    protected $fillable = [
        'name', 'key', 'display_name', 'description', 'payment_instructions', 'position', 'icon', 'is_offline'
    ];
    public $casts = [
        'extra_attributes' => 'array',
    ];

    public function getExtraAttributesAttribute(): SchemalessAttributes
    {
        return SchemalessAttributes::createForModel($this, 'extra_attributes');
    }

    public function scopeWithExtraAttributes(): Builder
    {
        return SchemalessAttributes::scopeWithSchemalessAttributes('extra_attributes');
    }

    #stupid name, why?
    public function identifier() {
        return $this->hasOne('App\Models\PaymentGateway', 'name', 'key');
    }

    public function getIsDefaultAttribute() {
        $default_provider = $this->fetchModuleDetails($this->key);
        if($default_provider) {
            return true;
        } else {
            return false;
        }
    }

    public function getSecretKeysAttribute() {
        $default_provider = $this->fetchModuleDetails($this->key);
        if($default_provider) {
            try {
                return $default_provider->secret_keys;
            } catch (\Exception $e) {
                return [];
            }
        } else {
            return [];
        }
    }

    private function fetchModuleDetails($key) {
        if($this->is_offline) {
            $key = 'offlinepayments';
        }

        $module = \Module::findByAlias($key);
        $default_provider = [];
        if($module && file_exists( module_path($module->name) .'/Resources/details.json' )) {
            $default_provider = (object) collect(json_decode(file_get_contents(module_path($module->name) . '/Resources/details.json')))->toArray();
        }
        return $default_provider;
    }

    public function getRequiredKeysAttribute() {
        $default_provider = $this->fetchModuleDetails($this->key);
        if($default_provider) {
            try {
                return $default_provider->required_keys;
            } catch (\Exception $e) {
                return [];
            }
        } else {
            return [];
        }
    }

    public function getControllerAttribute() {
        if($this->connection_url) {
            return "External";
        } elseif($this->is_offline) {
            return "Offline";
        } else {
            return studly_case($this->key);
        }
    }

    public function getConnectUrlAttribute()
    {
	#dev_dd($this->is_offline);
        if($this->is_offline) {
            return route('connect.offline.connect', ['key' => $this->key]);
        } else {
            $route_name = 'payments.'.str_slug($this->key).'.connect';
            #dd($route_name);
            if(Route::has($route_name)) {
                return route($route_name);
            }
            return "";
        }
        #return $this->updated_at->lt(Carbon::minValue()) ? "Never" : $this->updated_at->format('jS M Y, h:i');
    }
}
