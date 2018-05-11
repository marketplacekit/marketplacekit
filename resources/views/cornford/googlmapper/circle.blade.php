var circleCoordinates_{!! $id !!} = (
	@foreach ($options['coordinates'] as $key => $coordinate)
		new google.maps.LatLng({!! $coordinate['latitude'] !!}, {!! $coordinate['longitude'] !!})
	@endforeach
);

var circle_{!! $id !!} = new google.maps.Circle({
	strokeColor: '{!! $options['strokeColor'] !!}',
	strokeOpacity: {!! $options['strokeOpacity'] !!},
	strokeWeight: {!! $options['strokeWeight'] !!},
	fillColor: '{!! $options['fillColor'] !!}',
	fillOpacity: {!! $options['fillOpacity'] !!},
	center: circleCoordinates_{!! $id !!},
	radius: {!! $options['radius'] !!},
	editable: {!! $options['editable'] ? 'true' : 'false' !!}
});

circle_{!! $id !!}.setMap({!! $options['map'] !!});

shapes.push({
	'circle_{!! $id !!}': circle_{!! $id !!}
});