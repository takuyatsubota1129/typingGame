<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;

class GameController extends Controller
{
    public function joinStage(Request $request, $stageId)
    {
        // ステージに参加する処理
        Redis::publish('game-channel', json_encode([
            'event' => 'joinStage',
            'stageId' => $stageId
        ]));

        return response()->json(['success' => true]);
    }

    public function leaveStage(Request $request, $stageId)
    {
        // ステージから離脱する処理
        Redis::publish('game-channel', json_encode([
            'event' => 'leaveStage',
            'stageId' => $stageId
        ]));

        return response()->json(['success' => true]);
    }
}
