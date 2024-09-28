<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
  public function up()
  {
    Schema::create('article_tag', function (Blueprint $table) {
      $table->uuid('article_id');
      $table->uuid('tag_id');
      $table->primary(['article_id', 'tag_id']);

      $table->foreign('article_id')->references('id')->on('articles')->onDelete('cascade');
      $table->foreign('tag_id')->references('id')->on('tags')->onDelete('cascade');
    });
  }

  public function down()
  {
    Schema::dropIfExists('article_tag');
  }
};
