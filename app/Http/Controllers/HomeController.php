<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Ana sayfa (vitrin) gösterir
     */
    public function index()
    {
        // En yeni 8 ürünü çek
        $latestProducts = Product::where('is_active', true)
            ->latest()
            ->take(8)
            ->get();

        return view('welcome', [
            'latestProducts' => $latestProducts,
        ]);
    }
}
