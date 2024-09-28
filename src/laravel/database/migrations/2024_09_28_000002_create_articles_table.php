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
      $table->string('title', 255)->nullable();
      $table->text('content')->nullable();
      $table->uuid('author_id')->nullable();
      $table->uuid('category_id')->nullable();
      $table->enum('status', ['Draft', 'Published', 'Archived', 'Deleted']);
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
