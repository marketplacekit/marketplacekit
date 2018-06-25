<?php

namespace Modules\Panel\Forms;

use Kris\LaravelFormBuilder\Form;
use App\Models\PricingModel;
use App\Models\Category;

class CategoryForm extends Form
{
    public function buildForm()
    {
        $this->add('name', 'text', [
            'rules' => 'required|min:3'
        ]);

        $this->add('order', 'number', [
            'rules' => 'integer'
        ]);
		
		$pricing_models = PricingModel::pluck('seller_label','id');
		if($pricing_models)
			$pricing_models = $pricing_models->toArray();
		else
			$pricing_models = [];

		$this->add('pricing_models', 'select', [
			'label' => "Seller listing type",
			'choices' => $pricing_models,
			'attr' => [
                'class' => 'form-control',
                'style' => 'height: 160px',
                'multiple' => 'multiple'
            ],
			'selected' => function ($data) {
				if($data)
					return array_pluck($data, 'id');
				return [];
			},
            'rules' => '',
        ]);

        $this->add('submit', 'submit', ['attr' => ['class' => 'btn btn-primary']]);
    }
}
