<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\BookResource;
use Illuminate\Http\Request;
use App\Models\Buku;
use Illuminate\Support\Facades\Validator;

class BookApiController extends Controller
{
    public function index() {
        $books = Buku::latest()->paginate(5);
        
        return new BookResource(true, 'List Data Buku', $books);
    }

    public function store(Request $request) {
        
        $validator = Validator::make($request->all(), [
            'judul' => 'required|string|max:255',
            'penulis' => 'required|string|max:255',
            'harga' => 'required|numeric',
            'tgl_terbit' => 'required|date',
            'filepath' => 'required|string',
        ]);

        if ($validator->fails()) {
            return new BookResource(false, 'Validasi gagal', $validator->errors());
        }

        $buku = Buku::create([
            'judul' => $request->judul,
            'penulis' => $request->penulis,
            'harga' => $request->harga,
            'tgl_terbit' => $request->tgl_terbit,
            'filepath' => $request->filepath,
        ]);

        return new BookResource(true, 'Buku berhasil ditambahkan', $buku);
    }
}
