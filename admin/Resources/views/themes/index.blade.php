@extends('panel::layouts.master')

@section('content')

    <h2>Themes</h2>

    @include("panel::components.themes_menu")
    @include('alert::bootstrap')

    <div class="row" id="themes">
    @foreach($themes as $theme)
        <div class="col-md-4 col-lg-4 col-sm-3 mb-3">
            <div class="card" id="theme-{{ $theme->name }}">

                <img class="card-img-top" src="{{ $theme->thumbnail }}" alt="{{ $theme->name }} Theme">
                <div class="card-body">
                    <h6 class="card-title">{{ ucfirst($theme->name) }} @if($theme->name == Theme::get())<small class="badge badge-info  "><i>Active</i></small>@endif</h6>

                    <div class="row">
                        <div class="col-6">

                            <div class="pretty p-switch mt-3" ic-select-from-response="#themes" ic-target="#themes" ic-get-from="/panel/theme/{{ $theme->name }}/toggle">
                                <input type="checkbox" name="{{ $theme->name }}" value="{{ $theme->name }}" @if($theme->name == Theme::get()) checked @endif />
                                <div class="state p-success">
                                    <label></label>
                                </div>
                            </div>

                        </div>
                        <div class="col-6">
                            <a href="/?theme={{ $theme->name }}" target="_blank" class="btn btn-outline-primary btn-block">Preview</a>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    @endforeach

    </div>
@stop
