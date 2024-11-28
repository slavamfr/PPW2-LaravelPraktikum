<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" 
            content="width=device-width, user-scalable=no initial-scale=1.0,
            maximum-scale=1.0, minimum-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>Belajar Model PPW2</title>
    </head>
    <body>
    @extends('layout')

    @section('content')
    <div class="container">
        <h2>Create Review</h2>
        
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('reviews.store') }}">
            @csrf
            <div class="mb-3">
                <label class="form-label">Select Book</label>
                <select name="buku_id" class="form-control" required>
                    <option value="">Choose a book...</option>
                    @foreach($books as $book)
                        <option value="{{ $book->id }}">{{ $book->judul }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">Review Content</label>
                <textarea name="content" class="form-control" rows="5" required>{{ old('content') }}</textarea>
            </div>

            <div class="mb-3">
                <label class="form-label">Tags</label>
                <input type="text" name="tags" class="form-control" 
                    value="{{ old('tags') }}"
                    placeholder="Enter tags separated by comma" required>
                <small class="text-muted">Example: Fantasy, Adventure, Classic</small>
            </div>

            <button type="submit" class="btn btn-primary">Submit Review</button>
        </form>
    </div>
    @endsection
    </body>