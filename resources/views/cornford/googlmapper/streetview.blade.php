<div id="map-canvas-{!! $id !!}" style="height: 100%; margin: 0; padding: 0;"></div>

<script type="text/javascript">

	function initialize_{!! $id !!}() {
		var bounds = new google.maps.LatLngBounds();
		var position = new google.maps.LatLng({!! $options['latitude'] !!}, {!! $options['longitude'] !!});

		var mapOptions = {
			@if ($options['center'])
				center: position,
			@endif
			zoom: {!! $options['zoom'] !!},
			mapTypeId: google.maps.MapTypeId.{!! $options['type'] !!},
			disableDefaultUI: @if (!$options['ui']) true @else false @endif
		};

		var map = new google.maps.Map(document.getElementById('map-canvas-{!! $id !!}'), mapOptions);

		var panoramaOptions = {
			position: position,
			pov: {
				heading: {!! $options['heading'] !!},
				pitch: {!! $options['pitch'] !!}
			}
		};

		var panorama = new google.maps.StreetViewPanorama(document.getElementById('map-canvas-{!! $id !!}'), panoramaOptions);

		map.setStreetView(panorama);
	}

    @if (!$options['async'])

        google.maps.event.addDomListener(window, 'load', initialize_{!! $id !!});

    @endif

</script>