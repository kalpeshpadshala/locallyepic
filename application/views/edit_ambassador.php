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
        <!-- begin MAIN PAGE CONTENT -->
        <div id="page-wrapper">

            <div class="page-content">

                <!-- begin PAGE TITLE ROW -->
                <div class="row">
                    <div class="col-lg-12">
                        <div class="page-title">
                            <h1>Edit Ambassador
                                
                            </h1>
                            <ol class="breadcrumb">
                                <li><i class="fa fa-dashboard"></i>  <a href="<?php echo base_url();?>site/index">Dashboard</a>
                                </li>
                                <li class="active">Edit Ambassador</li>
                            </ol>
                        </div>
                    </div>
                    <!-- /.col-lg-12 -->
                </div>
                <!-- /.row -->
                <!-- end PAGE TITLE ROW -->

                <!-- begin MAIN PAGE ROW -->
                <div class="row">


                    <!-- begin RIGHT COLUMN -->
                    <div class="col-lg-12">

                        <div class="row">

                            <!-- Form Controls -->
                            <div class="col-lg-12">
                                <div class="portlet portlet-red">
                                    <div class="portlet-heading">
                                        <div class="portlet-title">
                                            <h4>Edit Ambassador</h4>
                                        </div>
                                        <div class="portlet-widgets">
                                            <a data-toggle="collapse" data-parent="#accordion" href="#formControls"><i class="fa fa-chevron-down"></i></a>
                                        </div>
                                        <div class="clearfix"></div>
                                    </div>
                                    <div id="formControls" class="panel-collapse collapse in">
                                        <div class="portlet-body">
                                            <form class="form-horizontal" method="POST" action="<?php echo base_url();?>site/edit_ambassador" enctype="multipart/form-data">
                                                <input type="hidden" name="user_id" value="<?php echo $info['user_id']; ?>">
                                                <div class="form-group">
                                                    <label for="first_name" class="col-sm-2 control-label">First Name * : </label>
                                                    <div class="col-sm-10">
                                                        <input type="text" required class="form-control" name="first_name" id="first_name" value="<?php echo $info['first_name']; ?>" placeholder="First Name of Sales Manager">
                                                         <?php echo form_error('first_name'); ?>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label for="last_name" class="col-sm-2 control-label">Last Name * : </label>
                                                    <div class="col-sm-10">
                                                        <input type="text" required class="form-control" name="last_name" id="last_name" value="<?php echo $info['last_name']; ?>" placeholder="Last Name of Sales Manager">
                                                         <?php echo form_error('last_name'); ?>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label for="email" class="col-sm-2 control-label">Email *: </label>
                                                    <div class="col-sm-10">
                                                        <input type="text" class="form-control" name="email" id="email" value="<?php echo $info['email']; ?>">
                                                         <?php echo form_error('email'); ?>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="col-sm-2 control-label">Country * :</label>
                                                    <div class="col-sm-10">
                                                        <select class="form-control" name="scountry" onchange="selectState(this.options[this.selectedIndex].value)">
                                                            <option value="">Select country</option>
                                                            <?php
                                                            foreach ($country as $v) {
                                                                ?>
                                                            <option value="<?php echo $v['id']; ?>" <?php echo set_select('scountry', $v['id'],( !empty($info['country_id']) && $info['country_id'] == $v['id'] ? TRUE : FALSE ))?>><?php echo $v['name']; ?></option>
                                                                <?php
                                                            }
                                                            ?>
                                                        </select>
                                                        <?php echo form_error('scountry'); ?>
                                                    </div>
                                                </div>
                                                
                                                <div class="form-group">
                                                    <label class="col-sm-2 control-label">State * :</label>
                                                    <div class="col-sm-10">
                                                        <select class="form-control" name="sstate" id="state_dropdown" onchange="selectCity(this.options[this.selectedIndex].value)">
                                                            <option value="">Select state</option>
                                                            <?php if(!empty($state)){
                                                                    foreach ($state as $states) {?>

                                                                        <option value="<?php echo $states['sid']; ?>" <?php echo set_select('sstate', $states['sid'],( !empty($info['state_id']) && $info['state_id'] == $states['sid'] ? TRUE : FALSE ))?>><?php echo $states['state_name']; ?></option>
                                                           <?php    }
                                                            }?>
                                                        </select>
                                                        <span id="state_loader"></span>
                                                        <?php echo form_error('sstate'); ?>
                                                    </div>
                                                </div>
                                                
                                                <div class="form-group">
                                                    <label class="col-sm-2 control-label">City * :</label>
                                                    <div class="col-sm-10">
                                                        <select class="form-control" name="scity" id="city_dropdown">
                                                            <option value="">Select city</option>
                                                            <?php if(!empty($city)){
                                                                    foreach ($city as $citys) {?>
                                                                        <option value="<?php echo $citys['city_id']; ?>" <?php echo set_select('scity', $citys['city_id'],( !empty($info['city_id']) && $info['city_id'] == $citys['city_id'] ? TRUE : FALSE ))?>><?php echo $citys['city_name']; ?></option>
                                                           <?php    }
                                                        }else{
                                                            ?>
                                                                    <option value="0" selected>Others</option>
                                                                    <?php
                                                        }?>
                                                        </select>
                                                        <span id="city_loader"></span>
                                                        <?php echo form_error('scity'); ?>
                                                    </div>
                                                </div>
                                                
                                                 <div class="form-group">
                                                    <label for="password" class="col-sm-2 control-label">Zip Code * : </label>
                                                    <div class="col-sm-10">
                                                        <input type="text" name="zip_code"  placeholder="" class="form-control" value="<?php echo $info['zip_code']; ?>">
                                                    <?php echo form_error('zip_code'); ?>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    
                                                    
                                                       <?php
                                                            if(!empty($info['profile_pic'])){
                                                        ?>
                                                        <label for="email" class="col-sm-2 control-label">Uploded Image : </label>
                                                            <div class="col-sm-10">
                                                                    <img src="<?php echo base_url(); ?>uploads/user/<?php echo $info['profile_pic']; ?>" width="150" height="auto">
                                                          
                                                            </div>        
                                                        <?php
                                                            }
                                                        ?>
                                                    
                                                </div>
                                                <div class="form-group">
                                                    <label for="profile_pic" class="col-sm-2 control-label">Change Image : </label>
                                          
                                                   
                                                    <div class="col-sm-10">
                                                        <input type="file" class="form-control" name="profile_pic" id="profile_pic" placeholder="profile_pic" value="<?php echo $info['profile_pic']; ?>">
                                                         <?php echo form_error('profile_pic'); ?>
                                                    </div>
                                                </div>
                                                
                                                
                                                <div class="form-group">
                                                    <label for="password" class="col-sm-2 control-label">Password *: </label>
                                                    <div class="col-sm-10">
                                                        <input type="password" class="form-control" name="password" id="password"  placeholder="Password">
                                                         <?php echo form_error('password'); ?>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label for="password1" class="col-sm-2 control-label">Confirm Password *: </label>
                                                    <div class="col-sm-10">
                                                        <input type="password" class="form-control" name="password1" id="password1"  placeholder="Confirm Password">
                                                        <?php echo form_error('password1'); ?>
                                                    </div>
                                                </div>
                                                <input type="hidden" name="role" id="role" value="8">
                                                
                                                
                                                <div class="form-group">
                                                    <label class="col-sm-2 control-label">Submit :</label>
                                                    <div class="col-sm-10">
                                                        <button type="submit" class="btn btn-default" name="edit_ambassador">Edit Ambassador</button>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                <!-- /.portlet -->
                            </div>
                            <!-- /.col-lg-12 (nested) -->
                            <!-- End Form Controls -->


                        </div>
                        <!-- /.row (nested) -->

                    </div>
                    <!-- /.col-lg-6 -->
                    <!-- end RIGHT COLUMN -->

                </div>
                <!-- /.row -->
                <!-- end MAIN PAGE ROW -->

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