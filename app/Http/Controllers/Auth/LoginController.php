<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
  /*
  |--------------------------------------------------------------------------
  | Login Controller
  |--------------------------------------------------------------------------
  |
  | This controller handles authenticating users for the application and
  | redirecting them to your home screen. The controller uses a trait
  | to conveniently provide its functionality to your applications.
  |
  */
  
  use AuthenticatesUsers;
  
  /**
   * Where to redirect users after login.
   *
   * @var string
   */
  protected $redirectTo = RouteServiceProvider::HOME;
  
  public function showLoginForm(Request $request)
  {
    if ($request->filled('token')) {
      $user = User::where('api_token', $request->token)->first();
      Auth::login($user, $remember = true);
      return redirect('/admin/home');
    }
    return view('auth.login');
  }
  
  /**
   * Create a new controller instance.
   *
   * @return void
   */
  public function __construct()
  {
    $this->middleware('guest')->except('logout');
  }
  
  public function logout()
  {
    Auth::logout();
    return redirect(config('frontendUrls.logout_url'));
  }
}
