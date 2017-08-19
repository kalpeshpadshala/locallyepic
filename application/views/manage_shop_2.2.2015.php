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
<script src="https://maps.googleapis.com/maps/api/js?v=3.exp"></script>
<script>
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(showPosition);
    }
    var glat;
    var glong;

    function showPosition(position) {
        glat = position.coords.latitude;
        glong = position.coords.longitude;
    }
    var geocoder;
    var map;
    function initialize() {

        geocoder = new google.maps.Geocoder();

        var latlng = new google.maps.LatLng(glat, glong);

        var mapOptions = {
            zoom: 12,
            center: latlng
        }
        map = new google.maps.Map(document.getElementById('map-canvas'), mapOptions);

    }

    function codeAddress() {

        var address = document.getElementById('address').value;
        var stat_val=$("#state_dropdown option:selected").text();

        var city_val=$("#city_dropdown option:selected").text();
        var country_val=$("#scountry option:selected").text();
        if(stat_val !== "Others"){
            address+=","+stat_val;
        }
        if(city_val !== "Others"){
            address+=","+city_val;
        }
    
        address+=","+country_val;
       
        geocoder.geocode({'address': address}, function(results, status){
            if (status == google.maps.GeocoderStatus.OK) {

                var aa = results[0].geometry.location;
                var aaa = aa.toString();
                var resaa = aaa.split(",");
                var latitude = resaa[0].replace("(", "");
                var longitude = resaa[1].replace(")", "");

                $("#latitude").val(latitude);
                $("#longitude").val(longitude);
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
                            <h2 class="heading">Add Shop</h2>
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

                                <form action="<?php echo base_url(); ?>site/manage_shop" method="POST" enctype="multipart/form-data">
                                    <input type="hidden" name="latitude" value="" id="latitude">
                                    <input type="hidden" name="longitude" value="" id="longitude">
                                    <table class="table">
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
                                                    <select style="height:200px" class="form-control" name="scat[]" multiple>
                                                        <option value="">Select Category</option>
                                                        <?php
                                                        $arr_category =  explode(",",set_value('scat[]'));
                                                        
                                                        foreach ($cats as $cat) {
                                                            ?>
                                                        <option value="<?php echo $cat['cid']; ?>" <?php if(in_array($cat['cid'], $arr_category)){echo 'selected';}?>><?php echo $cat['cname']; ?></option>
                                                            <?php
                                                        }
                                                        ?>
                                                    </select>
                                                    <?php echo form_error('scat[]'); ?>
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
                                                <td class="col-md-4">File upload</td>
                                                <td class="col-md-8"><input type="file" name="sfile"></td>
                                            </tr>
                                            <tr>
                                                <td class="col-md-4">Country *</td>
                                                <td class="col-md-4">
                                                    <select class="form-control" name="scountry" onchange="selectState(this.options[this.selectedIndex].value)">
                                                        <option value="">Select country</option>
                                                        <?php
                                                        foreach ($country as $v) {
                                                            ?>
                                                        <option value="<?php echo $v['id']; ?>" <?php if($v['id'] == set_value('scountry')){echo 'selected';}?>><?php echo $v['name']; ?></option>
                                                            <?php
                                                        }
                                                        ?>
                                                    </select>
                                                    <?php echo form_error('scountry'); ?>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="col-md-4">State *</td>
                                                <td class="col-md-4">
                                                    <select class="form-control" name="sstate" id="state_dropdown" onchange="selectCity(this.options[this.selectedIndex].value)">
                                                        <option value="">Select state</option>
                                                        <?php if(!empty($state)){
                                                                foreach ($state as $states) {?>
                                                        
                                                                    <option value="<?php echo $states['sid']; ?>" <?php if($states['sid'] == set_value('sstate')){echo 'selected';}?>><?php echo $states['state_name']; ?></option>
                                                       <?php    }
                                                        }?>
                                                    </select>
                                                    <span id="state_loader"></span>
                                                    <?php echo form_error('sstate'); ?>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="col-md-4">City *</td>
                                                <td class="col-md-4">
                                                    <select class="form-control" name="scity" id="city_dropdown">
                                                        <option value="">Select city</option>
                                                        <?php if(!empty($city)){
                                                                foreach ($city as $citys) {?>
                                                                    <option value="<?php echo $citys['city_id']; ?>" <?php if($citys['city_id'] == set_value('scity')){echo 'selected';}?>><?php echo $citys['city_name']; ?></option>
                                                       <?php    }
                                                        }?>
                                                    </select>
                                                    <span id="city_loader"></span>
                                                    <?php echo form_error('scity'); ?>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="col-md-4">Enter business address *</td>
                                                <td class="col-md-8">
                                                    <input type="text" name="sadd" onblur="codeAddress();" id="address" placeholder="" class="form-control" value="<?php echo set_value('sadd'); ?>">
                                                    <?php echo form_error('sadd'); ?>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="col-md-4">Enter Zip Code *</td>
                                                <td class="col-md-8">
                                                    <input type="text" name="zip_code"  placeholder="" class="form-control" value="<?php echo set_value('zip_code'); ?>">
                                                    <?php echo form_error('zip_code'); ?>
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
                                                <td class="col-md-4">Business Email *</td>
                                                <td class="col-md-8">
                                                    <input type="text" name="semail" placeholder="" class="form-control" value="<?php echo set_value('semail'); ?>">
                                                    <?php echo form_error('semail'); ?>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="col-md-4">Username *</td>
                                                <td class="col-md-8">
                                                    <input type="text" name="suname" placeholder="" class="form-control" value="<?php echo set_value('suname'); ?>">
                                                    <?php echo form_error('suname'); ?>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="col-md-4">Password *</td>
                                                <td class="col-md-8">
                                                    <input type="text" name="spass" placeholder="" class="form-control" value="<?php echo set_value('spass'); ?>">
                                                    <?php echo form_error('spass'); ?>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="col-md-4">Confirm Password *</td>
                                                <td class="col-md-8">
                                                    <input type="text" name="spass1" placeholder="" class="form-control" value="<?php echo set_value('spass1'); ?>">
                                                    <?php echo form_error('spass1'); ?>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="col-md-4">Location Map </td>
                                                <td class="col-md-8">
                                                    <div id="map-canvas" width="700" height="500"></div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="col-md-4"></td>
                                                <td class="col-md-8"><button type="submit" name="add_shop" class="btn btn-primary">Submit</button></td>
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