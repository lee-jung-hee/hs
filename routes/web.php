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

    // // 여기서부터 생 PHP코드
    // $host = config('database.connections.mysql.host');
    // $database = config('database.connections.mysql.database');
    // $username = config('database.connections.mysql.username');
    // $password = config('database.connections.mysql.password');


    // //pdo객체를 만들고
    // $conn = new PDO("mysql:host=$host;dbname=$database", $username, $password);

    // //쿼리 준비
    // $stmt = $conn->prepare("INSERT INTO articles (body, user_id) VALUES (:body, :userId)");


    // $body = $request->input('body'); //body만 가져올 때, request->all하면 배열형식으로 모든 정보를 다 가져옴, request->collect하면 collect로 가져옴(내용은 똑같이 배열로 되어있음)
    // //쿼리 값을 설정
    // $stmt->bindValue(':body', $input['body']);
    // $stmt->bindValue(':userId', Auth::id());    

    // // 실행
    // $stmt->execute();

    // 여기서부터는 킹갓제너럴 1줄로 끝내는 라라벨
    // DB::statement("INSERT INTO articles (body, user_id) VALUES (:body, :userId)", ['body' => $input['body'], 'userId' => Auth::id()]);

    // 쿼리 빌더를 사용하는 법
    // DB::table('articles')->insert([
    //     'body' => $input['body'],
    //     'user_id' => Auth::id()
    // ]);
    
    // 여기서 부터는 킹 갓 엠퍼러 제너럴 충무공 마제스티 엘로퀀트 ORM
    // $article = new Article;
    // $article->body = $input['body'];
    // $article->user_id = Auth::id();
    // $article->save();

    // Article::create([
    //     'body'=> $input['body'],
    //     'user_id'=> Auth::id()
    // ]);

    return '글이 등록되었습니다.';
});

Route::get('articles', function(Request $request) {
    $perPage = $request->input('per_page', 3);


    $articles = Article::select('user_id', 'body', 'created_at')
    ->latest()
    ->paginate($perPage);

    $totalCount = Article::count();

    return view('articles.index',['articles'=> $articles]);
});