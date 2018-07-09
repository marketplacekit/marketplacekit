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
        /*$this->add('is_admin', 'checkbox', [
            'rules' => ''
        ]);*/
        $this->add('is_banned', 'checkbox', [
            'rules' => ''
        ]);

        if(auth()->check() && auth()->user()->hasRole('admin')) {
            $this->add('role', 'select', [
                'choices' => [null => 'Unassigned', 'admin' => 'Admin', 'moderator' => 'Moderator', 'editor' => 'Editor', 'member' => 'Member'],
                'selected' => function ($data) {
                    return $this->model->getRoleNames()->first();
                },
                'help_block' => [
                    'text' => "Note: Moderators can edit/disable listings & ban members, Editors can edit/publish listings",
                    'attr' => ['class' => 'help-block text-muted']
                ],
            ]);
        }

        $this->add('submit', 'submit', ['attr' => ['class' => 'btn btn-primary']]);
    }
}
