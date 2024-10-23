<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, 
        initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Belajar Model PPW2</title>
</head>
<body>
    @extends('layout')

    @section('content')
    <div class="container">
        <h4>Edit Buku</h4>
        <form method="post" action="{{ route('buku.update', $buku->id) }}">
            @csrf
            <div>Judul <input type="text" name="judul" value="{{ $buku->judul }}"></div>
            <div>Penulis <input type="text" name="penulis" value="{{ $buku->penulis }}"></div>
            <div>Harga <input type="text" name="harga" value="{{ $buku->harga }}"></div>
            <div>Tanggal Terbit <input type="date" name="tgl_terbit" value="{{ $buku->tgl_terbit }}"></div>
            <button type="submit" class="btn btn-success">Simpan</button>
            <a href="{{ url('/buku') }}" class="btn btn-secondary">Kembali</a>
        </form>
    </div>
    @endsection
</body>
</html>