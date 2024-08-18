<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class WordsController extends Controller
{
    public function index()
    {
        // JSONファイルから単語リストを読み込む
        $wordsJson = File::get(config_path('words.json'));
        $words = json_decode($wordsJson);

        // ビューに単語リストを渡す
        return view('game.play')->with('words', $words);
    }
}
