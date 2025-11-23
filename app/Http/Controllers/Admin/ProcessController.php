<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProcessStep;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Storage;

class ProcessController extends Controller
{
    public function index()
    {
        $steps = ProcessStep::orderBy('step_number')
            ->orderBy('id')
            ->get();

        return Inertia::render('Admin/Process/Index', [
            'steps' => $steps,
        ]);
    }

    public function create()
    {
        return Inertia::render('Admin/Process/Create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'step_number' => 'required|integer|min:1',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'icon_name' => 'nullable|string|max:255',
            'image_path' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048', // Changed to accept image files
            'is_active' => 'boolean',
        ]);

        // Handle file upload
        if ($request->hasFile('image_path')) {
            $file = $request->file('image_path');
            $path = $file->store('process-steps', 'public');
            $validated['image_path'] = $path; // Store the path as string
        } else {
            $validated['image_path'] = null; // Ensure it's null if no file
        }

        ProcessStep::create($validated);

        return redirect()->route('admin.process-steps.index')
            ->with('success', 'Process step created successfully.');
    }

    public function edit(ProcessStep $process)
    {
        return Inertia::render('Admin/Process/Edit', [
            'step' => $process,
        ]);
    }

    public function update(Request $request, ProcessStep $process)
    {
        $validated = $request->validate([
            'step_number' => 'required|integer|min:1',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'icon_name' => 'nullable|string|max:255',
            'image_path' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048', // Changed to accept image files
            'is_active' => 'boolean',
        ]);

        // Handle file upload for update
        if ($request->hasFile('image_path')) {
            // Delete old image if exists
            if ($process->image_path) {
                Storage::disk('public')->delete($process->image_path);
            }
            
            $file = $request->file('image_path');
            $path = $file->store('process-steps', 'public');
            $validated['image_path'] = $path;
        } else {
            // If no new file uploaded, keep the existing image path
            if ($request->has('image_path') && $request->image_path === null) {
                // If image_path is explicitly set to null, delete the existing image
                if ($process->image_path) {
                    Storage::disk('public')->delete($process->image_path);
                }
                $validated['image_path'] = null;
            } else {
                // Otherwise, keep the existing image path
                unset($validated['image_path']);
            }
        }

        $process->update($validated);

        return redirect()->route('admin.process-steps.index')
            ->with('success', 'Process step updated successfully.');
    }

    public function destroy(ProcessStep $process)
    {
        // Delete associated image file
        if ($process->image_path) {
            Storage::disk('public')->delete($process->image_path);
        }

        $process->delete();

        return redirect()->route('admin.process-steps.index')
            ->with('success', 'Process step deleted successfully.');
    }
}