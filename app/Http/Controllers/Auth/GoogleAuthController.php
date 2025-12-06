<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Socialite;
use Psy\Util\Str;

class GoogleAuthController extends Controller
{
    public function redirectToGoogle() {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback() {
        try{
            $googleUser = Socialite::driver('google')->user();

            $findUser = User::where('google_id', $googleUser->id)->first();

            if($findUser) {
                Auth::login($findUser);

                return redirect()->intended('dashboard');
            } else {
                $existingUser = User::where('email', $googleUser->email)->first();

                if($existingUser) {
                    $existingUser->update([
                        'google_id' => $googleUser->id,
                    ]);
                    Auth::login($existingUser);
                } else {
                    $newUser = User::create([
                        'name' => $googleUser->name,
                        'email' => $googleUser->email,
                        'google_id' => $googleUser->id,
                        'password' => bcrypt(bin2hex(random_bytes(16))),
                        'role' => 'user', 
                    ]);
                    Auth::login($newUser);
                }

                return redirect()->intended('dashboard');
            }

        } catch (\Exception $e){
            return redirect()->route('login')->with('error', 'Gagal login dengan Google: ' . $e->getMessage());
        }
    }
}
