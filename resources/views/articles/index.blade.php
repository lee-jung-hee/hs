<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite(['resources/css/app.css', 'resources/js/app.js']) 
</head>
<body class="bg-slate-100">
    
    <div class="container p-5">
        <h1 class="text-xl">글목록</h1><a href="/">HOME</a><a href="/articles/create">글쓰기</a>
        @foreach($articles as $article)
            <div class="border-2 border-black rounded-2xl mt-5 p-4">
                <p>{{$article->user->name}}</p>
                <p>{{$article->body}}</p>
                <p><a href="{{route('articles.show',['article'=>$article->id])}}">{{$article->created_at->diffForHumans()}}</a></p>
                <p><a href="{{route('articles.edit',['article'=>$article->id])}}">수정</a></p>
            </div>
        @endforeach
        {{ $articles->links() }}
    </div>
</body>
</html>