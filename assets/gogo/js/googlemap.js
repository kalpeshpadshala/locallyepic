        function initGoogleMap(){
            initGoogleMap("span9");
        }
        function initGoogleMap(withClass){
            if(readyGoogleMap(withClass) == 0)
                return;
            $("#btn-go").click(function(){
                var address = $("#addr").val();
                if(address != ''){
                    geocoder.geocode({
                        'address': address
                    }, function(results, status){
                        if(status == google.maps.GeocoderStatus.OK){
                            $("#lat").html(results[0].geometry.location.lat());
                            $("#lng").html(results[0].geometry.location.lng());
                            map.setCenter(results[0].geometry.location);
                            if (!marker){
                                marker = new google.maps.Marker({
                                    position : results[0].geometry.location,
                                    map: map
                                });
                            }else{
                                marker.setMap(null);
                                marker = new google.maps.Marker({
                                    position: results[0].geometry.location,
                                    map: map
                                });
                            }
                        }else{
                            //alert("Geocoder failed due to: " + status);
                        }
                    });
                }
            });
            $("#google_helper_trigger").click(function(){
                $("#google_helper").slideToggle(1000, function(){
                    $(window).scrollTop($('#map-container').offset().top);
                });
            });
            $("#btn-input").click(function(){
                $("#latitude").val($("#lat").html());
                $("#longitude").val($("#lng").html());
                $("#google_helper").slideUp();
            });
                var latlng = new google.maps.LatLng(37.095798, -113.575171);
                var myOptions = {
                    zoom: 12,
                    center: latlng,
                    mapTypeId: google.maps.MapTypeId.ROADMAP
                }
                var map = new google.maps.Map(document.getElementById("map_canvas"), myOptions);
                var marker = new google.maps.Marker({
                    position: latlng,
                    map: map
                });

            var geocoder = new google.maps.Geocoder();
            google.maps.event.addListener(map, 'click', function(event){
                var location = event.latLng;
                geocoder.geocode({
                    'latLng' : location
                },
                function(results, status){
                    if (status == google.maps.GeocoderStatus.OK){
                        $('#addr').val(results[0].formatted_address);
                        $('#lat').html(results[0].geometry.location.lat());
                        $('#lng').html(results[0].geometry.location.lng());
                    }else{
                        //alert("Geocoder faild due to: " + status);
                    }
                });
                if(!marker){
                    marker = new google.maps.Marker({
                        position: location,
                        map: map
                    });
                }else{
                    marker.setMap(null);
                    marker = new google.maps.Marker({
                        position : location,
                        map : map
                    });
                }
                //map.setCenter(location);
            });
        };
        function readyGoogleMap(withClass){
            if( withClass == undefined ){
                withClass = "span9";
            }
            if($("#map-container").length == 0)
                return 0;
            //$("#map-container").css("max-width", 330);
            var mapcon = "<label></label>"
                            + "<div class='"+withClass+" googlemap' style='margin-left:0px;'>"
                            + "<div id='google_helper_trigger' class='btn-success'>Google Map</div>"
                            + "<div id='google_helper' style='display:none;'>"
                            + "<div style='border: 1px solid #cccccc; margin-bottom:10px; padding:2px;' class='pull-right'>"
                            + "<div id='map_canvas' style='height: 400px; border: 1px solid #cccccc;'></div>"
                            + "<div class='pull-left' style='width:70%; padding:3px;'>"
                            + "<div style='position:relative;'>"
                            + "<div style='width:30%;' class='pull-left'><label>Latitude:</label></div>"
                            + "<div id='lat' style='width:70%;' class='pull-left'>37.095798</div>"
                            + "</div>"
                            + "<div class='float-clear'></div>"
                            + "<div style='position:relative;'>"
                            + "<div style='width:30%;' class='pull-left'><label>Longitude:</label></div>"
                            + "<div id='lng' style='width:70%;' class='pull-left'>-113.575171</div>"
                            + "</div>"
                            + "</div>"
                            + "<div class='pull-left' style='width:30%; padding:3px;'>"
                            + "<div id='btn-input' class='btn-glow primary pull-right' style='width:60px;'>INPUT</div>"
                            + "</div>"
                            + "<div class='float-clear'></div>"
                            + "<div style='padding:3px;'>"
                            + "<div class='pull-left' style='width:70%; position:relative;'>"
                            + "<div class='pull-left' style='width:30%;'><label>Address:</label></div>"
                            + "<div class='pull-left' style='width:70%; position:relative;'>"
                            + "<input type='text' id='addr' value='' style='width:100%'/>"
                            + "</div>"
                            + "</div>"
                            + "<div class='pull-left' style='width:30%;'>"
                            + "<div id='btn-go' class='btn-glow success pull-right' style='width:60px;'>G O</div>"
                            + "</div>"
                            + "</div>"
                            + "</div>"
                            + "</div>"
                            + "</div>";
            $("#map-container").html(mapcon);
            return 1;
        }