<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserRolesTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('user_roles', function (Blueprint $table) {
      $table->uuid('id')->primary();
      $table->uuid('user_id');
      $table->uuid('role_id');
      $table->timestamp('assigned_at');
      $table->uuid('assigned_by');
      $table->timestamps();

      // user_idに対する外部キー制約
      // これにより、user_roles テーブルの user_id は必ず users テーブルの有効な id を参照することが保証されます
      // onDelete('cascade') は、参照先のユーザーが削除された場合、このユーザーロールレコードも自動的に削除されることを意味します
      $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

      // role_idに対する外部キー制約
      // これにより、user_roles テーブルの role_id は必ず roles テーブルの有効な id を参照することが保証されます
      // onDelete('cascade') は、参照先のロールが削除された場合、このユーザーロールレコードも自動的に削除されることを意味します
      $table->foreign('role_id')->references('id')->on('roles')->onDelete('cascade');

      // assigned_byに対する外部キー制約
      // これにより、user_roles テーブルの assigned_by は必ず users テーブルの有効な id を参照することが保証されます
      // onDelete('restrict') は、このユーザーロールを割り当てたユーザーが削除されようとした場合、
      // 削除操作を制限（拒否）することを意味します。これにより、割り当て履歴の整合性が保たれます
      $table->foreign('assigned_by')->references('id')->on('users')->onDelete('restrict');

      // user_idとrole_idの組み合わせに対するユニーク制約
      // これにより、同じユーザーに同じロールが重複して割り当てられることを防ぎます
      // つまり、(user_id, role_id) の組み合わせは、テーブル内で一意でなければなりません
      $table->unique(['user_id', 'role_id']);
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::dropIfExists('user_roles');
  }
}
