<?php

namespace Pmixsolutions\Story\Http\Controllers\Admin;

use Orchestra\Routing\Controller;
use Illuminate\Support\Facades\Input;
use Pmixsolutions\Story\Facades\StoryFormat;

abstract class EditorController extends Controller
{
    /**
     * Editor format.
     *
     * @var string
     */
    protected $editorFormat;

    /**
     * Define middleware for the controller.
     */
    public function __construct()
    {
        $this->setupMiddleware();
    }

    /**
     * Define the middleware.
     *
     * @return void
     */
    protected function setupMiddleware()
    {
        $this->editorFormat = StoryFormat::get(Input::get('format'));

        $this->middleware('orchestra.auth');
        $this->middleware("pmixsolutions.story.editor:{$this->editorFormat}");
    }
}
