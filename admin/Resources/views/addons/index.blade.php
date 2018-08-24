@extends('panel::layouts.master')

@section('content')

    <h2>Addons</h2>

    @include("panel::components.addons_menu")
    @include('alert::bootstrap')

    <div class="row">
    @foreach($modules as $module)
        <div class="col-md-4 col-lg-4 col-sm-3 mb-4">
            <div class="card" id="addon-{{ $module->alias }}">
                @if(module_enabled($module->alias))<a href="/panel/addons/{{ $module->alias }}">@endif
                    <img class="card-img-top border-bottom" @if(!module_enabled($module->alias))style="cursor: not-allowed;"@endif src="{{ $module->thumbnail }}" alt="{{ $module->name }}">
                @if(module_enabled($module->alias))</a>@endif
                <div class="card-body">
                    <h6 class="card-title">{{ $module->name }} @if(!$module->enabled())<small class="text-warning  "><i>Disabled</i></small>@endif</h6>
                    <p class="card-text" style="height: 60px">{{ $module->description }}</p>


                    <div class="row">
                        <div class="col-6">
                            <div class="pretty p-switch mt-3" ic-select-from-response="#addon-{{ $module->alias }}" ic-target="#addon-{{ $module->alias }}" ic-get-from="/panel/addon/{{ $module->alias }}/toggle">
                                <input type="checkbox" name="{{ $module->alias }}" value="{{ $module->enabled()?0:1 }}" @if($module->enabled()) checked @endif />
                                <div class="state p-success">
                                    <label></label>
                                </div>
                            </div>

                        </div>
                        <div class="col-6">
                            @if(module_enabled($module->alias))
                                <a href="/panel/addons/{{ $module->alias }}" class="btn btn-outline-primary btn-block">Settings</a>
                            @else
                                <button type="button" class="btn btn-outline-primary btn-block" disabled>Settings</button>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

        </div>
    @endforeach

    </div>
@stop
