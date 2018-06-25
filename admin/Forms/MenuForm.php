<?php

namespace Modules\Panel\Forms;

use Kris\LaravelFormBuilder\Form;
use LaravelLocalization;

class MenuForm extends Form
{
    public function buildForm()
    {
        $this->add('title', 'text', [
            'rules' => 'required|min:3'
        ]);
        $this->add('url', 'text', [
            'label' => 'URL',
            'rules' => ''
        ]);
        $this->add('position', 'text', [
            'rules' => ''
        ]);
        $choices = [];
        foreach(LaravelLocalization::getSupportedLocales() as $localeCode => $properties) {
            $choices[$localeCode] = $properties['native'];
        }
        $this->add('locale', 'select', [
            'choices' => $choices,
            'empty_value' => '-- SELECT --',
            'default'   => 'en',
            'rules' => 'required'
        ]);
        $this->add('submit', 'submit', ['attr' => ['class' => 'btn btn-primary']]);
    }
}
