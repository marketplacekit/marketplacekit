<?php

namespace App\Support;

use Illuminate\Support\ServiceProvider;
use Curl;

class PaypalClassic
{

    private $url;
    private $config;
    private $params;

    public function __construct(  ) {
        $this->url = 'https://api-3t.sandbox.paypal.com/nvp';
        if(setting('paypal_mode') != 'sandbox')
            $this->url = 'https://api-3t.paypal.com/nvp';

        $this->config = [
            'USER' => setting('paypal_user'),
            'PWD' => setting('paypal_password'),
            'SIGNATURE' => setting('paypal_signature'),
            'METHOD' => 'SetExpressCheckout',
            'VERSION' => 124.0,
        ];
    }

    public function setParams( $params ) {
        $this->params = $params;
    }

    public function send( $method ) {
        $data = array_merge($this->config, ['METHOD' => $method], $this->params);

        $result = Curl::to($this->url)->withData( $data )->post();
        $result = explode( '&', $result );
        $response = [];
        foreach ( $result as $param_paypal ) {
            list( $name, $value ) = explode( "=", $param_paypal );
            $response[ $name ] = urldecode( $value );
        }

        return $response;

    }



}
