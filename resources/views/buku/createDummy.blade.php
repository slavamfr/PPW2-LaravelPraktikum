<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, 
        initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Belajar Model PPW2</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>
<body>
    @extends('layout')
    @section('content')
    <div class="container">
        <h4>Tambah Buku</h4>
        @if (count($errors) > 0)
            <ul class="alert alert-danger">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        @endif

        <form method="post" action="{{ route('buku.store') }}">
            @csrf
            <div>Judul <input type="text" name="judul"></div>
            <div>Penulis <input type="text" name="penulis"></div>
            <div>Harga <input type="text" name="harga"></div>
            <div>Tanggal Terbit <input type="date" name="tgl_terbit"></div>
            <button type="submit">Simpan</button>
            <a href="{{ url('/buku') }}">Kembali</a>
        </form>
    </div>
    @endsection
</body>
</html>