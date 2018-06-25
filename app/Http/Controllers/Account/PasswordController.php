<?php

namespace App\Http\Controllers\Account;

use App\Http\Requests\StorePassword;
use App\Http\Controllers\Controller;
use App\Models\User;
use Hash;

class PasswordController extends Controller
{
    public function index()
    {
		$user = User::find(auth()->user()->id);
		return view('account.password', compact('user'));
    }

    public function store(StorePassword $request)
    {
        $user = User::find(auth()->user()->id);
        $user->password = Hash::make($request->get('password'));
        $user->save();
		alert()->success('Successfully saved!');
        return redirect(route('account.change_password.index'));
    }

}
