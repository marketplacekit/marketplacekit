<?php

namespace Modules\Panel\Forms;

use Kris\LaravelFormBuilder\Form;
use App\Models\Category;

class FilterForm extends Form
{
    protected $formOptions = [
        'id' => 'filter_form'
    ];

    public function buildForm()
    {
		$this->add('name', 'text', [
            'rules' => 'required|min:5',
            'attr' => [
                'disabled' => ($this->getFormOption('method') != 'POST')?'disabled':false
            ]
        ]);
        $this->add('field', 'hidden');
        $this->add('position', 'text', [
            'rules' => 'numeric',
        ]);

        $categories = Category::nested()->get();
        $categories = flatten($categories, 0);

        $list = [];
        foreach($categories as $category) {
            $list[ $category['id'] ] = str_repeat("&mdash;", $category['depth']) . $category['name'];
        }
        $this->add('is_category_specific', 'checkbox', [
            'label' => 'Show in specific categories',
            'id' => 'is_global'
        ]);
        $this->add('categories', 'select', [
            'choices' => $list,
            'id' => 'categories',
            'attr' => [
                'class' => 'form-control',
                'style' => 'height: 200px',
                'multiple' => 'multiple'
            ]
        ]);


        $this->add('is_searchable', 'checkbox', [
            'label' => 'Show in search sidebar',
            'id' => 'is_searchable'
        ]);
        $search_ui = [
            'inputFilter'       =>   'inputFilter',
            'refinementList'    =>   'refinementList',
            'menuSelect'        =>   'menuSelect',
            'rangeSlider'       =>   'rangeSlider',
            'priceRange'        =>   'priceRange',
        ];

		
		if($this->getModel() && $this->getModel()->is_default) {
			$this->add('search_ui', 'hidden');
		} else {
            $this->add('search_ui', 'select', [
                'choices' =>$search_ui
            ]);
		}


        $this->add('is_hidden', 'checkbox', [
            'label' => 'Hide field',
            'attr' => [
                'id' => 'show_in_entry_form'
            ]
        ]);
        $this->add('form_input_meta_str', 'hidden', [
            'attr' => [
                'id' => 'form_input_meta'
            ]
        ]);

        $form_ui = [
            'none'              =>   'none',
            'autocomplete'      =>   'autocomplete',
            'checkbox'          =>   'checkbox',
            'checkbox-group'    =>   'checkbox-group',
            'date'              =>   'date',
            'number'            =>   'number',
            'radio-group'       =>   'radio-group',
            'select'            =>   'select',
            'text'              =>   'text',
            'textarea'          =>   'textarea',
        ];
		if($this->getModel() && !$this->getModel()->is_default) {
			$this->add('form_input_type', 'select', [
				'choices' => $form_ui,
				'attr' => [
					'class' => 'form-control',
					'id' => 'form_ui'
				]
			]);
		} else {
			$this->add('form_input_type', 'hidden');
		}
		
        $this->add('submit', 'submit', ['attr' => ['class' => 'btn btn-primary']]);

    }
}
