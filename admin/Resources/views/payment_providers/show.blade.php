@extends('panel::layouts.master')

@section('content')
    <a href="{{ route('panel.payments.index') }}" class="mb-1"><i class="fa fa-angle-left" aria-hidden="true"></i> back</a>
<br />
<br />
    <h2>{{ $payment_provider->name  }}</h2>

    <div class="row mb-5 pb-5">

        <div class="col-sm-10">
            @include('alert::bootstrap')

            {!! Form::model($payment_provider, ['method' => 'put', 'route' => ['panel.payments.update',  $payment_provider->id]]) !!}
                <div class="form-group">
                    <label >Display name</label>
                    {{ Form::text('display_name', null, ['class' => 'form-control']) }}
                </div>
                <div class="form-group">
                    <label>Description</label>
                    {{ Form::textarea('description', null, ['class' => 'form-control', 'rows' => 3]) }}
                </div>
                <div class="form-group">
                    <label>Payment Instructions</label>
                    {{ Form::textarea('payment_instructions', null, ['class' => 'form-control', 'rows' => 3]) }}
                </div>
                <div class="form-group">
                    <label>Connection URL</label>
                    {{ Form::text('connection_url', null, ['class' => 'form-control']) }}
                    <small id="passwordHelpBlock" class="form-text text-muted">
                        Use this to connect to an external payment system
                    </small>
                </div>
                <div class="form-group form-check">
                    {{ Form::checkbox('is_enabled', 'value', true, ['class' => 'form-check-input', 'id' => 'is_enabled' ]) }}
                    <label class="form-check-label" for="is_enabled">Is enabled</label>
                </div>
                <button type="submit" class="btn btn-primary">Submit</button>
            {!! Form::close() !!}


        </div>
</div>
@stop
