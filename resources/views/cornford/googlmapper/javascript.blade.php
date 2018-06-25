@if (!$view->shared('javascript', false))

    @if ($view->share('javascript', true)) @endif

    @if ($options['async'])

        <script async defer type="text/javascript" src="//maps.googleapis.com/maps/api/js?v={!! $options['version'] !!}&region={!! $options['region'] !!}&language={!! $options['language'] !!}&key={!! $options['key'] !!}&libraries=places&callback=initialize_method" ></script>

    @else

        <script type="text/javascript" src="//maps.googleapis.com/maps/api/js?v={!! $options['version'] !!}&region={!! $options['region'] !!}&language={!! $options['language'] !!}&key={!! $options['key'] !!}&libraries=places"  data-turbolinks-eval="false"></script>

    @endif

    @if ($options['cluster'])

        <script type="text/javascript" src="//googlemaps.github.io/js-marker-clusterer/src/markerclusterer.js"></script>

    @endif

    @if ($options['async'])

        <script type="text/javascript">

            var initialize_items = [];

            function initialize_method() {
                initialize_items.forEach(function(item) {
                    item.method();
                });
            }

        </script>

    @endif

@endif