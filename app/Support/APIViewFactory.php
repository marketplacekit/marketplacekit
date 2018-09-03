<?php

namespace App\Support;

use Illuminate\View\Factory;

class APIViewFactory extends Factory {
    public function make($view, $data = array(), $mergeData = array())
    {
        $data = array_merge($mergeData, $this->parseData($data));

        if (\Request::wantsJson()) {
            return $data;
        }
        return parent::make($view, $data, $mergeData);
    }
}