<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Buku extends Model
{
    use HasFactory;

    protected $table = 'books';
    protected $fillable = ['id', 'judul', 'penulis', 'harga', 'tgl_terbit', 'created_at', 'updated_at', 'filename', 'filepath', 'discount', 'editorial_pick'];
    protected $dates = ['tgl_terbit'];

    public function galleries(): HasMany
    {
        return $this->hasMany(Gallery::class);
    }

    public function getDiscountedPrice()
    {
        if ($this->discount > 0) {
            return $this->harga * (1 - ($this->discount / 100));
        }
        return $this->harga;
    }

    public static function getEditorialPicks()
    {
        return self::where('editorial_pick', 1)
                   ->latest()
                   ->take(5)
                   ->get();
    }
}
