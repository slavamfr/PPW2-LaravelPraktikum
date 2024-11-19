<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\BookResource;
use Illuminate\Http\Request;
use App\Models\Buku;

class BookApiController extends Controller
{
    public function index() {
        $books = Buku::latest()->paginate(5);
        
        return new BookResource(true, 'List Data Buku', $books);
    }
}
