<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" 
            content="width=device-width, user-scalable=no initial-scale=1.0,
            maximum-scale=1.0, minimum-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>FaizBacaBuku</title>
    </head>
    <body>
        @extends('layout')

        @section('content')
        <div class="container">
            <div class="mb-4">
                <h2>Reviews tagged with: {{ $tag->name }}</h2>
                <a href="{{ route('reviews.index') }}" class="btn btn-secondary">Back to All Reviews</a>
            </div>

            @if($reviews->count() > 0)
                @foreach($reviews as $review)
                    <div class="card mb-3">
                        <div class="card-body">
                            <h5 class="card-title">{{ $review->book->judul }}</h5>
                            @if ($review->book->filepath)
                                <div class="relative h-2 w-2 me-3">
                                    <img class="h-2 w-2 rounded-2 object-center" 
                                        src="{{ asset($review->book->filepath) }}" 
                                        alt="Poster Buku" />
                                </div>
                            @endif
                            <br>
                            <h6 class="card-subtitle mb-2 text-muted">
                                Reviewed by 
                                <a href="{{ route('reviews.by-reviewer', $review->user->id) }}">
                                    {{ $review->user->name }}
                                </a> 
                                on {{ $review->created_at->format('d M Y') }}
                            </h6>
                            <p class="card-text">{{ $review->content }}</p>
                            <div class="tags">
                                @foreach($review->tags as $reviewTag)
                                    <a href="{{ route('reviews.by-tag', $reviewTag->name) }}" 
                                    class="badge bg-secondary {{ $reviewTag->id === $tag->id ? 'bg-primary' : '' }}">
                                        {{ $reviewTag->name }}
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endforeach

                {{ $reviews->links() }}
            @else
                <div class="alert alert-info">
                    No reviews found with this tag.
                </div>
            @endif
        </div>
        @endsection
    </body>