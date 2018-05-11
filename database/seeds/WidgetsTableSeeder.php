<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;
class WidgetsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('widgets')->delete();
        
        \DB::table('widgets')->insert(array (
            0 => 
            array (
                'id' => 1,
                'title' => 'Buy, Sell & Explore',
                'alignment' => 'center',
                'type' => 'hero',
                'locale' => 'en',
                'metadata' => '"What are you searching for? Start your search below, and don\'t forget, it\'s free to place a listing for sale with us!"',
                'background' => 'white',
                'position' => 0,
                'style' => NULL,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
            ),
            1 => 
            array (
                'id' => 2,
                'title' => 'Latest listings',
                'alignment' => NULL,
                'type' => 'latest_listings',
                'locale' => 'en',
                'metadata' => NULL,
                'background' => 'light',
                'position' => 1,
                'style' => NULL,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
            ),
            2 => 
            array (
                'id' => 3,
                'title' => 'Categories',
                'alignment' => 'center',
                'type' => 'category_listing',
                'locale' => 'en',
                'metadata' => NULL,
                'background' => 'white',
                'position' => 2,
                'style' => NULL,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
            ),
            3 => 
            array (
                'id' => 4,
                'title' => 'Popular listings',
                'alignment' => NULL,
                'type' => 'popular_listings',
                'locale' => 'en',
                'metadata' => NULL,
                'background' => 'light',
                'position' => 3,
                'style' => NULL,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
            ),
            4 => 
            array (
                'id' => 5,
                'title' => 'Why should you join us?',
                'alignment' => 'center',
                'type' => 'paragraph',
                'locale' => 'en',
                'metadata' => '[{"title":"For Sellers & Renters","icon":"store","blurb":"If you have anything to sell, equipment or even space to rent - list it with us for free and get some extra exposure","link":"\\/create","link_text":"Sell something"},{"title":"For Buyers","icon":"cart","blurb":"Explore a range of products from smartphones to rental properties and dog walking services","link":"\\/browse","link_text":"Browse products & services"},{"title":"For Professionals & Workers","icon":"axe","blurb":"Fitness instructor, dog walker? Advertise your services to our community and earn a little extra","link":"\\/create","link_text":"List your service"}]',
                'background' => 'white',
                'position' => 4,
                'style' => NULL,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
            ),
            5 => 
            array (
                'id' => 6,
                'title' => 'Featured categories',
                'alignment' => 'left',
                'type' => 'image_gallery',
                'locale' => 'en',
                'metadata' => '[{"title":"Nature","image":"https:\/\/source.unsplash.com\/618x221\/?nature","link":"\/browse","columns":"8"},{"image":"https:\/\/source.unsplash.com\/400x300\/?water","link":"\/browse?view=grid&distance=0&category=1","columns":"4"},{"image":"https:\/\/source.unsplash.com\/400x300\/?garden","link":"\/browse?view=list&distance=0&category=1","columns":"4"},{"image":"https:\/\/source.unsplash.com\/400x300\/?car","link":"\/browse?view=map&distance=0&category=1","columns":"4"},{"image":"https:\/\/source.unsplash.com\/400x300\/?bicycle","link":"\/listing\/Q81KoWKz3V\/my-first-listing","columns":"4"}]',
                'background' => 'light',
                'position' => 5,
                'style' => NULL,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
            ),
            6 => 
            array (
                'id' => 7,
                'title' => 'Featured listings
',
                'alignment' => NULL,
                'type' => 'featured_listings',
                'locale' => 'en',
                'metadata' => NULL,
                'background' => 'white',
                'position' => 6,
                'style' => NULL,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
            ),
            7 => 
            array (
                'id' => 8,
                'title' => 'Video',
                'alignment' => 'center',
                'type' => 'video',
                'locale' => 'en',
                'metadata' => '"https:\\/\\/www.youtube.com\\/watch?v=B7wkzmZ4GBw"',
                'background' => 'light',
                'position' => 7,
                'style' => NULL,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
            )
        ));
        
        
    }
}