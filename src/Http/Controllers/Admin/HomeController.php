<?php

namespace Pmixsolutions\Story\Http\Controllers\Admin;

use Pmixsolutions\Story\Model\Content;
use Illuminate\Support\Facades\Gate;

class HomeController extends EditorController
{
    /**
     * Show Dashboard.
     *
     * @return mixed
     */
    public function show()
    {
        $content = Content::newPostInstance();

        if (Gate::denies('create', $content)) {
            return view('pmixsolutions/story::admin.home');
        }

        return $this->writePost($content);
    }

    /**
     * Write a post.
     *
     * @param  \Pmixsolutions\Story\Model\Content  $content
     *
     * @return mixed
     */
    protected function writePost(Content $content)
    {
        set_meta('title', 'Write a Post');

        $content->setAttribute('format', $this->editorFormat);

        return view('pmixsolutions/story::admin.editor', [
            'content' => $content,
            'url'     => handles('pmixsolutions::storycms/posts'),
            'method'  => 'POST',
        ]);
    }
}
