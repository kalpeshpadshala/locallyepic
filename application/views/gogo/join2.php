<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Locally Epic Signup</title>

    <!-- GLOBAL STYLES -->
    <link href="/css/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href='//fonts.googleapis.com/css?family=Ubuntu:300,400,500,700,300italic,400italic,500italic,700italic' rel="stylesheet" type="text/css">
    <link href='//fonts.googleapis.com/css?family=Open+Sans:300italic,400italic,600italic,700italic,800italic,400,300,600,700,800' rel="stylesheet" type="text/css">
    <link href="/css/icons/font-awesome/css/font-awesome.min.css" rel="stylesheet">
    <link href="<?php echo base_url(); ?>assets/gogo/css/lib/select2.css" type="text/css" rel="stylesheet">

    <!-- PAGE LEVEL PLUGIN STYLES -->

    <!-- THEME STYLES -->
    <link href="/css/style.css" rel="stylesheet">
    <link href="/css/plugins.css" rel="stylesheet">

    <!-- THEME DEMO STYLES -->
    <link href="/css/demo.css" rel="stylesheet">

    <!--[if lt IE 9]>
    <script src="js/html5shiv.js"></script>
    <script src="js/respond.min.js"></script>
    <![endif]-->

<style>

      #map-canvas {
         width: 100%;
         height: 300px;
      }

      @media(min-width:768px) {
    body {
        background: white;
    }
}

.colorgraph {
  height: 5px;
  border-top: 0;
  background: #c4e17f;
  border-radius: 5px;
  background-image: -webkit-linear-gradient(left, #c4e17f, #c4e17f 12.5%, #f7fdca 12.5%, #f7fdca 25%, #fecf71 25%, #fecf71 37.5%, #f0776c 37.5%, #f0776c 50%, #db9dbe 50%, #db9dbe 62.5%, #c49cde 62.5%, #c49cde 75%, #669ae1 75%, #669ae1 87.5%, #62c2e4 87.5%, #62c2e4);
  background-image: -moz-linear-gradient(left, #c4e17f, #c4e17f 12.5%, #f7fdca 12.5%, #f7fdca 25%, #fecf71 25%, #fecf71 37.5%, #f0776c 37.5%, #f0776c 50%, #db9dbe 50%, #db9dbe 62.5%, #c49cde 62.5%, #c49cde 75%, #669ae1 75%, #669ae1 87.5%, #62c2e4 87.5%, #62c2e4);
  background-image: -o-linear-gradient(left, #c4e17f, #c4e17f 12.5%, #f7fdca 12.5%, #f7fdca 25%, #fecf71 25%, #fecf71 37.5%, #f0776c 37.5%, #f0776c 50%, #db9dbe 50%, #db9dbe 62.5%, #c49cde 62.5%, #c49cde 75%, #669ae1 75%, #669ae1 87.5%, #62c2e4 87.5%, #62c2e4);
  background-image: linear-gradient(to right, #c4e17f, #c4e17f 12.5%, #f7fdca 12.5%, #f7fdca 25%, #fecf71 25%, #fecf71 37.5%, #f0776c 37.5%, #f0776c 50%, #db9dbe 50%, #db9dbe 62.5%, #c49cde 62.5%, #c49cde 75%, #669ae1 75%, #669ae1 87.5%, #62c2e4 87.5%, #62c2e4);
}

.input-lg {
  height: 46px;
  padding: 10px 16px;
  font-size: 18px;
  line-height: 1.33;
  border-radius: 6px !important;
}

    </style>

<!-- <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDeVLluaH6RIe-LtcyLVrX-M1vtJit5m3Q&libraries=places&callback=initialize" async defer></script> -->
<script src="https://maps.googleapis.com/maps/api/js?key= AIzaSyDmeqOhbm61iUwTwR-ski3h5q0GSDa_kwA &libraries=places&callback=initialize" async defer></script>
    <script>

    //https://developers.google.com/maps/documentation/staticmaps/#Zoomlevels


    var placeSearch, autocomplete;
    var componentForm = {
        street_number: 'short_name',
        route: 'long_name',
        locality: 'long_name',
        administrative_area_level_1: 'short_name',
        country: 'long_name',
        postal_code: 'short_name'
      };


  var map;

  function initialize() {
    var mapOptions = {
      zoom: 20,
      center: new google.maps.LatLng(-34.397, 150.644),
      mapTypeId: google.maps.MapTypeId.HYBRID
      };

    //https://developers.google.com/maps/documentation/javascript/maptypes

 	map = new google.maps.Map(document.getElementById('map-canvas'), mapOptions);

 	 var input = /** @type {!HTMLInputElement} */(
            document.getElementById('autocomplete'));




    autocomplete = new google.maps.places.Autocomplete(input);
        autocomplete.bindTo('bounds', map);

    console.log(autocomplete);

    marker = new google.maps.Marker({
      map: map,
      anchorPoint: new google.maps.Point(0, -29)
    });

    google.maps.event.addListener(autocomplete, 'place_changed', function() {
      console.log(109);
      fillInAddress();
      putOnMap();

    });



    if (navigator.geolocation) {
         navigator.geolocation.getCurrentPosition(function (position) {
             initialLocation = new google.maps.LatLng(position.coords.latitude, position.coords.longitude);
             map.setCenter(initialLocation);
         });
    }

    google.maps.event.addListener(map, 'zoom_changed', function() {
      zoomLevel = map.getZoom();
      console.log(zoomLevel);
    });

  google.maps.event.addDomListener(window, "resize", function() {
   var center = map.getCenter();
   google.maps.event.trigger(map, "resize");
   map.setCenter(center);
});

}

//google.maps.event.addDomListener(window, 'load', initialize);

function putOnMap(){

  var marker = new google.maps.Marker({
    map: map,
    anchorPoint: new google.maps.Point(0, -29),
    draggable:true
  });

   google.maps.event.addListener(marker, 'dragend', function(evt){
    //document.getElementById('current').innerHTML = '<p>Marker dropped: Current Lat: ' + evt.latLng.lat().toFixed(3) + ' Current Lng: ' + evt.latLng.lng().toFixed(3) + '</p>';
    document.getElementById('latitude').value = evt.latLng.lat();
    document.getElementById('longitude').value = evt.latLng.lng();

  });

  var place = autocomplete.getPlace();

  console.log(place);

  if (place.geometry.viewport) {
      map.fitBounds(place.geometry.viewport);
    } else {
      map.setCenter(place.geometry.location);
      map.setZoom(20);  // Why 17? Because it looks good.
    }
    marker.setIcon(/** @type {google.maps.Icon} */({
      url: place.icon,
      size: new google.maps.Size(71, 71),
      origin: new google.maps.Point(0, 0),
      anchor: new google.maps.Point(17, 34),
      scaledSize: new google.maps.Size(35, 35)
    }));
    marker.setPosition(place.geometry.location);
    marker.setVisible(true);
}



function geolocate() {
  if (navigator.geolocation) {
    navigator.geolocation.getCurrentPosition(function(position) {
      var geolocation = new google.maps.LatLng(
          position.coords.latitude, position.coords.longitude);
      var circle = new google.maps.Circle({
        center: geolocation,
        radius: position.coords.accuracy
      });
      //autocomplete.setBounds(circle.getBounds());
      document.getElementById('latitude').value = position.coords.latitude;
    document.getElementById('longitude').value = position.coords.longitude;

    });
  }
}

function fillInAddress() {
  // Get the place details from the autocomplete object.
  var place = autocomplete.getPlace();
  //console.log(place);

  for (var component in componentForm) {
    document.getElementById(component).value = '';
    document.getElementById(component).disabled = false;
  }

  // Get each component of the address from the place details
  // and fill the corresponding field on the form.
  for (var i = 0; i < place.address_components.length; i++) {
    var addressType = place.address_components[i].types[0];
    if (componentForm[addressType]) {
      var val = place.address_components[i][componentForm[addressType]];
      document.getElementById(addressType).value = val;
      //console.log(val);
    }
  }
}
</script>

</head>

<body>

<div class="container">

  <?php //echo $this->session->userdata('user_id'); ?>

<div class="row">
    <div class="col-xs-12 col-sm-8 col-md-6 col-sm-offset-2 col-md-offset-3">

    <br><br><br>
    	<center><img src="/img/logo-black-trans.png"></center>
    	<br><br>

		<form role="form" method="post" action="/gogo/join2">
			<h2>Step 2 of 3:  <small>Enter Your Business Information.</small></h2>
			<hr class="colorgraph">

           <?php echo validation_errors('<div class="alert alert-danger">', '</div>'); ?>
            <div class="form-group">
                <input  value="<?php echo set_value('shop_name'); ?>" type="text" name="shop_name" id="shop_name" class="form-control input-lg" placeholder="Business Name" tabindex="3">
            </div>

            <div class="form-group">
                <input  value="<?php echo set_value('contact_number'); ?>" type="text" name="contact_number" id="contact_number" class="form-control input-lg" placeholder="Business Contact Number" tabindex="3">
            </div>

            <div class="form-group">
                <input  value="<?php echo set_value('website'); ?>" type="text" name="website" id="website" class="form-control input-lg" placeholder="Business Website" tabindex="3">
            </div>



            <div class="form-group">
              <label for="category">Please select your Business Category</label>
                <select class="form-control input-lg select2" name="category">
                  <option value="">Select a Category</option>
                  <?php
                  foreach ($cats as $v) {
                      ?>
                      <option value="<?php echo $v['cid']; ?>"><?php echo $v['cname']; ?></option>
                      <?php
                  }
                  ?>
                </select>
            </div>

			<div class="form-group">
				<label for="exampleInputPassword1">Start Typing Address..  Must select from Google suggested addresses</label>
                                    <input  value="<?php echo set_value('address'); ?>" name="address" id="autocomplete" placeholder="Enter your address" autofocus="autofocus" onFocus="geolocate()" autocomplete="off" class="form-control  input-lg"></input>
			</div>
			<div class="row">

				<div class="col-xs-12 col-sm-6 col-md-12">
					<div class="form-group">
						<div id="map-canvas"></div>
					</div>
				</div>
			</div>


			<hr class="colorgraph">
			<div class="row">
				<div class="col-xs-12 col-md-12"><input type="submit" value="Continue" class="btn btn-primary btn-block btn-lg" tabindex="7"></div>

			</div>
            <input value="<?php echo set_value('street_number'); ?>" type="hidden" class="field" id="street_number" name="street_number"></input>
            <input value="<?php echo set_value('route'); ?>" type="hidden" class="field" id="route" name="route"></input>
            <input value="<?php echo set_value('locality'); ?>" type="hidden" class="field" id="locality" name="locality"></input>
            <input value="<?php echo set_value('administrative_area_level_1'); ?>" type="hidden" class="field" id="administrative_area_level_1" name="administrative_area_level_1"></input>
            <input value="<?php echo set_value('postal_code'); ?>" type="hidden" class="field" id="postal_code" name="postal_code"></input>
            <input value="<?php echo set_value('country'); ?>" type="hidden" class="field" id="country" name="country"></input>
            <input value="<?php echo set_value('latitude'); ?>" type="hidden" class="field" id="latitude" name="latitude"></input>
            <input value="<?php echo set_value('longitude'); ?>" type="hidden" class="field" id="longitude" name="longitude"></input>
		</form>
	</div>
</div>
<!-- Modal -->
<div class="modal fade" id="t_and_c_m" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
				<h4 class="modal-title" id="myModalLabel">Terms & Conditions</h4>
			</div>
			<div class="modal-body">
				<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Similique, itaque, modi, aliquam nostrum at sapiente consequuntur natus odio reiciendis perferendis rem nisi tempore possimus ipsa porro delectus quidem dolorem ad.</p>
				<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Similique, itaque, modi, aliquam nostrum at sapiente consequuntur natus odio reiciendis perferendis rem nisi tempore possimus ipsa porro delectus quidem dolorem ad.</p>
				<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Similique, itaque, modi, aliquam nostrum at sapiente consequuntur natus odio reiciendis perferendis rem nisi tempore possimus ipsa porro delectus quidem dolorem ad.</p>
				<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Similique, itaque, modi, aliquam nostrum at sapiente consequuntur natus odio reiciendis perferendis rem nisi tempore possimus ipsa porro delectus quidem dolorem ad.</p>
				<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Similique, itaque, modi, aliquam nostrum at sapiente consequuntur natus odio reiciendis perferendis rem nisi tempore possimus ipsa porro delectus quidem dolorem ad.</p>
				<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Similique, itaque, modi, aliquam nostrum at sapiente consequuntur natus odio reiciendis perferendis rem nisi tempore possimus ipsa porro delectus quidem dolorem ad.</p>
				<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Similique, itaque, modi, aliquam nostrum at sapiente consequuntur natus odio reiciendis perferendis rem nisi tempore possimus ipsa porro delectus quidem dolorem ad.</p>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-primary" data-dismiss="modal">I Agree</button>
			</div>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->
</div>



    <!-- GLOBAL SCRIPTS -->
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
    <script src="/assets/plugins/bootstrap/bootstrap.min.js"></script>
    <script src="/assets/plugins/slimscroll/jquery.slimscroll.min.js"></script>
    <!-- HISRC Retina Images -->
    <script src="/assets/plugins/hisrc/hisrc.js"></script>
    <script src="<?php echo base_url(); ?>assets/gogo/js/select2.min.js"></script>

    <!-- PAGE LEVEL PLUGIN SCRIPTS -->

    <!-- THEME SCRIPTS -->
    <script src="/assets/flex.js"></script>

    <script>

$(function () {

    $(".select2").select2({placeholder: "Select a Category" });


    $('.button-checkbox').each(function () {

        // Settings
        var $widget = $(this),
            $button = $widget.find('button'),
            $checkbox = $widget.find('input:checkbox'),
            color = $button.data('color'),
            settings = {
                on: {
                    icon: 'glyphicon glyphicon-check'
                },
                off: {
                    icon: 'glyphicon glyphicon-unchecked'
                }
            };

        // Event Handlers
        $button.on('click', function () {
            $checkbox.prop('checked', !$checkbox.is(':checked'));
            $checkbox.triggerHandler('change');
            updateDisplay();
        });
        $checkbox.on('change', function () {
            updateDisplay();
        });

        // Actions
        function updateDisplay() {
            var isChecked = $checkbox.is(':checked');

            // Set the button's state
            $button.data('state', (isChecked) ? "on" : "off");

            // Set the button's icon
            $button.find('.state-icon')
                .removeClass()
                .addClass('state-icon ' + settings[$button.data('state')].icon);

            // Update the button's color
            if (isChecked) {
                $button
                    .removeClass('btn-default')
                    .addClass('btn-' + color + ' active');
            }
            else {
                $button
                    .removeClass('btn-' + color + ' active')
                    .addClass('btn-default');
            }
        }

        // Initialization
        function init() {

            updateDisplay();

            // Inject the icon if applicable
            if ($button.find('.state-icon').length == 0) {
                $button.prepend('<i class="state-icon ' + settings[$button.data('state')].icon + '"></i>');
            }
        }
        init();
    });
});

    </script>


<br><br><br>

</body>

</html>
