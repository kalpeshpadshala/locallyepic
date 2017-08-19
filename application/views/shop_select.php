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
                            <ol class="breadcrumb">
                                <li><i class="fa fa-dashboard"></i>  <a href="index.html">Dashboard</a>
                                </li>
                                <li class="active">Select Shop</li>
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
                <?php if($user_info['role'] == 1){ ?>
                <div class="row">
                 <!-- Hoverable Responsive Table -->
                    <div class="col-lg-12">
                        <div class="portlet portlet-default">
                            <div class="portlet-heading">
                                <div class="portlet-title">
                                    <h4>Select Shop</h4>
                                </div>
                                <div class="clearfix"></div>
                            </div>
                            <div class="portlet-body">
                                <div class="table-responsive">
                                    <form action="<?php echo base_url(); ?>site/statistic" method="GET" enctype="multipart/form-data">  
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th class="col-md-4">Description</th>
                                                <th class="col-md-8">Form Elements</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td class="col-md-4">Category</td>
                                                <td class="col-md-4">
                                                    <select class="form-control" name="scat">
                                                        <option value="">Select Category</option>
                                                        <?php
                                                        $arr_category =  explode(",",set_value('scat'));
                                                        
                                                        foreach ($cats as $cat) {
                                                            ?>
                                                        <option value="<?php echo $cat['cid']; ?>" <?php if(in_array($cat['cid'], $arr_category)){echo 'selected';}?> <?php if(isset($_GET['scat']) && ($_GET['scat']>0)){if($cat['cid'] == $_GET['scat']){echo 'selected';} }?>><?php echo $cat['cname']; ?></option>
                                                            <?php
                                                        }
                                                        ?>
                                                    </select>
                                                    <?php echo form_error('scat'); ?>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="col-md-4">Country</td>
                                                <td class="col-md-4">
                                                    <select class="form-control" name="scountry" onchange="selectState(this.options[this.selectedIndex].value)">
                                                        <option value="">Select country</option>
                                                        <?php
                                                        foreach ($country as $v) {
                                                            ?>
                                                        <option value="<?php echo $v['id']; ?>" <?php if($v['id'] == set_value('scountry')){echo 'selected';}?> <?php if(isset($_GET['scountry']) && ($_GET['scountry']>0)){if($v['id'] == $_GET['scountry']){echo 'selected';} }?>><?php echo $v['name']; ?></option>
                                                            <?php
                                                        }
                                                        ?>
                                                    </select>
                                                    <?php echo form_error('scountry'); ?>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="col-md-4">State</td>
                                                <td class="col-md-4">
                                                    <select class="form-control" name="sstate" id="state_dropdown" onchange="selectCity(this.options[this.selectedIndex].value)">
                                                        <option value="">Select state</option>
                                                        <?php if(!empty($state)){
                                                                foreach ($state as $states) {?>
                                                        
                                                                    <option value="<?php echo $states['sid']; ?>" <?php if($states['sid'] == set_value('sstate')){echo 'selected';}?> <?php if(isset($_GET['sstate']) && ($_GET['sstate']>0)){if($states['sid'] == $_GET['sstate']){echo 'selected';} }?>><?php echo $states['state_name']; ?></option>
                                                       <?php    }
                                                        }?>
                                                    </select>
                                                    <span id="state_loader"></span>
                                                    <?php echo form_error('sstate'); ?>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="col-md-4">City</td>
                                                <td class="col-md-4">
                                                    <select class="form-control" name="scity" id="city_dropdown">
                                                        <option value="">Select city</option>
                                                        <?php if(!empty($city)){
                                                                foreach ($city as $citys) {?>
                                                                    <option value="<?php echo $citys['city_id']; ?>" <?php if($citys['city_id'] == set_value('scity')){echo 'selected';}?> <?php if(isset($_GET['scity']) && ($_GET['scity']>0)){if($citys['city_id'] == $_GET['scity']){echo 'selected';} }?>><?php echo $citys['city_name']; ?></option>
                                                       <?php    }
                                                        }?>
                                                    </select>
                                                    <span id="city_loader"></span>
                                                    <?php echo form_error('scity'); ?>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="col-md-4">zip_code</td>
                                                <td class="col-md-4">
                                                   <input type="text" name="zip_code" class="form-control" <?php if(isset($_GET['zip_code']) && !empty($_GET['zip_code'])){echo 'value="' . $_GET['zip_code'] .'"'; }?>>
                                                </td>
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
                <?php } ?>
                <?php if(isset($info) && !empty($info)){?>
                
                <div class="row">
                 <!-- Hoverable Responsive Table -->
                    <div class="col-lg-12">
                        <div class="portlet portlet-default">
                            <div class="portlet-heading">
                                <div class="portlet-title">
                                    <h4>Shop List</h4>
                                </div>
                                <div class="clearfix"></div>
                            </div>
                            <div class="portlet-body">
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead>
                                           
                                                 <tr>
                                                    <th>Business Name</th>
                                                    <th>Shop Image</th>
                                                    <th>Address</th>
                                                    <th>Email</th>
                                                    <th>Date</th>
                                                    <th>Action</th>
                                                </tr>
                                           
                                        </thead>
                                        <tbody>
                                           <?php
                                                    foreach($info as $v){
                                                        
                                                        $src=(!empty($v['shop_image'])) ? base_url()."uploads/user/".$v['shop_image'] : base_url()."assets/images/shop_def.png";

                                                ?>
                                                        <tr>
                                                            <td><?php echo $v['shop_name']; ?></td>
                                                            <td>
                                                                <?php
                                                                    if(!empty($v['shop_image'])){
                                                                            echo '<img src="'.$src.'" style="width:128px;height:128px;" />';
                                                                    }else{
                                                                        echo "--";
                                                                    }
                                                                ?>
                                                               
                                                            </td>
                                                            <td><?php echo $v['address']; ?></td>
                                                            <td><?php echo $v['email']; ?></td>
                                                            <td><?php echo date("F j, Y, g:i a",strtotime($v['date'])); ?></td>
                                                             <td>
                                                                 <a class="btn btn-green" href="<?php echo base_url();?>site/statistics?id=<?php echo $v['shop_id']; ?>">Get Statistics</a>
                                                            </td> 
                                                        </tr>
                                                <?php
                                                    }
                                                ?>
                                        </tbody>
                                    </table>
                                    <ul class="pagination pagination-sm">
                                            
                                        <li><a href="<?php echo base_url(); ?>site/statistic/?page_no=<?php echo $prev; ?><?php if(isset($scountry)){ echo '&scountry=' . $scountry;}?><?php if(isset($sstate)){ echo '&sstate=' . $sstate;}?><?php if(isset($scity)){ echo '&scity=' . $scity;}?><?php if(isset($zip_code)){ echo '&zip_code=' . $zip_code;}?><?php if(isset($scat)){ echo '&scat=' . $scat;}?><?php if(isset($add_shop)){ echo '&add_shop=' . $add_shop;}?>">«</a></li>
                                            <?php
                                                for($i=$prev;$i<=$next;$i++){  
                                            ?>
                                            <li <?php if($curr_page==$i){echo 'class="active"';} ?>><a href="<?php echo base_url(); ?>site/statistic/?page_no=<?php echo $i; ?><?php if(isset($scountry)){ echo '&scountry=' . $scountry;}?><?php if(isset($sstate)){ echo '&sstate=' . $sstate;}?><?php if(isset($scity)){ echo '&scity=' . $scity;}?><?php if(isset($zip_code)){ echo '&zip_code=' . $zip_code;}?><?php if(isset($scat)){ echo '&scat=' . $scat;}?><?php if(isset($add_shop)){ echo '&add_shop=' . $add_shop;}?>" <?php if($curr_page==$i){echo 'class="active"';} ?>><?php echo $i; ?></a></li>
                                            <?php 
                                                }
                                            ?>
                                            <li><a href="<?php echo base_url(); ?>site/statistic/?page_no=<?php echo $next; ?><?php if(isset($scountry)){ echo '&scountry=' . $scountry;}?><?php if(isset($sstate)){ echo '&sstate=' . $sstate;}?><?php if(isset($scity)){ echo '&scity=' . $scity;}?><?php if(isset($zip_code)){ echo '&zip_code=' . $zip_code;}?><?php if(isset($scat)){ echo '&scat=' . $scat;}?><?php if(isset($add_shop)){ echo '&add_shop=' . $add_shop;}?>">»</a></li>
                                            
                                        </ul>
                                    </div>
                        </div>
                    </div>
                    <!-- /.portlet -->
                </div>
                <!-- /.col-lg-6 -->
            </div>
                <?php } ?>
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
    <!-- Logout Notification Box -->
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