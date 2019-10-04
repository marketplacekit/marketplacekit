@extends('panel::layouts.master')

@section('content')

    <h2>Content <a href="{{route('panel.pages.create', ['locale' => request()->input('locale')])}}" class="btn btn-link btn-xs"><i class="mdi mdi-plus"></i> Add page</a></h2>

    @include("panel::components.content_menu")
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
    <table class="table table-sm table-striped">
        <thead class="thead- border-0">
        <tr>
            <th scope="col" class="w-50 border-0">Title</th>
            <th scope="col" class="w-25 border-0">Slug</th>
            <th scope="col"  class="w-25 border-0"></th>
        </tr>
        </thead>
        <tbody>
        @foreach($pages as $item)
            <tr>
                <td>{{str_limit($item->title, 40)}} @if(!$item->visible)<i class="small text-muted">(hidden)</i>@endif</td>
                <td>{{$item->slug}}</td>
                <td>
                    <a href="" ic-target="#main" ic-select-from-response="#main" ic-delete-from="{{ route('panel.pages.destroy', $item->id) }}" ic-confirm="Are you sure?" class="text-muted float-right ml-2"><i class="fa fa-remove"></i></a>
                    <a href="{{route('panel.pages.edit', $item->id)}}" class="text-muted float-right"><i class="fa fa-pencil"></i></a>
                </td>
            </tr>
        @endforeach

        </tbody>
    </table>

    {{ $pages->appends(app('request')->except('page'))->links() }}

@stop
