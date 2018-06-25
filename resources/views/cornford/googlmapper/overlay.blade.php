var overlayCoordinates_{!! $id !!} = new google.maps.LatLngBounds(
	@foreach ($options['coordinates'] as $coordinate)
		new google.maps.LatLng({!! $coordinate['latitude'] !!}, {!! $coordinate['longitude'] !!}),
	@endforeach
);

overlay_{!! $id !!} = new google.maps.GroundOverlay(
	'{!! $options['image'] !!}',
	overlayCoordinates_{!! $id !!}
);

overlay_{!! $id !!}.setMap({!! $options['map'] !!});
