@if ($options['place'])

	var service = new google.maps.places.PlacesService({!! $options['map'] !!});
	var request = {
		placeId: '{!! $options['place'] !!}'
	};

	service.getDetails(request, function(placeResult, status) {
		if (status != google.maps.places.PlacesServiceStatus.OK) {
			alert('Unable to find the Place details.');
			return;
		}

@endif

@if ($options['locate'] && $options['marker'])
	if (typeof navigator !== 'undefined' && navigator.geolocation) {
		navigator.geolocation.getCurrentPosition(function (position) {
			marker_0.setPosition(new google.maps.LatLng(position.coords.latitude, position.coords.longitude));
		});
	}
@endif

var markerPosition_{!! $id !!} = new google.maps.LatLng({!! $options['latitude'] !!}, {!! $options['longitude'] !!});

var marker_{!! $id !!} = new google.maps.Marker({
	position: markerPosition_{!! $id !!},
	@if ($options['place'])
		place: {
			placeId: '{!! $options['place'] !!}',
			location: { lat: {!! $options['latitude'] !!}, lng: {!! $options['longitude'] !!} }
		},
		attribution: {
			source: document.title,
			webUrl: document.URL
		},
	@endif
		
	@if (isset($options['draggable']) && $options['draggable'] == true)
		draggable: true,
	@endif
	
	title: {!! json_encode((string) $options['title']) !!},
	label: {!! json_encode($options['label']) !!},
	animation: @if (empty($options['animation']) || $options['animation'] == 'NONE') '' @else google.maps.Animation.{!! $options['animation'] !!} @endif,
	@if ($options['symbol'])
		icon: {
			path: google.maps.SymbolPath.{!! $options['symbol'] !!},
			scale: {!! $options['scale'] !!}
		}
	@else
		icon:
		@if (is_array($options['icon']) && isset($options['icon']['url']))
			{
				url: {!! json_encode((string) $options['icon']['url']) !!},

				@if (isset($options['icon']['size']))
					@if (is_array($options['icon']['size']))
						scaledSize: new google.maps.Size({!! $options['icon']['size'][0] !!}, {!! $options['icon']['size'][1] !!})
					@else
						scaledSize: new google.maps.Size({!! $options['icon']['size'] !!}, {!! $options['icon']['size'] !!})
					@endif
				@endif
			}
		@else
			{!! json_encode($options['icon']) !!}
		@endif
	@endif
});

bounds.extend(marker_{!! $id !!}.position);

marker_{!! $id !!}.setMap({!! $options['map'] !!});
markers.push(marker_{!! $id !!});

@if ($options['place'])

		marker_{!! $id !!}.addListener('click', function() {
			infowindow.setContent('<a href="' + placeResult.website + '">' + placeResult.name + '</a>');
			infowindow.open({!! $options['map'] !!}, this);
		});
	});

@else

	@if (!empty($options['content']))

		var infowindow_{!! $id !!} = new google.maps.InfoWindow({
			content: {!! json_encode((string) $options['content']) !!}
		});

		@if (isset($options['maxWidth']))

			infowindow_{!! $id !!}.setOptions({ maxWidth: {!! $options['maxWidth'] !!} });

		@endif

		@if (isset($options['open']) && $options['open'])

			infowindow_{!! $id !!}.open({!! $options['map'] !!}, marker_{!! $id !!});

		@endif

		google.maps.event.addListener(marker_{!! $id !!}, 'click', function() {

			@if (isset($options['autoClose']) && $options['autoClose'])

				for (var i = 0; i < infowindows.length; i++) {
					infowindows[i].close();
				}

			@endif

			infowindow_{!! $id !!}.open({!! $options['map'] !!}, marker_{!! $id !!});
		});

		infowindows.push(infowindow_{!! $id !!});

	@endif

@endif

@foreach (['eventClick', 'eventDblClick', 'eventRightClick', 'eventMouseOver', 'eventMouseDown', 'eventMouseUp', 'eventMouseOut', 'eventDrag', 'eventDragStart', 'eventDragEnd', 'eventDomReady'] as $event)

	@if (isset($options[$event]))

		google.maps.event.addListener(marker_{!! $id !!}, '{!! str_replace('event', '', strtolower($event)) !!}', function (event) {
			{!! $options[$event] !!}
		});

	@endif

@endforeach
