<?php

namespace Modules\Panel\Forms;

use Kris\LaravelFormBuilder\Form;
use LaravelLocalization;

class WidgetForm extends Form
{
    protected $formOptions = [
        'id' => 'listings_form'
    ];

    public function buildForm()
    {
        $this->add('title', 'text');
        if($this->getFormOption('method') == 'POST') {
            $this->add('locale', 'select', [
                'label' => 'Language',
                'default_value' => app('request')->input('lang', config('app.default_locale')),
                'choices' =>  array_combine((array) setting('supported_locales'), (array) setting('supported_locales'))
            ]);
        }
        $this->add('alignment', 'select', [
            'label' => 'Title alignment',
            'choices' => ['left' => 'Left', 'center' => 'Center', 'right' => 'Right']
        ]);
        $this->add('position', 'text');
        $this->add('metadata_str', 'hidden', [
            'attr' => [
                'id' => 'metadata'
            ]
        ]);
        $form_ui = [
            'paragraph'         =>   'Paragraph',
            'hero'              =>   'Hero',
            'video'             =>   'Video',
            'category_listing'  =>   'Category Listing',
            'latest_listings'   =>   'Latest Listings',
            'featured_listings'  =>  'Featured Listings',
            'popular_listings'  =>   'Popular Listings',
            'image_gallery'     =>   'Image Gallery'
        ];
        $this->add('type', 'select', [
            'choices' => $form_ui,
            'attr' => [
                'class' => 'form-control',
                'id' => 'type'
            ]
        ]);

        $form_ui = [
            'light'  =>   'light',
            'white'  =>   'white',
            'dark'   =>   'dark',
        ];
        $this->add('background', 'select', [
            'choices' => $form_ui,
            'attr' => [
                'class' => 'form-control',
                'id' => 'background'
            ]
        ]);
        $this->add('style', 'text', [
            'label' => 'Advanced Inline CSS styling'
        ]);

        $this->add('submit', 'submit', ['attr' => ['class' => 'btn btn-primary']]);
    }
}
