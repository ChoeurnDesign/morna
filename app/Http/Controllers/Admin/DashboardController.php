<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\HeroSlide;
use App\Models\Faq;
use App\Models\Contact;
use App\Models\Setting;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Support\Facades\Log;

class DashboardController extends Controller
{
    public function index(): Response
    {
        try {
            $stats = [
                'totalProducts' => Product::count(),
                'totalHeroSlides' => HeroSlide::count(),
                'totalFaqs' => Faq::count(),
                'totalContacts' => Contact::count(),
            ];

            $recentActivities = $this->getRecentActivities();

            return Inertia::render('Admin/Dashboard', [
                'title' => 'Dashboard',
                'stats' => $stats,
                'recentActivities' => $recentActivities,
            ]);

        } catch (\Exception $e) {
            Log::error('DashboardController error: ' . $e->getMessage());
            
            return Inertia::render('Admin/Dashboard', [
                'title' => 'Dashboard',
                'stats' => [
                    'totalProducts' => 0,
                    'totalHeroSlides' => 0,
                    'totalFaqs' => 0,
                    'totalContacts' => 0,
                ],
                'recentActivities' => [],
            ]);
        }
    }

    /**
     * Get recent activities from all sources
     */
    private function getRecentActivities(): array
    {
        $activities = [];

        // Recent products (last 3)
        $products = Product::latest()->take(3)->get();
        foreach ($products as $product) {
            $activities[] = [
                'type' => 'product',
                'message' => 'Product added: ' . $product->name,
                'time' => $product->created_at->diffForHumans(),
                'created_at' => $product->created_at,
            ];
        }

        // Recent contacts (last 2)
        $contacts = Contact::latest()->take(2)->get();
        foreach ($contacts as $contact) {
            $activities[] = [
                'type' => 'contact',
                'message' => 'New message from ' . $contact->name,
                'time' => $contact->created_at->diffForHumans(),
                'created_at' => $contact->created_at,
            ];
        }

        // Recent hero slides (last 2)
        $slides = HeroSlide::latest()->take(2)->get();
        foreach ($slides as $slide) {
            $activities[] = [
                'type' => 'slide', 
                'message' => 'Hero slide updated',
                'time' => $slide->created_at->diffForHumans(),
                'created_at' => $slide->created_at,
            ];
        }

        // Recent FAQs (last 2)
        $faqs = Faq::latest()->take(2)->get();
        foreach ($faqs as $faq) {
            $activities[] = [
                'type' => 'faq',
                'message' => 'FAQ updated: ' . substr($faq->question, 0, 50) . '...',
                'time' => $faq->created_at->diffForHumans(),
                'created_at' => $faq->created_at,
            ];
        }

        // Recent settings updates (from the settings table)
        $settings = Setting::latest()->take(2)->get();
        foreach ($settings as $setting) {
            $activities[] = [
                'type' => 'settings',
                'message' => 'Site settings updated',
                'time' => $setting->updated_at->diffForHumans(),
                'created_at' => $setting->updated_at,
            ];
        }

        // Sort by creation date (newest first) and limit to 6 most recent
        usort($activities, function ($a, $b) {
            return strtotime($b['created_at']) - strtotime($a['created_at']);
        });

        // Remove the created_at field before returning
        $activities = array_map(function ($activity) {
            unset($activity['created_at']);
            return $activity;
        }, array_slice($activities, 0, 6));

        return $activities;
    }
}