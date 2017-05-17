@extends('admin')

@section('content')
    <style>
        #map {
            width: 800px;
            height: 300px;
        }
    </style>
    <!-- Page header -->
    <div class="page-header">
        <div class="page-header-content">
            <div class="page-title">
                <h2>Dashboard</h2>
            </div>

            <div class="heading-elements">
                <div class="heading-btn-group">
                </div>
            </div>
        </div>
    </div>
    <!-- /page header -->
    <!-- Page container -->
    <div class="page-container">
        <!-- Page content -->

        <div class="content-wrapper">

            @if (session('success'))
                <div class="alert bg-success alert-styled-left">
                    <button type="button" class="close" data-dismiss="alert"><span>×</span><span
                                class="sr-only">Close</span></button>
                    {{ session('success') }}
                </div>
            @endif
                <input id="pac-input" class="controls" type="text" placeholder="Search Box">
            <div class="panel panel-flat">
                <input id="clat" type="text">
                <input id="clong" type="text">


                <div id="map"></div>


            </div>
        </div>
        <!-- /main content -->
    </div>
    <!-- /page content -->

    <!-- /page container -->


@endsection

@push('scripts_foot')
<script>
    function initAutocomplete() {
        var map = new google.maps.Map(document.getElementById('map'), {
            zoom: 12,
            center: {lat: -34.397, lng: 150.644},
            mapTypeId: google.maps.MapTypeId.ROADMAP
        });

        // Create the search box and link it to the UI element.
        var input = document.getElementById('pac-input');
        var searchBox = new google.maps.places.SearchBox(input);
        map.controls[google.maps.ControlPosition.TOP_LEFT].push(input);

        // Bias the SearchBox results towards current map's viewport.
        map.addListener('bounds_changed', function () {
            searchBox.setBounds(map.getBounds());
        });

        var markers = [];


        google.maps.event.addListener(map, 'click', function (e) {
            var ll = {lat: e.latLng.lat(), lng: e.latLng.lng()};

            //alert(e.latLng.lat());
            markers.forEach(function (marker) {
                marker.setMap(null);
            });

            markers = [];

            lastMarker = new google.maps.Marker({
                position: ll,
                map: map,
                title: 'Hello World!'
            });
            markers.push(lastMarker);

            getAddressByLatlng(ll);


        });


        // Listen for the event fired when the user selects a prediction and retrieve
        // more details for that place.
        searchBox.addListener('places_changed', function () {
            var places = searchBox.getPlaces();

            if (places.length == 0) {
                return;
            }

            // Clear out the old markers.
            markers.forEach(function (marker) {
                marker.setMap(null);
            });
            markers = [];

            // For each place, get the icon, name and location.
            var bounds = new google.maps.LatLngBounds();

            places.forEach(function (place) {
                var icon = {
                    url: place.icon,
                    size: new google.maps.Size(71, 71),
                    origin: new google.maps.Point(0, 0),
                    anchor: new google.maps.Point(17, 34),
                    scaledSize: new google.maps.Size(25, 25)
                };

                // Create a marker for each place.
                markers.push(new google.maps.Marker({
                    map: map,
                    icon: icon,
                    title: place.name,
                    position: place.geometry.location
                }));

                if (place.geometry.viewport) {
                    // Only geocodes have viewport.
                    bounds.union(place.geometry.viewport);
                } else {
                    bounds.extend(place.geometry.location);
                }
                var e = place.geometry.location;
                var ll = {lat: e.lat(), lng: e.lng()};
                getAddressByLatlng(ll);
            });
            map.fitBounds(bounds);
        });
    }

</script>

<script type="text/javascript">

    function getAddressByLatlng(latlng) {

        var lat = latlng.lat;
        var lng = latlng.lng;

        var inputSearchBox = document.getElementById('pac-input');

        var cLatValId = document.getElementById('clat');
        var cLongValId = document.getElementById('clong');

        cLatValId.value = lat + ',' + lng;
        cLongValId.value = lng;

        var geocoder = new google.maps.Geocoder();
        geocoder.geocode({'latLng': latlng}, function (results, status) {
            if (status == google.maps.GeocoderStatus.OK) {
                if (results[1]) {
                    // myHomeLocText.value =  results[1].formatted_address;
                    inputSearchBox.value = results[1].formatted_address;
                }
            }
        });

    }


</script>


<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDUMRn1pnBk97Zay94WiBbMgdVlBh_vwYs&libraries=places&callback=initAutocomplete&language=vn"
        async defer></script>

@endpush

