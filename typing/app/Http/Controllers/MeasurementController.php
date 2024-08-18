<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Measurement; // Measurementモデルを使用するためにインポートします。

class MeasurementController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // リクエストデータのバリデーションを行います。
        $validatedData = $request->validate([
            // ここにバリデーションルールを設定します。例えば、'name' => 'required|max:255',
        ]);

        // バリデーションが通ったデータを使用して新しいMeasurementを作成します。
        $measurement = Measurement::create($validatedData);

        // レスポンスを返します。ここでは、作成したMeasurementのIDを返しています。
        return response()->json(['id' => $measurement->id], 201);
    }
}