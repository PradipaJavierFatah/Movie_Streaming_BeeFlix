<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Movie extends Model
{
    use HasFactory;

    protected $fillable = ['genre_id', 'title', 'photo', 'publish_date', 'description'];

    public function genre()
    {
        return $this->belongsTo(Genre::class);
    }
}

