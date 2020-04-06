<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Ramsey\Uuid\Uuid;
use App\Books;

class BookController extends Controller
{
    public function store(Request $request) {
        try {
            $book = new Books;
            $book->book_id = Uuid::uuid4()->getHex();
            $book->title = $request->title;
            $book->save();
            return response($book);
        } catch (\Exception $e) {
            return response($e->getMessage());
        }
    }

    public function update(Request $request) {
        try {
            // echo ($request->book_id);
            $book = Books::find($request->book_id);
            $book->title = $request->title;
            $book->save();
            return response($book);
        } catch (\Exception $e) {
            return response($e->getMessage());
        }
    }

    public function all() {
        try {
            $data = [];
            $i = 0;
            foreach (Books::all() as $book) {
                $data[$i] = $book;
                $j = 0;
                $data[$i]['category'] = "";
                foreach ($book->categoriesString() as $category) {
                    $data[$i]['category'] .= $category.",";
                    $j++;
                } 
                $data[$i]->setHidden(['categories']);
                $data[$i]['category'] = rtrim($data[$i]['category'], ',');
                $i++;
            }

            return response($data);
        } catch (\Exception $e) {
            return response($e->getMessage());
        }
    }

    public function get($id) {
        $book = Books::findOrFail($id);
        $book['rating'] = $book->ratings();
        $book['comments'] = $book->comments()->where('approved', 1)->get();
        $book->categories;
        return response($book);
    }

    public function delete($book_id, Request $request) {
        $book = Books::findOrFail($book_id);
        $status = $book->delete();
        return response(($status == 1) ? 'Deleted' : 'Fail to delete');
    }

    public function addCategory(Request $request) {
        try {
            $book = Books::findOrFail($request->book_id);
            $book->categories()->attach($request->category_id);
            return response($book);
        } catch (\Exception $e) {
            return response($e->getMessage());
        }
    }

    public function getCategories($id) {
        try {
            $book = Books::findOrFail($id);
            $categories = $book->categories;
            return response($categories);
        } catch (\Exception $e) {
            return response($e->getMessage());
        }
    }

}
