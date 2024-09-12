<?php

namespace App\Models;

use App\Domain\ValueObjects\PolicyGroupId;
use App\Domain\ValueObjects\PolicyGroupName;
use App\Domain\ValueObjects\PolicyGroupDescription;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class EloquentPolicyGroup extends Model
{
  use HasFactory, HasUuids;

  protected $table = 'policy_groups';

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
      if (empty($model->{$model->getKeyName()})) {
        $model->{$model->getKeyName()} = (new PolicyGroupId())->toString();
      }
    });
  }

  /**
   * ポリシーグループに関連付けられたポリシーを取得
   */
  public function policies(): BelongsToMany
  {
    return $this->belongsToMany(EloquentPolicy::class, 'policy_group_policy', 'policy_group_id', 'policy_id');
  }

  /**
   * ポリシーグループに関連付けられたロールを取得
   */
  public function roles(): BelongsToMany
  {
    return $this->belongsToMany(EloquentRole::class, 'role_policy_group', 'policy_group_id', 'role_id');
  }

  public function setNameAttribute($value)
  {
    $this->attributes['name'] = (new PolicyGroupName($value))->toString();
  }

  public function setDescriptionAttribute($value)
  {
    $this->attributes['description'] = (new PolicyGroupDescription($value))->toString();
  }
}
