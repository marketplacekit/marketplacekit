<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use Jrean\UserVerification\Traits\VerifiesUsers;
use Jrean\UserVerification\Facades\UserVerification;
use LaravelLocalization;

class EmailVerificationController extends Controller
{
    use VerifiesUsers;

    protected $redirectIfVerified = '/?verified=true';
    protected $redirectAfterVerification = '/?already_verified=true';

    public function sendEmailVerification()
    {
        $locale = LaravelLocalization::getCurrentLocale();
        config(['user-verification.email.view' => "emails.$locale.email_verification"]);


        UserVerification::generate(auth()->user());
        UserVerification::send(auth()->user(), __('Email Verification'));
    }
}
