<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Prize;

class InstantwinController extends Controller
{
    public function generateRandomName()
    {
        $gender = ['male', 'female'][array_rand(['male', 'female'])];

        if ($gender === 'male') {
            $firstNames = config('japanese_names.male_first_names');
        } else {
            $firstNames = config('japanese_names.female_first_names');
        }

        $lastNames = config('japanese_names.last_names');

        $firstName = $firstNames[array_rand($firstNames)];
        $lastName = $lastNames[array_rand($lastNames)];

        return [
            'first_name' => $firstName,
            'last_name' => $lastName,
            'gender' => $gender,
        ];
    }

    public function select(Request $request)
    {
        $groupId = 1; // IDを1に固定
        $prizes = Prize::where('group_id', $groupId)->get();
        
        if ($prizes->isEmpty()) {
            return redirect()->back()->with('message', 'No prizes available for this group.');
        }

        $totalProbability = $prizes->sum('probability');
        
        $cumulativeProbability = 0;
        $adjustedPrizes = [];

        foreach ($prizes as $prize) {
            $adjustedProbability = ($prize->probability / $totalProbability) * 100;
            $cumulativeProbability += $adjustedProbability;
            $adjustedPrizes[] = [
                'prize' => $prize,
                'cumulative_probability' => $cumulativeProbability
            ];
        }

        $randomNumber = mt_rand(0, 100);
        foreach ($adjustedPrizes as $adjustedPrize) {
            if ($randomNumber <= $adjustedPrize['cumulative_probability']) {
                $winnerName = $this->generateRandomName();
                $results[] = [
                    'prize' => $adjustedPrize['prize'],
                    'winner_name' => $winnerName
                ];
                return redirect()->route('instantwin.result')->with('results', $results);
            }
        }

        return redirect()->route('instantwin.result')->with('results', []);
    }

    public function selectTen(Request $request)
    {
        $groupId = 1; // IDを1に固定
        $prizes = Prize::where('group_id', $groupId)->get();
        
        if ($prizes->isEmpty()) {
            return redirect()->back()->with('message', 'No prizes available for this group.');
        }

        $totalProbability = $prizes->sum('probability');
        
        $cumulativeProbability = 0;
        $adjustedPrizes = [];

        foreach ($prizes as $prize) {
            $adjustedProbability = ($prize->probability / $totalProbability) * 100;
            $cumulativeProbability += $adjustedProbability;
            $adjustedPrizes[] = [
                'prize' => $prize,
                'cumulative_probability' => $cumulativeProbability
            ];
        }

        $results = [];
        for ($i = 0; $i < 10; $i++) {
            $randomNumber = mt_rand(0, 100);
            foreach ($adjustedPrizes as $adjustedPrize) {
                if ($randomNumber <= $adjustedPrize['cumulative_probability']) {
                    $winnerName = $this->generateRandomName();
                    $results[] = [
                        'prize' => $adjustedPrize['prize'],
                        'winner_name' => $winnerName
                    ];
                    break;
                }
            }
        }

        return redirect()->route('instantwin.result')->with('results', $results);
    }

    public function showForm()
    {
        return view('instantwin.form');
    }

    public function showResult(Request $request)
    {
        $results = $request->session()->get('results', []);
        return view('instantwin.result')->with('results', $results);
    }
}
