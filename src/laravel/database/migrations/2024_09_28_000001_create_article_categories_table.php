<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
  public function up()
  {
    Schema::create('article_categories', function (Blueprint $table) {
      $table->uuid('id')->primary();
      $table->string('name', 50)->unique();
      $table->string('description', 255)->nullable();
      $table->timestamps();
    });
  }

  public function down()
  {
    Schema::dropIfExists('article_categories');
  }
};
