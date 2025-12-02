<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Tüm ürünleri listeler (örn: /urunler sayfası için).
     */
    public function index(Request $request)
    {
        $query = Product::where('is_active', true);

        if ($request->filled('categories')) {
            $categoryIds = is_array($request->input('categories'))
                ? $request->input('categories')
                : explode(',', $request->input('categories'));

            $query->whereHas('categories', function ($q) use ($categoryIds) {
                $q->whereIn('categories.id', $categoryIds);
            });
        }

        $products = $query->latest() // En yeniden eskiye
            ->paginate(12); // Sayfada 12 ürün

        // 'products.index' adında bir view (tasarım) dosyası arayacak
        return view('products.index', [
            'products' => $products,
        ]);
    }

    /**
     * Tek bir ürünün detay sayfasını gösterir (örn: /urun/ataturk-tisort)
     */
    public function show(Product $product)
    {
        // Ürünün aktif olduğundan emin ol (değilse 404 hatası ver)
        if (! $product->is_active) {
            abort(404);
        }

        // 'products.show' adında bir view (tasarım) dosyası arayacak
        return view('products.show', [
            'product' => $product,
        ]);
    }

    /**
     * Belirli bir kategorideki ürünleri listeler (örn: /kategori/erkek-cocuk)
     */
    public function showCategory(Category $category)
    {
        // 1. O kategoriye ait aktif ürünleri çek
        $products = $category->products() // <-- AZ ÖNCE EKLEDİĞİMİZ İLİŞKİ
            ->where('is_active', true)
            ->latest()
            ->paginate(12);

        // 2. Müşteriye 'products.category' adındaki view'ı (tasarımı) göster
        // (resources/views/products/category.blade.php)
        return view('products.category', [ 
            'category' => $category,
            'products' => $products,
        ]);
    }
}