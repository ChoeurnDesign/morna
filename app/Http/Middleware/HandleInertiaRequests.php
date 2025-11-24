<?php

namespace App\Http\Middleware;

use App\Models\Setting;
use Illuminate\Http\Request;
use Inertia\Middleware;
use Illuminate\Support\Facades\Storage;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that is loaded on the first page visit.
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determine the current asset version.
     */
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        try {
            $settings = Setting::getSettings();
            $siteSettings = [
                'site_name'      => $settings->site_name,
                'contact_email'  => $settings->contact_email,
                'contact_phone'  => $settings->contact_phone,
                'address'        => $settings->address,
                'footer_text'    => $settings->footer_text,
                'logo_url'       => $settings->logo_path ? Storage::url($settings->logo_path) : null,
                'favicon_url'    => $settings->favicon_path ? Storage::url($settings->favicon_path) : null,
            ];
        } catch (\Exception $e) {
            // defaults...
        }

        return array_merge(parent::share($request), [
            'auth' => [
                'user' => $request->user(),
            ],
            'siteSettings' => $siteSettings,
        ]);
    }
}