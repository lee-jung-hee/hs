<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('글목록') }}
        </h2>
    </x-slot>

    <div class="container p-5">
        @foreach($articles as $article)
            <div class="border-2 border-black rounded-2xl mt-5 p-4">
                <p>{{$article->user->name}}</p>
                <p>{{$article->body}}</p>
                <p><a href="{{route('articles.show',['article'=>$article->id])}}">{{$article->created_at->diffForHumans()}}</a></p>
                
                <div>
                    @can('update', $article)
                    <p>
                        <a href="{{route('articles.edit',['article'=>$article->id])}}" class="py-1 px-3 bg-black text-white rounded text-xs">수정</a>
                    </p>
                    @endcan
                    @can('delete', $article)
                    <form action="{{ route('articles.destroy', ['article' => $article->id]) }}" method="POST">
                        @csrf  
                        @method('DELETE')
                        <button class="py-1 px-3 bg-black text-white rounded text-xs">삭제하기</button>
                    </form>
                    @endcan
                </div>
            </div>
        @endforeach
        {{ $articles->links() }}
    </div>
</x-app-layout>