@extends('panel::layouts.master')

@section('content')
    <a href="{{ route('panel.menu.index') }}" class="mb-1"><i class="fa fa-angle-left" aria-hidden="true"></i> back</a>

    <div class="row mb-3">
        <div class="col-sm-8">
            @if(!$form->getModel())
            <h2  class="mt-xxs">Adding menu item</h2>
            @else
            <h2  class="mt-xxs">Editing menu item</h2>
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
                  {!! form_rest($form)   !!}
                  {!! form_end($form, false)   !!}
              </div>
            </div>
    </div>

        <script type="text/javascript">

            $(document).on('turbolinks:load', function() {
                tinymce.init({
                    selector: '[name=content]',
                    height: 500,
                    menubar: false,
                    plugins: [
                        'advlist autolink lists link image charmap print preview anchor textcolor',
                        'searchreplace visualblocks code fullscreen',
                        'insertdatetime media table contextmenu paste code help'
                    ],
                    toolbar: 'undo redo | bold italic | link image',
                    content_css: [
                    ]
                });
            });

            $(document).on('turbolinks:before-cache', function() {
                // Tear down TinyMCE editor instances between Turbolinks pages
                tinymce.remove();
            });
        </script>
@endsection
