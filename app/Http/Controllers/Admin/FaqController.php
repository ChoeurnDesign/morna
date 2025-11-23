<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Faq;
use Illuminate\Http\Request;
use Inertia\Inertia;

class FaqController extends Controller
{
    public function index()
    {
        $faqs = Faq::orderBy('sort_order')
            ->orderBy('id')
            ->get()
            ->map(function ($faq) {
                return [
                    'id'        => $faq->id,
                    'question'  => $faq->question,
                    'answer'    => $faq->answer,
                    'is_active' => (bool) $faq->is_active,
                    'sort_order'=> $faq->sort_order,
                    'created_at'=> $faq->created_at,
                ];
            });

        return Inertia::render('Admin/Faqs/Index', [
            'faqs' => $faqs,
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'question'  => 'required|string|max:255',
            'answer'    => 'required|string',
        ]);

        $data['is_active'] = $request->has('is_active') ? 1 : 0;

        // Default sort_order: after last
        $maxOrder = Faq::max('sort_order');
        $data['sort_order'] = is_null($maxOrder) ? 1 : $maxOrder + 1;

        Faq::create($data);

        return redirect()
            ->route('admin.faqs.index')
            ->with('success', 'FAQ created successfully.');
    }

    public function update(Request $request, Faq $faq)
    {
        $data = $request->validate([
            'question'  => 'required|string|max:255',
            'answer'    => 'required|string',
        ]);

        $data['is_active'] = $request->has('is_active') ? 1 : 0;

        $faq->update($data);

        return redirect()
            ->route('admin.faqs.index')
            ->with('success', 'FAQ updated successfully.');
    }

    public function destroy(Faq $faq)
    {
        $faq->delete();

        return redirect()
            ->route('admin.faqs.index')
            ->with('success', 'FAQ deleted successfully.');
    }

    /**
     * Reorder FAQs – expects an array of IDs in new order.
     */
    public function reorder(Request $request)
    {
        $request->validate([
            'order'   => 'required|array',
            'order.*' => 'integer|exists:faqs,id',
        ]);

        foreach ($request->order as $index => $id) {
            Faq::where('id', $id)->update(['sort_order' => $index + 1]);
        }

        return redirect()
            ->route('admin.faqs.index')
            ->with('success', 'FAQs reordered successfully.');
    }

    public function toggleStatus(Faq $faq)
    {
        $faq->is_active = ! $faq->is_active;
        $faq->save();

        return redirect()
            ->route('admin.faqs.index')
            ->with('success', 'FAQ status updated.');
    }
}