<?php

namespace App\Http\Controllers;

use App\Models\ProcessStep;
use Inertia\Inertia;
use Inertia\Response;

class ProcessController extends Controller
{
    public function index(): Response
    {
        $steps = ProcessStep::where('is_active', true)
            ->orderBy('step_number')
            ->orderBy('id')
            ->get();

        return Inertia::render('Process', [
            'steps' => $steps,
        ]);
    }
}