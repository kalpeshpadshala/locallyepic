afasdf
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
<script>
    function selectState(country_id) {
        if (country_id != "") {
            loadData('state', country_id,<?php echo set_value("sstate",$info['state_id']);?>);
            $("#city_dropdown").html("<option value='-1'>Select city</option>");
        } else {
            $("#state_dropdown").html("<option value='-1'>Select state</option>");
            $("#city_dropdown").html("<option value='-1'>Select city</option>");
        }
    }
    function selectCity(state_id) {
        if (state_id != "-1") {
            loadData('city', state_id,<?php echo set_value("scity",$info['city_id']);?>);
        } else {
            $("#city_dropdown").html("<option value='-1'>Select city</option>");
        }
    }
    function loadData(loadType, loadId,setvalue) {
        var dataString = 'loadType=' + loadType + '&loadId=' + loadId + '&setvalue=' + setvalue;
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
    
     $( document ).ready(function() {
        
        if(<?php echo set_value("scountry",$info['country_id']);?>){
            $("#state_dropdown option[value='<?php echo set_value("sstate",$info["state_id"]);?>']").attr("selected", "selected");
            loadData('state', <?php echo set_value("scountry",$info['country_id']);?>,<?php echo set_value("sstate",$info['state_id']);?>);
        }
        if(<?php echo set_value("sstate",$info['state_id']);?>){
            $("#city_dropdown option[value='<?php echo set_value("scity",$info['city_id']);?>']").attr("selected", "selected");
            loadData('city', <?php echo set_value("sstate",$info['state_id']);?>,<?php echo set_value("scity",$info['city_id']);?>);
        }
        
    });
</script>
        <!-- begin MAIN PAGE CONTENT -->
        <div id="page-wrapper">

            <div class="page-content">

                <!-- begin PAGE TITLE ROW -->
                <div class="row">
                    <div class="col-lg-12">
                        <div class="page-title">
                            <h1>Edit Corporate User
                                
                            </h1>
                            <ol class="breadcrumb">
                                <li><i class="fa fa-dashboard"></i>  <a href="<?php echo base_url();?>site/index">Dashboard</a>
                                </li>
                                <li class="active">Edit Corporate User</li>
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
                            <div class="col-lg-12">
                        <?php
                        
                    if(!empty($this->session->userdata['current_message'])){
                 ?>
                            <div class="col-md-12">
                                <div class="alert alert-success alert-dismissable">
                                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                    <strong><?php echo $this->session->userdata['current_message']; ?></strong>
                                </div>
                            </div>
                 <?php
                    $array_items = array('current_message' => "");
                    $this->session->unset_userdata($array_items);
                    }
                 ?>
                      </div> 

                            <!-- Form Controls -->
                            <div class="col-lg-12">
                                <div class="portlet portlet-red">
                                    <div class="portlet-heading">
                                        <div class="portlet-title">
                                            <h4>Edit Corporate User</h4>
                                        </div>
                                        <div class="portlet-widgets">
                                            <a data-toggle="collapse" data-parent="#accordion" href="#formControls"><i class="fa fa-chevron-down"></i></a>
                                        </div>
                                        <div class="clearfix"></div>
                                    </div>
                                    <div id="formControls" class="panel-collapse collapse in">
                                        <div class="portlet-body">
                                            <?php function set_value1($col,$info){
                                            
                                             
                                                if(isset($_POST[$col])){
                                                    return $_POST[$col];
                                                }else if(isset($info[$col])){
                                                    return $info[$col];
                                                }else{
                                                    return "";
                                                }
                                            }?>
                                            <form class="form-horizontal" method="POST" enctype="multipart/form-data">
                                                
                                                <div class="form-group">
                                                    <label for="first_name" class="col-sm-2 control-label">First Name : </label>
                                                    <div class="col-sm-10">
                                                        <input type="text" required class="form-control" name="first_name" id="first_name" value="<?php echo set_value1('first_name', $info); ?>">
                                                         <?php echo form_error('first_name'); ?>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label for="last_name" class="col-sm-2 control-label">Last Name : </label>
                                                    <div class="col-sm-10">
                                                        <input type="text" required class="form-control" name="last_name" id="last_name" value="<?php echo set_value1('last_name', $info); ?>">
                                                         <?php echo form_error('last_name'); ?>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label for="email" class="col-sm-2 control-label">Email : </label>
                                                    <div class="col-sm-10">
                                                        <input type="text" required class="form-control" name="email" id="email" value="<?php echo set_value1('email', $info); ?>" placeholder="Email">
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
                                                    <label for="password" class="col-sm-2 control-label">Zip Code * : </label>
                                                    <div class="col-sm-10">
                                                        <input type="text" name="zip_code"  placeholder="" class="form-control" value="<?php echo set_value1('zip_code', $info); ?>">
                                                    <?php echo form_error('zip_code'); ?>
                                                    </div>
                                                </div>
                                                
                                                <div class="form-group">
                                                    
                                                    
                                                       <?php
                                                            if(!empty($info['profile_pic'])){
                                                        ?>
                                                        <label for="email" class="col-sm-2 control-label">Uploded Image : </label>
                                                            <div class="col-sm-10">
                                                                    <img src="<?php echo base_url(); ?>uploads/user/<?php echo set_value1('profile_pic', $info); ?>" width="150" height="auto">
                                                          
                                                            </div>        
                                                        <?php
                                                            }
                                                        ?>
                                                    
                                                </div>
                                                
                                                
                                                
                                                <div class="form-group">
                                                    <label for="profile_pic" class="col-sm-2 control-label">Change Image : </label>
                                          
                                                   
                                                    <div class="col-sm-10">
                                                        <input type="file" class="form-control" name="profile_pic" id="profile_pic" placeholder="profile_pic" value="<?php echo set_value1('profile_pic', $info); ?>">
                                                         <?php echo form_error('profile_pic'); ?>
                                                    </div>
                                                </div>
                                                

                                                <div class="form-group">
                                                    <label for="password" class="col-sm-2 control-label">Password : </label>
                                                    <div class="col-sm-10">
                                                        <input type="password" class="form-control" name="password" id="password" value="<?php set_value1('password', $info)?>"  placeholder="Password">
                                                         <?php echo form_error('password'); ?>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label for="password1" class="col-sm-2 control-label">Confirm Password : </label>
                                                    <div class="col-sm-10">
                                                        <input type="password" class="form-control" name="password1" id="password1" value="<?php set_value1('password', $info)?>"  placeholder="Confirm Password">
                                                        <?php echo form_error('password1'); ?>
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <label for="Account_num" class="col-sm-2 control-label">Enter Bank Account Number : </label>
                                                    <div class="col-sm-10">
                                                        <input type="text" name="bank_acount_num"  placeholder="Bank Account Number" class="form-control" value="<?php echo set_value1('bank_acount_num', $info); ?>" >
                                                    </div>
                                                </div>
                                                
                                                <div class="form-group">
                                                    <label for="Routing_num" class="col-sm-2 control-label">Enter Bank Routing Number : </label>
                                                    <div class="col-sm-10">
                                                        <input type="text" name="bank_routing_num"  placeholder="Bank Routing Number" class="form-control" value="<?php echo set_value1('bank_routing_num', $info); ?>" >
                                                    </div>
                                                </div>
                                                
                                                <div class="form-group">
                                                    <label for="full_address" class="col-sm-2 control-label">Enter Full Home Address : </label>
                                                    <div class="col-sm-10">
                                                        <textarea type="text" name="full_address"  placeholder="Full Home Address" class="form-control"><?php echo set_value1('full_address', $info); ?></textarea>
                                                    </div>
                                                </div>
                                             
                                                
                                                
                                                <div class="form-group">
                                                    <label class="col-sm-2 control-label">Submit :</label>
                                                    <div class="col-sm-10">
                                                        <button type="submit" class="btn btn-default" name="change_profile">Edit Profile</button>
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