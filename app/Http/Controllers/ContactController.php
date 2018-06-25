<?php
namespace App\Http\Controllers;

use App\Http\Requests\SendContactMessage;
use App\Mail\ContactUs;
use Illuminate\Http\Request;
use App\Models\Filter;
use App\Models\Listing;
use App\Models\Category;
use Grimzy\LaravelMysqlSpatial\Types\Point;
use Location;
use MetaTag;
use Mail;

class ContactController extends Controller {

	/**
	 * Returns all the blog posts.
	 *
	 * @return View
	 */
	public function index(Request $request)
	{
		$default_message = "";

		$data = [];

        $data['name'] = '';
        $data['email'] = '';
		$data['default_message'] = $default_message;

        MetaTag::set('title', __("Contact"));

        return view('contact', $data);
	}

	public function postIndex(SendContactMessage $request)
	{
        alert()->success( __("Thanks for your message. We'll get back to you shortly."));

        Mail::to(setting('email_address'))->send(new ContactUs($request->only('name', 'email_address', 'comment')));
        return redirect(route('contact'));

	}

}
