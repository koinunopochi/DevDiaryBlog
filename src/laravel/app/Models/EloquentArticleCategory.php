<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class EloquentArticleCategory extends Model
{
  use HasFactory;

  protected $table = 'article_categories';

  protected $fillable = [
    'id',
    'name',
    'description',
  ];

  protected $casts = [
    'id' => 'string',
  ];

  public $incrementing = false;

  protected $keyType = 'string';

  public function tags(): BelongsToMany
  {
    return $this->belongsToMany(EloquentTag::class, 'article_category_tag', 'article_category_id', 'tag_id');
  }
}
