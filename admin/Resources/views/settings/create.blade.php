@extends('panel::layouts.master')

@section('content')
<div class="container">
    <a href="./" class="mb-1"><i class="fa fa-angle-left" aria-hidden="true"></i> back</a>

    <div class="row mb-3">
        <div class="col-sm-8">
            @if(!$form->getModel())
            <h2  class="mt-xxs">Adding new category</h2>
            @else
            <h2  class="mt-xxs">Editing category</h2>
            @endif
        </div>
        <div class="col-sm-4">

        </div>

    </div>

    <div class="row">

        <div class="col-sm-12">

          <div class="panel panel-default">
              <div class="panel-body">

                  {!! form_start($form)  !!}
                  {!! form_until($form, 'order')  !!}
                  <label for="Parent category" class="control-label">Parent category</label>
                  <div class="form-group">
                      {!! $dropdown  !!}
                  </div>
                  {!! form_rest($form)   !!}
                  {!! form_end($form, false)   !!}
</div>
</div>
</div>
@endsection
