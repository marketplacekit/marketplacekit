<?php

namespace App\Http\Controllers;

use Grimzy\LaravelMysqlSpatial\Types\LineString;
use Grimzy\LaravelMysqlSpatial\Types\Polygon;
use Illuminate\Http\Request;
use App\Models\Filter;
use App\Models\Listing;
use App\Models\Category;
use App\Models\User;
use Grimzy\LaravelMysqlSpatial\Types\Point;
use Setting;
use MetaTag;
use GeoIP;

class BrowseController extends Controller
{

    public function listings(Request $request) {
        #save address in session
        foreach($request->only(['lat', 'lng', 'bounds', 'location']) as $k => $v) {
            session([$k => $v]);
        }

        $data = $this->getListingData($request);
        if($request->get('ajax')) {
            return response()->json($data);
        }
        MetaTag::set('title', __("Browse listings"));

        return view('browse.index', $data);
    }

    public function getFacets() {
        /*
            listing fields editor must have category selector

            inputFilter     ->  anything
            refinementList  ->  get list of values
            menuSelect      ->  get list of values
            rangeSlider     ->  min, max
            priceRange      ->  min, max

            go through listing fields
                get searchUI
                check categories
        */
        $filters = Filter::where('is_hidden', 0)->where('is_searchable', 1)->orderBy('position', 'ASC')->get();
        $facet_groups = [];
        foreach($filters as $filter) {
            $facet_group = [];
            $facet_group['field'] = $filter->field;
            $facet_group['name'] = $filter->name;
            $facet_group['search_ui'] = $filter->search_ui;
            $listings = new Listing();
            if(in_array($filter->search_ui, ['refinementList', 'menuSelect'])) {
                $facet_group['options'] = [];
                if($filter->form_input_meta && isset($filter->form_input_meta['values'])) {
                    foreach($filter->form_input_meta['values'] as $k => $v) {
                        $tmp = [];
                        $tmp['name'] = $v['label'];
                        $tmp['value'] = $v['value'] ;
                        $facet_group['options'][] = $tmp;
                    }

                } else {
                    $listings = $listings->groupBy('meta->'.$filter->field)
                                    ->whereNotNull('meta->'.$filter->field.'')
                                    ->select('meta->'.$filter->field.' as name', 'meta->'.$filter->field.' as value', \DB::raw('count(*) as total'))
                                    ->orderBy('meta->'.$filter->field, 'ASC')
                                    ->get();

                    $facet_group['options'] = $listings->toArray();
                }
                
            } else if(in_array($filter->search_ui, ['rangeSlider', 'priceRange'])) {
                $min = $listings->min('meta->'.$filter->field);
                $max = $listings->max('meta->'.$filter->field);
                $facet_group['options'] = [$min, $max];
            } else {
                $facet_group['options'] = null;
            }
            $facet_groups[$filter->field] = json_decode(json_encode($facet_group));
        }

        return $facet_groups;
    }

    public function getListingData(Request $request) {

        $data = [];
        $data['facets'] = $this->getFacets();

        $listings = new Listing();
        $listings = $listings->active();

        //search by title, description, tags
        if($request->get('q')) {
            $listings = $listings->search($request->get('q'));
            #dd(debug_backtrace ());
        }
        if($request->get('price_min')) {
            $listings = $listings->where('price', '>=', (int) $request->get('price_min'));
        }
        if($request->get('price_max')) {
            $listings = $listings->where('price', '<=', (int) $request->get('price_max'));
        }

        $listings = $listings->with('pricing_model')->with('user');

        $filters = Filter::orderBy('position', 'ASC')->where('is_hidden', 0)->where('is_default', 0)->get();
        $is_filtered = false;
        if($request->has('category')) {
            $is_filtered = true;
        }

        foreach($filters as $filter) {
            if($filter->default){
                continue;
            }
            if(in_array($filter->search_ui, ['menuSelect']) && $request->input($filter->field)) {
                $listings = $listings->where('meta->' . $filter->field, $request->input($filter->field));
                $is_filtered = true;
            } elseif(in_array($filter->search_ui, ['refinementList']) && $request->input($filter->field)) {

                $filter_values = collect($request->input($filter->field))->filter(function ($value, $key) {
                    return $value == 1;
                })->keys();

                $listings->where(function ($query) use($filter_values, $filter) {
                    foreach($filter_values as $filter_value) {
                        $filter_value = urldecode($filter_value);
                        $filter_value = trim($filter_value,'"');
                        $query->orWhereRaw("JSON_CONTAINS(meta, '".addslashes(json_encode([$filter->field => $filter_value]))."')");
                    }
                });
                $is_filtered = true;
            } elseif(in_array($filter->search_ui, ['rangeSlider', 'priceRange'])) {
                if($request->input($filter->field.'_min')) {
                    $listings = $listings->where('meta->'.$filter->field, '>=', (int) $request->input($filter->field.'_min'));
                    $is_filtered = true;
                }
                if($request->input($filter->field.'_max')) {
                    $listings = $listings->where('meta->'.$filter->field, '<=', (int) $request->input($filter->field.'_max'));
                    $is_filtered = true;
                }


            } else {
                if($request->input($filter->field)) {
                    $listings = $listings->where('meta->' . $filter->field, $request->input($filter->field));
                    $is_filtered = true;
                }
            }
        }

        $category_id = $request->get('category', 0) ? :0; //get the category

		//get listings with category and child categories
        $full_categories = Category::all();
        $categories = $this->getSearchableCategories($full_categories, $category_id); //get all child categories

        $listings = $listings->whereIn('category_id', $categories);
        $listings = $listings->whereNotNull('lat');
        $listings = $listings->whereNotNull('lng');
        $listings = $listings->whereNotNull('location');

        $this->categories = $categories;
        $category = Category::find($category_id); //current category

        $level_categories = Category::where('parent_id', $category_id)->get(); //categories on this level
        $parent_categories = $this->getParentCategories($category_id);
        if(count($level_categories) == 0 && $category) {
            $level_categories = Category::where('parent_id', $category->parent_id)->get();
            $parent_categories = Category::whereIn('id', $parent_categories)->get();
        } else {
            $parent_categories = Category::whereIn('id', array_merge([$category_id], $parent_categories))->get();
        }
        $data['parent_categories'] = $parent_categories;
        $data['level_categories'] = $level_categories;
        $data['category'] = $category;
        $data['category_id'] = $category_id;



        //distance calculations
        $lat = $request->get('lat') ? : GeoIP::getLatitude();
        $lng = $request->get('lng') ? : GeoIP::getLongitude();
        if($request->get('bounds') || ( $request->get('lat') && $request->get('lng') )) {
            $bounds = $request->get('bounds');
            $bounds = explode(",", $bounds);

            if(count($bounds) == 4) {
                $swLat = $bounds[0];
                $swLon = $bounds[1];
                $neLat = $bounds[2];
                $neLon = $bounds[3];

                $southWest = new \Geokit\LatLng($swLat, $swLon);
                $northEast = new \Geokit\LatLng($neLat, $neLon);
                $bounds = new \Geokit\Bounds($southWest, $northEast);

                if($request->input('distance') && (int) $request->input('distance') >= 0) {

                    $math = new \Geokit\Math();
                    $expandedBounds = $math->expand($bounds, $request->input('distance').config('marketplace.distance_unit'));

                    $swLat = $expandedBounds->getSouthWest()->getLatitude();
                    $swLon = $expandedBounds->getSouthWest()->getLongitude();
                    $neLat = $expandedBounds->getNorthEast()->getLatitude();
                    $neLon = $expandedBounds->getNorthEast()->getLongitude();
                }

                $polygon = new Polygon([new LineString([
                    new Point($swLat, $swLon),
                    new Point($neLat, $swLon),
                    new Point($neLat, $neLon),
                    new Point($swLat, $neLon),
                    new Point($swLat, $swLon),
                ])]);
                if($request->input('distance') >= 0) {
                    $listings = $listings->within('location', $polygon);
                }

            } else {
                if($request->get('distance')) {
                    $listings = $listings->distanceSphere($request->get('distance', 1000)*1000, new Point($lat, $lng), 'location');
                }
            }

            $listings = $listings->distanceSphereValue('location', new Point($lat, $lng));
        }

        $data['view'] = $request->get('view', setting('default_view', 'map'));
        $data['filters'] = $filters;
        $data['lat'] = $lat;
        $data['lng'] = $lng;

        $sort = $request->input('sort')?:'date';
        if($sort == 'date') {
            $listings = $listings->orderBy('created_at', 'DESC');
        }
        if($sort == 'price_lowest_first') {
            $listings = $listings->orderBy('price', 'ASC');
        }
        if($sort == 'price_highest_first') {
            $listings = $listings->orderBy('price', 'DESC');
        }
        if($sort == 'distance') {
            $listings = $listings->orderBy('distance', 'ASC');
        }

        if($request->get('ajax')) {
            $data['map_listings'] =  $listings->whereNotNull('lat')->whereNotNull('lng')->limit(1000)->get();
        }

        $data['params'] = $request->all();
        $data['sort'] = $sort;

        #dd($listings->get());
        $data['listings'] = $listings->paginate(24);
        $data['is_filtered'] = $is_filtered;

        $data['load_time'] = round(microtime(true) - LARAVEL_START);
        return $data;
    }

    private function getParentCategories($category_id, $parents = []) {
        $category = Category::find($category_id);
        if($category) {
            $parents[] = $category->parent_id;
            if($category->parent_id) {
                $parents = $this->getParentCategories($category->parent_id, $parents);
            }
        }
        return $parents;
    }

    private function getSearchableCategories($full_categories, $category_id, $level = null) {

        $categories = $full_categories->where('parent_id', (int) $category_id)->pluck('id')->all();

        foreach($categories as $category) {
            if(!$level) {
                $children = $this->getSearchableCategories($full_categories, $category);
                $categories = array_merge($categories, $children);
            }
        }
        $categories = array_unique($categories);

        //current category
        $categories[] = $category_id;
        return $categories;
    }

}
