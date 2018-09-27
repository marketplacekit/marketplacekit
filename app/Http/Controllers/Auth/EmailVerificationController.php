<?php

namespace App\Http\Controllers\Auth;

use App\Events\EmailVerified;
use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use Jrean\UserVerification\Traits\VerifiesUsers;
use Jrean\UserVerification\Facades\UserVerification;
use LaravelLocalization;
use MetaTag;
use Illuminate\Http\Request;
use Jrean\UserVerification\Facades\UserVerification as UserVerificationFacade;
use Jrean\UserVerification\Exceptions\UserNotFoundException;
use Jrean\UserVerification\Exceptions\UserIsVerifiedException;
use Jrean\UserVerification\Exceptions\TokenMismatchException;
class EmailVerificationController extends Controller
{
    use VerifiesUsers;

    protected $redirectIfVerified = '/?verified=true';
    protected $redirectAfterVerification = '/?already_verified=true';

    /**
     * Handle the user verification.
     *
     * @param  string  $token
     * @return \Illuminate\Http\Response
     */
    public function getVerification(Request $request, $token)
    {
        if (! $this->validateRequest($request)) {
            return redirect($this->redirectIfVerificationFails());
        }

        try {
            $user = UserVerificationFacade::process($request->input('email'), $token, $this->userTable());
        } catch (UserNotFoundException $e) {
            return redirect($this->redirectIfVerificationFails());
        } catch (UserIsVerifiedException $e) {
            return redirect($this->redirectIfVerified());
        } catch (TokenMismatchException $e) {
            return redirect($this->redirectIfVerificationFails());
        }

        if (config('user-verification.auto-login') === true) {
            auth()->loginUsingId($user->id);
        }

        event(new EmailVerified($user));
        return redirect($this->redirectAfterVerification());
    }

    public function sendEmailVerification()
    {
        $locale = LaravelLocalization::getCurrentLocale();
        config(['user-verification.email.view' => "emails.$locale.email_verification"]);


        UserVerification::generate(auth()->user());
        UserVerification::send(auth()->user(), __('Email Verification'));
    }

    public function redirectAfterVerification()
    {
        return route('email-verification.verified');
    }

    public function redirectIfVerified()
    {
        return route('email-verification.verified');
    }

    public function verified() {
        $url = session('from', route('home'));
        return view('auth.email_verified', compact('url'));
    }
    public function index() {

        if(auth()->user()->verified) {
            return redirect()->route("account.edit_profile.index");
        }

        MetaTag::set('title', __("Email Verification"));
        if(!session()->has('from')){
            session()->put('from', url()->previous());
        }
        return view('auth.email_verification');
    }

    public function resend(Request $request) {

        if(auth()->user()->verified) {
            alert()->success(__('Successfully verified.'));
            return redirect()->route("account.edit_profile.index");
        }

        $can_resend = true;
        if($request->session()->has('user_verification_resend')){
            if(time() - $request->session()->get('user_verification_resend') < 5 * 60) {
                $can_resend = false;
            }
        }
        #$can_resend = true;
        #regenerate and resend
        if ($can_resend) {
            session(['user_verification_resend' => time()]);
            UserVerification::generate(auth()->user());
            UserVerification::send(auth()->user(), __(':site_name verification code', ['site_name' => setting('site_name')]));
            alert()->success(__('Verification email was resent.'));
        } else {
            alert()->danger(__('Please wait up to 5 minutes before requesting a new verification email.'));
        }

        return redirect()->route("email-verification.index");
    }

}
