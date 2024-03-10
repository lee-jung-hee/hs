<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite(['resources/css/app.css', 'resources/js/app.js']) 
</head>
<body class="bg-slate-100">
    <div class="container p-5">
   <h1 class="text-xl">글목록</h1><a href="/">HOME</a>
    @foreach($articles as $article)
    <div class="border-2 border-black rounded-2xl mt-5 p-4">
        <p>{{$article->user_id}}</p>
        <p>{{$article->body}}</p>
        <p>{{$article->created_at->diffForHumans()}}</p>
    </div>
    @endforeach
</div>
{{ $articles->links() }}
</body>
</html>