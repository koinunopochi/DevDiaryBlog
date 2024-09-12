<?php

namespace App\Models;

use App\Domain\ValueObjects\PolicyId;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class EloquentPolicy extends Model
{
  use HasFactory, HasUuids;

  protected $table = 'policies';

  protected $fillable = [
    'id',
    'name',
    'description',
    'document',
  ];

  protected $casts = [
    'document' => 'array',
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
      $policyId = new PolicyId();
      if (empty($model->{$model->getKeyName()})) {
        $model->{$model->getKeyName()} = $policyId->toString();
      }
    });
  }

  public function getVersionAttribute()
  {
    return $this->document['Version'] ?? null;
  }

  public function getStatementsAttribute()
  {
    return $this->document['Statement'] ?? [];
  }

  /**
   * ポリシーに関連付けられたポリシーグループを取得
   */
  public function policyGroups(): BelongsToMany
  {
    return $this->belongsToMany(EloquentPolicyGroup::class, 'policy_group_policy', 'policy_id', 'policy_group_id');
  }

  /**
   * ポリシーに直接関連付けられたロールを取得
   */
  public function roles(): BelongsToMany
  {
    return $this->belongsToMany(EloquentRole::class, 'role_policy', 'policy_id', 'role_id');
  }
}
