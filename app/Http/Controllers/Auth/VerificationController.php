<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Carbon\Carbon;

class VerificationController extends Controller
{
    public function verify(Request $request, $token)
    {
        $user = User::where('remember_token', $token)->first();

        if (!$user) {
            return redirect()->route('verification.error')->with('error', 'Invalid verification token');
        }

        if ($user->remember_token !== $token) {
            return redirect()->route('verification.error')->with('error', 'Invalid verification token');
        }

        $expirationTime = 15;
        $isExpired = Carbon::parse($user->created_at)->addMinutes($expirationTime)->isPast();

        if ($isExpired) {
            return redirect()->route('verification.error')->with('error', 'Verification link has expired');
        }

        $user->update(['email_verified_at' => Carbon::now()]);

        return redirect()->route('verification.success');
    }
}
