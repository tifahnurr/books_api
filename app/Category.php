<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = [
        'name', 'category_id'
    ];

    protected $table = 'categories';
    protected $primaryKey = 'category_id';
    protected $keyType = 'string';
    public $incrementing = false;

    public function books() {
        return $this->belongsToMany('App\Books', 'book_category', 'category_id', 'book_id');
    }
}
