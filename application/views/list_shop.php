<?php

if (!isset($_GET["scity"])){$_GET["scity"]="";}
if (!isset($_GET["sstate"])){$_GET["sstate"]="";}
?>
<script>
$(document).ready(function(){

    $("select[name='scountry']").trigger('change');
});
    function selectState(country_id) {
        if (country_id != "") {
            loadData('state', country_id, 'state_dropdown', '<?php echo $_GET["sstate"]?>');
            $("#city_dropdown").html("<option value='-1'>Select city</option>");
            <?php if(isset($_GET['sstate'])){ ?>$("#state_dropdown option[value='<?php echo $_GET['sstate']?>']").prop('selected',true); <?php }?>
        } else {
            $("#state_dropdown").html("<option value='-1'>Select state</option>");
            $("#city_dropdown").html("<option value='-1'>Select city</option>");
        }
    }
    function selectCity(state_id) {
        if (state_id != "-1") {
            loadData('city', state_id, 'city_dropdown', '<?php echo $_GET["scity"]?>');
        } else {
            $("#city_dropdown").html("<option value='-1'>Select city</option>");
        }
    }
    function loadData(loadType, loadId, id, value) {

        id = typeof id !== 'undefined' ? id : '';
        value = typeof value !== 'undefined' ? value : '';

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
                if (id!=='' && value!==''){
                    console.log(id,value);
                    console.log($("#"+id+" option[value='"+value+"']"));
                    

                    if (id=='state_dropdown') {
                        $("#"+id+" option[value='"+value+"']").prop('selected',true);
                        $("#state_dropdown").trigger('change');
                    }

                    if (id=='city_dropdown') {
                        $("#"+id+" option[value='"+value+"']").prop('selected',true);
                        //$("#state_dropdown").trigger('change');
                    }

                }
            }
        });
    }
</script>
<script type="text/javascript">
function delete_row(id){
            //alert($(this).attr("id"));
            //alert("hi");
            var r = confirm("Are You sure you want to delete this business?");
            if (r == true) {
                ajax("delete_shop", id);
            } 
}

        function ajax(action, id) {


            $.ajax({
                type: "POST",
                url: "<?php echo base_url(); ?>site/"+action,
                data: {id: id},
                success: function(response){
                    if(action=="delete_shop"){
       
                        if (response == "1") {
                            location.reload();

                        }
                    }

                    if(action=="update_status"){
       
                        if (response == "2") {
                            alert('Please Try Later');
                        }
                    }

                }

            });
        }

function update_status(id,value){

    ajax("update_status", id);
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
                                <li><i class="fa fa-dashboard"></i>  <a href="/site/index">Dashboard</a>
                                </li>
                                <li class="active">Business List</li>
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
                                    <h4>Search Business</h4>
                                </div>
                                <div class="clearfix"></div>
                            </div>
                            <div class="portlet-body">
                             
                                  
                                  <?php if((is_permission($this->session->userdata['role'], "manage_shop")) == TRUE){ ?>       
                                <a class="btn btn-green" style="float:right" href="<?php echo base_url();?>site/manage_shop">Add Business</a>
                                <?php } ?>
                       
                                   
                                <div class="table-responsive">
                                    <form action="<?php echo base_url(); ?>site/list_shop" method="GET" enctype="multipart/form-data">  
                                    <table class="table table-hover">
                                         <tr><td colspan="5" style="border-top: 0px solid #ddd;">&nbsp;</td></tr>
                                            <tr>
                                                <td style="width: 7%; vertical-align: middle;">Search By :&nbsp;</td>
                                                <td style="width: 35%;"><input type="text" placeholder="shop name or email" name="search" id="search" class="form-control" <?php if(isset($search) && !empty($search)){ echo 'value="' . $search . '"';}?>></td>
                                                <td style="width: 7%; vertical-align: middle;">&nbsp;&nbsp;Per Page :&nbsp;</td>
                                                <td>
                                                    <select id="perpage" name="perpage" class="form-control">
                                                        <option>10</option>
                                                        <option>25</option>
                                                        <option>50</option>
                                                        <option>100</option>
                                                        <option>500</option>
                                                    </select>
                                                </td>
                                                
                                                <?php if($this->session->userdata['role'] == 9){?>
                                                <td style="width: 7%; vertical-align: middle;">&nbsp;&nbsp;Status :&nbsp;</td>
                                                 <td>
                                                    <select id="ustatus" name="ustatus" class="form-control">
                                                        <option <?php if(isset($_GET['ustatus'])){if('All' == $_GET['ustatus']){echo 'selected';}}?> >All</option>
                                                        <option <?php if(isset($_GET['ustatus'])){if('Active' == $_GET['ustatus']){echo 'selected';}}?> >Active</option>
                                                        <option <?php if(isset($_GET['ustatus'])){if('Deactivate' == $_GET['ustatus']){echo 'selected';}}?> >Deactivate</option>
                                                    </select>
                                                </td>
                                                <?php }?>

                                                <td>&nbsp;&nbsp;<button type="submit" name="go" value='filter' class="btn btn-default">GO</button> <a class="pull-right" href="<?php echo base_url(); ?>site/list_shop">Reset Search</a></td>
                                            </tr>
                                            <tr><td colspan="7" style="border-top: 1px solid #ddd;">&nbsp;</td></tr>
                                        </table>
                                        </form>
                                        <form action="<?php echo base_url(); ?>site/list_shop" method="GET" enctype="multipart/form-data">
                                        <table class="table table-hover">
                                            <tr><td colspan="7" style="border-top: 0px solid #ddd;">&nbsp;</td></tr>
                                            <tr>
                                                <td style="width: 7%; vertical-align: middle;">Search By :&nbsp;</td>
                                                <td style="width: 15%;">
                                                    <select class="form-control" name="scountry" <?php if(($user_info['role'] == 1) || ($user_info['role'] == 2)){?> onchange="selectState(this.options[this.selectedIndex].value)" <?php } ?>>
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
                                                    <select class="form-control" name="sstate" id="state_dropdown" <?php if(!($user_info['role'] == 4)){?> onchange="selectCity(this.options[this.selectedIndex].value)" <?php } ?>>
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
                                                <td style="width: 15%;">
                                                    <select class="form-control" name="scat">
                                                        <option value="">Select Category</option>
                                                        <?php

                                                        if (isset($_GET['scat'])) {
                                                            $sel_category =  $_GET['scat'];
                                                        } else {
                                                            $sel_category = 0;
                                                        }
                                                        
                                                        
                                                        foreach ($cats as $cat) {
                                                            ?>
                                                        <option value="<?php echo $cat['cid']; ?>" <?php if($cat['cid']==$sel_category){echo 'selected';}?>><?php echo $cat['cname']; ?></option>
                                                            <?php
                                                        }
                                                        ?>
                                                    </select>
                                                </td>
                                                <td style="width: 15%;">
                                                    <input type="text" name="zip_code" value="<?php if (isset($_GET['zip_code'])){echo $_GET['zip_code'];}?>"  placeholder="Zip Code" class="form-control" >
                                                </td>

                                                <td>&nbsp;&nbsp;<button type="submit" name="add_shop" value="add_shop" class="btn btn-default">GO</button></td>
                                            </tr>
                                            <tr><td colspan="7" style="border-top: 1px solid #ddd;">&nbsp;</td></tr>
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
                                    <h4>Business List</h4>
                                </div>
                                <div class="clearfix"></div>
                            </div>
                            <div class="portlet-body">
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead>
                                           
                                                 <tr>
                                                    <th>Business Name</th>
                                                    <th>Business Image</th>
                                                    <th>Address</th>
                                                    <th>Email</th>
                                                    <th>Date</th>
                                                    <th>Action</th>
                                                </tr>
                                           
                                        </thead>
                                        <tbody>
                                           <?php

                                                // echo "<pre>";
                                                // print_r($info);
                                                // exit();
                                         
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
                                                                 <a class="btn btn-green" href="<?php echo base_url(); ?>site/view_shop/?id=<?php echo $v['shop_id']; ?>">View</a>
                                                                <!--<a href="<?php echo base_url(); ?>site/view_shop/?id=<?php echo $v['shop_id']; ?>"><img src="<?php echo base_url(); ?>assets/icons/View-Details.png" width="14%" /></a>&nbsp;&nbsp;&nbsp;-->
                                                                <?php if($user_info['role'] != 5){ ?>
                                                                <a href="<?php echo base_url(); ?>site/edit_shop/?id=<?php echo $v['shop_id']; ?>"><img src="<?php echo base_url(); ?>assets/images/edit.png" /></a>&nbsp;
                                                                <?php }?>
                                                                <?php
                                                                if($user_info['role'] == 9){
                                                                    if( $v['user_id'] != $user_info['user_id']){
                                                                ?>
                                                                <select id="up_<?php echo $v['user_id']; ?>" onChange="javascript:update_status(this.id,this.value);">
                                                                    <option value='1' id='opt_1' <?php if($v['user_is_active'] == 'Yes'){echo 'selected';}?> >Active</option>
                                                                    <option value='0' id='opt_0' <?php if($v['user_is_active'] =="No"){echo 'selected';}?> >Deactivate</option>
                                                                </select>
                                                                <?php
                                                                    }
                                                                }
                                                                ?>


                                                                <?php
                                                                    if($user_info['role'] == 1 || $user_info['role'] == 2){
                                                                 ?>
                                                                
                                                                <a href="javascript:void(0);" id="del_<?php echo $v['shop_id']; ?>" onclick="javascript:delete_row(this.id);"><img src="<?php echo base_url(); ?>assets/images/del.png" /></a>
                                                                <?php
                                                                    }
                                                                ?>
                                                            </td> 
                                                        </tr>
                                                <?php
                                                    }
                                                ?>
                                        </tbody>
                                    </table>
                                    <?php 
                                    //echo "<pre>".print_r($_GET)."</pre>";
                                        $str = "";
                                        if (isset($_GET["scat"]) && $_GET["scat"]!=''){ $str.="&scat=".$_GET["scat"];}
                                        if (isset($_GET["search"]) && $_GET["search"]!=''){ $str.="&search=".$_GET["search"];}
                                        if (isset($_GET["perpage"]) && $_GET["perpage"]!=''){ $str.="&perpage=".$_GET["perpage"];}
                                        if (isset($_GET["scountry"]) && $_GET["scountry"]!=''){ $str.="&scountry=".$_GET["scountry"];}
                                        if (isset($_GET["sstate"]) && $_GET["sstate"]!==''){ $str.="&sstate=".$_GET["sstate"];}
                                        if (isset($_GET["scity"]) && $_GET["scity"]!==''){ $str.="&scity=".$_GET["scity"];}
                                        if (isset($_GET["zip_code"]) && $_GET["zip_code"]!==''){ $str.="&zip_code=".$_GET["zip_code"];}

                                    ?>
                                    <ul class="pagination pagination-sm">
                                            
                                            <li><a href="<?php echo base_url(); ?>site/list_shop/?page_no=<?php echo $prev.$str; ?>">«</a></li>
                                            <?php
                                                for($i=$prev;$i<=$next;$i++){  
                                            ?>
                                            <li <?php if($curr_page==$i){echo 'class="active"';} ?>><a href="<?php echo base_url(); ?>site/list_shop/?page_no=<?php echo $i.$str; ?>" <?php if($curr_page==$i){echo 'class="active"';} ?>><?php echo $i; ?></a></li>
                                            <?php 
                                                }
                                            ?>
                                            <li><a href="<?php echo base_url(); ?>site/list_shop/?page_no=<?php echo $next.$str; ?>">»</a></li>
                                            
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
