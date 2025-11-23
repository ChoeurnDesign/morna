<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Models\ActivityLog; // We'll create this model
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class SettingsController extends Controller
{
    public function index()
    {
        $settings = Setting::getSettings();

        // Add full URLs for images
        $settingsArray = [
            'site_name' => $settings->site_name,
            'contact_email' => $settings->contact_email,
            'contact_phone' => $settings->contact_phone,
            'address' => $settings->address,
            'footer_text' => $settings->footer_text,
            'logo_url' => $settings->logo_path ? Storage::url($settings->logo_path) : null,
            'favicon_url' => $settings->favicon_path ? Storage::url($settings->favicon_path) : null,
        ];

        return Inertia::render('Admin/Settings/Index', [
            'settings' => $settingsArray
        ]);
    }

    public function updateGeneral(Request $request)
    {
        $request->validate([
            'site_name' => 'nullable|string|max:255',
            'contact_email' => 'nullable|email',
            'contact_phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
            'footer_text' => 'nullable|string|max:1000',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'favicon' => 'nullable|image|mimes:ico,png,jpg,jpeg|max:1024',
        ]);

        $data = [
            'site_name' => $request->site_name,
            'contact_email' => $request->contact_email,
            'contact_phone' => $request->contact_phone,
            'address' => $request->address,
            'footer_text' => $request->footer_text,
        ];

        // Convert empty site_name to null
        if (isset($data['site_name']) && trim($data['site_name']) === '') {
            $data['site_name'] = null;
        }

        $changes = [];
        $oldSettings = Setting::getSettings();

        // Handle logo upload
        if ($request->hasFile('logo')) {
            // Delete old logo if exists
            $oldLogo = $oldSettings->logo_path;
            if ($oldLogo && Storage::disk('public')->exists($oldLogo)) {
                Storage::disk('public')->delete($oldLogo);
            }
            
            $data['logo_path'] = $request->file('logo')->store('settings/logo', 'public');
            $changes[] = 'logo updated';
        }

        // Handle favicon upload
        if ($request->hasFile('favicon')) {
            // Delete old favicon if exists
            $oldFavicon = $oldSettings->favicon_path;
            if ($oldFavicon && Storage::disk('public')->exists($oldFavicon)) {
                Storage::disk('public')->delete($oldFavicon);
            }
            
            $data['favicon_path'] = $request->file('favicon')->store('settings/favicon', 'public');
            $changes[] = 'favicon updated';
        }

        // Check for general changes
        if ($oldSettings->site_name != $data['site_name']) $changes[] = 'site name updated';
        if ($oldSettings->contact_email != $data['contact_email']) $changes[] = 'contact email updated';
        if ($oldSettings->contact_phone != $data['contact_phone']) $changes[] = 'contact phone updated';
        if ($oldSettings->address != $data['address']) $changes[] = 'address updated';
        if ($oldSettings->footer_text != $data['footer_text']) $changes[] = 'footer text updated';

        // Update settings
        Setting::updateSettings($data);

        // Log the activity if there were changes
        if (!empty($changes)) {
            // You can create an ActivityLog model or use sessions/flash messages
            // For now, we'll rely on the updated_at timestamp in settings
        }

        return back()->with('success', 'Settings updated successfully!');
    }

    public function deleteLogo()
    {
        $settings = Setting::getSettings();
        
        if ($settings->logo_path && Storage::disk('public')->exists($settings->logo_path)) {
            Storage::disk('public')->delete($settings->logo_path);
        }
        
        $settings->update(['logo_path' => null]);

        return back()->with('success', 'Logo removed successfully!');
    }

    public function deleteFavicon()
    {
        $settings = Setting::getSettings();
        
        if ($settings->favicon_path && Storage::disk('public')->exists($settings->favicon_path)) {
            Storage::disk('public')->delete($settings->favicon_path);
        }
        
        $settings->update(['favicon_path' => null]);

        return back()->with('success', 'Favicon removed successfully!');
    }
}