@extends('panel::layouts.master')

@section('content')
    <h2>Settings</h2>

    @include("panel::components.settings_menu")

    <script src="https://cdn.jsdelivr.net/npm/selectize@0.12.4/dist/js/standalone/selectize.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/selectize@0.12.4/dist/css/selectize.default.css" rel="stylesheet"/>

    <div class="row mb-5 pb-5">

        <div class="col-sm-10">
    @include('alert::bootstrap')
    {!! form($form)  !!}

            <script>
                $('#supported_locales').selectize({});
            </script>
</div>
</div>
@stop
