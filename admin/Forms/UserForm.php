<?php

namespace Modules\Panel\Forms;

use Kris\LaravelFormBuilder\Form;
use App\Models\Role;

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
		$this->add('new_password', 'password', [
            'rules' => 'min:6',
			'attr' => ['class' => 'form-control', 'autocomplete' => "new-password"],
        ]);
        /*$this->add('is_admin', 'checkbox', [
            'rules' => ''
        ]);*/
        $this->add('is_banned', 'checkbox', [
            'rules' => ''
        ]);
		$this->add('verified', 'checkbox', [
            'label' => 'Email Is Verified',
            'rules' => '',
			/*'help_block' => [
				'text' => "Check this to manually verify the email of a user.",
				'attr' => ['class' => 'help-block text-muted']
			],*/
        ]);

        if(auth()->check() && auth()->user()->hasRole('admin')) {
			
			if(true) {
				$roles = Role::orderBy('id')->pluck('name', 'id');
				if($roles)
					$roles = $roles->toArray();
				else
					$roles = [];

				$this->add('roles', 'select', [
					'choices' => $roles,
					'attr' => [
						'class' => 'form-control',
						'style' => 'height: 160px',
						'multiple' => 'multiple'
					],
					'selected' => function ($data) {
						return $this->model->roles->pluck('id');
					},
					'help_block' => [
						'text' => "Note: Moderators can edit/disable listings & ban members, Editors can edit/publish listings. Must have at least 1 role.",
						'attr' => ['class' => 'help-block text-muted']
					],
				]);
			} else {
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
        }

        $this->add('submit', 'submit', ['attr' => ['class' => 'btn btn-primary']]);
    }
}
