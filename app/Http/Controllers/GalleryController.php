<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Buku;
use App\Models\Gallery;
use Illuminate\Pagination\Paginator;
use Intervention\Image\Facades\Image;


class GalleryController extends Controller
{
    public function destroy($id)
    {
        $gallery = Gallery::findOrFail($id);

        // Hapus file gambar dari penyimpanan jika ada
        if (file_exists(public_path($gallery->path))) {
            unlink(public_path($gallery->path));
        }

        // Hapus rekaman galeri dari database
        $gallery->delete();

        return redirect()->back()->with('success', 'Gambar berhasil dihapus.');
    }


}
