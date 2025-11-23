<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HeroSlide;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Http\RedirectResponse;

class HeroSlideController extends Controller
{
    public function index(): Response
    {
        $slides = HeroSlide::with('images')
            ->orderBy('sort_order')
            ->orderBy('id')
            ->paginate(10);

        return Inertia::render('Admin/HeroSlides/Index', [
            'slides' => $slides,
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('Admin/HeroSlides/Create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title'       => ['required', 'string', 'max:255'],
            'subtitle'    => ['nullable', 'string'],
            'button_text' => ['nullable', 'string', 'max:255'],
            'button_link' => ['nullable', 'string', 'max:255'],
            'sort_order'  => ['nullable', 'integer'],
            'is_active'   => ['nullable', 'boolean'],
            'images'      => ['required', 'array', 'min:1'],
            'images.*'    => ['image', 'max:2048'],
        ]);

        $validated['is_active']  = $request->boolean('is_active');
        $validated['sort_order'] = $validated['sort_order'] ?? 0;

        $slide = HeroSlide::create($validated);

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $index => $file) {
                $path = $file->store('hero_slides', 'public');

                $slide->images()->create([
                    'image_path' => $path,
                    'sort_order' => $index,
                ]);
            }
        }

        return redirect()
            ->route('admin.hero-slides.index')
            ->with('success', 'Hero slide created successfully.');
    }

    public function edit(HeroSlide $heroSlide): Response
    {
        $heroSlide->load('images');

        return Inertia::render('Admin/HeroSlides/Edit', [
            'slide' => $heroSlide,
        ]);
    }

    public function update(Request $request, HeroSlide $heroSlide): RedirectResponse
    {
        $validated = $request->validate([
            'title'       => ['required', 'string', 'max:255'],
            'subtitle'    => ['nullable', 'string'],
            'button_text' => ['nullable', 'string', 'max:255'],
            'button_link' => ['nullable', 'string', 'max:255'],
            'sort_order'  => ['nullable', 'integer'],
            'is_active'   => ['nullable', 'boolean'],
            'images'      => ['sometimes', 'array'], // Changed to 'sometimes'
            'images.*'    => ['image', 'max:2048'],
        ]);

        $validated['is_active']  = $request->boolean('is_active');
        $validated['sort_order'] = $validated['sort_order'] ?? 0;

        $heroSlide->update($validated);

        // Replace images only if new ones are uploaded
        if ($request->hasFile('images') && count($request->file('images')) > 0) {
            // Delete old images
            foreach ($heroSlide->images as $img) {
                if ($img->image_path) {
                    Storage::disk('public')->delete($img->image_path);
                }
                $img->delete();
            }

            // Upload new images
            foreach ($request->file('images') as $index => $file) {
                $path = $file->store('hero_slides', 'public');

                $heroSlide->images()->create([
                    'image_path' => $path,
                    'sort_order' => $index,
                ]);
            }
        }

        return redirect()
            ->route('admin.hero-slides.index')
            ->with('success', 'Hero slide updated successfully.');
    }

    public function destroy(HeroSlide $heroSlide): RedirectResponse
    {
        foreach ($heroSlide->images as $img) {
            if ($img->image_path) {
                Storage::disk('public')->delete($img->image_path);
            }
        }

        $heroSlide->delete();

        return redirect()
            ->route('admin.hero-slides.index')
            ->with('success', 'Hero slide deleted successfully.');
    }
}