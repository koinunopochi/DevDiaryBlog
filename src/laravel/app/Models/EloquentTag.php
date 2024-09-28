<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EloquentTag extends Model
{
  use HasFactory;

  protected $table = 'tags';

  protected $fillable = [
    'id',
    'name',
  ];

  protected $casts = [
    'id' => 'string',
  ];

  public $incrementing = false;

  protected $keyType = 'string';
}
