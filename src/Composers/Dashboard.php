<?php

namespace Pmixsolutions\Story\Composers;

use Pmixsolutions\Story\Model\Content;
use Orchestra\Widget\WidgetManager;

class Dashboard
{
    /**
     * The widget manager implementation.
     *
     * @var \Orchestra\Widget\WidgetManager
     */
    protected $widget;

    /**
     * Construct a new composer.
     *
     * @param  \Orchestra\Widget\WidgetManager  $widget
     */
    public function __construct(WidgetManager $widget)
    {
        $this->widget = $widget;
    }

    /**
     * Handle pane for dashboard page.
     *
     * @return void
     */
    public function compose()
    {
        $pane = $this->widget->make('pane.orchestra');

        $posts = Content::post()->publish()->latest(10)->get();

        if ($posts->isEmpty()) {
            return;
        }

        $pane->add('story-latest-posts')
            ->attributes(['class' => 'six columns widget'])
            ->title('Latest Post')
            ->content(view('pmixsolutions/story::widgets.latest-posts')->with('posts', $posts));
    }
}
