<?php

namespace Pmixsolutions\Story;

use Pmixsolutions\Story\Composers\Dashboard;
use Pmixsolutions\Story\Listeners\AttachMarkdownEditor;
use Pmixsolutions\Story\Http\Middleware\SetEditorFormat;
use Orchestra\Foundation\Support\Providers\ModuleServiceProvider;

class StoryServiceProvider extends ModuleServiceProvider
{
    /**
     * The application or extension namespace.
     *
     * @var string|null
     */
    protected $namespace = 'Pmixsolutions\Story\Http\Controllers';

    /**
     * The application or extension group namespace.
     *
     * @var string|null
     */
    protected $routeGroup = 'pmixsolutions/story';

    /**
     * The fallback route prefix.
     *
     * @var string
     */
    protected $routePrefix = 'cms';

    /**
     * The event handler mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        'pmixsolutions.story.editor: markdown' => [AttachMarkdownEditor::class],
    ];

    /**
     * The application's route middleware.
     *
     * @var array
     */
    protected $routeMiddleware = [
        'pmixsolutions.story.editor' => SetEditorFormat::class,
    ];

    /**
     * Register service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->registerStoryTeller();

        $this->registerFormatManager();
    }

    /**
     * Register service provider.
     *
     * @return void
     */
    protected function registerStoryTeller()
    {
        $this->app->singleton('pmixsolutions.story', function ($app) {
            return new Storyteller($app);
        });
    }

    /**
     * Register service provider.
     *
     * @return void
     */
    protected function registerFormatManager()
    {
        $this->app->singleton('pmixsolutions.story.format', function ($app) {
            return new FormatManager($app);
        });
    }

    /**
     * Boot extension components.
     *
     * @return void
     */
    protected function bootExtensionComponents()
    {
        $path = realpath(__DIR__.'/../resources');

        $acl    = $this->app->make('orchestra.acl');
        $memory = $this->app->make('orchestra.platform.memory');

        $acl->make('pmixsolutions/story')->attach($memory);

        $this->addConfigComponent('pmixsolutions/story', 'pmixsolutions/story', "{$path}/config");
        $this->addViewComponent('pmixsolutions/story', 'pmixsolutions/story', "{$path}/views");
    }

    /**
     * Boot extension routing.
     *
     * @return void
     */
    protected function loadRoutes()
    {
        $path = realpath(__DIR__);

        $this->loadFrontendRoutesFrom("{$path}/Http/frontend.php");
        $this->loadBackendRoutesFrom("{$path}/Http/backend.php", "{$this->namespace}\Admin");
    }
}
