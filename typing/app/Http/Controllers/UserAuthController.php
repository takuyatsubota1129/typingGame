<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class UserAuthController extends Controller
{

    // ログイン処理
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            $user = Auth::user();
            $token = $user->createToken('authToken')->plainTextToken; // トークンを発行

            return response()->json(['token' => $token], 200); // トークンを返す
        }

        return response()->json(['error' => 'The provided credentials do not match our records.'], 401);
    }

    // 新規登録フォームを表示
    public function showRegistrationForm()
    {
        return view('user.register');
    }

    public function register(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = User::create([
            'name' => $validatedData['name'],
            'email' => $validatedData['email'],
            'password' => Hash::make($validatedData['password']),
        ]);

        Auth::login($user);

        return redirect()->intended('dashboard');
    }
    public function showLoginForm()
    {
        if (Auth::check()) {
            return redirect('/top');
        }
        return view('user.login');
    }
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete(); // 現在のトークンを削除
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return response()->json(['message' => 'ログアウトしました'], 200);
    }
}
