<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\User as EloquentUser;

class EloquentSeries extends Model
{
  use HasFactory;

  protected $table = 'series';

  protected $fillable = [
    'id',
    'title',
    'description',
    'author_id',
    'status',
    'created_at',
    'updated_at',
  ];

  protected $casts = [
    'id' => 'string',
    'author_id' => 'string',
    'status' => 'string',
  ];

  public $incrementing = false;

  protected $keyType = 'string';

  /**
   * Get the author that owns the series.
   */
  public function author(): BelongsTo
  {
    return $this->belongsTo(EloquentUser::class, 'author_id');
  }
}
