<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Origin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class OriginController extends Controller
{
    /**
     * Admin index – Inertia page with origins list.
     */
    public function index(Request $request)
    {
        $origins = Origin::orderBy('id', 'desc')
            ->get()
            ->map(function ($o) {
                return [
                    'id'            => $o->id,
                    'name'          => $o->name,
                    'province'      => $o->province,
                    'location_text' => $o->location_text,
                    'description'   => $o->description,
                    'main_image_path' => $o->main_image_path ?: '/images/placeholder-image.jpg',
                    'map_image_path'  => $o->map_image_path ?: null,
                    'is_active'       => (bool) $o->is_active,
                    'created_at'      => $o->created_at,
                    'updated_at'      => $o->updated_at,
                ];
            });

        return Inertia::render('Admin/Origins/Index', [
            'origins' => $origins,
        ]);
    }

    /**
     * Store a new origin.
     */
    public function store(Request $request)
    {
        // IMPORTANT: remove boolean rule that fails on "on"
        $data = $request->validate([
            'name'          => 'required|string|max:255',
            'province'      => 'nullable|string|max:255',
            'location_text' => 'nullable|string|max:255',
            'description'   => 'nullable|string',
            'main_image'    => 'nullable|image|max:5120',
            'map_image'     => 'nullable|image|max:5120',
        ]);

        // Normalize boolean AFTER validation
        $data['is_active'] = $request->has('is_active') ? 1 : 0;

        if ($request->hasFile('main_image')) {
            $path = $request->file('main_image')->store('origins', 'public');
            $data['main_image_path'] = Storage::url($path);
        }

        if ($request->hasFile('map_image')) {
            $path = $request->file('map_image')->store('origins', 'public');
            $data['map_image_path'] = Storage::url($path);
        }

        Origin::create($data);

        return redirect()
            ->route('admin.origins.index')
            ->with('success', 'Origin created successfully.');
    }

    /**
     * Update an existing origin.
     */
    public function update(Request $request, Origin $origin)
    {
        $data = $request->validate([
            'name'          => 'required|string|max:255',
            'province'      => 'nullable|string|max:255',
            'location_text' => 'nullable|string|max:255',
            'description'   => 'nullable|string',
            'main_image'    => 'nullable|image|max:5120',
            'map_image'     => 'nullable|image|max:5120',
        ]);

        $data['is_active'] = $request->has('is_active') ? 1 : 0;

        // Replace main image
        if ($request->hasFile('main_image')) {
            if ($origin->main_image_path) {
                $this->deletePublicStorageUrl($origin->main_image_path);
            }
            $path = $request->file('main_image')->store('origins', 'public');
            $data['main_image_path'] = Storage::url($path);
        }

        // Replace map image
        if ($request->hasFile('map_image')) {
            if ($origin->map_image_path) {
                $this->deletePublicStorageUrl($origin->map_image_path);
            }
            $path = $request->file('map_image')->store('origins', 'public');
            $data['map_image_path'] = Storage::url($path);
        }

        $origin->update($data);

        return redirect()
            ->route('admin.origins.index')
            ->with('success', 'Origin updated successfully.');
    }

    /**
     * Delete an origin.
     */
    public function destroy(Origin $origin)
    {
        if ($origin->main_image_path) {
            $this->deletePublicStorageUrl($origin->main_image_path);
        }
        if ($origin->map_image_path) {
            $this->deletePublicStorageUrl($origin->map_image_path);
        }

        $origin->delete();

        return redirect()
            ->route('admin.origins.index')
            ->with('success', 'Origin deleted successfully.');
    }

    /**
     * Toggle active status for an origin.
     */
    public function toggleActive(Origin $origin)
    {
        $origin->is_active = ! $origin->is_active;
        $origin->save();

        return redirect()
            ->route('admin.origins.index')
            ->with('success', 'Origin status updated.');
    }

    /**
     * Helper: delete a public storage URL created via Storage::url($path).
     */
    protected function deletePublicStorageUrl(?string $publicUrl): void
    {
        if (! $publicUrl) {
            return;
        }

        $path = parse_url($publicUrl, PHP_URL_PATH);
        $relative = preg_replace('#^/storage/#', '', $path);

        if ($relative) {
            Storage::disk('public')->delete($relative);
        }
    }
}