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
                                <li class="active">Add User</li>
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
                                    <h4>Add User</h4>
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
                                                <td class="col-md-4">User First Name *</td>
                                                <td class="col-md-8">
                                                    <input type="text" name="sfname" placeholder="" class="form-control" value="<?php echo set_value('sfname'); ?>">
                                                    <?php echo form_error('sfname'); ?>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="col-md-4">User Last Name *</td>
                                                <td class="col-md-8">
                                                    <input type="text" name="slname" placeholder="" class="form-control" value="<?php echo set_value('slname'); ?>">
                                                    <?php echo form_error('slname'); ?>
                                                </td>
                                            </tr>
                                         
                                            <tr>
                                                <td class="col-md-4">User Phone Number </td>
                                                <td class="col-md-8">
                                                    <input type="text" name="sphone" placeholder="" class="form-control" value="<?php echo set_value('sphone'); ?>">
                                                    <?php echo form_error('sphone'); ?>
                                                </td>
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
                                                        }else if(!empty($_POST)){
                                                            ?>
                                                                    <option value="0" selected>Others</option>
                                                                    <?php
                                                        }?>
                                                    </select>
                                                    <span id="city_loader"></span>
                                                    <?php echo form_error('scity'); ?>
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
                                                <td class="col-md-4">User Email *</td>
                                                <td class="col-md-8">
                                                    <input type="text" name="email" placeholder="" class="form-control" value="<?php echo set_value('email'); ?>">
                                                    <?php echo form_error('email'); ?>
                                                </td>
                                            </tr>
                                     
                                            <tr>
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
                                            </tr>

                                            <tr>
                                                <td class="col-md-4">Select Business</td>
                                                <td class="col-md-8">
                                                    <?php 
                                                    foreach ($corporate_busi_list as $cblkey => $cblvalue) { ?>
                                                        <input type="radio" name="cbid" <?php if (set_value('cbid')==$cblvalue['shop_id']) echo "checked";?> value="<?php echo $cblvalue['shop_id']?>"> <?php echo $cblvalue['shop_name']?><br>
                                                    <?php }?>
                                                    <?php echo form_error('cbid'); ?>
                                                </td>
                                            </tr>

                                            <tr>
                                                <td class="col-md-4">Profile Picture</td>
                                                <td class="col-md-8"><input type="file" name="sfile"></td>
                                            </tr>
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
     