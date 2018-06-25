<?php

namespace Modules\Panel\Forms;

use Kris\LaravelFormBuilder\Form;

class UserForm extends Form
{
    public function buildForm()
    {
        $this->add('name', 'text', [
            'rules' => 'required|min:3'
        ]);
        $this->add('email', 'email', [
            'rules' => ''
        ]);
        $this->add('display_name', 'text', [
            'rules' => ''
        ]);
        $this->add('is_admin', 'checkbox', [
            'rules' => ''
        ]);
        $this->add('is_banned', 'checkbox', [
            'rules' => ''
        ]);

        $this->add('submit', 'submit', ['attr' => ['class' => 'btn btn-primary']]);
    }
}
