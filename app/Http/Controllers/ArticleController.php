<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Article;
use Illuminate\Support\Facades\Auth; 

class ArticleController extends Controller
{
    public function create() {
        return view('articles/create');
    }

    public function store(Request $request) {
            // 요청 데이터 검증
        $input = $request->validate([
            'body' => 'required|string|max:255',
        ]);

        $article = new Article();
        $article->body = $input['body'];
        $article->user_id = Auth::id();
        $article->save();

        // 등록 성공 메시지 반환
        return redirect()->route('articles.index');
    }

    public function index(Request $request) {
            // 페이지당 글 수를 요청에서 가져오거나 기본값 설정
        $perPage = $request->input('per_page', 3);

        // Article 모델을 사용하여 글을 최신순으로 페이지네이션하여 조회
        $articles = Article::with('user')
            ->select()
            ->latest()
            ->paginate($perPage);

        // 조회된 글 목록을 articles.index 뷰에 전달하여 반환
        return view('articles.index', ['articles'=> $articles]);
    }

    public function show(Article $article) {
        return view('articles.show', ['article' => $article]);
    }

    public function edit(Article $article) {
        return view('articles.edit', ['article'=> $article]);
    }

    public function update(Request $request, Article $article) {
        $input = $request->validate([
            'body' => 'required|string|max:255',
        ]);
    
        $article->body = $input['body'];
        $article->save();
    
        return redirect()->route('articles.index');
    }

    public function destroy(Article $article) {
        $article->delete();

        return redirect()->route('articles.index');
    }
}
