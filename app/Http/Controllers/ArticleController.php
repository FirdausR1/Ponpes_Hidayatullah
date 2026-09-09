<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Article;

class ArticleController extends Controller
{
    public function index(Request $request)
    {
        $query = Article::published()->latest('published_at');

        if ($request->filled('q')) {
            $search = $request->q;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('excerpt', 'like', "%{$search}%")
                  ->orWhere('content', 'like', "%{$search}%");
            });
        }

        if ($request->filled('kategori') && $request->kategori !== 'Semua') {
            $query->where('category', $request->kategori);
        }

        $featured = null;
        if (!$request->filled('q') && (!$request->filled('kategori') || $request->kategori === 'Semua')) {
            $featured = Article::published()->latest('published_at')->first();
        }

        $articles = $query->paginate(9)->withQueryString();

        $categories = [
            'Semua',
            'Kajian Subuh',
            'Prestasi',
            'Literasi Turats',
            'Informasi PSB',
            'Warta Kampus',
        ];

        return view('berita.index', compact('articles', 'featured', 'categories'));
    }

    public function show($slug)
    {
        $article = Article::where('slug', $slug)->firstOrFail();

        // Increment views
        $article->increment('views');

        // Fetch related articles
        $related = Article::published()
            ->where('id', '!=', $article->id)
            ->where('category', $article->category)
            ->latest('published_at')
            ->take(3)
            ->get();

        if ($related->count() < 3) {
            $extra = Article::published()
                ->where('id', '!=', $article->id)
                ->whereNotIn('id', $related->pluck('id'))
                ->latest('published_at')
                ->take(3 - $related->count())
                ->get();
            $related = $related->merge($extra);
        }

        return view('berita.show', compact('article', 'related'));
    }
}
