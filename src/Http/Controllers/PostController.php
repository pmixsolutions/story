<?php

namespace Pmixsolutions\Story\Http\Controllers;

use Pmixsolutions\Story\Model\Content;
use Illuminate\Support\Facades\View;
use Orchestra\Support\Facades\Facile;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class PostController extends ContentController
{
    /**
     * {@inheritdoc}
     */
    protected function getResponse($page, $id, $slug)
    {
        $slug = preg_replace('/^_post_\//', '', $slug);

        if (! View::exists($view = "pmixsolutions/story::posts.{$slug}")) {
            $view = 'pmixsolutions/story::post';
        }

        $data = [
            'id'   => $id,
            'page' => $page,
            'slug' => $slug,
        ];

        return Facile::view($view)->with($data)->render();
    }

    /**
     * {@inheritdoc}
     */
    protected function getRequestedContent($id, $slug)
    {
        if (isset($id) and ! is_null($id)) {
            return Content::post()->publish()->where('id', $id)->firstOrFail();
        } elseif (isset($slug) and ! is_null($slug)) {
            return Content::post()->publish()->where('slug', "_post_/{$slug}")->firstOrFail();
        }

        throw new NotFoundHttpException();
    }
}
