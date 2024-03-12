<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use App\Models\Article;
use Illuminate\Support\Carbon;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';

Route::get('/articles/create', function () {
    return view('articles/create');
});


Route::post('/articles', function (Request $request) {
    $input = $request->validate([
        'body' => 'required|string|max:255',
    ]);

    return '글이 등록되었습니다.';
});

Route::get('articles', function(Request $request) {
    $perPage = $request->input('per_page', 3);

    $articles = Article::select('user_id', 'body', 'created_at')
    ->latest()
    ->paginate($perPage);

    $results = DB::table('articles as a')
    ->join('users as u','a.user_id','=','u.id')
    ->select(['a.*','u.name'])
    ->latest()
    ->paginate();

    return view('articles.index',
     [
        'articles'=> $articles,
        'results'=> $results
    ]);
});