<?php

namespace Pmixsolutions\Story\Http\Controllers;

use Orchestra\Routing\Controller;
use Pmixsolutions\Story\Model\Content;
use Illuminate\Support\Facades\View;
use Orchestra\Support\Facades\Facile;
use Illuminate\Support\Facades\Response;

class HomeController extends Controller
{
    /**
     * Get landing page.
     *
     * @return mixed
     */
    public function index()
    {
        $page = config('pmixsolutions/story::default_page', '_posts_');

        if ($page === '_posts_') {
            return $this->posts();
        }

        return $this->page($page);
    }

    /**
     * Show RSS.
     *
     * @return mixed
     */
    public function rss()
    {
        $posts = Content::post()->latestPublish()->limit(config('pmixsolutions/story::per_page', 10))->get();

        return Response::view('pmixsolutions/story::atom', ['posts' => $posts], 200, [
            'Content-Type' => 'application/rss+xml; charset=UTF-8',
        ]);
    }

    /**
     * Show posts.
     *
     * @return mixed
     */
    public function posts()
    {
        $posts = Content::post()->latestPublish()->paginate(config('pmixsolutions/story::per_page', 10));

        return Facile::view('pmixsolutions/story::posts')->with(['posts' => $posts])->render();
    }

    /**
     * Show default page.
     *
     * @param  string  $slug
     *
     * @return mixed
     */
    protected function page($slug)
    {
        $page = Content::page()->publish()->where('slug', '=', $slug)->firstOrFail();
        $slug = preg_replace('/^_page_\//', '', $slug);

        if (! View::exists($view = "pmixsolutions/story::pages.{$slug}")) {
            $view = 'pmixsolutions/story::page';
        }

        return Facile::view($view)->with(['page' => $page])->render();
    }
}
