@extends('panel::layouts.master')

@section('content')
	<h2>Pricing models <a href="{{route('panel.pricing-models.create')}}" class="btn btn-link btn-xs"><i class="mdi mdi-plus"></i> Add new</a></h2>

	@include("panel::components.settings_menu")

    @include('alert::bootstrap')
                  <table class="table">
                  	<thead>
                  		<tr>
                  			<th>#</th>
                  			<th>Title</th>
                  			<th></th>
                  		</tr>
                  	</thead>
                  	<tbody>

                        @foreach($pricing_models as $i => $pricing_model )
                  		<tr>
                  			<th scope="row">{{$i+1}}</th>
                  			<td>{{$pricing_model->name}}</td>
                  			<td><a href="{{ route('panel.pricing-models.edit', $pricing_model->id) }}">edit</a></td>
                  		</tr>
                        @endforeach
                  	</tbody>
                  </table>

@stop
