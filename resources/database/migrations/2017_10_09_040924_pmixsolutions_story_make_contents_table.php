<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Config;
use Pmixsolutions\Story\Model\Content;

class PmixsolutionsStoryMakeContentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $format = Config::get('pmixsolutions/story::config.default_format', 'markdown');

        Schema::create('pmix_story_contents', function (Blueprint $table) use ($format) {
            $table->increments('id');
            $table->integer('user_id')->unsigned();
            $table->string('slug');
            $table->string('title');
            $table->text('content');
            $table->string('format')->default($format);
            $table->string('type')->default(Content::POST);
            $table->string('status')->default(Content::STATUS_DRAFT);

            $table->nullableTimestamps();
            $table->datetime('published_at');
            $table->softDeletes();

            $table->index('user_id');
            $table->index('slug');
            $table->index('format');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('pmix_story_contents');
    }
}
