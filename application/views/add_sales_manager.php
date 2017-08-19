<script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script> 
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/moment.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/bootstrap-datetimepicker.js"></script>
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
	function selectrole(role_id){
   
        $("#perent_loader").show();
        $("#perent_loader").fadeIn(400).html('Please wait... <img src="<?php echo base_url(); ?>assets/images/loading.gif" />');
        $.ajax({
            type: "POST",
            url: "<?php echo base_url(); ?>site/loadparent",
            data:{role : role_id},
            cache: false,
            success: function(result) {
                $("#perent_loader").hide();
                console.log(result);
                $("div#perent").html(result)
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
                            <h1>Add Sales Manager
                                
                            </h1>
                            <ol class="breadcrumb">
                                <li><i class="fa fa-dashboard"></i>  <a href="<?php echo base_url();?>site/index">Dashboard</a>
                                </li>
                                <li class="active">Add Sales Manager</li>
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
                                            <h4>Add Sales Manager</h4>
                                        </div>
                                        <div class="portlet-widgets">
                                            <a data-toggle="collapse" data-parent="#accordion" href="#formControls"><i class="fa fa-chevron-down"></i></a>
                                        </div>
                                        <div class="clearfix"></div>
                                    </div>
                                    <div id="formControls" class="panel-collapse collapse in">
                                        <div class="portlet-body">
                                            <form class="form-horizontal" enctype="multipart/form-data" method="POST" action="<?php echo base_url();?>site/add_sales_manager">
                                                <div class="form-group">
                                                    <label for="first_name" class="col-sm-2 control-label">First Name * : </label>
                                                    <div class="col-sm-10">
                                                        <input type="text" required class="form-control" name="first_name" id="first_name" value="<?php echo set_value('first_name'); ?>" placeholder="First Name of Sales Manager">
                                                         <?php echo form_error('first_name'); ?>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label for="last_name" class="col-sm-2 control-label">Last Name * : </label>
                                                    <div class="col-sm-10">
                                                        <input type="text" required class="form-control" name="last_name" id="last_name" value="<?php echo set_value('last_name'); ?>" placeholder="Last Name of Sales Manager">
                                                         <?php echo form_error('last_name'); ?>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label for="email" class="col-sm-2 control-label">Email *: </label>
                                                    <div class="col-sm-10">
                                                        <input type="text" required class="form-control" name="email" id="email" value="<?php echo set_value('email'); ?>" placeholder="Email">
                                                         <?php echo form_error('email'); ?>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label for="contact_num" class="col-sm-2 control-label">Contact Number For Emergency *: </label>
                                                    <div class="col-sm-10">
                                                        <input type="text" class="form-control" name="contact_num" id="contact_num" value="<?php echo set_value('contact_num'); ?>" placeholder="Contact Number">
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label for="profile_pic" class="col-sm-2 control-label">Profile Image : </label>
                                                    <div class="col-sm-10">
                                                        <input type="file" class="form-control" name="profile_pic" id="profile_pic" placeholder="profile_pic" value="<?php echo set_value('profile_pic'); ?>">
                                                         <?php echo form_error('profile_pic'); ?>
                                                    </div>
                                                </div>
                                                
                                                <div class="form-group">
                                                    <label for="birthdate" class="col-sm-2 control-label">Birth Day : </label>
                                                    <div class="col-sm-10">
                                                        <input class="form-control" type="text" name="birthday" placeholder="Birth Month-Date" id='datetimepicker4' value="<?php echo set_value('birthday'); ?>" >
                                                         <?php //echo form_error('user_name'); ?>
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
                                                            <option value="<?php echo $v['id']; ?>" <?php if($v['id'] == set_value('scountry')){echo 'selected';}?>><?php echo $v['name']; ?></option>
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

                                                                        <option value="<?php echo $states['sid']; ?>" <?php if($states['sid'] == set_value('sstate')){echo 'selected';}?>><?php echo $states['state_name']; ?></option>
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
                                                    </div>
                                                </div>
                                                
                                                <div class="form-group">
                                                    <label for="password" class="col-sm-2 control-label">Enter Zip Code * : </label>
                                                    <div class="col-sm-10">
                                                        <input type="text" name="zip_code"  placeholder="Zip Code" class="form-control" value="<?php echo set_value('zip_code'); ?>">
                                                    <?php echo form_error('zip_code'); ?>
                                                    </div>
                                                </div>
                                                
                                                <div class="form-group">
                                                    <label for="Account_num" class="col-sm-2 control-label">Enter Bank Account Number : </label>
                                                    <div class="col-sm-10">
                                                        <input type="text" name="bank_acount_num"  placeholder="Bank Account Number" class="form-control" value="<?php echo set_value('bank_acount_num'); ?>">
                                                    </div>
                                                </div>
                                                
                                                <div class="form-group">
                                                    <label for="Routing_num" class="col-sm-2 control-label">Enter Bank Routing Number : </label>
                                                    <div class="col-sm-10">
                                                        <input type="text" name="bank_routing_num"  placeholder="Bank Routing Number" class="form-control" value="<?php echo set_value('bank_routing_num'); ?>">
                                                    </div>
                                                </div>
                                                
                                                <div class="form-group">
                                                    <label for="full_address" class="col-sm-2 control-label">Enter Full Home Address : </label>
                                                    <div class="col-sm-10">
                                                        <textarea type="text" name="full_address"  placeholder="Full Home Address" class="form-control"></textarea>
                                                    </div>
                                                </div>
                                                
                                                <div class="form-group">
                                                    <label for="password" class="col-sm-2 control-label">Password * : </label>
                                                    <div class="col-sm-10">
                                                        <input type="password" required class="form-control" name="password" id="password" value="<?php echo set_value('password'); ?>" placeholder="Password">
                                                         <?php echo form_error('password'); ?>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label for="password1" class="col-sm-2 control-label">Confirm Password *: </label>
                                                    <div class="col-sm-10">
                                                        <input type="password" required class="form-control" name="password1" id="password1" value="<?php echo set_value('password1'); ?>" placeholder="Confirm Password">
                                                        <?php echo form_error('password1'); ?>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="col-sm-2 control-label">Sales Manager Type *:</label>
                                                    <div class="col-sm-10">
                                                        <select class="form-control"  name="role" onchange="selectrole(this.options[this.selectedIndex].value)">
                                                            <option value="2" <?php if(set_value('role')=="2"){echo "selected";} ?>>National Sales Manager</option>
                                                            <option value="3" <?php if(set_value('role')=="3"){echo "selected";} ?>>State Sales Manager</option>
                                                            <option value="4" <?php if(set_value('role')=="4"){echo "selected";} ?>>Area Sales Manager</option>
                                                        </select>
                                                        <?php echo form_error('role'); ?>
														<span id="perent_loader"></span>
                                                    </div>
                                                </div>
                                                 <div class="form-group" id="perent">
                                                </div>
                                                
                                                <div class="form-group">
                                                    <label class="col-sm-2 control-label"></label>
                                                    <div class="col-sm-10">
                                                        <button type="submit" class="btn btn-default" name="add_sales_manager">Add Sales Manager</button>
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
    
    
    <script  type="text/javascript">
        $(function () {
            $('#datetimepicker4').datetimepicker({
                format: 'MM-DD'
            });
            });
    </script>

    <!-- GLOBAL SCRIPTS -->
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