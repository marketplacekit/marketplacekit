<?php

namespace App\Http\Controllers\Admin;

use App\Models\Menu;
use App\Models\Page;
use App\Models\PageTranslation;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Kris\LaravelFormBuilder\FormBuilder;
class MenuController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index(Request $request)
    {
        $data = [];
        $data['selected_lang'] = $request->get('locale', app('laravellocalization')->getDefaultLocale());
        $menu = Menu::where('locale', '=', $data['selected_lang'])->first();
        if(!$menu) {
            $menu = new Menu();
            $menu->locale = $data['selected_lang'];
            $menu->location = 'top';
            $menu->items = [];
        }
        $data['menu'] = $menu;

        return view('panel::menu.index', $data);
    }

    /**
     * Store a newly created resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        $menu_item = Menu::firstOrCreate(['locale' => $request->get('locale')]);
        $menu_item->location = 'top';
        $menu_item->locale = $request->get('locale');
        $menu_item->items = $request->get('items');
        $menu_item->save();

        #alert()->success('Successfully saved');
        return ['status' => 'true', 'redirect' => '/panel/menu?locale='.$menu_item->locale];

        #return redirect('panel/menu?locale='.$menu_item->locale);
    }

}
