<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Prize;
use Illuminate\Http\Request;

class PrizeController extends Controller
{
    public function index()
    {
        $prizes = Prize::all();
        return view('admin.prizes.index', compact('prizes'));
    }

    public function create()
    {
        return view('admin.prizes.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'probability' => 'required|numeric|min:0|max:100',
            'group_id' => 'required|integer|min:1',
            'type_id' => 'required|integer',
            'sub_id' => 'nullable|integer',
            'notes' => 'nullable|string',
        ]);

        Prize::create($request->all());

        return redirect()->route('admin.prizes.index')
            ->with('success', 'Prize created successfully.');
    }

    public function edit(Prize $prize)
    {
        return view('admin.prizes.edit', compact('prize'));
    }

    public function update(Request $request, Prize $prize)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'probability' => 'required|numeric|min:0|max:100',
            'group_id' => 'required|integer|min:1',
            'type_id' => 'required|integer',
            'sub_id' => 'nullable|integer',
            'notes' => 'nullable|string',
        ]);

        $prize->update($request->all());

        return redirect()->route('admin.prizes.index')
            ->with('success', 'Prize updated successfully.');
    }

    public function destroy(Prize $prize)
    {
        $prize->delete();

        return redirect()->route('admin.prizes.index')
            ->with('success', 'Prize deleted successfully.');
    }
}
