<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Prize;
use Illuminate\Support\Facades\Storage;

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
    public function adjustProbabilities($prizes)
    {
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
    
        return $adjustedPrizes;
    }
    public function getMaxDirectoryNumber($gender)
    {
        $directories = Storage::directories("public/character/{$gender}");
        $maxNumber = 0;
    
        foreach ($directories as $directory) {
            $number = (int) basename($directory);
            if ($number > $maxNumber) {
                $maxNumber = $number;
            }
        }
    
        return $maxNumber;
    }
    public function select(Request $request)
    {
        $groupId = 1; // IDを1に固定
        $prizes = Prize::where('group_id', $groupId)->get();
        
        if ($prizes->isEmpty()) {
            return response()->json(['message' => 'No prizes available for this group.'], 404);
        }
    
        $adjustedPrizes = $this->adjustProbabilities($prizes);
        $randomNumber = mt_rand(0, 100);
        foreach ($adjustedPrizes as $adjustedPrize) {
            if ($randomNumber <= $adjustedPrize['cumulative_probability']) {
                $winnerName = $this->generateRandomName();
                $maxNumber = $this->getMaxDirectoryNumber($winnerName['gender']);
                $randomImageNumber = mt_rand(1, $maxNumber);
                $imagePath = env('APP_URL') . "/storage/character/{$winnerName['gender']}/{$randomImageNumber}/face.png";
                $results = [[
                    'prize' => $adjustedPrize['prize'],
                    'winner_name' => $winnerName,
                    'image_path' => $imagePath
                ]];
                return response()->json(['results' => $results]);
            }
        }
        $results = [[
            'prize' => [
                'name' => $adjustedPrize['prize']->name,
                'probability' => $adjustedPrize['prize']->probability
            ],
            'winner_name' => $winnerName,
            'image_path' => $imagePath
        ]];
        return response()->json(['results' => $results]);
    }

    public function selectTen(Request $request)
    {
        try {
            \Log::info('selectTen method called');
            
            $groupId = 1; // IDを1に固定
            $prizes = Prize::where('group_id', $groupId)->get();
            
            \Log::info('Prizes:', $prizes->toArray());
            
            if ($prizes->isEmpty()) {
                return response()->json(['message' => 'No prizes available for this group.'], 404);
            }
    
            $adjustedPrizes = $this->adjustProbabilities($prizes);
    
            \Log::info('Adjusted prizes:', $adjustedPrizes);
    
            $results = [];
            for ($i = 0; $i < 10; $i++) {
                $randomNumber = mt_rand(0, 100);
                \Log::info('Random number: ' . $randomNumber);
                foreach ($adjustedPrizes as $adjustedPrize) {
                    if ($randomNumber <= $adjustedPrize['cumulative_probability']) {
                        $winnerName = $this->generateRandomName();
                        $maxNumber = $this->getMaxDirectoryNumber($winnerName['gender']);
                        $randomImageNumber = mt_rand(1, $maxNumber);
                        $imagePath = env('APP_URL') . "/storage/character/{$winnerName['gender']}/{$randomImageNumber}/face.png";
                        $results[] = [
                            'prize' => [
                                'name' => $adjustedPrize['prize']->name,
                                'probability' => $adjustedPrize['prize']->probability
                            ],
                            'winner_name' => $winnerName,
                            'image_path' => $imagePath
                        ];
                        break;
                    }
                }
            }
    
            \Log::info('Results:', $results);
            return response()->json(['results' => $results]);
        } catch (\Exception $e) {
            \Log::error('Error in selectTen: ' . $e->getMessage());
            \Log::error($e->getTraceAsString());
            return response()->json(['error' => 'An error occurred while processing your request.'], 500);
        }
    }
    // showForm と showResult メソッドは削除可能（APIでは使用しないため）
}