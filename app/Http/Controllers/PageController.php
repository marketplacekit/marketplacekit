<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Filter;
use App\Models\Listing;
use App\Models\Category;
use App\Models\PageTranslation;
use App\Models\Page;
use Grimzy\LaravelMysqlSpatial\Types\Point;
use Location;
use App;
use MetaTag;

class PageController extends Controller
{

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //$this->middleware('auth');
    }


    public function index($slug, Request $request)
    {


        $locale = App::getLocale();
        $page = PageTranslation::where('slug', $slug)->where('locale', $locale)->first();
        if(!$page) {
            return abort(404);
        }

        $data = [];
        $data['page'] = $page;

        //MetaTag::set('title', $page->title);
        //MetaTag::set('description', $page->content);
        MetaTag::set('title', $page->seo_title?$page->seo_title:$page->title);
        MetaTag::set('description', $page->seo_meta_description);
        MetaTag::set('keywords', $page->seo_meta_keywords);

        return view('page', $data);
    }


}
