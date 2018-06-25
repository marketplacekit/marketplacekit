var polygonCoordinates_{!! $id !!} = [
	@foreach ($options['coordinates'] as $coordinate)
		new google.maps.LatLng({!! $coordinate['latitude'] !!}, {!! $coordinate['longitude'] !!}),
	@endforeach
];

var polygon_{!! $id !!} = new google.maps.Polygon({
	paths: polygonCoordinates_{!! $id !!},
	strokeColor: '{!! $options['strokeColor'] !!}',
	strokeOpacity: {!! $options['strokeOpacity'] !!},
	strokeWeight: {!! $options['strokeWeight'] !!},
	fillColor: '{!! $options['fillColor'] !!}',
	fillOpacity: {!! $options['fillOpacity'] !!},
	editable: {!! $options['editable'] ? 'true' : 'false' !!}
});

polygon_{!! $id !!}.setMap({!! $options['map'] !!});

shapes.push({
	'polygon_{!! $id !!}': polygon_{!! $id !!}
});