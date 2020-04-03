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
}
