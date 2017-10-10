<?php

namespace Pmixsolutions\Story\Http\Controllers\Admin;

use Pmixsolutions\Story\Model\Content;
use Illuminate\Support\Facades\Gate;

class PostsController extends ContentController
{
    /**
     * Define the middleware.
     *
     * @return void
     */
    protected function setupMiddleware()
    {
        parent::setupMiddleware();

        $this->resource = 'posts';
    }

    /**
     * List all the posts.
     *
     * @return mixed
     */
    public function index()
    {
        $contents = Content::with('author')->latestBy(Content::CREATED_AT)->post()->paginate();

        set_meta('title', 'List of Posts');

        return view('pmixsolutions/story::admin.index', [
            'contents' => $contents,
            'create'   => Gate::allows('create', Content::newPostInstance()),
            'type'     => 'post',
        ]);
    }

    /**
     * Write a post.
     *
     * @return mixed
     */
    public function create()
    {
        $content = Content::newPostInstance();
        $content->setAttribute('format', $this->editorFormat);

        $this->authorize('create', $content);

        set_meta('title', 'Write a Post');

        return view('pmixsolutions/story::admin.editor', [
            'content' => $content,
            'url'     => handles('pmixsolutions::storycms/posts'),
            'method'  => 'POST',
        ]);
    }

    /**
     * Edit a post.
     *
     * @param  int  $id
     *
     * @return mixed
     */
    public function edit($id = null)
    {
        set_meta('title', 'Write a Post');

        $content = Content::post()->where('id', $id)->firstOrFail();

        $this->authorize('update', $content);

        return view('pmixsolutions/story::admin.editor', [
            'content' => $content,
            'url'     => handles("pmixsolutions::storycms/posts/{$content->getAttribute('id')}"),
            'method'  => 'PUT',
        ]);
    }

    /**
     * Response when content store has succeed.
     *
     * @param  \Pmixsolutions\Story\Model\Content  $content
     * @param  array  $input
     *
     * @return mixed
     */
    public function storeHasSucceed(Content $content, array $input)
    {
        messages('success', 'Post has been created.');

        return redirect(handles("pmixsolutions::storycms/posts/{$content->getAttribute('id')}/edit"));
    }

    /**
     * Response when content update has succeed.
     *
     * @param  \Pmixsolutions\Story\Model\Content  $content
     * @param  array  $input
     *
     * @return mixed
     */
    public function updateHasSucceed(Content $content, array $input)
    {
        messages('success', 'Post has been updated.');

        return redirect(handles("pmixsolutions::storycms/posts/{$content->getAttribute('id')}/edit"));
    }

    /**
     * Response when content deletion has succeed.
     *
     * @param  \Pmixsolutions\Story\Model\Content  $content
     *
     * @return mixed
     */
    public function deletionHasSucceed(Content $content)
    {
        messages('success', 'Post has been deleted.');

        return redirect(handles('pmixsolutions::storycms/posts'));
    }
}
