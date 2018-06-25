@extends('panel::layouts.master')

@section('content')
    <?/*<a href=""><i class="fa fa-angle-left"></i> Back</a>*/?>
    <h1>Listings</h1>
    <br />

{!! Form::open(['url' => url()->current(), 'method' => 'GET']) !!}
    <div class="input-group mb-3">
      {{Form::text('q', request('q'), ['class' => 'form-control', 'placeholder' => "Search..."])}}
      <div class="input-group-append">
        <button class="btn btn-secondary" type="submit">Search</button>
      </div>
    </div>
{!! Form::close() !!}

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
        <td>{{str_limit($item->title, 40)}}</td>
        <td>{{$item->user->email}}</td>
        <td>
            <a href="" class="text-muted float-right ml-2"><i class="fa fa-remove"></i></a>
            <a href="" class="text-muted float-right"><i class="fa fa-pencil"></i></a>
        </td>
      </tr>
      @endforeach

  </tbody>
</table>

{{ $listings->appends(app('request')->except('page'))->links() }}

@stop
