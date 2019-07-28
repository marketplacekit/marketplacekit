<?php

namespace Modules\Panel\Forms;

use Kris\LaravelFormBuilder\Form;
use App\Models\PricingModel;


class PaymentForm extends Form
{
    protected $formOptions = [
        'autocomplete' => 'off',
        'role' => 'presentation',
    ];

    public function buildForm()
    {
        $this->add('name', 'text', [
            'rules' => 'required|min:3'
        ]);
        if(!$this->model) {

            if (request()->has('offline')) {
                $this->add('key', 'hidden', [
                    'value' => 'offline'
                ]);
            } else {
                $this->add('key', 'text', [
                    'rules' => 'required|min:3'
                ]);
            }
        }
        $this->add('display_name', 'text', [
            'rules' => 'required|min:3'
        ]);
        $this->add('icon', 'text', [
            'rules' => 'required|min:3'
        ]);
        $this->add('description', 'text', [
            'rules' => 'min:3'
        ]);
        $this->add('payment_instructions', 'text', [
            'rules' => 'min:3'
        ]);
        if($this->model) {
			if($this->model->required_keys) {
				foreach($this->model->required_keys as $required_key) {
					#dd($this->model->extra_attributes[$required_key]);
					$value = $this->model->extra_attributes[$required_key];
					
					$type = 'text';
					$secret = '';
					if($this->model->secret_keys && in_array($required_key, $this->model->secret_keys)) {
						try {
							$value = \Crypt::decryptString($value);
							$type = 'text';
							$secret = 'secret';
						} catch (\Exception $e) {

						}
					}
					$this->add($required_key, $type, [
						'default_value' => $value,
						'placeholder' => "",
						'attr' => ['class' => 'form-control '.$secret, 'autocomplete' => 'off'],
						'rules' => 'min:3'
					]);
				}
            }
        }

        $this->add('is_enabled', 'select', [
            'choices' => [true => 'Yes', false => 'No'],
            'empty_value' => '-- SELECT --'
        ]);
        $this->add('position', 'number', [
            'rules' => 'integer'
        ]);


        $this->add('submit', 'submit', ['attr' => ['class' => 'btn btn-primary']]);
    }
}
