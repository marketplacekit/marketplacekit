@extends('panel::layouts.master')

@section('content')
    
    <h2>{{$listings_count}} Listings</h2>
    <br />
    @include('alert::bootstrap')


{!! Form::open(['url' => url()->current(), 'method' => 'GET']) !!}
    <div class="input-group mb-3">
      {{Form::text('q', request('q'), ['class' => 'form-control', 'placeholder' => "Search..."])}}
      <div class="input-group-append">
        <button class="btn btn-secondary" type="submit">Search</button>
      </div>
    </div>
{!! Form::close() !!}

    @if(module_enabled('moderatelistings'))
    <a href="{{route('panel.addons.moderatelistings.index')}}" class="float-right small"><i class="fa fa-user-secret" aria-hidden="true"></i> Moderate Listings</a>
    @endif

<table class="table table-sm table-striped">
  <thead class="thead- border-0">
    <tr>
      <th scope="col"  class="w- border-0"></th>
      <th scope="col" class="w-50 border-0">Title</th>
      <th scope="col" class="w-25 border-0">User</th>
      <th scope="col"  class="w-25 border-0"></th>
    </tr>
  </thead>
  <tbody>
      @foreach($listings as $item)
      <tr>
        <th scope="row">{{$item->id}}</th>
        <td><a href="{{route('panel.listings.edit', $item)}}">{{str_limit($item->title, 40)}}</a> @if($item->is_draft)<small class="badge badge-secondary">draft</small>@endif <br />@if($item->expires_at)<small class="text-muted">Expires {{$item->expires_at}}</small>@endif @if($item->ends_at)<small class="text-muted">Ends {{$item->ends_at}}</small>@endif</td>
        <td>{{@$item->user->email}}</td>
        <td>
            <a href="#" ic-target="#main" ic-select-from-response="#main" ic-delete-from="{{ route('panel.listings.destroy', $item) }}" ic-confirm="Are you sure?" class="text-muted float-right ml-2"><i class="fa fa-remove"></i></a>
			<a href="{{route('panel.listings.duplicate', $item)}}" class="text-muted float-right ml-2" title="Duplicate listing"><i class="fa fa-files-o" aria-hidden="true"></i></a>
            <a href="{{route('panel.listings.edit', $item)}}" class="text-muted float-right"><i class="fa fa-pencil"></i></a>
        </td>
      </tr>
      @endforeach

  </tbody>
</table>

{{ $listings->appends(app('request')->except('page'))->links() }}

@stop
