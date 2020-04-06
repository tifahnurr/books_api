<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Ramsey\Uuid\Uuid;
use App\Comments;

class CommentController extends Controller
{
    public function store(Request $request) {
        try {
            $comment = new Comments;
            $comment->comment_id = Uuid::uuid4()->getHex();
            $comment->user_id = auth()->user()->user_id;
            $comment->book_id = $request->book_id;
            $comment->comment = $request->comment;
            $comment->save();
            return response($comment);
        } catch (\Exception $e) {
            return response($e->getMessage());
        }
    }

    public function all() {
        try {
            $comments = Comments::all();
            return response($comments);
        } catch (\Exception $e) {
            return response($e->getMessage());
        }
    }

    public function approve(Request $request) {
        try {
            $comment = Comments::find($request->comment_id);
            $comment->approved = 1;
            $comment->save();
            return response($comment);
        } catch (\Exception $e) {
            return response($e->getMessage());
        }
    }
}
