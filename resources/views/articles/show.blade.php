<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
          {{$article->id}}번글
        </h2>
    </x-slot>

    <div>
        {{$article->body}}
    </div>
</x-app-layout>
