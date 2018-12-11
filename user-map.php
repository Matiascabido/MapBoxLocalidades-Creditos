<?php
include_once 'header.php';
include 'locations_model.php';
//get_unconfirmed_locations();exit;
?>
    <style>

        input[type=text], select {
            width: 100%;
            padding: 12px 20px;
            margin: 8px 0;
            display: inline-block;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }

        input[type=submit] {
            width: 100%;
            background-color: #4CAF50;
            color: white;
            padding: 14px 20px;
            margin: 8px 0;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        input[type=submit]:hover {
            background-color: #45a049;
        }

        .container {
            border-radius: 5px;
            background-color: #f2f2f2;
            padding: 20px;
            width:50%
        }
        #map { position:absolute;height:100% ;width:100%; }
        .geocoder {
            position:absolute;
        }
        #menu {
        position: absolute;
        background: #feff;
        font-family: 'Open Sans', sans-serif;

        #marker {
        background-size: cover;
        width: 50px;
        height: 50px;
        border-radius: 50%;
        cursor: pointer;
    }

    .mapboxgl-popup {
        max-width: 200px;
    }

    }
    </style>

    <div id="map">
    </div>

    <div class="container float:left">
        <div id='menu' class="form-group">
            <div class="form-control">
            <input id='basic' type='radio' name='rtoggle' value='basic' checked='checked'>
            <label for='basic'>basic</label>
            </div>
            <div class="form-control">
            <input id='streets' type='radio' name='rtoggle' value='streets'>
            <label for='streets'>streets</label>
            </div>
            <div class="form-control">
            <input id='bright' type='radio' name='rtoggle' value='bright'>
            <label for='bright'>bright</label>
            </div>
            <div class="form-control">
            <input id='light' type='radio' name='rtoggle' value='light'>
            <label for='light'>light</label>
            </div>
            <div class="form-control">
            <input id='dark' type='radio' name='rtoggle' value='dark'>
            <label for='dark'>dark</label>
            </div>
            <div class="form-control">
            <input id='satellite' type='radio' name='rtoggle' value='satellite'>
            <label for='satellite'>satellite</label>
            </div>
        </div>
    </div>
    <div id="ppp"> </div>

 </div>
   </div>

       
    <script src='https://api.tiles.mapbox.com/mapbox-gl-js/v0.48.0/mapbox-gl.js'></script>
    <link href='https://api.tiles.mapbox.com/mapbox-gl-js/v0.48.0/mapbox-gl.css' rel='stylesheet' />
 

    <script src='https://api.mapbox.com/mapbox-gl-js/plugins/mapbox-gl-geocoder/v2.3.0/mapbox-gl-geocoder.min.js'></script>
    <link rel='stylesheet' href='https://api.mapbox.com/mapbox-gl-js/plugins/mapbox-gl-geocoder/v2.3.0/mapbox-gl-geocoder.css' type='text/css' />

    <script>

        var saved_markers = <?= getlocationsinfo() ?>;
        var saved_markers_2 = <?= flauten() ?>;
        
        var user_location = [-63.583571,-32.204895];
        mapboxgl.accessToken = 'pk.eyJ1IjoiZmFraHJhd3kiLCJhIjoiY2pscWs4OTNrMmd5ZTNra21iZmRvdTFkOCJ9.15TZ2NtGk_AtUvLd27-8xA';
        var map = new mapboxgl.Map({
            container: 'map',
            style: 'mapbox://styles/mapbox/streets-v9',
            center: user_location,
            zoom: 6
        });
        //  geocoder here
        var geocoder = new MapboxGeocoder({
            accessToken: mapboxgl.accessToken,
            // limit results to Australia
            //country: 'IN',
        });

        var marker ;

        //Mapa selector

        var layerList = document.getElementById('menu');
        var inputs = layerList.getElementsByTagName('input');

        function switchLayer(layer) {
            var layerId = layer.target.id;
            map.setStyle('mapbox://styles/mapbox/' + layerId + '-v9');
        }

        for (var i = 0; i < inputs.length; i++) {
            inputs[i].onclick = switchLayer;
        }

        // After the map style has loaded on the page, add a source layer and default
        // styling for a single point.
        map.on('load', function() {
            //addMarker(user_location,'load');
            add_markers(saved_markers);

            // Listen for the `result` event from the MapboxGeocoder that is triggered when a user
            // makes a selection and add a symbol that matches the result.
            geocoder.on('result', function(ev) {
                console.log(ev.result.center);

            });
        });
       

        function addMarker(ltlng,event) {

            if(event === 'click'){
                user_location = ltlng;
            }
            marker = new mapboxgl.Marker({draggable: true,color:"#d02922"})
                .setLngLat(user_location)
                .addTo(map)
                .on('dragend', onDragEnd);
        }
        function add_markers(coordinates) {

            var geojson = (saved_markers == coordinates ? saved_markers : '');
            var popup;

            // create DOM element for the marker
            console.log(saved_markers_2);
            // add markers to map
            var i = 0;
            geojson.forEach(function (marker) {
                console.log(marker);
                // make a marker for each feature and add to the map

                               
                    if(saved_markers_2[i][4] == 1){
                        popup  = new mapboxgl.Popup({ offset: 25 }).setHTML('<b>Localidad: </b>'
                         + saved_markers_2[i][3] + '  <br>  '+ '<b>Creditos: </b>'+ saved_markers_2[i][4]+' <br> ' 
                         +'<input type="button" value="Mas Info" onclick="buscarmasdatos('+saved_markers_2[i][2]+')">'
                         +'<label id="atm"></label>'
                         +'<label id="complete"></label>'
                         );
                         new mapboxgl.Marker({color:"#0B610B"})
                    .setLngLat(marker)
                    .setPopup(popup)
                    .addTo(map);
                    }else
                    if(saved_markers_2[i][4] == 2){
                        popup  = new mapboxgl.Popup({ offset: 25 }).setHTML('<b>Localidad: </b>'
                         + saved_markers_2[i][3] + '  <br>  '+ '<b>Creditos: </b>'+ saved_markers_2[i][4]+' <br> ' 
                         +'<input type="button" value="Mas Info" onclick="buscarmasdatos('+saved_markers_2[i][2]+')">'
                         +'<label id="atm"></label>'
                         +'<label id="complete"></label>'
                         );
                    new mapboxgl.Marker({color:"#D7DF01"})
                    .setLngLat(marker)
                    .setPopup(popup)
                    .addTo(map);
                    }else
                    if(saved_markers_2[i][4] > 2){
                        
                        popup  = new mapboxgl.Popup({ offset: 25 }).setHTML('<b>Localidad: </b>'
                         + saved_markers_2[i][3] + '  <br>  '+ '<b>Creditos: </b>'+ saved_markers_2[i][4]+' <br> ' 
                         +'<input type="button" value="Mas Info" onclick="buscarmasdatos('+saved_markers_2[i][2]+')">'
                         +'<label id="atm"></label>'
                         +'<label id="complete"></label>'

                         );

                    new mapboxgl.Marker({color:"#610B0B"})
                    .setLngLat(marker)
                    .setPopup(popup)
                    .addTo(map);
                    }
                
                i++;
            });


        }

        function buscarmasdatos(id){
            
            $.ajax({
                url: 'locations_model.php?id='+id,
                method: 'GET',
                dataType: 'json',
                success: function(data){
                    var texts = '<hr>';
                    for (let i = 0; i < data.length; i++) {
                        if (data[i][1] == 'Solicitudes')
                            texts += '<hr>'+data[i][1] + ': '+data[i][0];
                        else                                                                          
                            texts += data[i][1] + ': '+data[i][0]+'<br>';
                    }
                    $('#complete').html('<b>'+texts + '</b>');

               
                }
            });
            $.ajax({
                url: 'locations_model.php?id_loc='+id,
                method: 'GET',
                dataType: 'json',
                success: function(data){
                    
                    var texts = '<hr> ';
                    for (let i = 0; i < data.length; i++) {
                            texts += 'ATM: '+data[i][0] + ' '+data[i][1]+'<br>';
                    }
                    $('#atm').html('<b>'+texts + '</b>');
                }
            });
            
        }
        function onDragEnd() {
            var lngLat = marker.getLngLat();
            document.getElementById("lat").value = lngLat.lat;
            document.getElementById("lng").value = lngLat.lng;
            console.log('lng: ' + lngLat.lng + '<br />lat: ' + lngLat.lat);
        }

        $('#signupForm').submit(function(event){
            event.preventDefault();
            var lat = $('#lat').val();
            var lng = $('#lng').val();
            var url = 'locations_model.php?add_location&lat=' + lat + '&lng=' + lng;
            $.ajax({
                url: url,
                method: 'GET',
                dataType: 'json',
                success: function(data){
                    alert(data);
                    location.reload();
                }
            });
        });

         //document.getElementById('geocoder').appendChild(geocoder.onAdd(map));

        map.addControl(new mapboxgl.NavigationControl());
    </script>



<?php
include_once 'footer.php';

?>