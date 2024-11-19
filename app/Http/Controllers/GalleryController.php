<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Buku;
use App\Models\Gallery;
use Illuminate\Pagination\Paginator;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Storage; // Tambahkan ini

class GalleryController extends Controller
{
    public function destroy($id)
    {
        $gallery = Gallery::findOrFail($id);

        // Hapus file gambar dari penyimpanan jika ada
        if (Storage::exists($gallery->path)) {
            Storage::delete($gallery->path);
        }

        // Hapus record dari database
        $gallery->delete();

        return redirect()->back()->with('success', 'Gambar berhasil dihapus.');
    }
}
