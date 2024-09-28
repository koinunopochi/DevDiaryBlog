<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
  public function up()
  {
    Schema::create('series_articles', function (Blueprint $table) {
      $table->uuid('id')->primary();
      $table->uuid('series_id');
      $table->uuid('article_id');
      $table->unsignedSmallInteger('order');
      $table->timestamps();

      $table->foreign('series_id')->references('id')->on('series')->onDelete('cascade');
      $table->foreign('article_id')->references('id')->on('articles')->onDelete('cascade');
      $table->unique(['series_id', 'article_id']);
      $table->unique(['series_id', 'order']);
    });
  }

  public function down()
  {
    Schema::dropIfExists('series_articles');
  }
};
