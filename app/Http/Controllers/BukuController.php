<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Buku;
use Illuminate\Pagination\Paginator;

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
        $this->validate($request,[
            'judul' => 'required|string',
            'penulis' => 'required|string|max:30',
            'harga' => 'required|numeric',
            'tgl_terbit' => 'required|date'
        ]);
        
        $buku = new Buku();
        $buku->judul = $request->judul;
        $buku->penulis = $request->penulis;
        $buku->harga = $request->harga;
        $buku->tgl_terbit = $request->tgl_terbit;
        $buku->save();

        return redirect('/buku')->with('pesanstore', 'Data Buku Berhasil di Simpan');
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
        $buku->judul = $request->input('judul');
        $buku->penulis = $request->input('penulis');
        $buku->harga = $request->input('harga');
        $buku->tgl_terbit = $request->input('tgl_terbit');
        $buku->save();

        return redirect('/buku')->with('pesanupdate', 'Buku berhasil diupdate!');
    }
}
