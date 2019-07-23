<?php

namespace App\Http\Controllers\Account;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Http\Requests\UpdateUserProfile;
use Image;
use Storage;
use GeoIP;
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
		$countries = [null=> 'Country...'] + json_decode(file_get_contents(resource_path("data/country.json")), true);

		$lat = GeoIP::getLatitude();
		$lng = GeoIP::getLongitude();

		return view('account.profile', compact('user', 'countries', 'lat', 'lng'));
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
		
		if($request->input('lat') && $request->input('lng')) {
            $lat = $request->input('lat');
            $lng = $request->input('lng');
            $user->location = \DB::raw("(GeomFromText('POINT($lat $lng)'))");
            $user->address = $request->input('location');
            if($request->input('country')) {
                $user->country = $request->input('country');
            }
            $user->save();
        }
		
		if($request->has('filters')) {
			foreach($request->input('filters') as $k => $v) {
				$user->filters[$k] = $v;
			}
			$user->save();
		}
		
		alert()->success(__('Successfully saved!'));
        return redirect(route('account.edit_profile.index'));
    }

}
