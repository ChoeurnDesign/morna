<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class ContactController extends Controller
{
    public function show(): Response
    {
        return Inertia::render('Contact');
    }

    public function submit(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name'    => ['required', 'string', 'max:255'],
            'phone'   => ['nullable', 'string', 'max:50'],
            'email'   => ['nullable', 'email', 'max:255'],
            'message' => ['nullable', 'string'],
        ]);

        // At least one of phone or email is required
        if (empty($data['phone']) && empty($data['email'])) {
            return back()
                ->withErrors([
                    'phone' => 'Please provide at least a phone number or an email.',
                    'email' => 'Please provide at least a phone number or an email.',
                ])
                ->withInput();
        }

        Contact::create([
            'name'    => $data['name'],
            'phone'   => $data['phone'] ?? null,
            'email'   => $data['email'] ?? null,
            'message' => $data['message'] ?? null,
            'status'  => 'new',
        ]);

        return back()->with('success', 'Thank you! Your message has been received.');
    }
}