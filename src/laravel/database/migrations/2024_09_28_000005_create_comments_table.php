<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
  public function up()
  {
    Schema::create('comments', function (Blueprint $table) {
      $table->uuid('id')->primary();
      $table->uuid('article_id');
      $table->uuid('author_id');
      $table->text('content');
      $table->timestamps();

      $table->foreign('article_id')->references('id')->on('articles')->onDelete('cascade');
      $table->foreign('author_id')->references('id')->on('users')->onDelete('cascade');
    });
  }

  public function down()
  {
    Schema::dropIfExists('comments');
  }
};
