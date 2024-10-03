<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class WordsController extends Controller
{
    public function getWords()
    {
        $wordsJson = File::get(resource_path('views/game/words.json'));
        $words = json_decode($wordsJson, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            \Log::error('JSON decode error: ' . json_last_error_msg());
            return response()->json(['error' => 'Failed to decode words'], 500);
        }

        return response()->json($words);
    }
}
