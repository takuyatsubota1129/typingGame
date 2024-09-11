<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        // ダッシュボードに必要なデータを取得
        $dashboardData = [
            'user' => $user,
            // その他のダッシュボードデータ
        ];
        return response()->json($dashboardData);
    }
}