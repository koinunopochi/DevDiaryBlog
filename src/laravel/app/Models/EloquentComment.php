<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\User as EloquentUser;
use App\Models\EloquentArticle;

class EloquentComment extends Model
{
  use HasFactory;

  protected $table = 'comments';

  protected $fillable = [
    'id',
    'article_id',
    'author_id',
    'content',
    'created_at',
    'updated_at',
  ];

  protected $casts = [
    'id' => 'string',
    'article_id' => 'string',
    'author_id' => 'string',
  ];

  public $incrementing = false;

  protected $keyType = 'string';

  /**
   * Get the article that the comment belongs to.
   */
  public function article(): BelongsTo
  {
    return $this->belongsTo(EloquentArticle::class, 'article_id');
  }

  /**
   * Get the author that owns the comment.
   */
  public function author(): BelongsTo
  {
    return $this->belongsTo(EloquentUser::class, 'author_id');
  }
}
