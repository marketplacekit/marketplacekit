<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;

use App\Models\Filter;
use App\Models\Listing;
use App\Models\User;
use Grimzy\LaravelMysqlSpatial\Types\Point;
use Location;
use Mapper;
use Setting;
use MetaTag;

class ProfileController extends Controller
{

    protected $category_id;

    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index($user, Request $request)
    {
        $data = [];
        $data['listings'] = $user->listings()->with('user')->with('pricing_model')->orderBy('created_at', 'DESC')->active()->limit(5)->get();
        $data['profile'] = $user;

        MetaTag::set('title', $user->display_name);
        MetaTag::set('description', $user->bio);
        MetaTag::set('image', url($user->avatar));

        return view('profile.show', $data);
    }

    public function reviews($user, Request $request)
    {
        $data = [];
        $data['profile'] = $user;
        $data['comments'] = $user->comments()->with('commenter')->paginate(20);

        MetaTag::set('title', __(":name's reviews", ['name' => $user->display_name]));
        MetaTag::set('description', $user->bio);
        MetaTag::set('image', url($user->avatar));

        return view('profile.reviews', $data);
    }

    public function star($id) {
        if(!auth()->check()) {
            return false;
        }
        $listing = Listing::find($id);
        $listing->toggleFavorite();
        return view('listing::widgets.favorite', compact('listing'));
    }

    public function follow($user) {
        if(!auth()->check()) {
            return false;
        }


        auth()->user()->follow($user);

        return ['status' => true, 'following' => auth()->user()->followings()->get()];
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
        return view('listing::create');
    }

    /**
     * Store a newly created resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function store(Request $request)
    {
    }

    /**
     * Show the specified resource.
     * @return Response
     */
    public function show()
    {
        return view('listing::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @return Response
     */
    public function edit($id)
    {
        $data = [];
        /*$listing = Listing::find($id);
        $data['has_map'] = false;
        if($listing->location) {
            Mapper::map($listing->location->getLat(), $listing->location->getLng(), ['zoom' => 14, 'zoomControl' => false, 'streetViewControl' => false, 'mapTypeControl' => false, 'center' => true, 'marker' => true]);
            $data['has_map'] = true;
        }
        #dd($listing);

        $data['listing'] = $listing;
        $data['comments'] = $listing->comments()->orderBy('created_at', 'DESC')->limit(5)->get();


        $data = [];
        $data['listings_form'] = Setting::get('listings_form', []);
        $position = Location::get();*/
        $data['listings_form'] = Setting::get('listings_form', []);

        $listing = Listing::find($id);
        $data['listing'] = $listing;
        $categories = Category::nested()->get();
        $categories = flatten($categories, 0);
        $list = [];
        foreach($categories as $category) {
            $list[''] = '';
            $list[$category['id']] = str_repeat("&mdash;", $category['depth']+1) . " " .$category['name'];
        }

        $data['categories'] = $list;
        $data['pricing_models'] = [];
        foreach(config('pricing-models') as $price_option => $value) {
            $data['pricing_models'][$price_option] = $value['label'];
        }

        $selected_category = null;
        $selected_category = Category::find(request('category', $listing->category_id));
        $selected_pricing_model = null;
        $selected_pricing_model = config('pricing-models')[request('pricing_model_id',  $listing->pricing_model_id)]['short_label'];

        $data['selected_category'] = $selected_category;
        $data['selected_pricing_model'] = $selected_pricing_model;
        $data['form'] = 'edit';

        return view('create::details', $data);
    }



    /**
     * Update the specified resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function update(Request $request)
    {
        dd($request->all());
    }

    /**
     * Remove the specified resource from storage.
     * @return Response
     */
    public function destroy()
    {
    }
}
