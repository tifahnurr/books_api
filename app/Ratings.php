<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Ratings extends Model
{
    protected $fillable = [
        'rating', 'user_id', 'book_id', 'rating_id'
    ];

    protected $table = 'ratings';
    protected $primaryKey = 'rating_id';
    protected $keyType = 'string';
    public $incrementing = false;
}
