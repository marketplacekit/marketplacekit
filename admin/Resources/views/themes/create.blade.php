@extends('panel::layouts.master')

@section('content')

    <h2>Themes</h2>
    @include("panel::components.themes_menu")

    <div class="row mb-3">
        <div class="col-sm-8">
            <p>If you have a theme in zip format you can upload it here.</p>
        </div>
        <div class="col-sm-4">

        </div>

    </div>

    <div class="row">

        <div class="col-sm-6">
            @include('alert::bootstrap')

          <div class="panel panel-default">
              <div class="panel-body">

                  {{ Form::open(array('route' => 'panel.themes.store', 'files' => true)) }}

                  <div class="input-group mb-3">
                      <div class="input-group-prepend">
                          <span class="input-group-text"><i class="fa fa-paint-brush" aria-hidden="true"></i></span>
                      </div>
                      <div class="custom-file">
                          <input type="file" class="custom-file-input" name="theme" accept=".zip" id="inputGroupFile01">
                          <label class="custom-file-label" for="inputGroupFile01">Choose file</label>
                      </div>
                  </div>

                  {{ Form::submit('Upload now', ['class' => 'btn btn-primary']) }}

                  {{ Form::close() }}

              </div>
            </div>
    </div>

        <script>
            $('.custom-file-input').on('change', function() {
                var fileName = $(this).val().split('\\').pop();
                $(this).siblings('.custom-file-label').addClass("selected").html(fileName);
            });
        </script>
@endsection
