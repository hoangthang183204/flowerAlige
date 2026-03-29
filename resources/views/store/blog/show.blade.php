@extends('layouts.store')

@section('title', $post->title)

@section('content')
    <h1 class="page-title">{{ $post->title }}</h1>
    @if($post->published_at)
        <p class="page-subtitle" style="margin-bottom:1rem;">
            Đăng ngày {{ $post->published_at->format('d/m/Y') }}
        </p>
    @endif

    <article style="font-size:.95rem;line-height:1.6;color:#333;white-space:pre-line;margin-bottom:1.5rem;">
        {{ $post->content }}
    </article>

    <a href="{{ route('blog.index') }}" class="btn btn-outline" style="border-style:solid;">
        Quay lại danh sách bài viết
    </a>
@endsection

