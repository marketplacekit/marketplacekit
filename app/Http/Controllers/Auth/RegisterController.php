<?php

namespace App\Http\Controllers\Auth;

use App\Events\EmailVerified;
use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use Jrean\UserVerification\Traits\VerifiesUsers;
use Jrean\UserVerification\Facades\UserVerification;
use Illuminate\Http\Request;
use MetaTag;
use App\Models\Role;
class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;
    use VerifiesUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        $messages = [
            'indisposable' => __('Disposable email addresses are not allowed.'),
        ];
        return Validator::make($data, [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users', #indisposable
            'password' => 'required|string|min:6|confirmed',
            //'terms' => 'required',
        ], $messages);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Models\User
     */
    protected function create(array $data)
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);
    }

    protected function redirectTo() {
        return '/path';
    }

    public function showRegistrationForm()
    {
        MetaTag::set('title', __("Register"));
        session()->put('from', request('redirect')?:url()->previous());

        $roles = Role::get();
        $selectable_roles = [];
        foreach($roles as $role) {
            if($role->getMeta('selectable'))
                $selectable_roles[$role->id] = $role;
        }

        return view('auth.register', compact('selectable_roles'));
    }

    /**
     * Handle a registration request for the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request)
    {
        $this->validator($request->all())->validate();

        $user = $this->create($request->all());

        event(new Registered($user));

        $this->guard()->login($user);

        $user->username = $request->get('name');
        $user->save();

        UserVerification::generate($user);

        UserVerification::send($user, __('Welcome and Email Verification'));

        $user->assignRole('member'); //make a member
        if($request->has('role')) {
            $role = Role::find($request->input('role'));
            if($role->getMeta('selectable')) {
                $user->assignRole($role);
                $user->save();
            }
        }

        return $this->registered($request, $user)
            ?: redirect(route("email-verification.index"));
    }
}
