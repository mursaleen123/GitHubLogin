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
        $user = User::where('linkedin_id', $linkedinUser->id)->first();

        if (!$user) {
            // User doesn't exist, create a new one
            $user = new User();
            $user->name = $linkedinUser->name;
            $user->email = $linkedinUser->email;
            $user->linkedin_id = $linkedinUser->id;
            $user->save();
        }

        // Log in the user
        auth()->login($user);

        // Redirect to home page or dashboard
        return redirect('/');
    }
}
