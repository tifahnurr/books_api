<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\User;
use App\Books;

class Comments extends Model
{
    protected $fillable = [
        'comment', 'user_id', 'book_id', 'comment_id'
    ];

    protected $table = 'comments';
    protected $primaryKey = 'comment_id';
    protected $keyType = 'string';
    public $incrementing = false;
    public function username() {
        return User::find($this->user_id)->username;
    }   
    public function book_title() {
        return Books::find($this->book_id)->title;
    }   
}
