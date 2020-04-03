<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Comments extends Model
{
    protected $fillable = [
        'comment', 'user_id', 'book_id', 'comment_id'
    ];

    protected $table = 'comments';
    protected $primaryKey = 'comment_id';
    protected $keyType = 'string';
    public $incrementing = false;
}
