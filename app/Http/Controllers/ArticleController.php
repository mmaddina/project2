<?php

namespace App\Http\Controllers;

use App\Models\Article;
use Illuminate\Http\Request;
use App\Models\Comment;

class ArticleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $article = Article::latest()->paginate(5);
        return view('article.index', ['articles'=>$article]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('article.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'date' => 'required',
            'title' => 'required|min:6',
            'text' => 'required'
        ]);
        $article = new Article();
        $article->date = $request->date;
        $article->title = $request->title;
        $article->shortDesc = $request->shortDesc;
        $article->text = $request->text;
        $article->user_id = 1;
        $article->save();
        return redirect()->route('article.index');

    }

    /**
     * Display the specified resource.
     */
    public function show(Article $article)
    {
        $comments = Comment::where('article_id', $article->id)->latest()->get();
        return view('article.show', ['article'=>$article, 'comments'=>$comments]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Article $article)
    {
        return view('article.edit', ['article'=>$article]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Article $article)
    {
        $request->validate([
            'date' => 'required',
            'title' => 'required|min:6',
            'text' => 'required'
        ]);
        $article->date = $request->date;
        $article->title = $request->title;
        $article->shortDesc = $request->shortDesc;
        $article->text = $request->text;
        $article->user_id = 1;
        $article->save();
        return redirect()->route('article.show', ['article'=>$article->id]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Article $article)
    {
        $article->delete();
        return redirect()->route('article.index');
    }
}
