<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class StageController extends Controller
{
    //一覧を表示する
    public function select()
    {
        // ダミーデータを作成（仮のデータ）
        $stages = [
            (object) [
                'id' => 1,
                'name' => 'ステージ1',
                'image_path' => 'stage1.jpg',
                'difficulty' => 'Easy',
                'items' => [
                    'name'=>'アイテム1','name'=> 'アイテム2','name'=> 'アイテム3']
            ],
            (object) [
                'id' => 2,
                'name' => 'ステージ2',
                'image_path' => 'stage2.jpg',
                'difficulty' => 'Normal',
                'items' => ['name'=>'アイテム4', 'name'=>'アイテム5','name'=> 'アイテム6']
            ],
            // 他のステージのダミーデータも同様に追加
            // 例えば、ステージ3、ステージ4、ステージ5など
        ];
        // 配列をオブジェクトに変換
        $stages = array_map(function ($stage) {
            return (object) $stage;
        }, $stages);

        return view('stage.select', compact('stages'));
    }
}
