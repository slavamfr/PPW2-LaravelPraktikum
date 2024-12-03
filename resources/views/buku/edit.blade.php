<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Edit Buku - Belajar Model PPW2</title>
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
                <div id="gallery-inputs-container">
                    <div class="input-group mb-2">
                        <input type="file" class="form-control" name="gallery[]" accept="image/*" onchange="previewImage(this)">
                        <button type="button" class="btn btn-danger remove-input" onclick="removeInput(this)">Remove</button>
                    </div>
                </div>
                <button type="button" class="btn btn-primary btn-sm mt-2" onclick="addInput()">Add More Images</button>
            </div>

            <!-- Menampilkan galeri item yang sudah ada -->
            <div class="row gallery_items mt-5">
                @foreach($buku->galleries as $gallery)
                    <div class="col-md-3 mb-3">
                        <div class="position-relative">
                            <img 
                                class="img-thumbnail"
                                src="{{ asset('storage/uploads/' . $gallery->foto) }}"
                                alt="{{ $gallery->nama_galeri }}"
                                style="max-width: 150px; object-fit: cover; width: 100%;"
                            />
                            <form action="{{ route('gallery.destroy', $gallery->id) }}" method="POST" class="mt-2">
                                @csrf
                                @method('DELETE')
                                <button type="button" class="btn btn-danger btn-sm" onclick="confirmDelete(this.form)">
                                    Delete
                                </button>
                            </form>
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
        function addInput() {
            const container = document.getElementById('gallery-inputs-container');
            const newInput = document.createElement('div');
            newInput.className = 'input-group mb-2';
            newInput.innerHTML = `
                <input type="file" class="form-control" name="gallery[]" accept="image/*" onchange="previewImage(this)">
                <button type="button" class="btn btn-danger remove-input" onclick="removeInput(this)">Remove</button>
            `;
            container.appendChild(newInput);
        }

        function removeInput(button) {
            const inputGroup = button.parentElement;
            inputGroup.remove();
        }

        function previewImage(input) {
            const previewsContainer = document.getElementById('image-previews');
            
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                const previewDiv = document.createElement('div');
                previewDiv.className = 'col-md-3 mb-3';
                
                reader.onload = function(e) {
                    previewDiv.innerHTML = `
                        <img src="${e.target.result}" class="img-thumbnail" style="height: 200px; object-fit: cover;">
                    `;
                }
                
                reader.readAsDataURL(input.files[0]);
                previewsContainer.appendChild(previewDiv);
            }
        }

        function confirmDelete(form) {
            if (confirm('Are you sure you want to delete this image?')) {
                form.submit();
            }
        }
    </script>
</body>
</html>
