<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{$article->id}}</title>
</head>
<body>
    <h1>{{$article->id}}</h1>
    <a href="/">HOME</a><a href="/articles">글목록</a>
    <div>
        {{$article->body}}
    </div>
</body>
</html>