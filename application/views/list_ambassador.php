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
<script type="text/javascript">
function delete_row(id){
            //alert($(this).attr("id"));
            //alert("hi");
            var r = confirm("Are You sure you want to delete the user");
            if (r == true) {
                ajax("delete_ambassador", id);
            } 
}

        function ajax(action, id) {


            $.ajax({
                type: "POST",
                url: "<?php echo base_url(); ?>site/"+action,
                data: {id: id},
                success: function(response){
                        if (response == 1) {
                            location.reload();

                        }
     
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
                                <li><i class="fa fa-dashboard"></i>  <a href="<?php echo base_url();?>site/index">Dashboard</a>
                                </li>
                                <li class="active">List Ambassador</li>
                            </ol>
                            
                        </div>
                    </div><!-- /.col-lg-12 -->
                </div>
                <!-- /.row -->
                <div class="row">
                    <div class="col-lg-12">
                    
                        <a class="btn btn-green" style="float:right" href="<?php echo base_url();?>site/add_ambassador">Add Ambassador</a>
                     
                    </div><!-- /.col-lg-12 -->
                </div>
                <br/>
                <!-- end PAGE TITLE ROW -->
                <div class="row">
                 <!-- Hoverable Responsive Table -->
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
                    
                    <div class="col-lg-12">
                        <div class="portlet portlet-default">
                            <div class="portlet-heading">
                                <div class="portlet-title">
                                    <h4>Search Ambassador</h4>
                                </div>
                                <div class="clearfix"></div>
                            </div>
                            <div class="portlet-body">
                                <div class="table-responsive">
                                    <form action="<?php echo base_url(); ?>site/list_ambassador" method="GET" >  
                                    <table class="table table-hover">
                                         
                                            <tr>
                                                
                                                <td><input type="text" name="search" id="search" class="form-control" <?php if(isset($search) && !empty($search)){ echo 'value="' . $search . '"';}?> placeholder="Search by name or email"></td>
                                           
                                               
                                                <td style="width: 15%;">
                                                    <select id="by_area_manager" name="by_area_manager" class="form-control">
                                                        <option value="0">Search By Area Manager</option>
                                                        <?php if(!empty($area_manager)){
                                                                foreach ($area_manager as $am) {?>
                                                        
                                                                    <option value="<?php echo $am['user_id']; ?>" <?php if(isset($_GET['by_area_manager'])){if($am['user_id'] == $_GET['by_area_manager']){echo 'selected';}}?>><?php echo $am['first_name'].' '.$am['last_name']; ?></option>
                                                       <?php    }
                                                        }?>
                                                    </select>
                                                </td>
                      
                                                <td style="width: 15%;">
                                                    <select class="form-control" name="scountry" onchange="selectState(this.options[this.selectedIndex].value)">
                                                        <option value="">Select country</option>
                                                        <?php
                                                        foreach ($country as $v) {
                                                            ?>
                                                        <option value="<?php echo $v['id']; ?>" <?php if(isset($_GET['scountry'])){if($v['id'] == $_GET['scountry']){echo 'selected';}}?>><?php echo $v['name']; ?></option>
                                                            <?php
                                                        }
                                                        ?>
                                                    </select>
                                                </td>
                                                <td style="width: 15%;">
                                                    <select class="form-control" name="sstate" id="state_dropdown" onchange="selectCity(this.options[this.selectedIndex].value)">
                                                        <option value="">Select state</option>
                                                        <?php if(!empty($state)){
                                                                foreach ($state as $states) {?>
                                                        
                                                                    <option value="<?php echo $states['sid']; ?>" <?php if(isset($_GET['sstate'])){if($states['sid'] == $_GET['sstate']){echo 'selected';}}?>><?php echo $states['state_name']; ?></option>
                                                       <?php    }
                                                        }?>
                                                    </select>
                                                    <span id="state_loader"></span>
                                                </td>
                                                <td style="width: 15%;">
                                                    <select class="form-control" name="scity" id="city_dropdown">
                                                        <option value="">Select city</option>
                                                        <?php if(!empty($city)){
                                                                foreach ($city as $citys) {?>
                                                                    <option value="<?php echo $citys['city_id']; ?>" <?php if(isset($_GET['scity'])){if($citys['city_id'] == $_GET['scity']){echo 'selected';}}?>><?php echo $citys['city_name']; ?></option>
                                                       <?php    }
                                                        }?>
                                                    </select>
                                                    <span id="city_loader"></span>
                                                </td>
                                             
                                                <td style="width: 10%;">
                                                    <input type="text" name="zip_code"  placeholder="Zip Code" class="form-control" >
                                                </td>
                                                <td>
                                                    <select id="perpage" name="perpage" class="form-control">
                                                        <option value="25" <?php if($page_row_limit=="25"){echo "selected";} ?>>Record per page</option>
                                                        <option <?php if($page_row_limit=="50"){echo "selected";} ?>>50</option>
                                                        <option <?php if($page_row_limit=="100"){echo "selected";} ?>>100</option>
                                                        <option <?php if($page_row_limit=="500"){echo "selected";} ?>>500</option>
                                                    </select>
                                                </td>
                                                <td><button type="submit" name="go" class="btn btn-default">GO</button></td>
                                            </tr>
                                    </table>
                                    </form> 
                                </div>
                            </div>
                        </div>
                        <!-- /.portlet -->
                    </div>
                    <!-- /.col-lg-6 -->
                </div>
                <div class="row">
                 <!-- Hoverable Responsive Table -->
                    <div class="col-lg-12">
                        <div class="portlet portlet-default">
                            <div class="portlet-heading">
                                <div class="portlet-title">
                                    <h4>List Ambassador</h4>
                                </div>
                                <div class="clearfix"></div>
                            </div>
                            <div class="portlet-body">
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead>
                                        
                                                 <tr>
                                                    <th>Name</th>
                                                    <th>Profile Image</th>
                                                    
                                                    <th>Email</th>
                                                    <th>Country</th>
                                                    <th>State</th>
                                                    <th>City</th>
                                                    
                                                 
                                                    <th>Action</th>
                                                </tr>
                                            
                                        </thead>
                                        <tbody>
                                           <?php
                               
                                                    foreach($info as $v){
                                                        
                                      
                                                            $country_name=get_country_name($v['country_id']);
                                                          
                                                 
                                                            $state_name=get_state_name($v['state_id']);
                                                           
                                                  
                                                            $city_name=get_city_name($v['city_id']);
                                                           
                                                           
                                                        
                                                        
                                                        $src=(!empty($v['profile_pic'])) ? base_url()."uploads/user/".$v['profile_pic'] : base_url()."assets/img/profile-pic.jpg";

                                                ?>
                                                        <tr>
                                                            <td><?php echo $v['first_name']." ".$v['last_name']; ?></td>
                                                            <td>
                                                                <?php
             
                                                                            echo '<img src="'.$src.'" width="150" height="auto"/>';
                                                                
                                                                ?>
                                                               
                                                            </td>
                                                            
                                                            <td><?php echo $v['email']; ?></td>
                                                            <td><?php echo $country_name; ?></td>
                                                            <td><?php echo $state_name; ?></td>
                                                            <td><?php echo $city_name; ?></td>
                                                            
                                                         
                                                             <td>
                                                                <a href="<?php echo base_url(); ?>site/edit_ambassador/?id=<?php echo $v['user_id']; ?>"><img src="<?php echo base_url(); ?>assets/images/edit.png" /></a>&nbsp;&nbsp;&nbsp;
                                                                <a href="javascript:void(0);" id="del_<?php echo $v['user_id']; ?>" onclick="javascript:delete_row(this.id);"><img src="<?php echo base_url(); ?>assets/images/del.png" /></a>
                                                            </td> 
                                                        </tr>
                                                <?php
                                                    }
                                                ?>
                                        </tbody>
                                    </table>
                                    <ul class="pagination pagination-sm">
                                            
                                            <li><a href="<?php echo base_url(); ?>site/list_ambassador/?page_no=<?php echo $prev; ?>">«</a></li>
                                            <?php
                                                for($i=$prev;$i<=$next;$i++){  
                                            ?>
                                            <li <?php if($curr_page==$i){echo 'class="active"';} ?>><a href="<?php echo base_url(); ?>site/list_ambassador/?page_no=<?php echo $i; ?>" <?php if($curr_page==$i){echo 'class="active"';} ?>><?php echo $i; ?></a></li>
                                            <?php 
                                                }
                                            ?>
                                            <li><a href="<?php echo base_url(); ?>site/list_ambassador/?page_no=<?php echo $next; ?>">»</a></li>
                                            
                                        </ul>
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