<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
  public function up()
  {
    Schema::create('series', function (Blueprint $table) {
      $table->uuid('id')->primary();
      $table->string('title', 100);
      $table->string('description', 500)->nullable();
      $table->uuid('author_id');
      $table->enum('status', ['draft', 'published', 'archived', 'deleted']);
      $table->timestamps();

      $table->foreign('author_id')->references('id')->on('users')->onDelete('cascade');
    });
  }

  public function down()
  {
    Schema::dropIfExists('series');
  }
};
