<?php

namespace App\Http\Controllers;

use App\Models\Origin;
use Inertia\Inertia;
use Inertia\Response;

class OriginController extends Controller
{
    public function index(): Response
    {
        // For now, just show the first active origin
        $origin = Origin::where('is_active', true)
            ->orderBy('id')
            ->first();

        return Inertia::render('Origin', [
            'origin' => $origin,
        ]);
    }
}