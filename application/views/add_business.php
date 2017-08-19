<script>
    function selectState(country_id) {
        if (country_id != "") {
            loadData('state', country_id);
            $("#city_dropdown").html("<option value='-1'>Select city</option>");
        } else {
            $("#state_dropdown").html("<option value='-1'>Select state</option>");
            $("#city_dropdown").html("<option value='-1'>Select city</option>");
        }
    }
    function selectCity(state_id) {
        if (state_id != "-1") {
            loadData('city', state_id);
        } else {
            $("#city_dropdown").html("<option value='-1'>Select city</option>");
        }
    }
    function loadData(loadType, loadId) {
        var dataString = 'loadType=' + loadType + '&loadId=' + loadId;
        $("#" + loadType + "_loader").show();
        $("#" + loadType + "_loader").fadeIn(400).html('Please wait... <img src="<?php echo base_url(); ?>assets/images/loading.gif" />');
        $.ajax({
            type: "POST",
            url: "<?php echo base_url(); ?>site/loadData",
            data: dataString,
            cache: false,
            success: function(result) {
                $("#" + loadType + "_loader").hide();
                $("#" + loadType + "_dropdown").html("<option value='-1'>Select " + loadType + "</option>");
                $("#" + loadType + "_dropdown").append(result);
                $("#" + loadType + "_dropdown").append("<option value='0'>Others</option>");
            }
        });
    }
</script>
<style>
    #map-canvas {
        width:700px;
        height:300px;
        padding: 0px
    }
    #panel {
        position: absolute;
        top: 5px;
        left:25%;
        margin-top:5px;
        z-index: 5;
        background-color: #fff;
        padding: 5px;
        border: 1px solid #999;
    }
</style>
 <script src="//ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
<!-- <script type='text/javascript' src='https://maps.googleapis.com/maps/api/js?v=3.exp&#038;sensor=false&#038;ver=2014-07-18&libraries=places&key=AIzaSyDeVLluaH6RIe-LtcyLVrX-M1vtJit5m3Q'></script> -->
<script type='text/javascript' src='https://maps.googleapis.com/maps/api/js?v=3.exp&#038;sensor=false&#038;ver=2014-07-18&libraries=places&key=AIzaSyDmeqOhbm61iUwTwR-ski3h5q0GSDa_kwA'></script>
<script type='text/javascript' src='<?php echo base_url();?>assets/js/gmap3.min.js?ver=2014-07-18'></script>
<script type='text/javascript' src='<?php echo base_url();?>assets/js/gmap3.infobox.js?ver=2014-07-18'></script>

   <script type='text/javascript' src='//code.jquery.com/ui/1.10.4/jquery-ui.js?ver=2014-07-18'></script>

<!-- begin MAIN PAGE CONTENT -->
        <div id="page-wrapper">

            <div class="page-content">

                <!-- begin PAGE TITLE ROW -->
                <div class="row">
                    <div class="col-lg-12">
                        <div class="page-title">
                            <ol class="breadcrumb">
                                <li><i class="fa fa-dashboard"></i>  <a href="<?php echo base_url();?>site/index">Dashboard</a>
                                </li>
                                <li class="active">Add Business</li>
                            </ol>
                            
                        </div>
                    </div><!-- /.col-lg-12 -->
                    <div class="col-lg-12">
                        <?php if($message){ ?>
                            <div class="alert alert-success">
                                 <strong>Success Alert:</strong> <?php echo $message; ?>
                            </div>
                        <?php  } ?>
                    </div>
                    <!-- /.col-lg-12 -->
                </div>
                <!-- /.row -->
                <!-- end PAGE TITLE ROW -->
                <div class="row">
                 <!-- Hoverable Responsive Table -->
                    <div class="col-lg-12">
                        <div class="portlet portlet-default">
                            <div class="portlet-heading">
                                <div class="portlet-title">
                                    <h4>Add Business</h4>
                                </div>
                                <div class="clearfix"></div>
                            </div>
                            <div class="portlet-body">
                                <div class="table-responsive">
                                    <form  method="POST" enctype="multipart/form-data" autocomplete="off">  
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th class="col-md-4">Description</th>
                                                <th class="col-md-8">Form Elements</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td class="col-md-4">Category *</td>
                                                <td class="col-md-4">
                                                    <select class="form-control" name="scat">
                                                        <option value="">Select Category</option>
                                                        <?php
                                                        $sel_category =  set_value('scat');
                                                        
                                                        foreach ($cats as $cat) {
                                                            ?>
                                                        <option value="<?php echo $cat['cid']; ?>" <?php if($cat['cid']==$sel_category){echo 'selected';}?>><?php echo $cat['cname']; ?></option>
                                                            <?php
                                                        }
                                                        ?>
                                                    </select>
                                                    <?php echo form_error('scat'); ?>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="col-md-4">Enter name of business *</td>
                                                <td class="col-md-8">
                                                    <input type="text" name="sname" placeholder="" class="form-control" value="<?php echo set_value('sname'); ?>">
                                                    <?php echo form_error('sname'); ?>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="col-md-4">Enter description of business *</td>
                                                <td class="col-md-8">
                                                    <textarea rows="3" class="form-control" name="sdesp"><?php echo set_value('sdesp'); ?></textarea>
                                                    <?php echo form_error('sdesp'); ?>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="col-md-4">Logo upload</td>
                                                <td class="col-md-8"><input type="file" name="sfile"></td>
                                            </tr>
                                            <tr>
                                                <td class="col-md-4">Enter business address *</td>
                                                <td class="col-md-8">
                                                    <input type="text" onFocus="geolocate()" autocomplete="off" name="sadd" id="address" placeholder=""  class="form-control input-textarea half" value="<?php echo set_value('sadd'); ?>">
                                                    <input type="hidden" name="city" id="administrative_area_level_2" value="<?php echo set_value('city'); ?>">
                                                    <input type="hidden" name="state" id="administrative_area_level_1" value="<?php echo set_value('state'); ?>">
                                                    <input type="hidden" name="country" id="country" value="<?php echo set_value('country'); ?>">
                                                    <?php echo form_error('sadd'); ?>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="col-md-4">Location Map </td>
                                                <td class="col-md-8">
                                                 <div id="map_canvas" class="map_canvas" style="width: 600px; height: 400px"></div>
                                                 <script type="text/javascript">
                                                     var inilat=33.68220812703748;
                                                     var inilng=-78.92789399999998;
                                                        <?php
                                                        if(isset($_POST['lat'])){
                                                        ?>
                                                           inilat='<?php echo $_POST['lat']; ?>'; 
                                                            <?php
                                                        }
                                                        if(isset($_POST['lng'])){
                                                        ?>
                                                           inilng='<?php echo $_POST['lng']; ?>'; 
                                                            <?php
                                                        }
                                                        ?>
                                                        jQuery(document).ready(function($) {
                                                        var geocoder;
                                                        var map;
                                                        var marker;
                                                        var latlng = new google.maps.LatLng(inilat, inilng);
                                                        var geocoder = new google.maps.Geocoder();

                                                                function geocodePosition(pos) {
                                                                    geocoder.geocode({
                                                                        latLng: pos
                                                                    }, function(responses) {
                                                                        if (responses && responses.length > 0) {
                                                                            updateMarkerAddress(responses[0].formatted_address);
                                                                        } else {
                                                                            updateMarkerAddress('Cannot determine address at this location.');
                                                                        }
                                                                    });
                                                                }

                                                                function updateMarkerPosition(latLng) {
                                                                    jQuery('#latitude').val(latLng.lat());
                                                                    jQuery('#longitude').val(latLng.lng());
                                                                }

                                                                function updateMarkerAddress(str) {
                                                                    jQuery('#address').val(str);
                                                                }

                                                                function initialize(latlng) {
                                                                    
                                                                    var mapOptions = {
                                                                        zoom: 20,
                                                                        center: latlng,
                                                                        mapTypeId: google.maps.MapTypeId.HYBRID
                                                                    }
                                                                    map = new google.maps.Map(document.getElementById('map_canvas'), mapOptions);
                                                                    geocoder = new google.maps.Geocoder();
                                                                    marker = new google.maps.Marker({
                                                                        position: latlng,
                                                                        map: map,
                                                                        draggable: true
                                                                    });

                                                                    
                                                                                                                                    // Add dragging event listeners.
                                                                    google.maps.event.addListener(marker, 'dragstart', function() {
                                                                        //updateMarkerAddress('Dragging...');
                                                                    });
                                                                    google.maps.event.addListener(marker, 'drag', function() {
                                                                        updateMarkerPosition(marker.getPosition());
                                                                    });
                                                                    google.maps.event.addListener(marker, 'dragend', function() {
                                                                        updateMarkerPosition(marker.getPosition());
                                                                        geocoder.geocode({'latLng': marker.getPosition()}, function(results, status) {
                                                                            if (status == google.maps.GeocoderStatus.OK) {
                                                                                if (results[0]) {
                                                                                    jQuery('#address').val(results[0].formatted_address);
                                                                                    jQuery('#latitude').val(marker.getPosition().lat());
                                                                                    jQuery('#longitude').val(marker.getPosition().lng());
                                                                                }
                                                                            }
                                                                        });
                                                                    });

                                                                    

                                                                }
                                                                //google.maps.event.addDomListener(window, 'load', initialize);
                                                                jQuery(document).ready(function() {
                                                                    initialize(latlng);
                                                                    
                                                                    <?php
                                                                        if(! isset($_POST['lat'])){
                                                                        ?>
                                                                    if (navigator.geolocation) {
                                                                         navigator.geolocation.getCurrentPosition(function (position) {
                                                                             initialLocation = new google.maps.LatLng(position.coords.latitude, position.coords.longitude);
                                                                             map.setCenter(initialLocation);
                                                                             latlng = new google.maps.LatLng(position.coords.latitude, position.coords.longitude);

                                                                             marker.setPosition(latlng);


                                                                         });
                                                                    }
                                                                    <?php } else {?>
                                                                        initialize(latlng);
                                                                        <?php } ?>

                                                                    
                                                                    jQuery(function() {
                                                                        var markers = [];
                                                                         var componentForm = {
                                                                            administrative_area_level_2: 'long_name',
                                                                            administrative_area_level_1: 'long_name',
                                                                            country: 'long_name',
                                                                            postal_code: 'short_name'
                                                                          };
                                                                        var input = /** @type {HTMLInputElement} */(
                                                                            document.getElementById('address'));
                                                                        var searchBox = new google.maps.places.SearchBox(
                                                                              /** @type {HTMLInputElement} */(input));
                                                                        google.maps.event.addListener(searchBox, 'places_changed', function() {
                                                                            
                                                                            var places = searchBox.getPlaces();
                                                                                //console.log(places);
                                                                                //console.log(places[0]);

                                                                                for (var component in componentForm) {
                                                                                  document.getElementById(component).value = '';
                                                                                  document.getElementById(component).disabled = false;
                                                                                }

                                                                            if (places[0]) {

                                                                                for (var i = 0; i < places[0].address_components.length; i++) {
                                                                                    var addressType = places[0].address_components[i].types[0];

                                                                                  if (componentForm[addressType]) {
                                                                                    var val = places[0].address_components[i][componentForm[addressType]];
                                                                                    document.getElementById(addressType).value = val;
                                                                                    console.log(addressType+'=>'+val);
                                                                                  }
                                                                                }
                                                                                
                                                                                //console.log("places0:",places[0]);
                                                                              jQuery('#address').val(places[0].formatted_address);
                                                                            jQuery('#latitude').val(places[0].geometry.location.lat());
                                                                                jQuery('#longitude').val(places[0].geometry.location.lng());
                                                                            initialize(places[0].geometry.location);
                                                                            }
                                                                          });
                                                                    });
                                                                    //Add listener to marker for reverse geocoding
                                                                    google.maps.event.addListener(marker, 'drag', function() {
                                                                        geocoder.geocode({'latLng': marker.getPosition()}, function(results, status) {
                                                                            if (status == google.maps.GeocoderStatus.OK) {
                                                                                if (results[0]) {
                                                                                    jQuery('#address').val(results[0].formatted_address);
                                                                                    jQuery('#latitude').val(marker.getPosition().lat());
                                                                                    jQuery('#longitude').val(marker.getPosition().lng());
                                                                                }
                                                                            }
                                                                        });
                                                                    });
                                                                });
                                                            });

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
                                                    </script>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="col-md-4">Latitude </td>
                                                <td class="col-md-8">
                                                 <input type="text" name="lat" id="latitude"   value="<?php echo set_value('lat'); ?>">
                                                 <?php echo form_error('lat'); ?>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="col-md-4">Longitude </td>
                                                <td class="col-md-8">
                                                <input type="text" name="lng"  id="longitude"   value="<?php echo set_value('lng'); ?>">
                                                <?php echo form_error('lng'); ?>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="col-md-4">Enter Zip Code *</td>
                                                <td class="col-md-8">
                                                    <input type="text" name="postal_code" id='postal_code' placeholder="" class="form-control" value="<?php echo set_value('postal_code'); ?>">
                                                    <?php echo form_error('postal_code'); ?>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="col-md-4">Enter website url </td>
                                                <td class="col-md-8">
                                                    <input type="text" name="burl" placeholder="" class="form-control" value="<?php echo set_value('burl'); ?>">
                                                    <?php echo form_error('burl'); ?>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="col-md-4">Business Contact First Name *</td>
                                                <td class="col-md-8">
                                                    <input type="text" name="sfname" placeholder="" class="form-control" value="<?php echo set_value('sfname'); ?>">
                                                    <?php echo form_error('sfname'); ?>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="col-md-4">Business Contact Last Name *</td>
                                                <td class="col-md-8">
                                                    <input type="text" name="slname" placeholder="" class="form-control" value="<?php echo set_value('slname'); ?>">
                                                    <?php echo form_error('slname'); ?>
                                                </td>
                                            </tr>
                                         
                                            <tr>
                                                <td class="col-md-4">Business Owner Phone Number </td>
                                                <td class="col-md-8">
                                                    <input type="text" name="sphone" placeholder="" class="form-control" value="<?php echo set_value('sphone'); ?>">
                                                    <?php echo form_error('sphone'); ?>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="col-md-4">Business Email *</td>
                                                <td class="col-md-8">
                                                    <input type="text" name="email" placeholder="" class="form-control" value="<?php echo set_value('email'); ?>">
                                                    <?php echo form_error('email'); ?>
                                                </td>
                                            </tr>
                                     
                                          <!--   <tr>
                                                <td class="col-md-4">Password *</td>
                                                <td class="col-md-8">
                                                    <input type="password" name="password" placeholder="" class="form-control" value="<?php echo set_value('password'); ?>">
                                                    <?php echo form_error('password'); ?>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="col-md-4">Confirm Password *</td>
                                                <td class="col-md-8">
                                                    <input type="password" name="password1" placeholder="" class="form-control" value="<?php echo set_value('password1'); ?>">
                                                    <?php echo form_error('password1'); ?>
                                                </td>
                                            </tr> -->
                                            <tr>
                                                <td class="col-md-4"></td>
                                                <td class="col-md-8"><button type="submit" name="add_shop" class="btn btn-default">Submit</button></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                    </form> 
                                </div>
                            </div>
                        </div>
                        <!-- /.portlet -->
                    </div>
                    <!-- /.col-lg-6 -->
                </div>
            </div>
            <!-- /.page-content -->
        </div>
        <!-- /#page-wrapper -->
        <!-- end MAIN PAGE CONTENT -->
    
    </div>
    <!-- /#wrapper -->

    <!-- GLOBAL SCRIPTS -->
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
    <script src="<?php echo base_url(); ?>assets/js/plugins/bootstrap/bootstrap.min.js"></script>
    <script src="<?php echo base_url(); ?>assets/js/plugins/slimscroll/jquery.slimscroll.min.js"></script>
    <script src="<?php echo base_url(); ?>assets/js/plugins/popupoverlay/jquery.popupoverlay.js"></script>
    <script src="<?php echo base_url(); ?>assets/js/plugins/popupoverlay/defaults.js"></script>
    <?php require('logout.php'); ?>
    <!-- Logout Notification jQuery -->
    <script src="<?php echo base_url(); ?>assets/js/plugins/popupoverlay/logout.js"></script>
    <!-- HISRC Retina Images -->
    <script src="<?php echo base_url(); ?>assets/js/plugins/hisrc/hisrc.js"></script>

    <!-- PAGE LEVEL PLUGIN SCRIPTS -->

    <!-- THEME SCRIPTS -->
    <script src="<?php echo base_url(); ?>assets/js/flex.js"></script>

</body>

</html>
     