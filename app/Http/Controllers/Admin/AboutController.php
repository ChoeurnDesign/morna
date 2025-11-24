<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AboutPage;
use App\Models\TeamMember;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class AboutController extends Controller
{
    public function index()
    {
        $aboutPage = AboutPage::first();
        $teamMembers = TeamMember::ordered()->get();

        return Inertia::render('Admin/About/Index', [
            'aboutPage' => $aboutPage ? [
                'id' => $aboutPage->id,
                'title' => $aboutPage->title,
                'subtitle' => $aboutPage->subtitle,
                'story_text' => $aboutPage->story_text,
                'mission_text' => $aboutPage->mission_text,
                'vision_text' => $aboutPage->vision_text,
                'banner_image_path' => $aboutPage->banner_image_url,
            ] : null,
            'teamMembers' => $teamMembers->map(function ($member) {
                return [
                    'id' => $member->id,
                    'name' => $member->name,
                    'role' => $member->role,
                    'bio' => $member->bio,
                    'image_path' => $member->image_url,
                    'sort_order' => $member->sort_order,
                    'is_active' => $member->is_active,
                ];
            }),
        ]);
    }

    public function updateAbout(Request $request)
    {
        $request->validate([
            'title' => 'nullable|string|max:255',
            'subtitle' => 'nullable|string|max:500',
            'story_text' => 'nullable|string',
            'mission_text' => 'nullable|string',
            'vision_text' => 'nullable|string',
            'banner_image' => 'nullable|image|mimes:jpeg,png,jpg|max:5120', // Updated to 5MB
        ]);

        // Get all the validated data
        $data = $request->only(['title', 'subtitle', 'story_text', 'mission_text', 'vision_text']);
        
        // Convert null values to empty strings for database
        $data = array_map(function ($value) {
            return $value ?? '';
        }, $data);

        // Find or create with the actual data
        $aboutPage = AboutPage::first();
        
        if (!$aboutPage) {
            // Create with all data (even if empty)
            $aboutPage = AboutPage::create($data);
        } else {
            // Update existing
            $aboutPage->update($data);
        }

        // Handle banner image upload
        if ($request->hasFile('banner_image')) {
            // Delete old banner if exists
            if ($aboutPage->banner_image_path && Storage::disk('public')->exists($aboutPage->banner_image_path)) {
                Storage::disk('public')->delete($aboutPage->banner_image_path);
            }
            
            $aboutPage->update([
                'banner_image_path' => $request->file('banner_image')->store('about/banner', 'public')
            ]);
        }

        return back()->with('success', 'About page updated successfully!');
    }

    public function updateTeamMember(Request $request, TeamMember $teamMember)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'role' => 'required|string|max:255',
            'bio' => 'required|string|max:1000',
            'sort_order' => 'integer',
            'is_active' => 'boolean',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:5120', // Updated to 5MB
        ]);

        $data = $request->only(['name', 'role', 'bio', 'sort_order', 'is_active']);

        // Handle image upload
        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($teamMember->image_path && Storage::disk('public')->exists($teamMember->image_path)) {
                Storage::disk('public')->delete($teamMember->image_path);
            }
            
            $data['image_path'] = $request->file('image')->store('team/members', 'public');
        }

        $teamMember->update($data);

        return back()->with('success', 'Team member updated successfully!');
    }

    public function storeTeamMember(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'role' => 'required|string|max:255',
            'bio' => 'required|string|max:1000',
            'sort_order' => 'required|integer|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:5120', // Updated to 5MB
        ]);

        $data = $request->only(['name', 'role', 'bio', 'sort_order']);
        $data['is_active'] = true;

        // Handle image upload
        if ($request->hasFile('image')) {
            $data['image_path'] = $request->file('image')->store('team/members', 'public');
        }

        TeamMember::create($data);

        return back()->with('success', 'Team member added successfully!');
    }

    public function destroyTeamMember(TeamMember $teamMember)
    {
        // Delete image if exists
        if ($teamMember->image_path && Storage::disk('public')->exists($teamMember->image_path)) {
            Storage::disk('public')->delete($teamMember->image_path);
        }

        $teamMember->delete();

        return back()->with('success', 'Team member deleted successfully!');
    }

    public function deleteBanner()
    {
        $aboutPage = AboutPage::first();
        
        if ($aboutPage && $aboutPage->banner_image_path && Storage::disk('public')->exists($aboutPage->banner_image_path)) {
            Storage::disk('public')->delete($aboutPage->banner_image_path);
            $aboutPage->update(['banner_image_path' => null]);
        }

        return back()->with('success', 'Banner image removed successfully!');
    }
}