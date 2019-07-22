<?php

namespace Modules\Panel\Forms;

use Kris\LaravelFormBuilder\Form;
use App\Models\Permission;
use App\Models\Category;

class RolesForm extends Form
{
    public function buildForm()
    {
        $this->add('name', 'text', [
            'rules' => 'required|min:3'
        ]);

		$permissions_list = Permission::pluck('name','id');
		if($permissions_list)
			$permissions_list = $permissions_list->toArray();
		else
			$permissions_list = [];
		
		$this->add('permissions', 'select', [
			'choices' => $permissions_list,
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
		#dd(setting('selectable_roles', []));
		$label = "Continue";
		#if($this->formOptions['method'] != 'POST') {
			#if($this->model && $this->model->id > 3) {
        $checked = false;
        if($this->model && $this->model->getMeta('selectable')) {
            $checked = true;
        }
				$label = "Submit";
				$this->add('selectable', 'checkbox', [
					'label' => "Allow user to select this role on registration",
					'value' => 1,
					'checked' => (bool) $checked
				]);
			#}
		#}
		
        $this->add('submit', 'submit', ['label' => $label, 'attr' => ['class' => 'btn btn-primary']]);
    }
}
