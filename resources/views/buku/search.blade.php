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
        @extends('layout') <!-- Menggunakan layout utama -->

        @section('title', 'Daftar Buku') <!-- Menambahkan judul halaman -->

        @section('content')
            @if(Session::has('pesanstore')) 
                <div class="alert alert-success">{{ Session::get('pesanstore') }}</div>
            @endif

            @if(Session::has('pesanupdate'))
                <div class="alert alert-primary">{{ Session::get('pesanupdate') }}</div>
            @endif

            @if(Session::has('pesandelete'))
                <div class="alert alert-warning">{{ Session::get('pesandelete') }}</div>
            @endif

            <h1>Daftar Buku</h1>

            @if(count($data_buku)) 
                <div class="alert alert-success">Ditemukan 
                    <strong>{{count($data_buku)}}</strong> 
                    data dengan kata: <strong>{{ $cari }}</strong>
                </div>
            @endif

            <div class="d-flex justify-content-between mb-3">    
            @if(Auth::check() && Auth::user()->level == 'admin')
                <!-- Form for "Tambah Buku" button -->
                <form action="{{ route('buku.create') }}" method="GET">
                    <button type="submit" class="btn btn-primary">Tambah Buku</button>
                </form>
            @endif

                <!-- Form for search input -->
                <form action="{{route('buku.search')}}" method="get" class="d-flex">
                    @csrf
                    <input type="text" name="kata" class="form-control" placeholder="Cari..." style="width: 300px; margin-left: 10px;">
                    <button type="submit" class="btn btn-secondary ms-2">Cari</button>
                </form>
            </div>



            <table class="table table-stripped">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>ID</th>
                        <th>Judul Buku</th>
                        <th>Penulis</th>
                        <th>Harga</th>
                        <th>Tanggal Terbit</th>
                        @if(Auth::check() && Auth::user()->level == 'admin')
                        <th>Aksi</th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                    @foreach ($data_buku as $index => $buku)
                    <tr>
                        <td>{{ $index+1 }}</td>
                        <td>{{ $buku->id }}</td>
                        <td>{{ $buku->judul }}</td>
                        <td>{{ $buku->penulis }}</td>
                        <td>{{ "Rp. ".number_format($buku->harga, 0, ',','.') }}</td>
                        <td>{{ (new DateTime($buku->tgl_terbit))->format('d/m/Y') }}</td>
                        <td>
                            @if(Auth::check() && Auth::user()->level == 'admin')
                                <div class="d-grid gap-2">
                                    <form action="{{ route('buku.destroy', $buku->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button onclick="return confirm('Yakin mau dihapus?')" type="submit" class="btn btn-danger w-100">Hapus</button>
                                    </form>
                                    <form action="{{ route('buku.edit', $buku->id) }}" method="GET">
                                        <button type="submit" class="btn btn-primary w-100">Edit</button>
                                    </form>
                                </div>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

            <div>{{ $data_buku->links() }}</div>
            <div><strong>Jumlah Buku: {{ $jumlah_buku }}</strong></div>

            <p>Total buku: {{ $total_buku }}</p>
        @endsection
    </body>
</html>
