<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use App\Models\User as EloquentUser;

class EloquentArticle extends Model
{
  use HasFactory;

  protected $table = 'articles';

  protected $fillable = [
    'id',
    'title',
    'content',
    'author_id',
    'category_id',
    'status',
    'created_at',
    'updated_at',
  ];

  protected $casts = [
    'id' => 'string',
    'author_id' => 'string',
    'category_id' => 'string',
  ];

  public $incrementing = false;

  protected $keyType = 'string';

  /**
   * Get the author that owns the article.
   */
  public function author(): BelongsTo
  {
    return $this->belongsTo(EloquentUser::class, 'author_id');
  }

  /**
   * Get the category that the article belongs to.
   */
  public function category(): BelongsTo
  {
    return $this->belongsTo(EloquentArticleCategory::class, 'category_id');
  }

  /**
   * The tags that belong to the article.
   */
  public function tags(): BelongsToMany
  {
    return $this->belongsToMany(EloquentTag::class, 'article_tag', 'article_id', 'tag_id');
  }
}
