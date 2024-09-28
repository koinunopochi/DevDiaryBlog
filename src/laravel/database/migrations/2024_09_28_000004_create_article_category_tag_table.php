<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
  public function up()
  {
    Schema::create('article_category_tag', function (Blueprint $table) {
      $table->uuid('article_category_id');
      $table->uuid('tag_id');
      $table->primary(['article_category_id', 'tag_id']);

      $table->foreign('article_category_id')->references('id')->on('article_categories')->onDelete('cascade');
      $table->foreign('tag_id')->references('id')->on('tags')->onDelete('cascade');
    });
  }

  public function down()
  {
    Schema::dropIfExists('article_category_tag');
  }
};
