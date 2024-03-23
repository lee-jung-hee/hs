<?php

// 네임스페이스를 사용하여 클래스를 임포트합니다.
use App\Http\Controllers\ProfileController; // ProfileController 클래스 임포트
use Illuminate\Support\Facades\Route; // 라우트를 정의하기 위한 Route 퍼사드
use Illuminate\Http\Request; // HTTP 요청을 캡슐화하는 클래스
use Illuminate\Support\Facades\Auth; // 사용자 인증을 위한 Auth 퍼사드
use Illuminate\Support\Facades\DB; // 데이터베이스 작업을 위한 DB 퍼사드
use Illuminate\Support\Facades\Redirect; // 리다이렉트 응답을 생성하기 위한 Redirect 퍼사드
use App\Models\Article; // Article 모델 클래스 임포트
use Illuminate\Support\Carbon; // 날짜와 시간을 다루기 위한 Carbon 라이브러리

// '/' 경로로 GET 요청이 오면 welcome 뷰를 반환하는 라우트 정의
Route::get('/', function () {
    return view('welcome'); // 뷰 반환
});

// '/dashboard' 경로로 GET 요청이 오면 dashboard 뷰를 반환하는 라우트 정의
// auth와 verified 미들웨어를 사용하여 인증 및 이메일 검증을 요구
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// auth 미들웨어를 적용한 라우트 그룹
Route::middleware('auth')->group(function () {
    // '/profile' 경로로 GET 요청이 오면 ProfileController의 edit 메소드를 호출
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    // '/profile' 경로로 PATCH 요청이 오면 ProfileController의 update 메소드를 호출
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    // '/profile' 경로로 DELETE 요청이 오면 ProfileController의 destroy 메소드를 호출
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php'; // 다른 PHP 파일 포함

// '/articles/create' 경로로 GET 요청이 오면 articles/create 뷰를 반환하는 라우트 정의
Route::get('/articles/create', function () {
    return view('articles/create');
})->name('articles.create');

// '/articles' 경로로 POST 요청이 오면 글 등록 처리를 수행하는 라우트 정의
Route::post('/articles', function (Request $request) {
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
})->name('articles.store');

// '/articles' 경로로 GET 요청이 오면 Article 모델을 사용하여 글 목록을 조회하는 라우트 정의
Route::get('articles', function(Request $request) {
    // 페이지당 글 수를 요청에서 가져오거나 기본값 설정
    $perPage = $request->input('per_page', 3);

    // Article 모델을 사용하여 글을 최신순으로 페이지네이션하여 조회
    $articles = Article::with('user')
        ->select()
        ->latest()
        ->paginate($perPage);

    // 조회된 글 목록을 articles.index 뷰에 전달하여 반환
    return view('articles.index', ['articles'=> $articles]);
})->name('articles.index');

// '/articles/{article}' 경로로 GET 요청이 오면 Article 모델 인스턴스를 사용하여 특정 글을 보여주는 라우트 정의
// {article} 파라미터는 Article 모델 인스턴스로 자동 바인딩됨
Route::get('articles/{article}', function(Article $article) {
    // 조회된 Article 모델 인스턴스를 articles.show 뷰
    return view('articles.show', ['article' => $article]);
})->name('articles.show');

Route::get('articles/{article}/edit', function(Article $article) {
    return view('articles.edit', ['article'=> $article]);
})->name('articles.edit');


Route::put('articles/{article}', function (Request $request, Article $article) {
    $input = $request->validate([
        'body' => 'required|string|max:255',
    ]);

    $article->body = $input['body'];
    $article->save();

    return redirect()->route('articles.index');
})->name('articles.update');


