<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Comment;
use App\Models\Article;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Mail\CommentMail;
use App\Jobs\VeryLongJob;

class CommentController extends Controller
{

    function index(){
       $comments = DB::table('comments')
            ->join('articles', 'articles.id', 'comments.article_id')
            ->join('users', 'users.id', 'comments.user_id')
            ->select('comments.*','articles.id as article_id', 'articles.title as article', 'users.name')
            ->get(); 
        return view('comment.index', ['comments'=>$comments]);      
    }

    function store(Request $request){
        $request->validate([
            'title'=>'required|min:5',
            'desc'=>'required'
        ]);

        $article = Article::findOrFail($request->article_id);
        $comment = new Comment;
        $comment->title = $request->title;
        $comment->desc= $request->desc;
        $comment->user_id = auth()->id();
        $comment->article_id = $request->article_id;
        $res = $comment->save();

        if($res) VeryLongJob::dispatch($comment, $article);

        return redirect()->route('article.show', ['article'=>$request->article_id])->with(['res'=>$res]);
    }

    function edit(Comment $comment){
        Gate::authorize('comment', ['comment'=>$comment]);
        return view('comment.update', ['comment'=>$comment]);
    }

    function delete(Comment $comment){
        Gate::authorize('comment', ['comment'=>$comment]);
        return redirect()->route('article.show', ['article'=>1]);
    }

    public function accept(Comment $comment){
        Log::alert($comment);
        $comment->accept = true;
        $comment->save();
        return redirect()->route('comment.index');
    }

    public function reject(Comment $comment){
        $comment->accept = false;
        $comment->save();
        return redirect()->route('comment.index');
    }
}

