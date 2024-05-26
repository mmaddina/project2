<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Comment;
use App\Models\Article;
use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use App\Mail\CommentMail;
use App\Jobs\VeryLongJob;
use Illuminate\Support\Facades\Notification;
use App\Notifications\CommentNotify;

class CommentController extends Controller
{
    function index(){
        $comment = Cache::rememberForever('comments', function(){
            return DB::table('comments')
            ->join('articles', 'articles.id', 'comments.article_id')
            ->join('users', 'users.id', 'comments.user_id')
            ->select('comments.*', 'articles.id as article_id', 'articles.title as article', 'users.name')
            ->get();
        });
        if(request()->expectsjson()) return response()->json(['comments'=>$comment]);
        return view('comment.index', ['comments'=>$comment]);
    }

    function store(Request $request){
        Cache::forget('comments');
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
        Cache::forget('article_comment'.$comment->article_id);

        if($res){
            VeryLongJob::dispatch($comment, $article);
        } 
        if(request()->expectsjson()) return response()->json($res);
        return redirect()->route('article.show', ['article'=>$request->article_id])->with(['res'=>$res]);
    }

   
    function edit(Comment $comment){
        Gate::authorize('comment', ['comment'=>$comment]);
        return view('comment.edit', ['comment'=>$comment]);
    }

    // function delete(Comment $comment){
    //     Gate::authorize('comment', ['comment'=>$comment]);
    //     return redirect()->route('article.show', ['article'=>1]);
    // }

    public function accept(Comment $comment){
        Cache::forget('comments');
        Cache::forget('article_comment'.$comment->article_id);
        $comment->accept = true;
        $res = $comment->save();
        $users = User::where('id', '!=', $comment->user_id)->get();
        if(request()->expectsjson()) return response()->json($res);
        if ($res) Notification::send($users, new CommentNotify($comment->title, $comment->article_id)); 
        return redirect()->route('comment.index');
    }

    public function reject(Comment $comment){
        Cache::forget('comments');
        Cache::forget('article_comment'.$comment->article_id);
        $comment->accept = false;
        $res = $comment->save();
        if(request()->expectsjson()) return response()->json($res);
        return redirect()->route('comment.index');
    }
    // public function update(Request $request, Comment $comment)
    // {
    //     $keys = DB::table('cache')
    //     ->select('key')
    //     ->whereRaw('`key` GLOB :key', [':key'=> 'comment*[0-9]'])->get();
    //     Gate::authorize('create',[self::class]);
    //     $request->validate([
    //         'date' => 'required',
    //         'title' => 'required|min:6',
    //         'text' => 'required'
    //     ]);
    //     $article->date = $request->date;
    //     $article->title = $request->title;
    //     $article->shortDesc = $request->shortDesc;
    //     $article->text = $request->text;
    //     $article->user_id = 1;
    //     $res = $comment->save();
    //     if(request()->expectsjson()) return response()->json($res);
    //     return redirect()->route('comment.show', ['comment'=>$comment->id]);
    // }

    public function update(Request $request, Comment $comment){
        Cache::forget('comments');
        Cache::forget('article_comment' .$comment->article_id);
        Gate::authorize('comment', ['comment'=>$comment]);
        $request->validate([
            'title'=>'required|min:5',
            'text'=>'required'
        ]);
    
        // $comment->content = $request->content;
        // $comment->save();
        $comment->title = $request->title;
        $comment->desc = $request->text; // Предполагая, что описание комментария хранится в атрибуте 'desc'
        $res = $comment->save();
        if(request()->expectsjson()) return response()->json($res);
        // return redirect()->route('article.show', ['article' => $comment->$article->id]);
        // return redirect()->route('article.show', ['article' => $comment->article->id]);
        return redirect()->route('article.show', ['article'=>$request->article_id])->with(['res'=>$res]);        
        // return view('comment.update', ['comment'=>$comment]);

    }




    
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Comment $comment)
    {
        Cache::flush();
        Gate::authorize('create',[self::class]);
        Gate::authorize('create',[self::class]);
        $res = $comment->delete();
        if(request()->expectsjson()) return response()->json($res);
        return redirect()->route('comment.index');
    }

    public function delete(Comment $comment){
        Cache::forget('comments');
        Cache::forget('article_comment' .$comment->article_id);
        Gate::authorize('comment', ['comment'=>$comment]);
        $comment->delete();
        return redirect()->route('article.show', ['article'=>$comment->article_id]);
    }
}

