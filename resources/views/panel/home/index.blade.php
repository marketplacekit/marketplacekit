@extends('panel::layouts.master')

@section('content')
    
    <h2>Homepage Widgets <a href="{{route('panel.home.create')}}" class="btn btn-link btn-xs"><i class="mdi mdi-plus"></i> Add widget</a></h2>
    <p>Your home page is made up of widgets. Add a widget or edit a widget to change the appearance.</p>
    @include('alert::bootstrap')
    <div class="btn-group" role="group" aria-label="Button group with nested dropdown">
        <div class="btn-group" role="group">
            <button id="btnGroupDrop1" type="button" class="btn btn-secondary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                {{app('laravellocalization')->getSupportedLocales()[$selected_lang]['name']}}
            </button>
            <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                @foreach(LaravelLocalization::getSupportedLocales() as $localeCode => $properties)
                    <a class="dropdown-item" href="?locale={{ $localeCode }}">{{ $properties['native'] }}</a>
                @endforeach
            </div>
        </div>
    </div>
    <br />
    <br />
    <table class="table table-sm table-striped">
        <thead class="thead- border-0">
        <tr>
            <th>Title</th>
            <th>Widget Type</th>
            <th></th>
        </tr>
        </thead>
        <tbody>
        @foreach($widgets as $i => $widget )
            <tr>
                <td>{{$widget->title}} {{$widget->is_hidden?'(hidden)':''}}</td>
                <td>{{$form_ui[$widget->type]}}</td>
                <td>
                    <a href="" class="text-muted float-right ml-2"><i class="fa fa-remove"></i></a>
                    <a href="{{route('panel.home.edit', $widget->id)}}" class="text-muted float-right"><i class="fa fa-pencil"></i></a>
                </td>
            </tr>
        @endforeach

        </tbody>
    </table>


@stop
