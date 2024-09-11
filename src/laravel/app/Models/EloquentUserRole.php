<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Domain\ValueObjects\UserRoleId;
use App\Models\User as EloquentUser;

class EloquentUserRole extends Model
{
  use HasFactory, HasUuids;

  protected $table = 'user_roles';

  protected $fillable = [
    'id',
    'user_id',
    'role_id',
    'assigned_at',
    'assigned_by',
  ];

  protected $casts = [
    'assigned_at' => 'datetime',
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
      $userRoleId = new UserRoleId();
      if (empty($model->{$model->getKeyName()})) {
        $model->{$model->getKeyName()} = $userRoleId->toString();
      }
    });
  }

  /**
   * このユーザーロールに関連するユーザーを取得
   */
  public function user(): BelongsTo
  {
    return $this->belongsTo(EloquentUser::class, 'user_id');
  }

  /**
   * このユーザーロールに関連するロールを取得
   */
  public function role(): BelongsTo
  {
    return $this->belongsTo(EloquentRole::class, 'role_id');
  }

  /**
   * このユーザーロールを割り当てたユーザーを取得
   */
  public function assignedByUser(): BelongsTo
  {
    return $this->belongsTo(EloquentUser::class, 'assigned_by');
  }
}
