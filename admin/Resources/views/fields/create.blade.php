@extends('panel::layouts.master')

@section('content')
    <div class="container">
        <a href="{{route('panel.fields.index')}}" class="mb-1"><i class="fa fa-angle-left" aria-hidden="true"></i> back</a>

        <div class="row mb-3">
            <div class="col-sm-8">
				@if($form->getFormOption('method') == 'POST')
                    <h2 class="mt-xxs">Adding new field</h2>
				@else
					<h2 class="mt-xxs">Editing field: {{ $filter->name }}</h2>
				@endif
            </div>
            <div class="col-sm-4">

            </div>

        </div>

        <div class="row">

            <div class="col-sm-10">

                <div class="panel panel-default">
                    <div class="panel-body">
    <div class="row">

        <div class="col-sm-12">

          <div class="panel panel-default">
              <div class="panel-body">

                  {!! form_start($form) !!}

                  {!! form_until($form, 'form_input_type') !!}
                  @if($form->method != 'POST' || !$filter->is_default)
                  <div id="fb-editor"></div>
                  @endif
                  {!! form_rest($form) !!}

                  {!! form_end($form) !!}
</div>
</div>
</div>

                    </div>
                </div>
            </div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.11.4/jquery-ui.min.js"></script>
<script src="http://formbuilder.online/assets/js/form-builder.min.js"></script>
<script type="text/javascript" src="http://formbuilder.online/assets/js/form-builder.min.js"></script>
<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/formbuilder/0.2.1/formbuilder-min.css"/>
<script src="https://cdnjs.cloudflare.com/ajax/libs/speakingurl/14.0.1/speakingurl.min.js"></script>

<style>
.form-wrap.form-builder button {
    color: #fff;
}
.form-wrap.form-builder .frmb>li:hover {
    box-shadow: none;
    border: none;
}
.field-actions, .close-field, .cb-wrap, .field-label  {
    display: none;
}
.form-wrap.form-builder .stage-wrap {
width: 100%;
}
 .form-group.className-wrap, .form-group.name-wrap, .form-group.access-wrap, .form-group.value-wrap { display: none !important; }
 .form-wrap.form-builder .frmb {transition: background-color 4.1s ease-in-out;}
 .form-wrap.form-builder .frmb li{transition: background-color 4.25s ease-in-out,margin-top 4.4s}
</style>
<script>
$('#filter_form').submit(function(){
    $('#form_input_meta').val(formBuilder.formData);
    $('[name="field"]').val(getSlug( $('[name="name"]').val() ));
    return true;
});


$('#form_ui').on('change', function() {
  changeFormElement( this.value );
});
$('#is_category_specific').on('change', function() {
  toggleCategories();
});

function toggleCategories() {
    //$('[name="categories[]"]').prop('disabled', $('#is_category_specific').prop('checked'));
    if($('#is_category_specific').prop('checked')) {
        $('[name="categories[]"]').closest( ".form-group" ).show();
    } else {
        $('[name="categories[]"]').closest( ".form-group" ).hide();
    }
}
toggleCategories();
$('#is_searchable').on('change', function() {
  toggleSearchUI();
});
function toggleSearchUI() {
	@if(isset($filter) && $filter->is_default)
		$('[name="search_ui"]').closest( ".form-group" ).hide();
		return;
	@endif
    //$('[name="categories[]"]').prop('disabled', $('#is_category_specific').prop('checked'));
    if($('#is_searchable').prop('checked')) {
        $('[name="search_ui"]').closest( ".form-group" ).show();
    } else {
        $('[name="search_ui"]').closest( ".form-group" ).hide();
    }
}
toggleSearchUI();

function changeFormElement(field) {
    if(field == 'none') {
        $('#fb-editor').hide();
    } else {
        $('#fb-editor').show();
        formBuilder.actions.clearFields(false);
        var label = $('[name="name"]').val();
        var name = getSlug(label, '_');
        @if($form->getFormOption('method') != 'POST')
            label = "{{$form->getModel()->name}}";
            name = "{{$form->getModel()->field}}";
        @endif
		
		var className = 'form-control';
		if(field == 'checkbox' || field == 'checkbox-group' || field == 'radio' ) {
			className = '';
		}
		
        var field = {
              id: 1,
              label: label,
              name: name,
              type: field,
              className: className
        };

        var result = formBuilder.actions.addField(field, undefined);
        $('.toggle-form')[0].click();

    }
}


var options = {
  controlPosition: 'top',
  disableFields: ['button', 'file'],
  disabledAttrs: ['access'],
  disabledActionButtons: ['data', 'clear', 'save'],
  sortableControls: false,
  editOnAdd: true
};
@if($form->getFormOption('method') != 'POST')
	@if($form->getModel()->form_input_meta)
        options.formData = @json([($form->getModel()->form_input_meta)], JSON_PRETTY_PRINT);
	@else
		//options.formData = {};
	@endif
@endif


var formBuilder = $(document.getElementById('fb-editor')).formBuilder(options);
function show_form() {
    @if($form->getFormOption('method') == 'POST')
    $('#fb-editor').hide();
    @else
    @if($form->getModel()->form_input_meta)
    $('.toggle-form')[0].click();
    @else
    $('#form_ui').val('text')
    changeFormElement($('#form_ui').val());
    @endif
    @endif
}

setTimeout(function(){
    show_form();
}, 800)

setTimeout(function(){
    if($('.form-elements').length == 0) {
        show_form();
    }
}, 1800)

setTimeout(function(){
    if($('.form-elements').length == 0) {
        show_form();
    }
}, 3000)

</script>

@endsection
