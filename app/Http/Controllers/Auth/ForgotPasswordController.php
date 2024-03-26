<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Auth\PasswordReset;
use App\Mail\ResetPasswordEmail;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use App\Http\Requests\Auth\ForgotPasswordRequest;


class ForgotPasswordController extends Controller
{
    public function sendResetLinkEmail(ForgotPasswordRequest $request)
    {
        $request->validated();

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return back()->withErrors(['email' => 'Email is not registered.']);
        }

        $token = Str::random(60);

        $passwordReset = PasswordReset::updateOrCreate(
            ['email' => $user->email],
            ['token' => $token, 'created_at' => Carbon::now()]
        );

        $resetLink = route('password.reset', $token); // Tạo link đặt lại mật khẩu

        // Gửi email đến người dùng với link đặt lại mật khẩu
        Mail::to($user->email)->send(new ResetPasswordEmail($resetLink));

        return back()->with('success', 'Please check your email and reset your password.');
    }
}




