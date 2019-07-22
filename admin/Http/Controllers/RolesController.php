<?php

namespace App\Http\Controllers\Admin;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Role;
use Setting;
use App\DataTables\UsersDataTable;
use Kris\LaravelFormBuilder\FormBuilder;

class RolesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $data = [];
        $data['roles'] = Role::orderBy('id')->get();
        $roles = Role::get();

				#Setting::forget('selectable_roles');
				#Setting::save();
		#dev_dd(setting('selectable_roles'));
        return view('panel::roles.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(FormBuilder $formBuilder)
    {
        $form = $formBuilder->create('Modules\Panel\Forms\RolesForm', [
           'method' => 'POST',
           'url' => route('panel.roles.store')
        ]);
        return view('panel::roles.create', compact('form'));
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
		#dd($request->has('selectable'));
		$count = Role::where('name', $request->input('name'))->count();
		if($count) {
			alert()->success('This role has already been created.');
			return back();
		}
		$role = Role::create(['name' => $request->input('name')]);
		$role->syncPermissions($request->input('permissions'));

        $role->updateMeta('selectable', $request->has('selectable'));
		$role->save();
		
		alert()->success('Successfully saved');
        return redirect()->route('panel.roles.index');
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
    public function edit($id, FormBuilder $formBuilder)
    {
	    $role = Role::findOrFail($id);
		#dd($role);
        $form = $formBuilder->create('Modules\Panel\Forms\RolesForm', [
            'method' => 'PUT',
            'url' => route('panel.roles.update', $role),
            'model' => $role
        ]);
        return view('panel::roles.create', compact('form'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //	
		$role = Role::findOrFail($id);
		$role->name = $request->input('name');
		#$role = Role::create(['name' => $request->input('name')]);
		$role->syncPermissions($request->input('permissions'));
		$role->save();

        $role->updateMeta('selectable', $request->has('selectable'));


        alert()->success('Successfully saved');
        return redirect()->route('panel.roles.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($user)
    {
	#dd($user);
        $user->delete();

        alert()->success('Successfully deleted');
        return redirect()->route('panel.users.index');
    }
}
