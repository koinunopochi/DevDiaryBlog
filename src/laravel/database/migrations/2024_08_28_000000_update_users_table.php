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
    Schema::table('users', function (Blueprint $table) {
      // 既存の主キー制約を削除
      $table->dropPrimary('id');

      // id カラムを UUID に変更し、主キーに設定
      $table->uuid('id')->primary()->change();

      // status カラムを追加
      $table->enum('status', ['Active', 'Inactive', 'Suspended', 'Deleted'])->default('Active');
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::table('users', function (Blueprint $table) {
      // id カラムを bigIncrements に戻す
      $table->bigIncrements('id')->change();

      // status カラムを削除
      $table->dropColumn('status');
    });
  }
};
