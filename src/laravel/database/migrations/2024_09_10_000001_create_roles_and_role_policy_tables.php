<?php
// info: 中間テーブルの話
// https://github.com/koinunopochi/DevDiaryBlog/issues/248#issuecomment-2341149187
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRolesAndRolePolicyTables extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('roles', function (Blueprint $table) {
      $table->uuid('id')->primary();
      $table->string('name')->unique();
      $table->text('description');
      $table->timestamps();

      // 名前に対するインデックスを追加
      $table->index('name');
    });

    Schema::create('role_policy', function (Blueprint $table) {
      $table->uuid('role_id');
      $table->uuid('policy_id');

      $table->foreign('role_id')->references('id')->on('roles')->onDelete('cascade');
      $table->foreign('policy_id')->references('id')->on('policies')->onDelete('cascade');

      // 複合主キー
      $table->primary(['role_id', 'policy_id']);

      // role_idとpolicy_idそれぞれに対するインデックスを追加
      $table->index('role_id');
      $table->index('policy_id');
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::dropIfExists('role_policy');
    Schema::dropIfExists('roles');
  }
}
