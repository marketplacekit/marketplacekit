<?php

namespace Modules\Panel\Forms;

use Kris\LaravelFormBuilder\Form;

class PricingModelForm extends Form
{
    public function buildForm()
    {
        $this->add('site_name', 'text', [
            'rules' => '',
            'default_value' => $this->getData('site_name')
        ]);
        $this->add('google_analytics_key', 'text', [
            'rules' => '',
            'default_value' => $this->getData('google_analytics_key')
        ]);
        $this->add('theme', 'text', [
            'rules' => '',
            'default_value' => $this->getData('theme')
        ]);

        $this->add('submit', 'submit', ['attr' => ['class' => 'btn btn-primary']]);
    }
}
