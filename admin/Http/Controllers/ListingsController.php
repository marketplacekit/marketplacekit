<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use App\Models\Listing;

class ListingsController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index(Request $request)
    {
        $listings = new Listing();
        if($request->get('q')) {
            $listings = $listings->search($request->get('q'));
        }
        $listings = $listings->orderBy('created_at', 'desc');
        $data['listings'] = $listings->paginate(10);

        return view('panel::listings.index', $data);
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
    public function edit($listing)
    {
        return redirect($listing->url);
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
    public function destroy($listing)
    {
        $listing->delete();

        alert()->success('Successfully deleted');
        return redirect()->route('panel.listings.index');
    }
}
