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
             console.log(16, country_id);
            return true;

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
             console.log(29, state_id);
            return true;

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
                console.log("50", loadType);
                $("#" + loadType + "_loader").hide();
                $("#" + loadType + "_dropdown").html("<option value='-1'>Select " + loadType + "</option>"+result+"<option value='0'>Others</option>");

                console.log("54",id,value);
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
            var r = confirm("Are You sure you want to delete the offer?");
            if (r == true) {
                ajax("delete_deal", id);
            }
}

        function ajax(action, id) {


            $.ajax({
                type: "POST",
                url: "<?php echo base_url(); ?>site/"+action,
                data: {id: id},
                success: function(response){
                    if(action=="delete_deal"){

                        if (response == "1") {
                            location.reload();

                        }
                    }

                }

            });
        }

</script>
<style>

td:first-child a{text-decoration: underline;}
</style>

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
                                <li class="active">Offer List</li>
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
                                    <h4>Search Offer</h4>
                                </div>
                                <div class="clearfix"></div>
                            </div>
                            <div class="portlet-body">
                                <?php if((is_permission($this->session->userdata['role'], "manage_shop")) == TRUE){ ?>
                                <a class="btn btn-green" style="float:right" href="<?php echo base_url();?>site/create_deal">Add Offer</a>
                                <?php } ?>
                                <div class="table-responsive">
                                    <form action="<?php echo base_url(); ?>site/manage_deal" method="GET" enctype="multipart/form-data">
                                    <table class="table table-hover">
                                         <tr><td colspan="5" style="border-top: 0px solid #ddd;">&nbsp;</td></tr>
                                            <tr>
                                                <td style="width: 8%; vertical-align: middle;">Search By :&nbsp;</td>
                                                <td style="width: 40%;"><input type="text" placeholder="Offer Title" name="search" id="search" class="form-control" <?php if(isset($search) && !empty($search)){ echo 'value="' . $search . '"';}?>></td>
                                                <td style="width: 8%; vertical-align: middle;">&nbsp;&nbsp;Per Page :&nbsp;</td>
                                                <td>
                                                    <select id="perpage" name="perpage" class="form-control">
                                                        <option>10</option>
                                                        <option>25</option>
                                                        <option>50</option>
                                                        <option>100</option>
                                                        <option>500</option>
                                                    </select>
                                                </td>
                                                <td>&nbsp;&nbsp;<button type="submit" name="go" class="btn btn-default">GO</button> <a class="pull-right" href="<?php echo base_url(); ?>site/manage_deal">Reset Search</a></td>
                                            </tr>
                                            <tr><td colspan="5" style="border-top: 1px solid #ddd;">&nbsp;</td></tr>
                                        </table>
                                        </form>

                                    <form action="<?php echo base_url(); ?>site/manage_deal" method="GET" enctype="multipart/form-data">
                                        <table class="table table-hover">
                                            <tr><td colspan="7" style="border-top: 0px solid #ddd;">&nbsp;</td></tr>
                                            <tr>
                                                <td style="width: 8%; vertical-align: middle;">Search By :&nbsp;</td>
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
                                                <td style="width: 15%;">
                                                    <select class="form-control" name="scat">
                                                        <option value="">Select Category</option>
                                                        <?php
                                                        $sel_category =  $_GET['scat'];

                                                        foreach ($cats as $cat) {
                                                            ?>
                                                        <option value="<?php echo $cat['cid']; ?>" <?php if($cat['cid']==$sel_category){echo 'selected';}?>><?php echo $cat['cname']; ?></option>
                                                            <?php
                                                        }
                                                        ?>
                                                    </select>
                                                </td>
                                                <td style="width: 15%;">
                                                    <input type="text" name="zip_code"  placeholder="Zip Code" class="form-control" >
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
                                    <h4>Offer List</h4>
                                </div>
                                <div class="clearfix"></div>
                            </div>
                            <div class="portlet-body">
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead>

                                                <tr>
                                                    <th>Business</th>
                                                    <th>Title</th>
                                                    <th>Image</th>
                                                    <th>Start Date</th>
                                                    <th>Start Time</th>
                                                    <th>End Time</th>
                                                    <th>Repeat</th>
                                                    <th>Action</th>
                                                </tr>

                                        </thead>
                                        <tbody>
                                           <?php
                                                    foreach($info as $v){

                                                        $src=$v['deal_image'];
                                                        $Repeat_str="";
                                                        $rep_arr= explode(",", $v['repeat']);
                                                        foreach($rep_arr as $repi){
                                                            $Repeat_str.=get_dayname($repi)."<br/>";
                                                        }
                                                        $Repeat_str= rtrim($Repeat_str, ",");

                                                         $start_date = utc_to_local($v['deal_start'],$v['deal_time'],$v["timezone"]);
                                                         $end_date = utc_to_local($v['deal_start'],$v['deal_end_time'],$v["timezone"]);

                                                        //print_rr($start_date,0);

                                                ?>
                                                       <tr>
                                                            <td><a href="/site/view_shop/?id=<?php echo $v['shop_id']; ?>"><?php echo $v['shop_name']; ?></a></td>
                                                            <td><b><?php echo $v['deal_title']; ?></b><br>Org Price: <?php echo $v['original_price']; ?><br>Offer Price: <?php echo $v['offer_price']; ?></td>
                                                            <td>
                                                              <div style="position:relative;padding-bottom:30%;">
                                                                <img src="<?php echo $src; ?>" style="position:absolute;top:0;left:0;right:0;bottom:0;width:100%;height:100%;object-fit:contain;background-color:#151515;">
                                                              </div>
                                                            </td>

                                                             <td><?php echo $start_date["user_date"] ?></td>
                                                            <td><?php echo $start_date["user_time"] ?></td>
                                                            <td><?php echo $end_date["user_time"] ?></td>

                                                            <td><?php echo $Repeat_str; ?></td>



                                                             <td>
                                                                <a class="btn btn-green btn-xs" href="<?php echo base_url(); ?>site/view_deal/?id=<?php echo $v['id']; ?>">View</a>&nbsp;&nbsp;&nbsp;
                                                                <?php if((is_permission($this->session->userdata['role'], "manage_shop")) == TRUE){ ?>
                                                                <a href="<?php echo base_url(); ?>site/edit_deal/?id=<?php echo $v['id']; ?>"><img src="<?php echo base_url(); ?>assets/images/edit.png" /></a>&nbsp;&nbsp;&nbsp;
                                                                <a href="javascript:void(0);" id="del_<?php echo $v['id']; ?>" onclick="javascript:delete_row(this.id);"><img src="<?php echo base_url(); ?>assets/images/del.png" /></a>
                                                                <?php } ?>

                                                             </td>
                                                        </tr>
                                                <?php
                                                    }
                                                ?>
                                        </tbody>
                                    </table>
                                    <ul class="pagination pagination-sm">

                                            <li><a href="<?php echo base_url(); ?>site/manage_deal/?page_no=<?php echo $prev; ?>">«</a></li>
                                            <?php
                                                for($i=$prev;$i<=$next;$i++){
                                            ?>
                                            <li <?php if($curr_page==$i){echo 'class="active"';} ?>><a href="<?php echo base_url(); ?>site/manage_deal/?page_no=<?php echo $i; ?>" <?php if($curr_page==$i){echo 'class="active"';} ?>><?php echo $i; ?></a></li>
                                            <?php
                                                }
                                            ?>
                                            <li><a href="<?php echo base_url(); ?>site/manage_deal/?page_no=<?php echo $next; ?>">»</a></li>

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