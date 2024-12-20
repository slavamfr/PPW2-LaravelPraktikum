<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Buku;
use App\Models\Gallery;
use Illuminate\Pagination\Paginator;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

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


    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'judul' => 'required',
            'penulis' => 'required',
            'harga' => 'required|numeric',
            'tgl_terbit' => 'required|date',
            'gallery_images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
            'gallery_descriptions.*' => 'nullable|string'
        ]);

        $buku = Buku::findOrFail($id);
        
        // Update basic book info
        $updateData = $request->only(['judul', 'penulis', 'harga', 'tgl_terbit', 'discount']);
        $updateData['editorial_pick'] = $request->has('editorial_pick') ? 1 : 0;
        
        // Handle thumbnail update if provided
        if ($request->hasFile('thumbnail')) {
            $thumbnail = $request->file('thumbnail');
            $filename = time() . '_' . $thumbnail->getClientOriginalName();
            $thumbnail->move(public_path('storage/images'), $filename);
            $updateData['filepath'] = 'storage/images/' . $filename;
        }

        // Handle gallery uploads
        if ($request->hasFile('gallery_images')) {
            foreach($request->file('gallery_images') as $key => $image) {
                if ($image) {
                    $filename = time() . '_' . $image->getClientOriginalName();
                    $image->move(public_path('storage/gallery'), $filename);
                    
                    Gallery::create([
                        'buku_id' => $buku->id,
                        'nama_galeri' => $filename,
                        'foto' => 'storage/gallery/' . $filename,
                        'keterangan' => $request->gallery_descriptions[$key] ?? '',
                    ]);
                }
            }
        }

        $buku->update($updateData);

        return redirect('/buku')->with('pesanupdate', 'Buku berhasil diupdate!');
    }

    public function deleteGallery($id)
    {
        $gallery = Gallery::find($id);
        if($gallery) {
            // Delete file from storage
            if(File::exists(public_path($gallery->foto))) {
                File::delete(public_path($gallery->foto));
            }
            $gallery->delete();
            return response()->json(['success' => true]);
        }
        return response()->json(['success' => false]);
    }
}
