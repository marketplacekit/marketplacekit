<?php

namespace Modules\Panel\Forms;

use Kris\LaravelFormBuilder\Form;
use App\Models\PricingModel;
use App\Models\Category;

class ListingForm extends Form
{
    public function buildForm()
    {
        $this->add('title', 'text', [
			'attr' => [
                'disabled' => 'disabled',
                'class' => 'form-control',
            ],
            'rules' => 'required|min:3'
        ]);        
		
		$this->add('expires_at', 'text', [
			'attr' => [
                'class' => 'form-control date-picker',
            ],
			'help_block' => [
				'class' => 'pb-0',
				'style' => 'margin-bottom: 0',
                'text' => "Format: YYYY-MM-DD HH:MI:SS.",
            ]
        ]);		
		$this->add('priority_until', 'text', [
			'label' => "Boost until",
			'attr' => [
                'class' => 'form-control date-picker',
            ],
			'help_block' => [
				'class' => 'pb-0',
				'style' => 'margin-bottom: 0',
                'text' => "Set this to boost the listing in the search results (YYYY-MM-DD HH:MI:SS).",
            ]
        ]);
		
		$pricing_models = PricingModel::pluck('name','id');
		if($pricing_models)
			$pricing_models = $pricing_models->toArray();
		else
			$pricing_models = [];

		$this->add('pricing_model_id', 'select', [
			'label' => "Pricing model",
			'choices' => $pricing_models,
			'attr' => [
                'class' => 'form-control',
            ],
            'rules' => '',
        ]);

		$currencies = ["AFA","ALL","DZD","USD","EUR","AOA","XCD","NOK","XCD","ARA","AMD","AWG","AUD","EUR","AZM","BSD","BHD","BDT","BBD","BYR","EUR","BZD","XAF","BMD","BTN","BOB","BAM","BWP","NOK","BRL","GBP","BND","BGN","XAF","BIF","KHR","XAF","CAD","CVE","KYD","XAF","XAF","CLF","CNY","AUD","AUD","COP","KMF","CDZ","XAF","NZD","CRC","HRK","CUP","EUR","CZK","DKK","DJF","XCD","DOP","TPE","USD","EGP","USD","XAF","ERN","EEK","ETB","FKP","DKK","FJD","EUR","EUR","EUR","EUR","XPF","EUR","XAF","GMD","GEL","EUR","GHC","GIP","EUR","DKK","XCD","EUR","USD","GTQ","GNS","GWP","GYD","HTG","AUD","EUR","HNL","HKD","HUF","ISK","INR","IDR","IRR","IQD","EUR","ILS","EUR","XAF","JMD","JPY","JOD","KZT","KES","AUD","KPW","KRW","KWD","KGS","LAK","LVL","LBP","LSL","LRD","LYD","CHF","LTL","EUR","MOP","MKD","MGF","MWK","MYR","MVR","XAF","EUR","USD","EUR","MRO","MUR","EUR","MXN","USD","MDL","EUR","MNT","XCD","MAD","MZM","MMK","NAD","AUD","NPR","EUR","ANG","XPF","NZD","NIC","XOF","NGN","NZD","AUD","USD","NOK","OMR","PKR","USD","PAB","PGK","PYG","PEI","PHP","NZD","PLN","EUR","USD","QAR","EUR","ROL","RUB","RWF","XCD","XCD","XCD","WST","EUR","STD","SAR","XOF","EUR","SCR","SLL","SGD","EUR","EUR","SBD","SOS","ZAR","GBP","EUR","LKR","SHP","EUR","SDG","SRG","NOK","SZL","SEK","CHF","SYP","TWD","TJR","TZS","THB","XAF","NZD","TOP","TTD","TND","TRY","TMM","USD","AUD","UGS","UAH","SUR","AED","GBP","USD","USD","UYU","UZS","VUV","VEF","VND","USD","USD","XPF","XOF","MAD","ZMK","USD"];
		if(module_enabled('virtualcoins')) {
			$currencies[] = setting('virtualcoin_shortcode');
		}
		if(module_enabled('crypto')) {
            $currencies[] = setting('crypto_symbol');
        }
        $currencies = array_combine($currencies, $currencies);

        $this->add('currency', 'select', [
            'choices' => $currencies,
            'selected' => $this->getData('currency'),
            'empty_value' => '-- SELECT --'
        ]);
		
        $this->add('submit', 'submit', ['attr' => ['class' => 'btn btn-primary']]);
    }
}
