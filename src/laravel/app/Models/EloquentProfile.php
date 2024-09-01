<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EloquentProfile extends Model
{
  use HasFactory;
  protected $table = 'profiles';

  protected $fillable = [
    'user_id',
    'display_name',
    'bio',
    'avatar_url',
    'social_links'
  ];
}
