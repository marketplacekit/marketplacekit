@extends('panel::layouts.master')

@section('content')
    
    <h2>Users</h2>
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

    <table class="table table-sm table-striped">
        <thead class="thead- border-0">
        <tr>
            <th scope="col" class="w-25 border-0">Name</th>
            <th scope="col" class="w-25 border-0">Display name</th>
            <th scope="col" class="w-25 border-0">Email</th>
            <th scope="col" class="w-25 border-0">Date registered</th>
            <th scope="col"  class="w-25 border-0"></th>
        </tr>
        </thead>
        <tbody>
        @foreach($users as $item)
            <tr>
                <td>
                    {{str_limit($item->name, 40)}}
                    {!!  ($item->banned_at)?'<i class="text-muted">(banned)</i>':'' !!}
                </td>
                <td>{{$item->display_name}}</td>
                <td>{{$item->email}}</td>
                <td>{{$item->created_at}}</td>
                <td>
                    <a href="{{route('panel.users.edit', $item)}}" class="text-muted float-right"><i class="fa fa-pencil"></i></a>
                </td>
            </tr>
        @endforeach

        </tbody>
    </table>

    {{ $users->appends(app('request')->except('page'))->links() }}

@stop
