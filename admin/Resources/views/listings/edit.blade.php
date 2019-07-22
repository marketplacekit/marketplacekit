@extends('panel::layouts.master')

@section('content')
<div class="container">
    <a href="{{ route('panel.listings.index') }}" class="mb-1"><i class="fa fa-angle-left" aria-hidden="true"></i> back</a>

    <div class="row mb-3">
        <div class="col-sm-8">
            <h2 class="mt-xxs">Editing Listing </h2> <a href="{{ $listing->url }}" class=" btn btn-link btn-xs">(View/edit details)</a>
        </div>
        <div class="col-sm-4">
        </div>

    </div>

    <div class="row">

        <div class="col-sm-12">

          <div class="panel panel-default">
				<div class="panel-body">
					@include('alert::bootstrap')
					{!! form_start($form)  !!}
					{!! form_until($form, 'title')  !!}
					<label for="Category" class="control-label">Category</label>
					<div class="form-group">
						{!! $dropdown  !!}
					</div>
					{!! form_rest($form)   !!}
					{!! form_end($form, false)   !!}
				</div>
			</div>
		</div>
	</div>
	
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
	<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
	
	<script>
		$(".date-picker").flatpickr({
			dateFormat: "Y-m-d H:i:S",
		});
	</script>

@endsection
