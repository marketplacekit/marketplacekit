<?php

namespace App\Http\Controllers\Account;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Http\Requests\UpdateUserProfile;
use Image;
use Storage;
class ProfileController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
		$user = auth()->user();
		return view('account.profile', compact('user'));
    }

    public function store(UpdateUserProfile $request)
    {
        $user = auth()->user();
        if($request->file('image')) {
            $image = Image::make($request->file('image'))
                    ->fit(300, 300, function ($constraint) {
                        $constraint->aspectRatio();
                        $constraint->upsize();
                    })
                    ->resizeCanvas(300, 300);
            Storage::cloud()->put('avatars/'.$user->path, (string) $image->encode());
            $user->avatar = Storage::cloud()->url("avatars/".$user->path);
            $user->save();
        }
        $user->fill($request->except('email'))->save();
		alert()->success(__('Successfully saved!'));
        return redirect(route('account.edit_profile.index'));
    }

}
