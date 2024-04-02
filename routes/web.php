<?php

// 네임스페이스를 사용하여 클래스를 임포트합니다.
use App\Http\Controllers\ProfileController; // ProfileController 클래스 임포트
use App\Http\Controllers\ArticleController;
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

Route::controller(ArticleController::class)->group(function () {
    Route::get('/articles/create', 'create')->name('articles.create');
    Route::post('/articles', 'store')->name('articles.store');
    Route::get('articles', 'index')->name('articles.index');
    Route::get('articles/{article}', 'show')->name('articles.show');
    Route::get('articles/{article}/edit', 'edit')->name('articles.edit');
    Route::patch('articles/{article}', 'update')->name('articles.update');
    Route::delete('articles/{article}', 'destroy')->name('articles.delete');
});
//위 처럼 그룹으로 묶어주면 아래처럼 반복되는 컨트롤러를 생략할 수 있다
    // Route::get('/articles/create', [ArticleController::class, 'create'])->name('articles.create');
    // Route::post('/articles', [ArticleController::class, 'store'])->name('articles.store');
    // Route::get('articles', [ArticleController::class, 'index'])->name('articles.index');
    // Route::get('articles/{article}', [ArticleController::class, 'show'])->name('articles.show');
    // Route::get('articles/{article}/edit', [ArticleController::class, 'edit'])->name('articles.edit');
    // Route::patch('articles/{article}', [ArticleController::class, 'update'])->name('articles.update');
    // Route::delete('articles/{article}', [ArticleController::class, 'destroy'])->name('articles.delete');