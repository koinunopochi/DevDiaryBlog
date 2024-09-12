<?php

namespace App\Models;

use App\Domain\ValueObjects\RoleId;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class EloquentRole extends Model
{
  use HasFactory, HasUuids;

  protected $table = 'roles';

  protected $fillable = [
    'id',
    'name',
    'description',
  ];

  /**
   * IDの自動生成を無効化
   */
  public $incrementing = false;

  /**
   * UUIDの自動生成を無効化
   */
  protected static function boot()
  {
    parent::boot();
    static::creating(function ($model) {
      // IDが設定されていない場合のみUUIDを生成
      $roleId = new RoleId();
      if (empty($model->{$model->getKeyName()})) {
        $model->{$model->getKeyName()} = $roleId->toString();
      }
    });
  }

  /**
   * ロールに関連付けられたポリシーを取得
   */
  public function policies(): BelongsToMany
  {
    return $this->belongsToMany(EloquentPolicy::class, 'role_policy', 'role_id', 'policy_id');
  }

  /**
   * ロールに関連付けられたポリシーグループを取得
   */
  public function policyGroups(): BelongsToMany
  {
    return $this->belongsToMany(EloquentPolicyGroup::class, 'role_policy_group', 'role_id', 'policy_group_id');
  }
}
