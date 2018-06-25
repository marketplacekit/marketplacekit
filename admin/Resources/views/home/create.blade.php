@extends('panel::layouts.master')

@section('content')
    <a href="{{ route('panel.home.index', ['locale' => request('locale')]) }}" class="mb-1"><i class="fa fa-angle-left" aria-hidden="true"></i> back</a>

    <div class="row mb-3">
        <div class="col-sm-8">
            @if(!$form->getModel())
            <h2  class="mt-xxs">Adding new widget</h2>
            @else
            <h2  class="mt-xxs">Editing widget</h2>
            @endif
        </div>
        <div class="col-sm-4">

        </div>

    </div>

    <div class="row">

        <div class="col-sm-12">

          <div class="panel panel-default">
              <div class="panel-body">


                  {!! form_start($form) !!}

                  {!! form_until($form, 'style') !!}

                  <div  class="card mb-4" id="metadata_form_wrapper">
                      <div class="card-header">
                          Custom Data
                      </div>
                      <div class="card-body">
                          <div id="metadata_form"></div>
                      </div>
                  </div>

                  {!! form_rest($form) !!}

                  {!! form_end($form) !!}
              </div>
            </div>
    </div>


        <script type="text/javascript">
            $('[name="type"]').on('change', function() {
                toggleMeta();
            });
            function toggleMeta() {
                $('#metadata_form_wrapper').show();
                $('#metadata_form').html('');
                var schema = {};
                schema['hero'] = {
                    "$schema": "http://json-schema.org/draft-03/schema#",
                    "type": "object",
                    "properties": {
                        "values": {
                            "type": "string",
                            "format": "text",
                            "title": "Paragraph"
                        }
                    }
                };
                schema['video'] = {
                    "$schema": "http://json-schema.org/draft-03/schema#",
                    "type": "object",
                    "properties": {
                        "values": {
                            "type": "string",
                            "title": "Video URL"
                        }
                    }
                };
                schema['paragraph'] = {
                    "$schema": "http://json-schema.org/draft-03/schema#",
                    "type": "object",
                    "properties": {
                        "values": {
                            "type": "array",
                            "title": "Paragraphs",
                            "uniqueItems": true,
                            "items": {
                                "description": "Query item",
                                "type": "object",
                                "properties": {
                                    "title": {
                                        "type": "string",
                                        "title": "Title"
                                    },
                                    "icon": {
                                        "type": "string",
                                        "title": "Icon"
                                    },
                                    "blurb": {
                                        "type": "string",
                                        "format": "text",
                                        "title": "Blurb"
                                    },
                                    "link": {
                                        "type": "string",
                                        "title": "Link (e.g. http://)"
                                    },
                                    "link_text": {
                                        "type": "string",
                                        "title": "Link text"
                                    }
                                }
                            }
                        }
                    }
                };
                schema['image_gallery'] = {
                    "$schema": "http://json-schema.org/draft-03/schema#",
                    "type": "object",
                    "properties": {
                        "values": {
                            "type": "array",
                            "title": "Images",
                            "uniqueItems": true,
                            "items": {
                                "description": "Query item",
                                "type": "object",
                                "properties": {
                                    "title": {
                                        "type": "string",
                                        "title": "Title"
                                    },
                                    "image": {
                                        "type": "string",
                                        "title": "Image URL"
                                    },
                                    "link": {
                                        "type": "string",
                                        "title": "Link URL"
                                    },
                                    "columns": {
                                        "type": "string",
                                        "enum": [ '1','2','3','4','5','6','7','8','9','10','11','12' ],
                                        "title": "Columns"
                                    }
                                }
                            }
                        }
                    }
                };

                if($('[name="type"]').val() in schema) {
                    var BrutusinForms = brutusin["json-forms"];
                    bf = BrutusinForms.create(schema[$('[name="type"]').val()]);

                    var data = {};
                    @if($form->getModel() && $form->getModel()->metadata)
                        data = {'values' : <?= json_encode($form->getModel()->metadata) ?>};
                    @endif
                    var container = document.getElementById('metadata_form');
                    bf.render(container, data);
                    console.log($('#metadata_form').html().length);
                    if($('#metadata_form').html().length) {
                        $('#metadata_form_wrapper').show();
                    } else {
                        $('#metadata_form_wrapper').hide();
                    }
                } else {
                    $('#metadata_form_wrapper').hide();
                }
            }
            var bf = null;
            toggleMeta();

            $('#listings_form').submit(function(){
                $('#metadata').val(JSON.stringify(bf.getData()));
                return true;
            });

            /*
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
            });*/

        </script>
@endsection
