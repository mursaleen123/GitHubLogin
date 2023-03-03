<?php

use App\Http\Controllers\Auth\LoginController;
use Illuminate\Support\Facades\Route;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified'
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
});

// Route::get('/auth/callback', function () {
//     $githubUser = Socialite::driver('github')->user();

//     $user = User::where('github_id', $githubUser->id)->first();

//     if ($user) {
//         $user->update([
//             'github_token' => $githubUser->token,
//             'github_refresh_token' => $githubUser->refreshToken,
//         ]);
//     } else {
//         $user = User::create([
//             'name' => $githubUser->name,
//             'email' => $githubUser->email,
//             'github_id' => $githubUser->id,
//             'github_token' => $githubUser->token,
//             'github_refresh_token' => $githubUser->refreshToken,
//         ]);
//     }

//     Auth::login($user);

//     return redirect('/dashboard');
// });

// Route::get('/auth/redirect', function () {
//     return Socialite::driver('github')->redirect();
// });

Route::get('/auth/redirect', [LoginController::class, 'redirectToGithub']);
Route::get('/auth/callback', [LoginController::class, 'handleGithubCallback']);

Route::get('/login/linkedin', [LoginController::class, 'redirectToLinkedin'])->name('linkedin.login');
Route::get('/linkedin/callback', [LoginController::class, 'handleLinkedinCallback']);


