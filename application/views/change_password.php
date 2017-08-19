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
    <script src="https://maps.googleapis.com/maps/api/js?v=3.exp"></script>
    <script>
    var geocoder;
    var map;
    function initialize() {
        
        geocoder = new google.maps.Geocoder();
        var latlng = new google.maps.LatLng(21.1592002, 72.8222963);
        var mapOptions = {
            zoom: 12,
            center: latlng
        }
        map = new google.maps.Map(document.getElementById('map-canvas'), mapOptions);
       
    }

    function codeAddress() {

        var address = document.getElementById('address').value;
        geocoder.geocode({'address': address}, function(results, status) {
            if (status == google.maps.GeocoderStatus.OK) {
                map.setCenter(results[0].geometry.location);
                var marker = new google.maps.Marker({
                    map: map,
                    position: results[0].geometry.location
                });
            } else {
                //alert('Geocode was not successful for the following reason: ' + status);
            }
        });
    }

    google.maps.event.addDomListener(window, 'load', initialize);

</script>
<div class="right-sec">
    <!-- Right Section Header Start -->
    <?php $this->load->view('top'); ?>
    <!-- Right Section Header End -->
    <!-- Content Section Start -->
    <div class="content-section">
        <div class="container-liquid">
            <div class="row">
                <div class="col-xs-12">
                    <div class="sec-box">
                        <a class="closethis">Close</a>
                        <header>
                            <h2 class="heading">Change Password</h2>
                            <?php
                            if ($message) {
                                ?>
                                <br/>
                                <div class="alert alert-success">
                                    <?php echo $message; ?>
                                </div>
                                <?php
                            }
                            ?>

                        </header>
                        <div class="contents">
                            <a class="togglethis">Toggle</a>
                            <div class="table-box">

                                <form action="<?php echo base_url(); ?>site/change_password" method="POST" enctype="multipart/form-data">  
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th class="col-md-4">Description</th>
                                                <th class="col-md-8">Form Elements</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                          
                                            <tr>
                                                <td class="col-md-4">Old password *</td>
                                                <td class="col-md-8">
                                                    <input type="password" name="old_password" placeholder="" class="form-control" value="<?php echo set_value('old_password'); ?>">
                                                    <?php echo form_error('old_password'); ?>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="col-md-4">New password *</td>
                                                <td class="col-md-8">
                                                    <input type="password" class="form-control" name="new_password1" value="<?php echo set_value('new_password1'); ?>">
                                                    <?php echo form_error('new_password1'); ?>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="col-md-4">Confirm password *</td>
                                                <td class="col-md-8">
                                                    <input type="password" class="form-control" name="new_password2" value="<?php echo set_value('new_password2'); ?>">
                                                    <?php echo form_error('new_password2'); ?>
                                                </td>
                                            </tr>
                                      
                                            <tr>
                                                <td class="col-md-4"></td>
                                                <td class="col-md-8"><button type="submit" name="change_password" class="btn btn-primary">Submit</button></td>
                                            </tr>


                                        </tbody>
                                    </table>
                                </form>   
                            </div>
                            <div class="clearfix"></div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Row End -->
        </div>
    </div>
    <!-- Content Section End -->
</div>