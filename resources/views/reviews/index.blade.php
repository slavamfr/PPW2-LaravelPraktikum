@extends('layout')

@section('content')
<div class="container">
    <h2>Book Reviews</h2>
    
    @if(Auth::user() && (Auth::user()->level == 'admin' || Auth::user()->level == 'internal_reviewer'))
        <a href="{{ route('reviews.create') }}" class="btn btn-primary mb-3">Write Review</a>
    @endif

    @foreach($reviews as $review)
        <div class="card mb-3">
            <div class="card-body">
                <h5 class="card-title">{{ $review->book->judul }}</h5>
                <div class="d-flex align-items-center mb-2">
                    @if ($review->book->filepath)
                        <div class="relative h-2 w-2 me-3">
                            <img class="h-2 w-2 rounded-2 object-center" 
                                 src="{{ asset($review->book->filepath) }}" 
                                 alt="Poster Buku" />
                        </div>
                    @endif
                    <h6 class="card-subtitle text-muted mb-0">
                        Reviewed by 
                            <a href="{{ route('reviews.by-reviewer', $review->user->id) }}">
                                {{ $review->user->name }}
                            </a>
                        on {{ $review->created_at->format('d M Y') }}
                    </h6>
                </div>
                <p class="card-text">{{ $review->content }}</p>
                <div class="tags">
                    @foreach($review->tags as $tag)
                        <a href="{{ route('reviews.by-tag', $tag->name) }}" 
                           class="badge bg-secondary">
                            {{ $tag->name }}
                        </a>
                    @endforeach
                </div>
            </div>
        </div>
    @endforeach

    {{ $reviews->links() }}
</div>
@endsection