<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePolicyGroupsTable extends Migration
{
  public function up()
  {
    Schema::create('policy_groups', function (Blueprint $table) {
      $table->uuid('id')->primary();
      $table->string('name')->unique();
      $table->text('description');
      $table->timestamps();

      // 名前に対するインデックスを追加
      $table->index('name');
    });

    Schema::create('policy_group_policy', function (Blueprint $table) {
      $table->uuid('policy_group_id');
      $table->uuid('policy_id');

      $table->foreign('policy_group_id')->references('id')->on('policy_groups')->onDelete('cascade');
      $table->foreign('policy_id')->references('id')->on('policies')->onDelete('cascade');

      // 複合主キー
      $table->primary(['policy_group_id', 'policy_id']);

      // それぞれのIDに対するインデックスを追加
      $table->index('policy_group_id');
      $table->index('policy_id');
    });

    Schema::create('role_policy_group', function (Blueprint $table) {
      $table->uuid('role_id');
      $table->uuid('policy_group_id');

      $table->foreign('role_id')->references('id')->on('roles')->onDelete('cascade');
      $table->foreign('policy_group_id')->references('id')->on('policy_groups')->onDelete('cascade');

      // 複合主キー
      $table->primary(['role_id', 'policy_group_id']);

      // それぞれのIDに対するインデックスを追加
      $table->index('role_id');
      $table->index('policy_group_id');
    });
  }

  public function down()
  {
    Schema::dropIfExists('role_policy_group');
    Schema::dropIfExists('policy_group_policy');
    Schema::dropIfExists('policy_groups');
  }
}
