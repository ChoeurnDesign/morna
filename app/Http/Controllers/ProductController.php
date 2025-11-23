<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Inertia\Inertia;
use Inertia\Response;

class ProductController extends Controller
{
    public function showMain(): Response
    {
        $mainProduct = Product::where('is_active', true)
            ->where('is_main', true)
            ->first();

        $otherProducts = Product::where('is_active', true)
            ->where('is_main', false)
            ->get();

        // Ensure image_path contains full URLs
        if ($mainProduct) {
            $mainProduct->image_path = $mainProduct->image_url;
        }

        $otherProducts->each(function ($product) {
            $product->image_path = $product->image_url;
        });

        return Inertia::render('Product', [
            'mainProduct'   => $mainProduct,  
            'otherProducts' => $otherProducts,
        ]);
    }
}