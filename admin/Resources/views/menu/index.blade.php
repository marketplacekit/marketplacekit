@extends('panel::layouts.master')

@section('content')
    
    <h2>Menu</h2>

    @include("panel::components.content_menu")
    @include('alert::bootstrap')
    <div class="btn-group" role="group" aria-label="Button group with nested dropdown">
        <div class="btn-group" role="group">
            <button id="btnGroupDrop1" type="button" class="btn btn-secondary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                {{app('laravellocalization')->getSupportedLocales()[$selected_lang]['name']}}
            </button>
            <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                @foreach(LaravelLocalization::getSupportedLocales() as $localeCode => $properties)
                <a class="dropdown-item" href="?locale={{ $localeCode }}">{{ $properties['native'] }}</a>
                @endforeach
            </div>
        </div>
    </div>


    <div class="row mt-3" id="instance">

        <div class="col-sm-12">

            <div class="panel panel-default">
                <div class="panel-body">


                    <div class="row">
                        <div class="col-sm-5 mb-0">


                            <div class="form-group  mb-0">
                                <label>Title</label>
                            </div>
                        </div>
                        <div class="col-sm-5  mb-0">


                            <div class="form-group  mb-0">
                                <label>URL</label>
                            </div>
                        </div>
                        <div class="col-sm-2">
                            <label>&nbsp;</label>
                        </div>
                    </div>
                    <div class="row" v-for="(item, index) in rows">
                        <div class="col-sm-5">
                            <div class="form-group">
                                <input type="text" class="form-control" placeholder="" v-model="item.title"/>
                            </div>
                        </div>
                        <div class="col-sm-5">


                            <div class="form-group">
                                <input type="text" class="form-control" v-model="item.url"/>
                            </div>
                        </div>
                        <div class="col-sm-2">
                            <p class="form-control-static"><a href="" @click.prevent="remove(index)"><i class="mdi mdi-delete"></i></a></p>
                        </div>
                    </div>

                    <a href="" @click.prevent="addItem"><i class="mdi mdi-plus"></i> Add menu item</a>
                    <br />
                    <br />
                    <a href="" class="btn btn-primary" @click.prevent="saveMenu">Save menu</a>

                </div>
            </div>
        </div>
    </div>
    @javascript('selected_lang', $selected_lang)
    @javascript('items', $menu->items)

    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/axios@0.18.0/dist/axios.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/vue/2.5.11/vue.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/alertify.js@1.0.12/dist/js/alertify.min.js"></script>

    <script>
        var vm = new Vue({
            el: '#instance',
            data: {
                rows: items
            },
            created: function () {
                if(this.rows.length == 0) {
                    this.addItem();
                }
            },
            methods: {
                saveMenu: function () {
                    console.log(this.rows);
                    axios
                        .post("/panel/menu", {locale: selected_lang, items: this.rows})
                        .then(function(response) {
                            console.log(response);
                            alertify.alert("Saved");
                        })
                        .catch(function(error) {
                            console.log(error);
                        });
                },
                remove: function (index) {
                    this.rows.splice(index, 1);
                },
                addItem: function () {
                    this.rows.push({
                        title: "",
                        url: "/",
                        position: this.rows.length+1
                    })
                }
            }

        });
    </script>
@stop
