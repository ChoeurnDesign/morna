<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TeamMember;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class TeamMemberController extends Controller
{
    public function index()
    {
        $teamMembers = TeamMember::ordered()->get();

        return Inertia::render('Admin/TeamMembers/Index', [
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

    public function create()
    {
        return Inertia::render('Admin/TeamMembers/Create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'role' => 'required|string|max:255',
            'bio' => 'required|string|max:1000',
            'sort_order' => 'required|integer|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:5120', // Changed to 5MB (5120KB)
        ]);

        $data = $request->only(['name', 'role', 'bio', 'sort_order']);
        $data['is_active'] = true;

        if ($request->hasFile('image')) {
            $data['image_path'] = $request->file('image')->store('team/members', 'public');
        }

        TeamMember::create($data);

        return redirect()->route('admin.team-members.index')->with('success', 'Team member added successfully!');
    }

    public function edit(TeamMember $teamMember)
    {
        return Inertia::render('Admin/TeamMembers/Edit', [
            'member' => [
                'id' => $teamMember->id,
                'name' => $teamMember->name,
                'role' => $teamMember->role,
                'bio' => $teamMember->bio,
                'image_path' => $teamMember->image_url,
                'sort_order' => $teamMember->sort_order,
                'is_active' => $teamMember->is_active,
            ],
        ]);
    }

    public function update(Request $request, TeamMember $teamMember)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'role' => 'required|string|max:255',
            'bio' => 'required|string|max:1000',
            'sort_order' => 'required|integer',
            'is_active' => 'boolean',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:5120', // Changed to 5MB (5120KB)
        ]);

        $data = $request->only(['name', 'role', 'bio', 'sort_order', 'is_active']);

        if ($request->hasFile('image')) {
            if ($teamMember->image_path && Storage::disk('public')->exists($teamMember->image_path)) {
                Storage::disk('public')->delete($teamMember->image_path);
            }
            
            $data['image_path'] = $request->file('image')->store('team/members', 'public');
        }

        $teamMember->update($data);

        return redirect()->route('admin.team-members.index')->with('success', 'Team member updated successfully!');
    }

    public function destroy(TeamMember $teamMember)
    {
        if ($teamMember->image_path && Storage::disk('public')->exists($teamMember->image_path)) {
            Storage::disk('public')->delete($teamMember->image_path);
        }

        $teamMember->delete();

        return redirect()->route('admin.team-members.index')->with('success', 'Team member deleted successfully!');
    }
}