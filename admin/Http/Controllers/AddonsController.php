<?php

namespace App\Http\Controllers\Admin;

use App\Models\Page;
use App\Models\PageTranslation;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Kris\LaravelFormBuilder\FormBuilder;
use Module;
use Zipper;
class AddonsController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index(Request $request)
    {
        $data['modules'] = $this->getOrdered();
        if(!count($data['modules'])) {
            alert()->danger('You have no addons installed.');
        }
        #dd(setting('modules.homepage', 0));
        return view('panel::addons.index', $data);
    }

    public function getOrdered($direction = 'asc') : array
    {
        $modules = Module::all();

        uasort($modules, function ($a, $b) use ($direction) {
            if ($a->order == $b->order) {
                return 0;
            }
            if ($direction == 'desc') {
                return $a->order < $b->order ? 1 : -1;
            }
            return $a->order > $b->order ? 1 : -1;
        });
        return $modules;
    }

    public function toggle($module_alias, Request $request)
    {
        foreach(Module::all() as $v) {
            if($module_alias == $v->alias) {
                $module_name = $v->getName();
                break;
            }
        }
        $module = Module::find($module_name);

        if($module) {
            if($module->enabled())
                $module->disable();
            else
                $module->enable();
        }

        return redirect('/panel/addons');
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
        $data = [];
        return view('panel::addons.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function store(Request $request)
    {

        try {

            $addon_name = pathinfo(Zipper::make($request->file('addon'))->listFiles('/\module.json/i')[0], PATHINFO_DIRNAME);
            $addon_info = json_decode(Zipper::make($request->file('addon'))->getFileContent($addon_name.'/module.json'));

            Zipper::make($request->file('addon'))->folder($addon_name)->extractTo(base_path('Modules/'.$addon_name));

            alert()->success("Successfully added $addon_name addon");

        } catch (\Exception $e) {
            alert()->danger("Error: ". $e->getMessage());
            return redirect()->route('panel.addons.create');
        }

        alert()->success("Successfully added $addon_name addon");
        return redirect()->route('panel.addons.index');
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
    public function edit($id, FormBuilder $formBuilder)
    {
        $page = PageTranslation::findOrFail($id);
        $form = $formBuilder->create('Modules\Panel\Forms\PageForm', [
            'method' => 'PUT',
            'url' => route('panel.pages.update', $id),
            'model' => $page
        ]);
        return view('panel::pages.create', compact('form'));
    }

    /**
     * Update the specified resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function update(Request $request, $id)
    {
        $page = PageTranslation::findOrFail($id);
        $page->fill($request->all());
        $page->save();

        alert()->success('Successfully saved');
        return redirect()->route('panel.pages.index', ['locale' => $page->locale]);
    }

    /**
     * Remove the specified resource from storage.
     * @return Response
     */
    public function destroy()
    {
    }
}
