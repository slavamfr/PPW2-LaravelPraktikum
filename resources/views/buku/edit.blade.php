<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, 
        initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Edit Buku - Belajar Model PPW2</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+4telH+8AMfyDm0ynQ6K8K7AjGpG0" crossorigin="anonymous">
</head>
<body>
    @extends('layout')

    @section('content')
    <div class="container mt-5">
        <h4 class="mb-4">Edit Buku</h4>

        <form method="post" action="{{ route('buku.update', $buku->id) }}">
            @csrf
            <div class="mb-3">
                <label for="judul" class="form-label">Judul</label>
                <input type="text" class="form-control" id="judul" name="judul" value="{{ $buku->judul }}">
            </div>

            <div class="mb-3">
                <label for="penulis" class="form-label">Penulis</label>
                <input type="text" class="form-control" id="penulis" name="penulis" value="{{ $buku->penulis }}">
            </div>

            <div class="mb-3">
                <label for="harga" class="form-label">Harga</label>
                <input type="text" class="form-control" id="harga" name="harga" value="{{ $buku->harga }}">
            </div>

            <div class="mb-3">
                <label for="tgl_terbit" class="form-label">Tanggal Terbit</label>
                <input type="date" class="form-control" id="tgl_terbit" name="tgl_terbit" value="{{ $buku->tgl_terbit }}">
            </div>

            <div class="d-flex justify-content-between">
                <button type="submit" class="btn btn-success">Simpan</button>
                <a href="{{ url('/buku') }}" class="btn btn-secondary">Kembali</a>
            </div>
        </form>
    </div>
    @endsection

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-9ndCyUa0Jn3WL8pnhD9WmH8eKe6i5k90tqPjsqfCI5Ff5f0vuHV8/qb5mQd/gtds" crossorigin="anonymous"></script>
</body>
</html>
