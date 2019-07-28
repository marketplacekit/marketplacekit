@extends('panel::layouts.master')

@section('content')
    <a href="{{ route('panel.payments.index') }}" class="mb-1"><i class="fa fa-angle-left" aria-hidden="true"></i> back</a>

    <div class="row mb-3">
        <div class="col-sm-8">
            <h2  class="mt-xxs">Payment details</h2>
        </div>
        <div class="col-sm-4">

        </div>

    </div>

    <div class="row">

        <div class="col-sm-12">

          <div class="panel panel-default">
              <div class="panel-body">
					@if($payment_provider->connection_url)
												
						<div class="card mb-3">
							<div class="card-body bg-light">
								<div class="input-group mb-0">
									<div class="input-group-prepend">
										<span class="input-group-text" id="basic-addon1">API Secret</span>
									</div>
									<input type="text" class="form-control secret" value="{{ setting('marketplace_admin_secret') }}"><br />

								</div>
								<small id="passwordHelpBlock" class="form-text text-muted">
									Use this to connect to the Admin API
								</small>
							</div>
						</div>
						
					@endif
				
					{!! form($form)  !!}
              </div>
            </div>
    </div>

<style>
    .secret:hover, .secret:focus {
		color: #000;
		text-shadow: none;
	}    
	.secret {
		color: transparent;
		text-shadow: 0 0 8px rgba(0,0,0,0.5);
	}
</style>
@endsection
