@extends('layouts.app')

@section('title', 'Thể Loại Game')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold text-white mb-8">
        <i class="fas fa-list mr-2"></i> Thể Loại Game
    </h1>

    @if($categories->isEmpty())
        <div class="bg-gray-800 rounded-lg p-12 text-center">
            <i class="fas fa-folder text-6xl text-gray-600 mb-4"></i>
            <p class="text-xl text-gray-400">Chưa có thể loại nào</p>
        </div>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            @foreach($categories as $category)
            <a href="{{ route('categories.show', $category->id) }}" 
               class="group bg-gradient-to-br from-gray-800 to-gray-900 rounded-lg p-6 hover:shadow-xl transition-all transform hover:scale-105">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 bg-blue-600 rounded-lg flex items-center justify-center group-hover:bg-blue-500 transition-colors">
                        <i class="fas fa-gamepad text-2xl text-white"></i>
                    </div>
                    <span class="bg-gray-700 text-gray-300 px-3 py-1 rounded-full text-sm">
                        {{ $category->games_count }} game
                    </span>
                </div>
                
                <h3 class="text-xl font-bold text-white mb-2 group-hover:text-blue-400 transition-colors">
                    {{ $category->name }}
                </h3>
                
                @if($category->description)
                <p class="text-gray-400 text-sm line-clamp-2">
                    {{ $category->description }}
                </p>
                @endif
                
                <div class="mt-4 flex items-center text-blue-400 text-sm group-hover:text-blue-300">
                    <span>Xem tất cả</span>
                    <i class="fas fa-arrow-right ml-2 group-hover:translate-x-1 transition-transform"></i>
                </div>
            </a>
            @endforeach
        </div>
    @endif
</div>
@endsection
