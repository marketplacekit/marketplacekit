@extends('panel::layouts.master')

@section('content')

    <h2>Addons</h2>
    @include("panel::components.addons_menu")

    <div class="row mb-3">
        <div class="col-sm-8">
            <p>If you have an addon in zip format you can upload it here.</p>
        </div>
        <div class="col-sm-4">

        </div>

    </div>

    <div class="row">

        <div class="col-sm-6">

            <div class="panel panel-default">
                <div class="panel-body">
                    @include('alert::bootstrap')

                    {{ Form::open(array('route' => 'panel.addons.store', 'files' => true)) }}

                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fa fa-puzzle-piece" aria-hidden="true"></i></span>
                        </div>
                        <div class="custom-file">
                            <input type="file" accept=".zip" class="custom-file-input" name="addon" id="inputGroupFile01">
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
