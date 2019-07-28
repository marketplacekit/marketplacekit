@extends('panel::layouts.master')

@section('content')
    <a href="{{ route('panel.payments.index') }}" class="mb-1"><i class="fa fa-angle-left" aria-hidden="true"></i> back</a>

    <div class="row mb-3">
        <div class="col-sm-8">
            @if(!$form->getModel())
            <h2  class="mt-xxs">Adding custom payment method</h2>
            @else
            <h2  class="mt-xxs">Editing page</h2>
            @endif
        </div>
        <div class="col-sm-4">

        </div>

    </div>

    <div class="row">

        <div class="col-sm-12">

          <div class="panel panel-default">
              <div class="panel-body">

                  {!! form($form)  !!}
              </div>
            </div>
    </div>

@endsection
