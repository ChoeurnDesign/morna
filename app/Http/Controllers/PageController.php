<?php

namespace App\Http\Controllers;

use App\Models\AboutPage;
use App\Models\TeamMember;
use Inertia\Inertia;
use Inertia\Response;

class PageController extends Controller
{
    public function about(): Response
    {
        $page = AboutPage::getContent();
        $teamMembers = TeamMember::active()->ordered()->get()->map(function ($member) {
            return [
                'id' => $member->id,
                'name' => $member->name,
                'role' => $member->role, // Changed from 'position'
                'bio' => $member->bio,   // Changed from 'description'
                'image_path' => $member->image_url,
                'sort_order' => $member->sort_order,
            ];
        });

        return Inertia::render('About', [
            'page' => $page ? [
                'title' => $page->title,
                'subtitle' => $page->subtitle,
                'story_text' => $page->story_text,
                'mission_text' => $page->mission_text,
                'vision_text' => $page->vision_text,
                'banner_image_path' => $page->banner_image_url,
            ] : null,
            'teamMembers' => $teamMembers,
        ]);
    }
}