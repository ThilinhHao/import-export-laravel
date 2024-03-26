<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use App\Models\Auth\PasswordReset;
use App\Models\User;
use App\Http\Requests\Auth\ResetPasswordRequest;
use App\Mail\SuccessResetEmail;
use Illuminate\Support\Facades\Mail;

class ResetPasswordController extends Controller
{
    public function showResetPasswordForm($token)
    {
        $passwordReset = PasswordReset::where('token', $token)
            ->where('created_at', '>', Carbon::now()->subMinutes(15))
            ->first();

        if (!$passwordReset) {
            return redirect()->route('reset.error')->with('error', 'Reset password link has expired');
        }

        return view('auth.reset_password', ['token' => $token]);
    }

    public function resetPassword(ResetPasswordRequest $request)
    {
        $request->validated();

        $user = User::where('email', $request->email)->first();
        if (!$user) {
            return back()->withErrors(['email' => 'Email is not registered.']);
        }

        $passwordReset = PasswordReset::where('token', $request->token)
            ->where('email', $request->email)
            ->where('created_at', '>', Carbon::now()->subMinutes(15))
            ->first();

        if (!$passwordReset) {
            return back()->withErrors(['email' => 'Invalid email.']);
        }

        $user->password = Hash::make($request->password);
        $user->save();

        $passwordReset->delete();

        Mail::to($user->email)->send(new SuccessResetEmail());
        return redirect()->route('login')->with('success', 'Your password has been changed!');
    }
}