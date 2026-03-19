<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Inertia\Inertia;

class PollingController extends Controller
{
    public function __invoke()
    {
        return Inertia::render('Polling', [
            'notifications' => Notification::latest()->take(10)->get(),
            'serverTime' => now()->format('H:i:s'),
            'randomMetric' => rand(1, 100),
        ]);
    }
}
