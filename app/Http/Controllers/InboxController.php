<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreDirectMessage;
use App\Mail\ReceiveMessage;
use App\Models\Listing;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Kris\LaravelFormBuilder\FormBuilder;
use App\Models\User;
use App\Models\Message;
//use Nahid\Talk\Messages\Message;
use Nahid\Talk\Conversations\Conversation;
use Talk;
use Validator;
use Mail;
use App\Support\AdminNotification;

class InboxController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        $inboxes = Talk::getInbox();
        return view('inbox.index', compact('inboxes'));
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create(Request $request, FormBuilder $formBuilder)
    {
        //alert()->danger('You cannot send a message to yourself.');

        $user_id = $request->input('user_id');
        $listing_id = $request->input('listing_id');
        $message = $request->input('message', "");
        $recipient = User::find($user_id);

        if($listing_id && !$message) {
            $listing = Listing::find($listing_id);
            if($listing) {
                $message = __("I'm interested in :title", ['title' => $listing->title]);
            }
        }

        return view('inbox.create', compact('recipient', 'message'));
    }

    /**
     * Store a newly created resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        $params = $request->all();
        #return response('OK', 200)->header('X-IC-Redirect', '/create/r4W0J7ObQJ/edit#images_section');
        $validator = Validator::make($request->all(), [
            'message' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect(route('inbox.create', ['user_id' => $request->get('recipient_id')]))
                ->withErrors($validator)
                ->withInput();
        }

        if($request->get('recipient_id') == auth()->user()->id) {
            alert()->danger(__('Alert - You cannot send a message to yourself.'));
            return redirect(route('inbox.create', ['user_id' => $request->get('recipient_id')]))
                ->withErrors($validator)
                ->withInput();
        }

        Talk::sendMessageByUserId($request->get('recipient_id'), $request->get('message'));
        $conversationId = Talk::isConversationExists($request->get('recipient_id'));
        $user = User::find($request->get('recipient_id'));
        $user->increment('unread_messages');

        Mail::to($user->email)->send(new ReceiveMessage($user, auth()->user(), $conversationId));

		#Does message contain contact info?
        if(setting('messaging_monitor_contact_sharing')) {

            $message = $request->input('message');
            $message = preg_replace('/[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,4}/i','(phone hidden)', $message); // extract email
            $message = preg_replace('/(?:(?:\+?1\s*(?:[.-]\s*)?)?(?:\(\s*([2-9]1[02-9]|[2-9][02-8]1|[2-9][02-8][02-9])\s*\)|([2-9]1[02-9]|[2-9][02-8]1|[2-9][02-8][02-9]))\s*(?:[.-]\s*)?)?([2-9]1[02-9]|[2-9][02-9]1|[2-9][02-9]{2})\s*(?:[.-]\s*)?([0-9]{4})(?:\s*(?:#|x\.?|ext\.?|extension)\s*(\d+))?/','(email hidden)', $message);

            if($message != $request->input('message')) {
                AdminNotification::notify(
                    "Contact Sharing",
                    "The user $user->email has shared his contact details using direct message."
                );
            }
        }

        return redirect(route('inbox.show', $conversationId));
    }

    /**
     * Show the specified resource.
     * @return Response
     */
    public function show($id)
    {

        session(['conversation_id' => $id]);
        #$inboxes = Talk::getInbox();
        $message_count = Message::where('conversation_id', $id)->count();
        $limit = 2000;
        $offset = max($message_count-$limit,0);
        $conversations = Talk::getConversationsAllById($id, $offset, $limit);

        $messages = $conversations->messages;
        $recipient = $conversations->withUser;

        #mark as seen
        foreach($messages as $message) {
            if(!$message->is_seen && auth()->user()->id != $message->sender->id) {
                Talk::makeSeen($message->id);
                auth()->user()->update(['unread_messages' => \DB::raw('GREATEST(unread_messages-1, 0)')]);
            }
        }

        return view('inbox.show', compact('messages', 'recipient'));
    }

    /**
     * Show the form for editing the specified resource.
     * @return Response
     */
    public function edit()
    {
        return view('inbox.edit');
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
