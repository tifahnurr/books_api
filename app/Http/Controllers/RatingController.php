<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Ramsey\Uuid\Uuid;
use App\Ratings;

class RatingController extends Controller
{
    public function store(Request $request) {
        try {
            $rating = new Ratings;
            $rating->rating_id = Uuid::uuid4()->getHex();
            $rating->user_id = auth()->user()->user_id;
            $rating->book_id = $request->book_id;
            $rating->rating = $request->rating;
            $rating->save();
            return response($rating);
        } catch (\Exception $e) {
            return response($e->getMessage());
        }
    }
    public function edit(Request $request) {
        try {
            $rating = Ratings::find($request->rating_id);
            $rating->rating = $request->rating;
            $rating->save();
            return response($rating);
        } catch (\Exception $e) {
            return response($e->getMessage());
        }
    }

    public function delete($rating_id) {
        try {
            $rating = Ratings::find($rating_id);
            $status = $rating->delete();
            return response($status);
        } catch (\Exception $e) {
            return response($e->getMessage());
        }
    }

}
