<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Ramsey\Uuid\Uuid;
use App\Category;

class CategoryController extends Controller
{
    public function store(Request $request) {
        try {
            $category = new Category;
            $category->category_id = Uuid::uuid4()->getHex();
            $category->name = $request->name;
            $category->save();
            return response($category);
        } catch (\Exception $e) {
            return response($e->getMessage());
        }
    }

    public function update(Request $request) {
        try {
            $category = Category::findOrFail($request->category_id);
            $category->name = $request->name;
            $category->save();
            return response($category);
        } catch (\Exception $e) {
            return response($e->getMessage());
        }
    }
    
    public function getBooks(Request $request) {
        try {
            $category = Category::find($request->category_id);
            return $category->books;
        } catch (\Exception $e) {
            return response($e->getMessage());
        }
    }

    public function all() {
        try {
            return response(Category::all());
        } catch (\Exception $e) {
            return response($e->getMessage());
        }
    }

    public function delete(Request $request) {
        try {
            $category = Category::findOrFail($request->category_id);
            $status = $category->delete();
            return response(($status == 1) ? 'Deleted' : 'Fail to delete');
        } catch(\Exception $e) {
            return response($e->getMessage());
        }
    }
}
