<style>

#spinner.active {
    display: block;
    
}

#spinner {
    display: none;
    position: absolute;
    height: 60px;
    width: 60px;
    top: 40%;
    left: 48%;
    z-index: 1;
}
.spinner_ball {
    position: absolute;
    display: block;
    background-color: white;
    left: 24px;
    width: 12px;
    height: 12px;
    border-radius: 6px;
}
#ball_1 {
    -webkit-animation-timing-function: cubic-bezier(0.5, 0.3, 0.9, 0.9);
    -webkit-animation-name: rotate; 
    -webkit-animation-duration: 2s; 
    -webkit-animation-iteration-count: infinite;
    -webkit-transform-origin: 6px 30px;
}
#ball_2 {
    -webkit-animation-timing-function: cubic-bezier(0.5, 0.5, 0.9, 0.9);
    -webkit-animation-name: rotate; 
    -webkit-animation-duration: 2s; 
    -webkit-animation-iteration-count: infinite;
    -webkit-transform-origin: 6px 30px;
}
#ball_3 {
    -webkit-animation-timing-function: cubic-bezier(0.5, 0.7, 0.9, 0.9);
    -webkit-animation-name: rotate; 
    -webkit-animation-duration: 2s; 
    -webkit-animation-iteration-count: infinite;
    -webkit-transform-origin: 6px 30px;
}
@-webkit-keyframes rotate {
  0% {
    -webkit-transform: rotate(0deg) scale(1);
  }
  100% { 
    -webkit-transform: rotate(1440deg) scale(1); 
  }
}​
</style>
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
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
function disable(id){
            //alert($(this).attr("id"));
//            alert(id);
            var r = confirm("Are You sure you want to Disable this User?");
            if (r == true) {
                ajax("disable_user", id);
            } 
}

function ajax(action, id) {


    $.ajax({
        type: "POST",
        url: "<?php echo base_url(); ?>site/"+action,
        data: {id: id},
        success: function(response){
            if(action=="disable_user"){
                location.reload();

            }

        }

    });
}


function send_reset_password(user_id){
    $(".page-content").css("opacity", "0.1");
    $("#spinner").addClass("active");
    $.ajax({
        type: "POST",
        url: "<?php echo base_url(); ?>wapi/send_reset_password",
        data: {user_id: user_id},
        success: function(response){
            
            alert("The reset password link has been successfully sent");
            $(".page-content").css("opacity", "1");
            $("#spinner").removeClass("active");
            //location.reload();

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
                                <li class="active">App Research</li>
                            </ol>
                            
                        </div>
                    </div><!-- /.col-lg-12 -->
                </div>
                <!-- /.row -->
                <!-- end PAGE TITLE ROW -->
                <div class="row">
                 <!-- Hoverable Responsive Table -->
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
               
                            
          
                  <div class="col-lg-12">
                           
                        <div class="portlet portlet-default">
                            <div class="portlet-heading">
                                <div class="portlet-title">
                                    <h4>Search customer</h4>
                                </div>
                                <div class="clearfix"></div>
                            </div>
                            <div class="portlet-body">
                             
                            <a class="btn btn-green" style="float:right" href="/site/add_consumers">Add Customer</a>
                       
                                   
                                <div class="table-responsive">
                                    <form action="<?php echo base_url(); ?>site/list_consumers" method="GET" enctype="multipart/form-data">  
                                    <table class="table table-hover">
                                         <tr><td colspan="5" style="border-top: 0px solid #ddd;">&nbsp;</td></tr>
                                            <tr>
                                                <td style="width: 6%; vertical-align: middle;">Search By :&nbsp;</td>
                                                <td style="width: 43%;"><input type="text" name="search" id="search" placeholder="search by business name or email" <?php if(isset($search) && !empty($search)){ echo 'value="' . $search . '"';}?> class="form-control"></td>
                                                <td style="width: 6%; vertical-align: middle;">&nbsp;&nbsp;entity :&nbsp;</td>
                                                <td>
                                                    <select id="which" name="which" class="form-control">
                                                        <option>consumer</option>
                                                        <option>shop</option>
                                                       
                                                    </select>
                                                </td>
                                                <td>&nbsp;&nbsp;<button type="submit" name="go" class="btn btn-default">GO</button></td>
                                            </tr>
                                            <tr><td colspan="5" style="border-top: 1px solid #ddd;">&nbsp;</td></tr>
                                        </table>
                                        </form> 
                                </div>
                            </div>
                        </div>
                        <!-- /.portlet -->
                    </div>
                    <!-- /.col-lg-6 -->
                </div>
                <?php if(isset($info) && !empty($info)){?>
                <div class="row">
                 <!-- Hoverable Responsive Table -->
                    <div class="col-lg-12">
                        <div class="portlet portlet-default">
                            <div class="portlet-heading">
                                <div class="portlet-title">
                                    <h4>Customer List</h4>
                                </div>
                                <div class="clearfix"></div>
                            </div>
                            <div class="portlet-body">
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead>
                                           
                                                 <tr>
                                                    <th>Customer Name</th>
                                                    <th>Device</th>
                                                    <th>Address</th>
                                                    <th>Email</th>
                                                    <th>Date</th>
                                                    <th>Action</th>
                                                </tr>
                                           
                                        </thead>
                                        <tbody>
                                           <?php foreach($info as $v){  ?>
                                                    <tr>
                                                        <td><?php echo $v['name']; ?></td>
                                                        <td><?php if($v['device_type'] == 1){echo "I-Phone";}elseif ($v['device_type'] == 2){echo "Android";}else {echo " ";} ?></td>
                                                        <td><?php echo $v['address']; ?></td>
                                                        <td><?php echo $v['email']; ?></td>
                                                        <td><?php echo date("F j, Y, g:i a",strtotime($v['date'])); ?></td>
                                                        <td>
                                                            <a class="btn btn-green btn-lg" data-toggle="modal" data-target="#flexModal_push_<?php echo $v['user_id']; ?>" href="#"  >Send Push</a>
                                                     
                                                            <div class="modal modal-flex fade" id="flexModal_push_<?php echo $v['user_id']; ?>" tabindex="-1" role="dialog" aria-labelledby="flexModalLabel" aria-hidden="true">
                                                                <div class="modal-dialog">
                                                                    <div class="modal-content">
                                                                        <div class="modal-header">
                                                                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                                                            <h4 class="modal-title" id="flexModalLabel">Send Push to <?php echo $v['name']; ?></h4>
                                                                        </div>
                                                                        
                                                                        <form name="frm_send_push" action="<?php echo base_url(); ?>wapi/push_send_user/?page_id=<?php echo $curr_page; ?>" method="POST">
                                                                            <input type="hidden" name="user_id" value="<?php echo $v['user_id']; ?>">
                                                                            <div class="modal-body">
                                                                                <textarea name="push_text" rows="5" cols="45" placeholder="Enter text for push message"></textarea>
                                                                            </div>
                                                                            <div class="modal-footer">
                                                                                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                                                                <button type="submit" class="btn btn-green">Send Push</button>
                                                                            </div>
                                                                        </form>
                                                                    </div>
                                                                    <!-- /.modal-content -->
                                                                </div>
                                                                <!-- /.modal-dialog -->
                                                            </div>
                                                            
                                                        </td>
                                                        <td>
                                                            <a href="<?php echo base_url(); ?>site/edit_consumers/?id=<?php echo $v['user_id']; ?>"><img src="<?php echo base_url(); ?>assets/images/edit.png" /></a>
                                                        </td>
                                                        <td><a class="btn btn-purple" href="#" onclick="send_reset_password(<?php echo $v['user_id']; ?>)">Send Reset Password</a></td>
                                                        <?php if($v['is_disable'] == 0){?>
                                                        <td><a class="btn btn-red" href="#" onclick="disable(<?php echo $v['user_id']; ?>)">Disable</a></td>
                                                        <?php }else{?>
                                                        <td><a class="btn btn-red disabled" href="">Disabled</a></td>
                                                        <?php }?>
                                                    </tr>
                                                <?php
                                                    }
                                                ?>
                                        </tbody>
                                    </table>
                                    <ul class="pagination pagination-sm">
                                            
                                            <li><a href="<?php echo base_url(); ?>site/list_consumers/?page_no=<?php echo $prev; ?>">«</a></li>
                                            <?php
                                                for($i=$prev;$i<=$next;$i++){  
                                            ?>
                                            <li <?php if($curr_page==$i){echo 'class="active"';} ?>><a href="<?php echo base_url(); ?>site/list_consumers/?page_no=<?php echo $i; ?>" <?php if($curr_page==$i){echo 'class="active"';} ?>><?php echo $i; ?></a></li>
                                            <?php 
                                                }
                                            ?>
                                            <li><a href="<?php echo base_url(); ?>site/list_consumers/?page_no=<?php echo $next; ?>">»</a></li>
                                            
                                        </ul>
                                    </div>
                        </div>
                    </div>
                    <!-- /.portlet -->
                </div>
                <!-- /.col-lg-6 -->
            </div>
                
                <?php }?>
            </div>
            <!-- /.page-content -->
        </div>
        <!-- /#page-wrapper -->
        <!-- end MAIN PAGE CONTENT -->
          
    </div>
    
    
    <div id="spinner">
        <span id="ball_1" class="spinner_ball"></span>
        <span id="ball_2" class="spinner_ball"></span>
        <span id="ball_3" class="spinner_ball"></span>
    </div>
    
    <!-- /#wrapper -->

    <!-- GLOBAL SCRIPTS -->
    
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
    <script src="<?php echo base_url(); ?>assets/js/plugins/messenger/messenger.min.js"></script>
    <script src="<?php echo base_url(); ?>assets/js/plugins/messenger/messenger-theme-flat.js"></script>
    <!-- PAGE LEVEL PLUGIN SCRIPTS -->

    <!-- THEME SCRIPTS -->
    <script src="<?php echo base_url(); ?>assets/js/flex.js"></script>
      <!-- PAGE LEVEL PLUGIN SCRIPTS -->
  

    <!-- THEME SCRIPTS -->
   
    <script src="<?php echo base_url(); ?>assets/js/demo/notifications-demo.js"></script>

</body>

</html>