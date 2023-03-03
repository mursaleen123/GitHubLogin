<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class LoginController extends Controller
{
    public function redirectToGithub()
    {
        return Socialite::driver('github')->redirect();
    }

    public function handleGithubCallback()
    {

        $githubUser = Socialite::driver('github')->user();

        $user = User::where('github_id', $githubUser->id)->first();
        if ($user) {
            $user->update([
                'github_token' => $githubUser->token,
                'github_refresh_token' => $githubUser->refreshToken,
            ]);
        } else {
            $user = User::create([
                'name' => $githubUser->name,
                'email' => $githubUser->email,
                'github_id' => $githubUser->id,
                'github_token' => $githubUser->token,
                'github_refresh_token' => $githubUser->refreshToken,
            ]);
        }

        Auth::login($user);

        return redirect('/dashboard');
    }

    public function redirectToLinkedin()
    {
        return Socialite::driver('linkedin')->redirect();
    }
    public function handleLinkedinCallback()
    {
        $linkedinUser = Socialite::driver('linkedin')->user();
        // Check if the user already exists in your database
        $user = User::where('linkedin_id', $linkedinUser->id)
        ->orWhere('email', $linkedinUser->email)->first();

        if ($user) {
            $user->update([
                'linkedin_id' => $linkedinUser->id,
            ]);
        } else {
            $user = User::create([
                'name' => $linkedinUser->name,
                'email' => $linkedinUser->email,
                'linkedin_id' => $linkedinUser->id,
            ]);
        }

        auth()->login($user);

        // Redirect to home page or dashboard
        return redirect('/dashboard');
    }
}
