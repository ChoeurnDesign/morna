<?php

namespace App\Http\Controllers;

use App\Models\HeroSlide;
use App\Models\Product;
use App\Models\Setting; // Changed from SiteSetting to Setting
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Log;

class HomeController extends Controller
{
    public function index(): Response
    {
        try {
            $heroSlides = HeroSlide::with('images')
                ->where('is_active', true)
                ->orderBy('sort_order')
                ->orderBy('id')
                ->get()
                ->map(function (HeroSlide $slide) {
                    return [
                        'id'          => $slide->id,
                        'title'       => $slide->title,
                        'subtitle'    => $slide->subtitle,
                        'button_text' => $slide->button_text,
                        'button_link' => $slide->button_link,
                        'sort_order'  => $slide->sort_order,
                        'is_active'   => $slide->is_active,
                        'images'      => $slide->images->map(function ($img) {
                            return [
                                'id'         => $img->id,
                                'image_path' => $img->image_path,
                                'url'        => $img->image_path
                                    ? asset('storage/' . $img->image_path)
                                    : null,
                            ];
                        })->values()->all(),
                    ];
                });
        } catch (QueryException $e) {
            Log::error('Hero slides query failed: ' . $e->getMessage());
            $heroSlides = [];
        }

        try {
            $mainProductModel = Product::where('is_active', true)
                ->where('is_main', true)
                ->first();

            $mainProduct = $mainProductModel ? [
                'id'                => $mainProductModel->id,
                'name'              => $mainProductModel->name,
                'short_description' => $mainProductModel->short_description,
                'size_ml'           => $mainProductModel->size_ml,
                'price'             => $mainProductModel->price,
                'image_url'         => $mainProductModel->image_path
                    ? asset('storage/' . $mainProductModel->image_path)
                    : null,
            ] : null;
        } catch (QueryException $e) {
            Log::error('Product query failed: ' . $e->getMessage());
            $mainProduct = null;
        }

        // Get site settings using Setting model
        try {
            $settings = Setting::first();
            $siteData = $settings ? [
                'site_name' => $settings->site_name,
                'logo_url' => $settings->logo_path ? asset('storage/' . $settings->logo_path) : null,
            ] : [
                'site_name' => 'Morna Mulberry',
                'logo_url' => null,
            ];
        } catch (QueryException $e) {
            Log::error('Settings query failed: ' . $e->getMessage());
            $siteData = [
                'site_name' => 'Morna Mulberry',
                'logo_url' => null,
            ];
        }

        return Inertia::render('Home', [
            'heroSlides'  => $heroSlides,
            'mainProduct' => $mainProduct,
            'siteSettings' => $siteData,
        ]);
    }
}