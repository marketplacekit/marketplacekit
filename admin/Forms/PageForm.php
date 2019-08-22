<?php

namespace Modules\Panel\Forms;

use Kris\LaravelFormBuilder\Form;
use LaravelLocalization;

class PageForm extends Form
{
    public function buildForm()
    {
        $this->add('title', 'text', [
            'rules' => 'required|min:3'
        ]);
        $this->add('slug', 'text', [
            'rules' => ''
        ]);

        $choices = [];
        foreach(LaravelLocalization::getSupportedLocales() as $localeCode => $properties) {
            $choices[$localeCode] = $properties['native'];
        }
        $this->add('locale', 'select', [
            'choices' => $choices,
            'empty_value' => '-- SELECT --',
            'rules' => 'required'
        ]);
        $this->add('content', 'textarea', [
            'rules' => ''
        ]);

        $this->add('seo_title', 'text', [
            'rules' => '',
            'attr' => [
                'class' => 'form-control',
            ],
        ]);

        $this->add('seo_meta_description', 'text', [
            'rules' => '',
            'attr' => [
                'class' => 'form-control',
            ],
        ]);

        $this->add('seo_meta_keywords', 'textarea', [
            'rules' => '',
            'attr' => [
                'class' => 'form-control',
                'style' => 'height: 60px',
            ],
        ]);

        $this->add('submit', 'submit', ['attr' => ['class' => 'btn btn-primary']]);
    }
}
