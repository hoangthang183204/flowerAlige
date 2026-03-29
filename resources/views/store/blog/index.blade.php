@extends('layouts.store')

@section('title', 'Blog về hoa')

@section('content')
    <h1 class="page-title">Blog về hoa</h1>
    <p class="page-subtitle">Những bài viết về ý nghĩa hoa, cách chăm sóc và gợi ý quà tặng.</p>

    <form action="{{ route('blog.index') }}" method="GET" style="margin-bottom:1rem;max-width:360px;">
        <div style="display:flex;gap:.5rem;">
            <input
                type="text"
                name="q"
                value="{{ request('q') }}"
                placeholder="Tìm bài viết..."
                style="flex:1;padding:.45rem .75rem;border-radius:.35rem;border:1px solid #e3e3e0;font-size:.9rem;"
            >
            <button type="submit" class="btn btn-primary">
                Tìm
            </button>
        </div>
    </form>

    @if($posts->isEmpty())
        <p style="font-size:.9rem;color:#706f6c;">Chưa có bài viết nào.</p>
    @else
        <div style="display:flex;flex-direction:column;gap:.9rem;">
            @foreach($posts as $post)
                <a href="{{ route('blog.show', $post->slug) }}" style="border-radius:.8rem;border:1px solid #e3e3e0;padding:.75rem 1rem;background:#fff;display:block;">
                    <div style="font-size:1rem;font-weight:600;margin-bottom:.25rem;">
                        {{ $post->title }}
                    </div>
                    @if($post->published_at)
                        <div style="font-size:.8rem;color:#999;margin-bottom:.25rem;">
                            {{ $post->published_at->format('d/m/Y') }}
                        </div>
                    @endif
                    @if($post->excerpt)
                        <div style="font-size:.9rem;color:#555;">
                            {{ $post->excerpt }}
                        </div>
                    @endif
                </a>
            @endforeach
        </div>

        <div style="margin-top:1rem;font-size:.9rem;">
            {{ $posts->links() }}
        </div>
    @endif
@endsection

