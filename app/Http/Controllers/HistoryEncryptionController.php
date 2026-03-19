<?php

namespace App\Http\Controllers;

use Illuminate\Support\Str;
use Inertia\Inertia;

class HistoryEncryptionController extends Controller
{
    public function __invoke()
    {
        Inertia::encryptHistory();

        return Inertia::render('HistoryEncryption', [
            'sensitiveData' => [
                'apiKey' => 'sk-demo-' . Str::random(32),
                'secretToken' => Str::random(64),
                'userEmail' => 'admin@example.com',
            ],
        ]);
    }
}
