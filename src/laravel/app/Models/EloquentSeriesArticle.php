<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EloquentSeriesArticle extends Model
{
  use HasFactory;

  protected $table = 'series_articles';

  protected $fillable = [
    'id',
    'series_id',
    'article_id',
    'order',
    'created_at',
    'updated_at',
  ];

  protected $casts = [
    'id' => 'string',
    'series_id' => 'string',
    'article_id' => 'string',
    'order' => 'integer',
  ];

  public $incrementing = false;

  protected $keyType = 'string';

  /**
   * Get the series that owns the series article.
   */
  public function series(): BelongsTo
  {
    return $this->belongsTo(EloquentSeries::class, 'series_id');
  }

  /**
   * Get the article that is included in the series.
   */
  public function article(): BelongsTo
  {
    return $this->belongsTo(EloquentArticle::class, 'article_id');
  }
}
