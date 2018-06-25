<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;
class MenusTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('menus')->delete();
        
        \DB::table('menus')->insert(array (
            
            array (
                'id' => 3,
                'locale' => 'en',
                'location' => 'top',
                'items' => '[{"url":"\\/pages\\/about","title":"About","position":2},{"url":"\\/pages\\/help","title":"Help","position":3},{"url":"\\/contact","title":"Contact","position":4}]',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
            ),
        ));
        
        
    }
}