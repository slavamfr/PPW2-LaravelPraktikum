<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Edit Buku - Belajar Model PPW2</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body>
    @extends('layout')

    @section('content')

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif
    
    <div class="container mt-5">
        <h4 class="mb-4">Edit Buku</h4>

        <form method="post" action="{{ route('buku.update', $buku->id) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')

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
                <label for="discount" class="form-label">Discount</label>
                <input type="text" class="form-control" id="discount" name="discount" value="{{ $buku->discount }}">
            </div>

            <div class="mb-3">
                <label for="tgl_terbit" class="form-label">Tanggal Terbit</label>
                <input type="date" class="form-control" id="tgl_terbit" name="tgl_terbit" value="{{ $buku->tgl_terbit }}">
            </div>

            <div class="form-check mb-3">
                <input type="checkbox" class="form-check-input" id="editorial_pick" 
                       name="editorial_pick" value="1" 
                       {{ $buku->editorial_pick ? 'checked' : '' }}>
                <label class="form-check-label" for="editorial_pick">
                    Editorial Pick
                </label>
            </div>

            <div class="mb-3">
                <label for="thumbnail" class="form-label">Thumbnail</label>
                <input type="file" class="form-control" name="thumbnail" id="thumbnail">
            </div>

            <div class="mb-3">
                <label class="form-label">Gallery Images</label>
                <div id="gallery-uploads">
                    <div class="row mb-3 gallery-input-group">
                        <div class="col-md-4">
                            <input type="file" class="form-control" name="gallery_images[]" accept="image/*">
                        </div>
                        <div class="col-md-6">
                            <input type="text" class="form-control" name="gallery_descriptions[]" placeholder="Image description">
                        </div>
                        <div class="col-md-2">
                            <button type="button" class="btn btn-success add-gallery-btn">+</button>
                            <button type="button" class="btn btn-danger remove-gallery-btn" style="display:none;">-</button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Existing Gallery Display -->
            <div class="row gallery_items mt-5">
                <h4>Existing Gallery Images</h4>
                @foreach($buku->galleries as $gallery)
                    <div class="col-md-3 mb-3 gallery-item" id="gallery-{{$gallery->id}}">
                        <div class="card">
                            <img src="{{ asset($gallery->foto) }}" class="card-img-top" alt="Gallery Image">
                            <div class="card-body">
                                <p class="card-text">{{ $gallery->keterangan }}</p>
                                <button type="button" 
                                        class="btn btn-danger btn-sm delete-gallery" 
                                        data-gallery-id="{{ $gallery->id }}">
                                    Delete
                                </button>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="d-flex justify-content-between mt-3">
                <button type="submit" class="btn btn-success">Simpan</button>
                <a href="{{ url('/buku') }}" class="btn btn-secondary">Kembali</a>
            </div>
        </form>
    </div>
    @endsection

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-9ndCyUa0Jn3WL8pnhD9WmH8eKe6i5k90tqPjsqfCI5Ff5f0vuHV8/qb5mQd/gtds" crossorigin="anonymous"></script>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelector('.add-gallery-btn').addEventListener('click', function() {
            const template = document.querySelector('.gallery-input-group').cloneNode(true);
            template.querySelector('input[type="file"]').value = '';
            template.querySelector('input[type="text"]').value = '';
            template.querySelector('.add-gallery-btn').style.display = 'none';
            template.querySelector('.remove-gallery-btn').style.display = 'inline-block';
            
            document.getElementById('gallery-uploads').appendChild(template);
        });

        document.addEventListener('click', function(e) {
            if (e.target.classList.contains('remove-gallery-btn')) {
                e.target.closest('.gallery-input-group').remove();
            }
        });

        document.querySelectorAll('.delete-gallery').forEach(button => {
            button.addEventListener('click', function() {
                if(confirm('Are you sure you want to delete this image?')) {
                    const galleryId = this.dataset.galleryId;
                    fetch(`/gallery/delete/${galleryId}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if(data.success) {
                            document.getElementById(`gallery-${galleryId}`).remove();
                        }
                    });
                }
            });
        });
    });
    </script>
</body>
</html>
