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

        $this->add('submit', 'submit', ['attr' => ['class' => 'btn btn-primary']]);
    }
}
