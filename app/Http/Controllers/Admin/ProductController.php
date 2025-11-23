<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::orderBy('is_main', 'desc')
            ->orderBy('created_at', 'desc')
            ->get();

        return inertia('Admin/Products/Index', [
            'products' => $products->map(function ($product) {
                return [
                    'id' => $product->id,
                    'name' => $product->name,
                    'slug' => $product->slug,
                    'short_description' => $product->short_description,
                    'size_ml' => $product->size_ml,
                    'price' => $product->price,
                    'is_main' => $product->is_main,
                    'is_active' => $product->is_active,
                    // IMPORTANT: this is a full URL, or null
                    'image_path' => $product->image_url,
                    'description' => $product->description,
                    'ingredients_text' => $product->ingredients_text,
                    'created_at' => $product->created_at->format('M j, Y'),
                ];
            }),
        ]);
    }

    public function create()
    {
        return inertia('Admin/Products/Create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'short_description' => 'nullable|string|max:500',
            'description' => 'nullable|string',
            'size_ml' => 'required|integer|min:1',
            'price' => 'required|numeric|min:0',
            'ingredients_text' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_active' => 'boolean',
        ]);

        $data = $request->only([
            'name', 'short_description', 'description',
            'size_ml', 'price', 'ingredients_text', 'is_active',
        ]);

        // slug
        $data['slug'] = Str::slug($request->name);
        $counter = 1;
        $originalSlug = $data['slug'];
        while (Product::where('slug', $data['slug'])->exists()) {
            $data['slug'] = $originalSlug.'-'.$counter;
            $counter++;
        }

        // image upload
        if ($request->hasFile('image')) {
            $data['image_path'] = $request->file('image')->store('products', 'public');
        }

        // first product as main
        if (Product::count() === 0) {
            $data['is_main'] = true;
        }

        Product::create($data);

        return redirect()->route('admin.products.index')
            ->with('success', 'Product created successfully!');
    }

    public function show(Product $product)
    {
        return redirect()->route('admin.products.index');
    }

    public function edit(Product $product)
    {
        return inertia('Admin/Products/Edit', [
            'product' => [
                'id' => $product->id,
                'name' => $product->name,
                'slug' => $product->slug,
                'short_description' => $product->short_description,
                'description' => $product->description,
                'size_ml' => $product->size_ml,
                'price' => $product->price,
                'ingredients_text' => $product->ingredients_text,
                'is_main' => $product->is_main,
                'is_active' => $product->is_active,
                // full URL for preview
                'image_path' => $product->image_url,
            ],
        ]);
    }

    public function update(Request $request, Product $product)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'short_description' => 'nullable|string|max:500',
            'description' => 'nullable|string',
            'size_ml' => 'required|integer|min:1',
            'price' => 'required|numeric|min:0',
            'ingredients_text' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_active' => 'boolean',
        ]);

        $data = $request->only([
            'name', 'short_description', 'description',
            'size_ml', 'price', 'ingredients_text', 'is_active',
        ]);

        // update slug if name changed
        if ($product->name !== $request->name) {
            $data['slug'] = Str::slug($request->name);

            $counter = 1;
            $originalSlug = $data['slug'];
            while (Product::where('slug', $data['slug'])
                ->where('id', '!=', $product->id)
                ->exists()) {
                $data['slug'] = $originalSlug.'-'.$counter;
                $counter++;
            }
        }

        // image upload
        if ($request->hasFile('image')) {
            if ($product->image_path && Storage::disk('public')->exists($product->image_path)) {
                Storage::disk('public')->delete($product->image_path);
            }

            $data['image_path'] = $request->file('image')->store('products', 'public');
        }

        $product->update($data);

        return redirect()->route('admin.products.index')
            ->with('success', 'Product updated successfully!');
    }

    public function destroy(Product $product)
    {
        // Allow deleting any product for now
        if ($product->image_path && Storage::disk('public')->exists($product->image_path)) {
            Storage::disk('public')->delete($product->image_path);
        }

        $product->delete();

        return redirect()->route('admin.products.index')
            ->with('success', 'Product deleted successfully!');
    }

    public function setAsMain(Product $product)
    {
        if (! $product->is_active) {
            return back()->with('error', 'Cannot set inactive product as main.');
        }

        Product::where('is_main', true)->update(['is_main' => false]);

        $product->update(['is_main' => true]);

        return back()->with('success', 'Product set as main successfully!');
    }

    public function toggleStatus(Product $product)
    {
        if ($product->is_main && $product->is_active) {
            return back()->with('error', 'Cannot deactivate the main product. Set another product as main first.');
        }

        $product->update(['is_active' => ! $product->is_active]);

        $status = $product->is_active ? 'activated' : 'deactivated';

        return back()->with('success', "Product {$status} successfully!");
    }
}