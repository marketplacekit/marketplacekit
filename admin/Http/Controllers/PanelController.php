<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use App\Models\Listing;
use App\Models\Category;
use Orchestra\Support\Facades\Table;
use Orchestra\Support\Facades\HTML;
use App\DataTables\ListingsDataTable;
class PanelController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index(Request $request)
    {
        if(auth()->user()->hasRole('moderator')) {
            return redirect('panel/users');
        }

        $listings = Listing::paginate(10);
        if($request->get('q')) {
            $listings = Listing::search($request->get('q'))->paginate(10);
        }

        $data['listings'] = $listings;
        $data['category_count'] = Category::count();

        return view('panel::index', $data);
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
        return view('panel::create');
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
        return view('panel::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @return Response
     */
    public function edit()
    {
        return view('panel::edit');
    }

    /**
     * Update the specified resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function update(Request $request)
    {
    }

    /**
     * Remove the specified resource from storage.
     * @return Response
     */
    public function destroy()
    {
    }
}
