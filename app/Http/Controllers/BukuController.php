<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Buku;
use App\Models\Gallery;
use Illuminate\Pagination\Paginator;
use Intervention\Image\Facades\Image;

class BukuController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        Paginator::useBootstrapFive();
        $data_buku = Buku::all();
        $total_harga = $data_buku->sum('harga'); // Menghitung jumlah total harga buku

        $batas = 5;
        $jumlah_buku = Buku::count();
        $data_buku = Buku::orderBy('id', 'desc')->paginate($batas);
        $no = $batas * ($data_buku->currentPage() - 1);
        return view('buku.index', compact('data_buku', 'no','jumlah_buku', 'total_harga'));
    }

    /** Search */
    public function search(Request $request) {
        Paginator::useBootstrapFive();
        $data_buku = Buku::all();
        $total_buku = $data_buku->count();

        $batas = 5; // Batas data per halaman
        $cari = $request->kata; // Kata kunci pencarian
        $data_buku = Buku::where('judul', 'like', "%".$cari."%")
            ->orWhere('penulis', 'like', "%".$cari."%")
            ->paginate($batas); // Ambil data buku dengan paginasi
        $jumlah_buku = $data_buku->count(); // Hitung jumlah total buku
        $no = $batas * ($data_buku->currentPage() - 1); // Nomor urut
    
        return view('buku.search', compact('jumlah_buku', 'data_buku', 'total_buku', 'no', 'cari'));
    }
    

    public function create()
    {
        return view('buku.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'judul' => 'required|string',
            'penulis' => 'required|string|max:30',
            'harga' => 'required|numeric',
            'tgl_terbit' => 'required|date',
            'thumbnail' => 'image|mimes:jpeg,jpg,png|max:2048'
        ]);

        $filename = null;
        $filepath = null;

        if ($request->hasFile('thumbnail')) {
            $filename = time().'-' . $request->thumbnail->getClientOriginalName();
            $filepath = $request->file('thumbnail')->storeAs('uploads', $filename, 'public');
        }

        Image::make(storage_path('app/public/uploads/' . $filename))
            ->fit(240, 320)
            ->save();

        Buku::create([
            'judul' => $request->judul,
            'penulis' => $request->penulis,
            'harga' => $request->harga,
            'tgl_terbit' => $request->tgl_terbit,
            'filename' => $filename,
            'filepath' => $filepath ? '/storage/' . $filepath : null,
        ]);

        return redirect('/buku')->with('pesanstore', 'Buku berhasil ditambahkan!');
    }


    public function destroy(string $id)
    {
        $buku = Buku::find($id);
        $buku->delete();

        return redirect('/buku')->with('pesandelete', 'Data Buku Berhasil di Hapus!');
        /*return redirect('/buku'); */ 
    }

    public function edit(string $id)
    {
        $buku = Buku::find($id);

        return view('buku.edit', compact('buku'));
    }  

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }


    public function update(Request $request, string $id)
    {
        $buku = Buku::find($id);

        $request->validate([
            'judul' => 'required|string',
            'penulis' => 'required|string|max:30',
            'harga' => 'required|numeric',
            'tgl_terbit' => 'required|date',
            'thumbnail' => 'nullable|image|mimes:jpeg,jpg,png|max:2048',
            'gallery.*' => 'image|mimes:jpeg,jpg,png|max:2048',
            'discount' => 'nullable|numeric',
        ]);

        // Initialize update data
        $updateData = [
            'judul' => $request->judul,
            'penulis' => $request->penulis,
            'harga' => $request->harga,
            'tgl_terbit' => $request->tgl_terbit,
            'discount' => $request->discount,
            'editorial_pick' => $request->has('editorial_pick') ? 1 : 0,
        ];

        // Process thumbnail only if new file is uploaded
        if ($request->hasFile('thumbnail')) {
            $filename = time() . '-' . $request->thumbnail->getClientOriginalName();
            $filepath = $request->file('thumbnail')->storeAs('uploads', $filename, 'public');

            Image::make(storage_path('app/public/uploads/' . $filename))
                ->fit(240, 320)
                ->save();

            $updateData['filename'] = $filename;
            $updateData['filepath'] = '/storage/' . $filepath;
        }

        // Process gallery images if any
        if ($request->hasFile('gallery')) {
            foreach ($request->file('gallery') as $file) {
                $fileName = time() . '-' . $file->getClientOriginalName();
                $filePath = $file->storeAs('uploads', $fileName, 'public');

                Gallery::create([
                    'nama_galeri' => $fileName,
                    'foto' => $fileName,
                    'filepath' => '/storage/' . $filePath,
                    'buku_id' => $id
                ]);
            }
        }

        $buku->update($updateData);

        return redirect('/buku')->with('pesanupdate', 'Buku berhasil diupdate!');
    }

    public function destroyGallery(string $id)
    {
        $gallery = Gallery::find($id);
        
        if ($gallery) {
            // Delete file from storage
            if (file_exists(public_path('storage/uploads/' . $gallery->foto))) {
                unlink(public_path('storage/uploads/' . $gallery->foto));
            }
            
            // Delete database record
            $gallery->delete();
            
            return redirect()->back()->with('success', 'Image deleted successfully');
        }
        
        return redirect()->back()->with('error', 'Image not found');
    }

}
