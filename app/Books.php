<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Books extends Model
{
    protected $fillable = [
        'title', 'book_id'
    ];
    // protected $hidden = [
    //     'categories'
    // ];

    protected $table = 'books';
    public $incrementing = false;
    protected $primaryKey = 'book_id';
    protected $keyType = 'string';

    public function categories() {
        return $this->belongsToMany('App\Category', 'book_category', 'book_id', 'category_id');
        
    }

    public function categoriesString() {
        $categories = $this->categories;
        $data = [];
        $i = 0;
        foreach($categories as $category) {
            $data[$i] = $category->name;
            $i++;
        }
        return $data;
    }

    public function comments() {
        $comments = $this->hasMany('App\Comments', 'book_id');
        $index=0;
        foreach ($comments as $comment) {
            $comments[$index]->username = $comment->username();
            $index++;
        }
        return $comments;
    }
    public function ratings() {
        $rating = $this->hasMany('App\Ratings', 'book_id')->avg('rating');
        return $rating;
    }
}
