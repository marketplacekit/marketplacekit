<?php

namespace App\Http\Controllers\Auth;

use App\Events\EmailVerified;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use MetaTag;
use Socialite;
use Image;
use Storage;
use Auth;
use App\Models\User;
use Illuminate\Http\Request;

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
    protected $redirectTo = '/account';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function redirectToProvider()
    {
		config(['services.facebook.redirect' => \Request::root() .'/login/facebook/callback']);
        return Socialite::driver('facebook')->redirect();
    }

    /**
     * Obtain the user information from GitHub.
     *
     * @return \Illuminate\Http\Response
     */
    public function handleProviderCallback($provider = 'facebook')
    {
		config(['services.facebook.redirect' => \Request::root() .'/login/facebook/callback']);
        $user = Socialite::driver($provider)->user();
		
		$authUser = $this->findOrCreateUser($user, $provider);
        Auth::login($authUser, true);
		return redirect($this->redirectTo);
    }
	
	public function findOrCreateUser($user, $provider)
    {
        $local_user = User::where('provider', $provider)->where('provider_id', $user->id)->first();
        if ($local_user) {
            return $local_user;
        }

		$local_user = User::where('email', $user->email)->first();
		if ($local_user) {
			$local_user->provider = $provider;
			$local_user->provider_id = $user->id;
			$local_user->save();
            return $local_user;
        }
		
        $local_user = User::create([
            'name'     => $user->name,
            'email'    => $user->email,
            'provider' => $provider,
            'provider_id' => $user->id
        ]);
		
		if(is_null($local_user->getOriginal('avatar'))) {
			$image = file_get_contents($user->avatar);
			$image = Image::make($image)
                    ->fit(300, 300, function ($constraint) {
                        $constraint->aspectRatio();
                    })
                    ->resizeCanvas(300, 300);
            $path = "avatars/".$local_user->id.'.jpg';
            $avatar = \Storage::cloud()->put($path, (string) $image->encode('jpg', 75));
            $local_user->avatar = Storage::cloud()->url($path);
            $local_user->save();
		}
		
		$local_user->username = $user->name;
		$local_user->verified = true;
		$local_user->save();

        event(new EmailVerified($local_user));
        return $local_user;
    }

    protected function redirectTo()
    {
        #$this->redirectTo = session('back_url') ? session('back_url') : $this->redirectTo;
        return $this->redirectTo;
    }

    public function logout(Request $request)
    {
        $this->guard()->logout();
        $request->session()->forget(['from']);
        $request->session()->invalidate();

        return back();
    }

    public function showLoginForm(Request $request)
    {
        MetaTag::set('title', __("Login"));
        if(!session()->has('from')){
            session()->put('from', url()->previous());
        }
        return view('auth.login');
    }

    public function authenticated($request, $user)
    {
        if($user->verified) {
            $redirect_to = session()->pull('from', $this->redirectTo);
            $request->session()->forget(['from']);
        } else {
            return redirect()->route("email-verification.index");
        }
        return redirect($redirect_to);
    }

}
