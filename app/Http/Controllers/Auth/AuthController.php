<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Constants\AppConstants;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    // Hiển thị form đăng nhập
    public function showLoginForm()
    {
        return view('auth.login');
    }

    // Xử lý đăng nhập
    public function login(LoginRequest $request)
    {
        $credentials = $request->validated();

        if (Auth::attempt($credentials)) {
            // Kiểm tra quyền người dùng
            $user = Auth::user();
            if ($user->role === AppConstants::ADMIN) {
                return redirect()->route('dashboardAdmin');
            } elseif ($user->role === AppConstants::USER) {
                return redirect()->route('dashboardUser');
            } else {
                // Trường hợp quyền không xác định rõ ràng
                return back()->withErrors(['email' => 'Quyền người dùng không hợp lệ.']);
            }
        } else {
            // Đăng nhập thất bại
            return back()->withErrors(['email' => 'Email hoặc mật khẩu không đúng.']);
        }
    }

    // Hiển thị dashboard admin
    public function showAdmin()
    {
        return view('admin.index');
    }

    // Hiển thị dashboard user
    public function showUser()
    {
        return view('user.index');
    }
}
