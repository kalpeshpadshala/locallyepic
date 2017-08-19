$(document).ready(function(){
  $('.gm-style-iw').parent().parent().parent().css({'display' : 'none'});
});

var map;
var markers=[];

var infowindow;



function getLocation(){

	console.log("is It?: ", window.navigator.geolocation);
    if (window.navigator.geolocation) {
		console.log("Line8");
        window.navigator.geolocation.getCurrentPosition(function(position) {
            var pos = {
                lat: position.coords.latitude,
                lng: position.coords.longitude
            };

            Cookies.set('lat', pos.lat, { expires: 7 });
            Cookies.set('lng', pos.lng, { expires: 7 });

            saveLocation(pos);
        });
    }

}

function initMap() {
    map = new google.maps.Map(document.getElementById('map1'), {
        center: {
            lat: -34.397,
            lng: 150.644
        },
        zoom: 13
    });

    /*var infoWindow = new google.maps.InfoWindow({
        map: map
    });*/

	    infowindow = new google.maps.InfoWindow({
			content: '',

			// Assign a maximum value for the width of the infowindow allows
			// greater control over the various content elements
			maxWidth: 350
			});

    if (window.navigator.geolocation) {
		console.log("Line 38");
        window.navigator.geolocation.getCurrentPosition(function(position) {
            var pos = {
                lat: position.coords.latitude,
                lng: position.coords.longitude
            };

			console.log("45: ",pos);

            saveLocation(pos);

            var image = '/assets/icons/Retail-shop-icon-small.png';
			var beachMarker = new google.maps.Marker({
				position: pos,
				map: map,
				icon: image,
				title: "You are Here"
			  });
            map.setCenter(pos);
            radius_center(pos, 5);


        }, function(error) {
            handleLocationError(true, infoWindow, map.getCenter(), error);
        });
    } else {
        // Browser doesn't support Geolocation
        handleLocationError(false, infoWindow, map.getCenter());
    }

    map.addListener('idle', showMarkers);


} // end of the function




function handleLocationError(browserHasGeolocation, infoWindow, pos, error) {
    infoWindow.setPosition(pos);
    infoWindow.setContent(browserHasGeolocation ?
        'Error: The Geolocation service failed.'+error.code+' ' + error.message :
        'Error: Your browser doesn\'t support geolocation.');
}

function showMarkers() {

    try {
        console.log('Map is idle');
        var lat0 = map.getBounds().getNorthEast().lat();
        var lng0 = map.getBounds().getNorthEast().lng();
        var lat1 = map.getBounds().getSouthWest().lat();
        var lng1 = map.getBounds().getSouthWest().lng();
        var zoom = map.getZoom();
        var bounds = map.getBounds();

        console.log('Lat Lon for Bounds: ', lat0, lng0, lat1, lng1, zoom);
        console.log("Bounds: ", bounds);


        /*
        Logic  is to use ajax to pull all the markers in that are in the bounds.
        Then remove all markers on the map, and then add the new ones.
        With the new list of markers you can remove the current markers (marker.setMap(null)) that are on the map and add the new ones (marker.setMap(map)).
        */

		var time_in_sec = Math.floor((new Date()).getTime() / 1000);

		var jqxhr = $.ajax({

				// The URL for the request
				url: "/map/aj_mapdeals_idle",
				data: {
					lat: lat0,
					lng: lng0,
					lat1: lat1,
					lng1: lng1,
					time_in_sec:time_in_sec
				},
				type: "POST",
			    dataType: 'json'

			})
			.done(function(data) {
				console.log("returned data", data.data);
				createMarkerBasedOnFetchedPosition(data);
				$(".sdlist").empty();
				//populate_list(data,'.sdlist');
			})
			.fail(function(xhr, status, errorThrown) {
				alert("Sorry, there was a problem!");
				console.log("Error: " + errorThrown);
				console.log("Status: " + status);
				console.dir(xhr);
                $("#errordiv").append(errorThrown);
                $("#errordiv").append(status);
                $("#errordiv").append(xhr.responseText);

			})
			.always(function(xhr, status) {
				console.log("The request is complete!");
			});






    } catch (err) {
        alert(err);
        $("#errordiv").append(err);
                
    }

}

function createMarkerBasedOnFetchedPosition(fetchedMarkersPosition)
{
   //var jsonData = JSON.parse(fetchedMarkersPosition.data);
   var jsonData =fetchedMarkersPosition.data;

   console.log("function createMarkerBasedOnFetchedPosition:",jsonData);



   for (var i = 0; i < jsonData.users.length; i++) {
		//console.log( jsonData.users[i].user_id, jsonData.users[i].latitude, jsonData.users[i].longitude  );
	   if (!markers[jsonData.users[i].id])
	   {
			markers[jsonData.users[i].id]=true;


			var content = '<div id="iw-container" style="background-color:white">' +
                      '<div class="iw-title">'+ jsonData.users[i].name +'</div>' +
                      '<div class="iw-content">' +
                        '<div class="row">' +
                          '<div class="col-md-5">' +
                            '<div class="iw-img-wrap">' +jsonData.users[i].user_id+ ' '+
                            '</div>'+
                          '</div>' +
                          '<div class="col-md-7"> <!--Last Seen: Feb 16, 2016 at 11:30pm-->' +
                          '</div>' +
                      '<div class="iw-bottom-gradient"></div>' +
                    '</div>';

			// A new Info Window is created and set content
			/*var infowindow = new google.maps.InfoWindow({
			content: content,

			// Assign a maximum value for the width of the infowindow allows
			// greater control over the various content elements
			maxWidth: 350
			});*/





      var imagePin = '/assets/icons/Pin-small.png';
			var marker = new google.maps.Marker({
				position:  new google.maps.LatLng(jsonData.users[i].latitude,jsonData.users[i].longitude),
				title: jsonData.users[i].user_id,
				animation: google.maps.Animation.DROP,
				icon: imagePin
			});
			marker.setMap(map);


			google.maps.event.addListener(marker,'click', (function(marker,content,infowindow){
				return function() {
					infowindow.setContent(content);
					infowindow.open(map,marker);
				};
			})(marker,content,infowindow));

			/*google.maps.event.addListener(marker, 'click', function() {
				infowindow.open(map,marker);
			});*/



		  // Event that closes the Info Window with a click on the map
		  google.maps.event.addListener(map, 'click', function() {
			infowindow.close();
		  });

		  // *
		  // START INFOWINDOW CUSTOMIZE.
		  // The google.maps.event.addListener() event expects
		  // the creation of the infowindow HTML structure 'domready'
		  // and before the opening of the infowindow, defined styles are applied.
		  // *
			google.maps.event.addListener(infowindow, 'domready', function() {

				// Reference to the DIV that wraps the bottom of infowindow
				var iwOuter = $('.gm-style-iw');

				/* Since this div is in a position prior to .gm-div style-iw.
				 * We use jQuery and create a iwBackground variable,
				 * and took advantage of the existing reference .gm-style-iw for the previous div with .prev().
				*/
				var iwBackground = iwOuter.prev();

				iwOuter.parent().parent().parent().css({'display' : 'block'});

				// Removes background shadow DIV
				iwBackground.children(':nth-child(2)').css({'display' : 'none'});

				// Removes white background DIV
				iwBackground.children(':nth-child(4)').css({'display' : 'none'});

				// Moves the infowindow 115px to the right.
				iwOuter.parent().parent().css({left: '115px'});

				// Moves the shadow of the arrow 76px to the left margin.
				iwBackground.children(':nth-child(1)').attr('style', function(i,s){ return s + 'left: 76px !important;'});

				// Moves the arrow 76px to the left margin.
				iwBackground.children(':nth-child(3)').attr('style', function(i,s){ return s + 'left: 76px !important;'});

				// Changes the desired tail shadow color.
				iwBackground.children(':nth-child(3)').find('div').children().css({'box-shadow': 'rgba(72, 181, 233, 0.6) 0px 1px 6px', 'z-index' : '1'});

				// Reference to the div that groups the close button elements.
				var iwCloseBtn = iwOuter.next();

				// Apply the desired effect to the close button
				iwCloseBtn.css({opacity: '1', right: '38px', top: '3px', border: '1px solid #48b5e9', 'border-radius': '13px', 'box-shadow': '0 0 5px #3990B9'});

				// If the content of infowindow not exceed the set maximum height, then the gradient is removed.
				if($('.iw-content').height() < 140){
				  $('.iw-bottom-gradient').css({display: 'none'});
				}

				// The API automatically applies 0.7 opacity to the button after the mouseout event. This function reverses this event to the desired value.
				iwCloseBtn.mouseout(function(){
				  $(this).css({opacity: '1'});
				});
			});
		}
   }
}



function saveLocation(position) {

    var time_in_sec = Math.floor((new Date()).getTime() / 1000);

    var jqxhr = $.ajax({

            // The URL for the request
            url: "/map/aj_latlng",
            data: {
                lat: position.lat,
                lng: position.lng,
                time_in_sec:time_in_sec
            },
            type: "POST"

        })
        .done(function() {
            console.log("Location Updated");
        })
        .fail(function(xhr, status, errorThrown) {
            alert("Sorry, there was a problem!");
            console.log("Error: " + errorThrown);
            console.log("Status: " + status);
            console.dir(xhr);

            $("#errordiv").append(errorThrown);
            $("#errordiv").append(status);
            $("#errordiv").append(xhr.responseText);

        })
        .always(function(xhr, status) {
            console.log("The request is complete!");
        });

    // Perform other work here ...

    // Set another completion function for the request above
    //jqxhr.always(function() {
    //alert( "second complete" );
    //});


}


function radius_center(latlng, r) {
    // latlng is the point of the zipcode
    var circ = new google.maps.Circle({
        strokeColor: "#2790FF",
        strokeOpacity: 0.8,
        strokeWeight: 2,
        fillColor: "#2790FF",
        fillOpacity: 0.4
    });
    circ.setRadius(r * 1609.34);
    circ.setCenter(latlng);
    circ.setMap(map);

    map.setCenter(latlng);
    map.fitBounds(circ.getBounds());


    var zoom = map.getZoom();
    map.setZoom(zoom + 2);




    console.log('radius_center');

    // updates markers
    //google.maps.event.trigger(map,'dragend');
};


$(document).ready(function(){

	var href = window.location.pathname;

	var action = href.substr(href.lastIndexOf('/') + 1);

	if (action !='searchdeals' && action!='login')
	{
		getLocation();
	}





});