<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('글 수정하기') }}
        </h2>
    </x-slot>

    <div class="container p-5">
        <form action="{{route('articles.update',['article'=>$article->id])}}" method="POST" class="mt-5">
        @csrf
        @method('PATCH')
        <input type="text" name="body" class="block w-full mb-2 rounded" value="{{old('body') ?? $article->body}}">
        @error('body')
            <p class="text-xs text-red-500 mb-3">{{$message}}</p>
        @enderror
        <button class="mt-3 py-1 px-3 bg-black text-white rounded text-5">수정하기</button>
        </form>
    </div>
</x-app-layout> 