<?php

namespace Pmixsolutions\Story\Listeners;

use Orchestra\Asset\Factory;

class AttachMarkdownEditor
{
    /**
     * The asset factory implementation.
     *
     * @var \Orchestra\Asset\Factory
     */
    protected $asset;

    /**
     * Construct a new instance.
     *
     * @param  \Orchestra\Asset\Factory  $asset
     */
    public function __construct(Factory $asset)
    {
        $this->asset = $asset;
    }

    /**
     * Handle event.
     *
     * @return void
     */
    public function handle()
    {
        $asset = $this->asset->container('orchestra/foundation::footer');

        $asset->script('simplemde', 'packages/pmixsolutions/story/js/simplemde.js');
        $asset->style('simplemde', 'packages/pmixsolutions/story/css/simplemde.css');
        $asset->script('storycms', 'packages/pmixsolutions/story/js/story.js', ['orchestra']);
    }
}
