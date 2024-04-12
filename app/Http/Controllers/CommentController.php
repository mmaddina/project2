<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Comment;

class CommentController extends Controller
{
    function store(Request $request){
        $request->validate([
            'title'=>'required|min:5',
            'desc'=>'required'
        ]);

        $comment = new Comment;
        $comment->title = $request->title;
        $comment->desc= $request->desc;
        $comment->user_id = 1;
        $comment->article_id = $request->article_id;
        $comment->save();
        return redirect()->route('article.show', ['article'=>$request->article_id]);
    }
}

