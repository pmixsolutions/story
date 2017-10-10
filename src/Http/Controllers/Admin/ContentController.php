<?php

namespace Pmixsolutions\Story\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Pmixsolutions\Story\Model\Content;
use Pmixsolutions\Story\Processor\Content as Processor;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Pmixsolutions\Story\Contracts\Listener\Content as Listener;

abstract class ContentController extends EditorController implements Listener
{
    use AuthorizesRequests;

    /**
     * Current Resource.
     *
     * @var string
     */
    protected $resource;

    /**
     * Processor instance.
     *
     * @var \Pmixsolutions\Story\Processor\Content
     */
    protected $processor;

    /**
     * Content CRUD Controller.
     *
     * @param \Pmixsolutions\Story\Processor\Content  $processor
     */
    public function __construct(Processor $processor)
    {
        $this->processor = $processor;

        parent::__construct();
    }

    /**
     * List all the contents.
     *
     * @return mixed
     */
    abstract public function index();

    /**
     * Write a content.
     *
     * @return mixed
     */
    abstract public function create();

    /**
     * Store a content.
     *
     * @param  \Illuminate\Http\Request  $request
     *
     * @return mixed
     */
    public function store(Request $request)
    {
        return $this->processor->store($this, $request->all());
    }

    /**
     * Edit a content.
     *
     * @param  int  $id
     *
     * @return mixed
     */
    abstract public function edit($id = null);

    /**
     * Update a content.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     *
     * @return mixed
     */
    public function update(Request $request, $id = null)
    {
        return $this->processor->update($this, $id, $request->all());
    }

    /**
     * Delete a content.
     *
     * @param  int  $id
     *
     * @return mixed
     */
    public function delete($id = null)
    {
        return $this->destroy($id);
    }

    /**
     * Delete a content.
     *
     * @param  int  $id
     *
     * @return mixed
     */
    public function destroy($id)
    {
        return $this->processor->destroy($this, $id);
    }

    /**
     * Response when content update has failed validation.
     *
     * @param  \Illuminate\Support\MessageBag|array  $errors
     *
     * @return mixed
     */
    public function storeHasFailedValidation($errors)
    {
        return redirect_with_errors(handles("pmixsolutions::storycms/{$this->resource}/create"), $errors);
    }

    /**
     * Response when content store has succeed.
     *
     * @param  \Pmixsolutions\Story\Model\Content  $content
     * @param  array  $input
     *
     * @return mixed
     */
    abstract public function storeHasSucceed(Content $content, array $input);

    /**
     * Response when content update has failed validation.
     *
     * @param  int|string  $id
     * @param  \Illuminate\Support\MessageBag|array  $errors
     *
     * @return mixed
     */
    public function updateHasFailedValidation($id, $errors)
    {
        return redirect_with_errors(handles("pmixsolutions::storycms/{$this->resource}/{$id}/edit"), $errors);
    }

    /**
     * Response when content update has succeed.
     *
     * @param  \Pmixsolutions\Story\Model\Content  $content
     * @param  array  $input
     *
     * @return mixed
     */
    abstract public function updateHasSucceed(Content $content, array $input);

    /**
     * Response when content deletion has succeed.
     *
     * @param  \Pmixsolutions\Story\Model\Content  $content
     *
     * @return mixed
     */
    abstract public function deletionHasSucceed(Content $content);
}
