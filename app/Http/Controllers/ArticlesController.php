<?php

namespace App\Http\Controllers;

use App\Models\Article;

class ArticlesController extends Controller
{
    /**
     * Display a article
     * @param  int $id
     * @return Response
     */
    public function show($id)
    {
        $article = Article::find($id);
        $article->timestamps = false;
        $article->hits++;
        $article->update();
        /** get same article */
        $same_articles = Article::whereRaw('public = 1 and id != ' . $article->id . ' and category_id = ' . $article->category_id)
            ->orderBy('id', 'desc')
            ->take(10)
            ->get();
        /** get tag by article*/
        $related_tag_articles = Article::withAnyTag($article->tagNames())
            ->where('id', '!=', $id)
            ->wherePublic(1)
            ->whereHas('category', function ($query) {
                $query->where('public', '=', 1);
            })
            ->orderBy('hits', 'desc')
            ->take(5)
            ->get();
        return view('frontend.articles.show', compact('article', 'same_articles', 'related_tag_articles'));
    }
}
