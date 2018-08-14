@extends('panel::layouts.master')

@section('content')
    <h2>Settings</h2>

    @include("panel::components.settings_menu")

    <script src="https://cdn.jsdelivr.net/npm/selectize@0.12.4/dist/js/standalone/selectize.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/selectize@0.12.4/dist/css/selectize.default.css" rel="stylesheet"/>

    <div class="row mb-5 pb-5">

        <div class="col-sm-10">
    @include('alert::bootstrap')

            {!! form_start($form)  !!}
            {!! form_until($form, 'site_logo')  !!}
            <div>
                @if(setting('site_logo'))
                    <div class="card card-body bg-light mb-4 pt-2">
                    <div class="row">
                        <div class="col-sm-12">
                            <small class="text-muted mb-2">Current logo <a href="{{ route('panel.settings.remove', ['site_logo' => 1])  }}" class="text-danger ">(remove)</a></small><br />
                            <img src="{{ setting('logo') }}" class="mt-2" style="max-height: 120px; max-width: 200px;"/>

                        </div>
                        <div class="col-sm-6">

                        </div>
                    </div>
                    </div>
                @endif
            </div>
            {!! form_rest($form)   !!}
            {!! form_end($form, false)   !!}

            <script>
                $('#supported_locales').selectize({});
            </script>
</div>
</div>
@stop
