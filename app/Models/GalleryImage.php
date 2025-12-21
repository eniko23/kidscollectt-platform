<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GalleryImage extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'filename'];

    /**
     * Görselin URL'ini döndür
     */
    public function getUrlAttribute(): string
    {
        return asset('uploads/' . $this->filename);
    }

    /**
     * Görselin tam path'ini döndür
     */
    public function getPathAttribute(): string
    {
        return public_path('uploads/' . $this->filename);
    }
}
