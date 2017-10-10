<?php

namespace Pmixsolutions\Story\Validation;

use Orchestra\Support\Validator;

class Content extends Validator
{
    /**
     * Validation rules.
     *
     * @var array
     */
    protected $rules = [
        'title'   => ['required'],
        'slug'    => ['required', 'not_in:rss,posts'],
        'content' => ['required'],
    ];

    /**
     * On create scenario.
     *
     * @return void
     */
    protected function onCreate()
    {
        $this->rules['slug'] = ['required', 'not_in:rss,posts', 'unique:pmix_story_contents,slug'];
    }

    /**
     * On update scenario.
     *
     * @return void
     */
    protected function onUpdate()
    {
        $this->rules['slug'] = ['required', 'not_in:rss,posts', 'unique:pmix_story_contents,slug,{id}'];
    }
}
