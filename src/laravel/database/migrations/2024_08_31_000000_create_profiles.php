<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
  /**
   * Run the migrations.
   */
  public function up(): void
  {
    Schema::create('profiles', function (Blueprint $table) {
      $table->id();
      $table->uuid('user_id')->unique(); // user_id を外部キーとして設定し、ユニーク制約を追加
      $table->string('display_name', 50); // 1文字以上50文字以下の文字列
      $table->string('bio', 500)->nullable(); // 0文字以上500文字以下の文字列、NULL可
      $table->string('avatar_url'); // URL形式
      $table->json('social_links'); // JSON形式で保存

      $table->timestamps(); // created_at, updated_at カラムを追加

      // 外部キー制約を設定
      // $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('profiles');
  }
};
