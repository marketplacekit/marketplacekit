@extends('panel::layouts.master')

@section('content')
    
    <h2>Roles <a href="/panel/roles/create" class="btn btn-link btn-xs"><i class="mdi mdi-plus"></i> Add new</a></h2>
    @include("panel::components.user_menu")
    @include('alert::bootstrap')

    <table class="table table-sm table-striped">
        <thead class="thead- border-0">
			<tr>
				<th scope="col" class="w-25 border-0">Role</th>
				<th scope="col" class="w-25 border-0"></th>
			</tr>
        </thead>
        <tbody>
        @foreach($roles as $item)
            <tr>
                <td>@if($item->id > 4)<a href="{{route('panel.roles.edit', $item)}}">{{$item->name}}</a>@else<span>{{$item->name}}</span>@endif</td>
                <td>{{$item->created_at}}</td>
				<td>
					@if($item->id > 4)
                    <a href="{{route('panel.roles.edit', $item)}}" class="text-muted float-right"><i class="fa fa-pencil"></i></a>
					@endif
                </td>
            </tr>
        @endforeach
		</tbody>
    </table>

@stop
