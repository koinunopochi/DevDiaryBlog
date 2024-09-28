<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
  public function up()
  {
    Schema::create('articles', function (Blueprint $table) {
      $table->uuid('id')->primary();
      $table->string('title', 255);
      $table->text('content');
      $table->uuid('author_id');
      $table->uuid('category_id');
      $table->enum('status', ['draft', 'published', 'archived', 'deleted']);
      $table->timestamps();

      $table->foreign('author_id')->references('id')->on('users')->onDelete('cascade');
      $table->foreign('category_id')->references('id')->on('article_categories')->onDelete('cascade');
    });
  }

  public function down()
  {
    Schema::dropIfExists('articles');
  }
};
