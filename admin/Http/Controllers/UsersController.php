<?php

namespace App\Http\Controllers\Admin;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\DataTables\UsersDataTable;
use Kris\LaravelFormBuilder\FormBuilder;

class UsersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $users = new User;
        if($request->get('q')) {
            $users = User::where('name', 'like', "%{$request->get('q')}%")
                        ->orWhere('email', 'like', "%{$request->get('q')}%");
        }

        $data['users'] = $users->orderBy('created_at', 'DESC')->paginate(10);

        return view('panel::users.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($user, FormBuilder $formBuilder)
    {

        $form = $formBuilder->create('Modules\Panel\Forms\UserForm', [
            'method' => 'PUT',
            'url' => route('panel.users.update', $user),
            'model' => $user
        ]);
        return view('panel::users.create', compact('form'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $user)
    {
        //
        $validatedData = $request->validate([
            'email' => 'unique:users,email,' . $user->id . ',id',
        ]);

        if($user->is_admin && $request->get('is_banned')) {
            alert()->danger('Cannot ban admin');
            return back();
        }

        if($user->is_admin && $user->id == 1 && !$request->get('is_admin', 0)) {
            alert()->danger('Cannot remove superadmin');
            return back();
        }

        $user->fill($request->all());

        $user->is_admin = $request->get('is_admin', 0);
        if($request->get('is_banned')) {
            $user->ban();
        } else {
            $user->unban();
        }
        $user->save();

        alert()->success('Successfully saved');

        return redirect()->route('panel.users.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
