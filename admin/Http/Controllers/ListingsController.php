<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use App\Models\Listing;
use App\Models\Category;
use Kris\LaravelFormBuilder\FormBuilder;
use Carbon\Carbon;

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
        } else {
			$listings = $listings->orderBy('created_at', 'desc');
        }
        $data['listings'] = $listings->paginate(100);
        $data['listings_count'] = Listing::count();

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

	public function duplicate($listing, Request $request)
    {
		
		$new_listing = $listing->replicate();
		$new_listing->title = $listing->title . '(COPY)';
		$new_listing->is_draft = 1;
		$new_listing->is_published = 0;
		$new_listing->is_admin_verified = now();
		$new_listing->priority_until = null;
		$new_listing->bold_until = null;
		$new_listing->views_count = null;
		$new_listing->expires_at = null;
		$new_listing->ends_at = null;
		$new_listing->ends_at = null;
		$new_listing->save();
		
		alert()->success('Duplicated listing');
        return redirect()->route('panel.listings.index');
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
    public function edit($listing, FormBuilder $formBuilder)
    {
		if(true) {
			$data = [];
			$data['listing'] = $listing;
			$data['dropdown'] = Category::attr(['name' => 'category_id', 'class'=>  'form-control'])
									->placeholder(0, '--SELECT--')
									->orderBy('order', 'ASC')
									->nested()
									->selected($listing->category_id)
									->renderAsDropdown();
			$data['form'] = $formBuilder->create(\Modules\Panel\Forms\ListingForm::class, [
				'method' => 'PUT',
				'url' => route('panel.listings.update', $listing),
				'model' => $listing
			]);			
			return view('panel::listings.edit', $data);
		}
        return redirect($listing->url);
    }

    /**
     * Update the specified resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function update($listing, Request $request)
    {
        
		$listing->category_id = $request->input('category_id');
        $listing->pricing_model_id = $request->input('pricing_model_id');
		if($request->has('currency'))
			$listing->currency = $request->input('currency');
		
		if (\DateTime::createFromFormat('Y-m-d H:i:s', $request->input('expires_at')) !== false) {
			$listing->expires_at = $request->input('expires_at');
		}		
		#dd($request->input('priority_until'));
		if (\DateTime::createFromFormat('Y-m-d H:i:s', $request->input('priority_until')) !== false) {
			$listing->priority_until = $request->input('priority_until');
		}
		#dev_dd($request->input('expires_at'));

        $listing->save();
		
		alert()->success('Successfully saved');
        return redirect()->route('panel.listings.index');
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
