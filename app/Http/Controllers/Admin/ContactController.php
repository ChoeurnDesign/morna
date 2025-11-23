<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ContactController extends Controller
{
    public function index()
    {
        $contacts = Contact::latest()
            ->get()
            ->map(function ($contact) {
                return [
                    'id'         => $contact->id,
                    'name'       => $contact->name,
                    'phone'      => $contact->phone,
                    'email'      => $contact->email,
                    'message'    => $contact->message,
                    'status'     => $contact->status,
                    'created_at' => $contact->created_at,
                ];
            });

        return Inertia::render('Admin/Contacts/Index', [
            'contacts' => $contacts,
        ]);
    }

    public function show(Contact $contact)
    {
        return Inertia::render('Admin/Contacts/Show', [
            'contact' => [
                'id'         => $contact->id,
                'name'       => $contact->name,
                'phone'      => $contact->phone,
                'email'      => $contact->email,
                'message'    => $contact->message,
                'status'     => $contact->status,
                'created_at' => $contact->created_at,
                'updated_at' => $contact->updated_at,
            ],
        ]);
    }

    public function destroy(Contact $contact)
    {
        $contact->delete();

        return redirect()
            ->route('admin.contacts.index')
            ->with('success', 'Contact message deleted successfully.');
    }

    public function markAsRead(Contact $contact)
    {
        $contact->status = 'read';
        $contact->save();

        return redirect()
            ->route('admin.contacts.index')
            ->with('success', 'Contact marked as read.');
    }

    public function markAsUnread(Contact $contact)
    {
        $contact->status = 'new';
        $contact->save();

        return redirect()
            ->route('admin.contacts.index')
            ->with('success', 'Contact marked as unread.');
    }
}