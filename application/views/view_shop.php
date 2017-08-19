    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/moment.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/bootstrap-datetimepicker.js"></script>
<style>
    .bootstrap-datetimepicker-widget{
        display: block !important;
        top: 715px !important;
        bottom: auto !important;
        left: 400px !important;
        right: auto !important;
    }
</style>

<?php if(isset($shop) && !empty($shop)){?>


<script>
$(document).ready(function(){
    var x =0;
    $('#deal').scroll(function(){
       if ($('#deal').scrollTop() <= $('table').height() - $('#deal').height()){
        $.ajax({
        method: "POST",
        url: "<?php echo base_url();?>site/ajax_deal",
        data: { id: <?php echo $_GET['id'];?>, offset: x += 7 },

        beforeSend: function( xhr ) {
            $('tbody#deal_appand').append("<tr class='tr_loading' colspan='4'><td>Loading Please Wait .....<td></tr>");
         }



      })
        .done(function( response ) {
             //console.log(response);
             $('.tr_loading').remove();
             $('tbody#deal_appand').append(response);
        });
        }
    });
});

function delete_row(id){
            //alert($(this).attr("id"));
            //alert("hi");
            var r = confirm("Are You sure you want to delete the business");
            if (r == true) {
                ajax("delete_shop", id);
            }
}

function add_notes() {

    var notes_text=$("#notes_text").val();
    $.ajax({
        type: "POST",
        url: "<?php echo base_url(); ?>site/add_notes",
        data: {business_id: <?php echo $shop[0]['shop_id'];?>,notes_text:notes_text,},
        beforeSend: function() {
            $("#btn_add_note").html('Adding The Notes...');
        },
        success: function(response){
            if(response!=''){
                //alert("You have successfully post the notes");
                $("#btn_add_note").html("Add Notes");
                $('tbody#notes_appand').prepend(response);

            }

        }

    });
}

function add_task(){
    var task_text=$("#task_text").val();
    var date = $("#datetimepicker4").val();
//    alert(task_text);
    if(task_text != "" && date != ""){
    $.ajax({
        type: "POST",
        url: "<?php echo base_url(); ?>site/add_task",
        data: {business_id: <?php echo $shop[0]['shop_id'];?>,task_text:task_text,date:date},
        beforeSend: function() {
            $("#btn_add_task").html('Adding The Task...');
        },
        success: function(response){
            if(response!=''){
//                alert(response);
                $("#btn_add_task").html("Add Task");
//                $('tbody#task_appand').prepend(response);
        //location.reload();
            }

        }

    });
    }
    else{
    return false;
    }
}

</script>


<!-- begin MAIN PAGE CONTENT -->
        <div id="page-wrapper">

            <div class="page-content">

                <!-- begin PAGE TITLE ROW -->
                <div class="row">
                    <div class="col-lg-12">
                        <div class="page-title">
                            <h1>
                                View Business
                            </h1>
                            <ol class="breadcrumb">
                                <li><i class="fa fa-dashboard"></i>  <a href="/site/index">Dashboard</a>
                                </li>
                                <li class="active">View Business</li>
                            </ol>
                        </div>
                    </div>
                    <!-- /.col-lg-12 -->
                </div>
                <!-- /.row -->
                <!-- end PAGE TITLE ROW -->
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

                <div class="row">
                    <div class="col-lg-8 col-lg-offset-2 col-md-12">
                        <div class="portlet portlet-default">
                            <div class="portlet-body">
                                        <p>
                                          <div style="position:relative;padding-bottom:30%;">
                                            <?php
                                            $src=(!empty($shop[0]['shop_image'])) ? base_url()."uploads/user/".$shop[0]['shop_image'] : base_url()."assets/images/shop_def.png";
                                              echo '<img src="'.$src.'" style="position:absolute;top:0;left:0;right:0;bottom:0;width:100%;height:100%;object-fit:contain;background-color:#151515;" />';
                                            ?>
                                          </div>
                                        </p>

                                <div class="row">
                                    <div class="col-md-6">
                                        <h1><i class="fa fa-gears"></i> <?php echo $shop[0]['shop_name'];?></h1>
                                        <br>
                                        <address>
                                            <strong><?php echo $shop[0]['address'];?></strong>
                                            <br><abbr title="Phone">P:</abbr><?php echo $shop[0]['business_phone'];?>

                                            <?php if((is_permission($this->session->userdata['role'], "manage_shop")) == TRUE){ ?>
                                            <br><a href="/site/edit_shop/?id=<?php echo $shop[0]['shop_id']; ?>"><img src="/assets/images/edit.png"></a>
                                            <?php }?>
                                        </address>
                                    </div>
                                    <div class="col-md-6 invoice-terms">
<!--                                        <h3>Invoice #3024</h3>-->
                                        <p>
                                            <!--<a class="btn btn-green" href="<?php // echo base_url(); ?>site/edit_shop/?id=<?php // echo $shop[0]['shop_id']; ?>">Edit Business</a>-->
                                            <?php if((is_permission($this->session->userdata['role'], "create_deal")) == TRUE){ 

                                                if( $shop[0]['corporate_main_shop'] == 0 ){?>

                                            <a class="btn btn-green" href="<?php echo base_url(); ?>site/create_deal/?id=<?php echo $shop[0]['shop_id']; ?>">Create Offer</a>
                                            <?php } } ?>
                                        </p>
                                    </div>
                                </div>
                                <!-- /.row -->
                                <hr>
                                <div class="row">
                                    <div class="col-md-6">
                                        <h3><?php echo $shop[0]['cname'];?></h3>
                                        <address>
                                            <strong><?php echo $shop[0]['contact_first_name'];?> <?php echo $shop[0]['contact_last_name'];?></strong>
                                            <br><?php echo $shop[0]['contact_email'];?>
                                        </address>
                                    </div>
                                    <div class="col-md-6 invoice-terms">
                                        <h3>Business Description</h3>
                                        <p>
                                            <?php echo $shop[0]['shop_description'];?>
                                        </p>

                                    </div>
                                </div>

                                    <!-- /.row -->
                                <hr>
                                <div class="row">
                                    <div class="portlet-body">
                                        <ul id="myTab" class="nav nav-tabs">
                                            <li class="active"><a href="#deals" data-toggle="tab">Offers</a>
                                            </li>
                                            <?php if($user_info['role'] != 6){ ?><li><a href="#notes" data-toggle="tab">Notes</a></li><?php } ?>
                                            <?php if($user_info['role'] != 6){ ?><li><a href="#task" data-toggle="tab">Tasks</a></li><?php } ?>
                                            <?php if($user_info['role'] != 6){ ?><li><a href="#documents" data-toggle="tab">Credit Card Detail</a></li> <?php } ?>
                                        </ul>
                                        <div id="myTabContent" class="tab-content">
                                            <div class="tab-pane fade in active" id="deals">
                                                <?php if(isset($deal) && !empty($deal)){?>
                                                    <div class="table-responsive" id="deal" style="height: 900px; overflow-y: scroll;">
                                                        <table class="table table-hover">
                                                            <thead>

                                                                    <tr>
                                                                        <th>Offer Title</th>
                                                                        <th>Offer Image</th>
                                                                        <th>Original Price($)</th>
                                                                        <th>Offer Price($)</th>
                                                                        <th>Start Date</th>
                                                                        <th>Start Time</th>
                                                                        <th>End Time</th>
                                                                        <th>View Offer</th>
                                                                    </tr>

                                                            </thead>
                                                            <tbody id="deal_appand">
                                                               <?php

                                                               // /print_rr($deal);
                                                                        foreach($deal as $v){

                                                                            //print_rr($v,0);

                                                                            $start_date = utc_to_local($v['deal_start'],$v['deal_time'],$v["timezone"]);
                                                                            $end_date = utc_to_local($v['deal_end'],$v['deal_end_time'],$v["timezone"]);

                                                                            //print_rr($start_date,0);
                                                                            //print_rr($end_date);



                                                                            $src=(!empty($v['deal_image'])) ? base_url()."uploads/".$v['deal_image'] : base_url()."assets/images/shop_def.png";
                                                                            $Repeat_str="";
                                                                            $rep_arr= explode(",", $v['repeat']);
                                                                            foreach($rep_arr as $repi){
                                                                                $Repeat_str.=get_dayname($repi)."<br/>";
                                                                            }
                                                                            $Repeat_str= rtrim($Repeat_str, ",");


                                                                            $src=$v['deal_image'];
                                                                    ?>
                                                                           <tr>
                                                                                <td><?php echo $v['deal_title']; ?></td>
                                                                                  <td>
                                                                                    <div style="position:relative;padding-bottom:30%;">
                                                                                        <img src="<?php echo $src; ?>" style="position:absolute;top:0;left:0;right:0;bottom:0;width:100%;height:100%;object-fit:contain;background-color:#151515;">
                                                                                    </div>
                                                                                  </td>
                                                                                <td><?php echo $v['original_price']; ?></td>
                                                                                <td><?php echo $v['offer_price']; ?></td>
                                                                                <td><?php echo $start_date["user_date"]."<br>".$end_date["user_date"] ?></td>

                                                                                <td><?php echo $start_date["user_time"] ?></td>
                                                                                <td><?php echo $end_date["user_time"] ?></td>
                                                                                <td>
                                                                                    <a target="_blank" class="btn btn-green" href="<?php echo base_url(); ?>site/view_deal/?id=<?php echo $v['id']; ?>">View</a>

                                                                                </td>
                                                                            </tr>
                                                                    <?php
                                                                        }
                                                                    ?>
                                                            </tbody>
                                                        </table>
                                                        </div>
                                                <?php }else{ ?>
                                                    <p>No Deals Found</p>
                                                <?php } ?>
                                            </div>
                                            <?php if($user_info['role'] != 6){ ?><div class="tab-pane fade" id="notes">

                                                   <table class="table table-hover">
                                                            <thead>
                                                                <tr>
                                                                    <th>Title</th>
                                                                    <th>Form Element</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                 <tr>
                                                                     <td>Enter Notes :</td>
                                                                     <td><textarea name="notes_text" id="notes_text" cols="8" rows="5" style="width:100%;" required></textarea></td>
                                                                     <td><a class="btn btn-green" href="javascript:void(0);" onclick="add_notes();" id="btn_add_note">Add Notes</a></td>
                                                                 </tr>

                                                            </tbody>
                                                        </table>
							<div class="table-responsive" id="nots">
                                                        <table class="table table-hover">
                                                            <thead>

                                                                    <tr>
                                                                        <th>User</th>
                                                                        <th>Text Note</th>
                                                                        <th>Date</th>
                                                                    </tr>

                                                            </thead>
                                                            <tbody id="notes_appand">
                                                               <?php foreach($notes as $v1){ $src=(!empty($v1['profile_pic'])) ? base_url()."uploads/user/".$v1['profile_pic'] : base_url()."assets/img/profile-pic.jpg"; ?>
                                                                    <tr>
                                                                        <td><?php echo $v1['first_name'].' '.$v1['last_name']; ?> <?php if(!empty($v1['profile_pic'])){ ?><img src="<?php echo $src;?>" width="70" height="auto"/><?php }?></td>
                                                                         <td><?php echo $v1['notes_text']; ?></td>
                                                                         <td><?php echo date("F j, Y",strtotime($v1['notes_date'])); ?></td>
                                                                     </tr>
                                                                    <?php } ?>
                                                            </tbody>
                                                        </table>
                                                        </div>
                                            </div> <?php } ?>
                                            <?php if($user_info['role'] != 6){ ?>

                                            <div class="tab-pane fade" id="task">
                                                <form>
                                                <table class="table table-hover">
                                                    <thead>
                                                        <tr>
                                                            <th>Title</th>
                                                            <th>Form Element</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                         <tr>
                                                             <td style="width: 33.333333333%;"><label for="Task" class="col-sm-6 control-label">Enter Task :</label></td>
                                                             <td style="width: 33.333333333%;"><textarea name="task_text" id="task_text" cols="8" rows="5" style="width:100%;" required></textarea></td>
                                                             <td style="width: 33.333333333%;"></td>
                                                         </tr>
                                                         <tr>
                                                             <td style="width: 33.333333333%;"><label for="date" class="col-sm-6 control-label">Enter Date :</label></td>
                                                             <td style="width: 33.333333333%;">
																	<input type="hidden" name="id" value="<?php echo $_GET['id'];?>">
                                                                    <input class="form-control" type="text" name="deal_start" id='datetimepicker4' required>

                                                             </td>
                                                             <td style="width: 33.333333333%;"><button type="submit" class="btn btn-green"  onclick="add_task();" id="btn_add_task">Add Task</button></td>
                                                         </tr>
<!--                                                         <tr>
                                                             <td style="width: 33.333333333%;">
                                                                 <label for="timepicker1_edit" class="col-sm-6 control-label">Time :</label>
                                                             </td>
                                                             <td style="width: 33.333333333%;">
                                                                 <div class="bootstrap-timepicker input-group">
                                                                    <input id="timepicker1_edit" class="form-control time" type="text" name="time" value="">
                                                                    <span class="input-group-btn">
                                                                        <button class="btn btn-green add-on" type="button"><i class="fa fa-clock-o"></i>
                                                                        </button>
                                                                    </span>

                                                                 </div>
                                                             </td>
                                                             <td style="width: 33.333333333%;"></td>
                                                         </tr>-->

                                                    </tbody>
                                                </table>
                                                </form>
<!--                                                <div class="table-responsive" id="task">
                                                    <table class="table table-hover">
                                                        <thead>

                                                                <tr>
                                                                    <th>User</th>
                                                                    <th>Task</th>
                                                                    <th>Date</th>
                                                                </tr>

                                                        </thead>
                                                        <tbody id="task_appand">
                                                           <?php // foreach($task as $v2){ $src=(!empty($v1['profile_pic'])) ? base_url()."uploads/user/".$v2['profile_pic'] : base_url()."assets/img/profile-pic.jpg"; ?>
                                                                <tr>
                                                                    <td><?php // echo $v2['first_name'].' '.$v2['last_name']; ?> <?php // if(!empty($v2['profile_pic'])){ ?><img src="<?php // echo $src;?>" width="70" height="auto"/><?php // }?></td>
                                                                     <td><?php // echo $v2['task']; ?></td>
                                                                     <td><?php // echo date("F j, Y",strtotime($v2['time'])); ?></td>
                                                                 </tr>
                                                                <?php // } ?>
                                                        </tbody>
                                                    </table>
                                                </div>-->
                                            </div><?php } ?>
                                           <?php if($user_info['role'] != 6){ ?> <div class="tab-pane fade" id="documents">
                                                <p>Trust fund seitan letterpress, keytar raw denim keffiyeh etsy art party before they sold out master cleanse gluten-free squid scenester freegan cosby sweater. Fanny pack portland seitan DIY, art party locavore wolf cliche high life echo park Austin. Cred vinyl keffiyeh DIY salvia PBR, banh mi before they sold out farm-to-table VHS viral locavore cosby sweater. Lomo wolf viral, mustache readymade thundercats keffiyeh craft beer marfa ethical. Wolf salvia freegan, sartorial keffiyeh echo park vegan.</p>
                                                <p>Trust fund seitan letterpress, keytar raw denim keffiyeh etsy art party before they sold out master cleanse gluten-free squid scenester freegan cosby sweater. Fanny pack portland seitan DIY, art party locavore wolf cliche high life echo park Austin. Cred vinyl keffiyeh DIY salvia PBR, banh mi before they sold out farm-to-table VHS viral locavore cosby sweater. Lomo wolf viral, mustache readymade thundercats keffiyeh craft beer marfa ethical. Wolf salvia freegan, sartorial keffiyeh echo park vegan.</p>
                                            </div> <?php } ?>
                                        </div>
                                    </div>
                                    <!-- /.portlet-body -->
                                </div>
                                 <!--/.row -->
                            </div>
                        </div>
                    </div>
                    <!-- /.col-lg-12 -->
                </div>
                <!-- /.row -->

            </div>
            <!-- /.page-content -->

        </div>
        <!-- /#page-wrapper -->
        <!-- end MAIN PAGE CONTENT -->
<?php }else{ ?>

<!-- begin MAIN PAGE CONTENT -->
        <div id="page-wrapper">

            <div class="page-content">

                <!-- begin PAGE TITLE ROW -->
                <div class="row">
                    <div class="col-lg-12">
                        <div class="page-title">
                            <h1>
                                View Business
                            </h1>
                            <ol class="breadcrumb">
                                <li><i class="fa fa-dashboard"></i>  <a href="/site/index">Dashboard</a>
                                </li>
                                <li class="active">View Business</li>
                            </ol>
                        </div>
                    </div>
                    <!-- /.col-lg-12 -->
                </div>
                <!-- /.row -->
                <!-- end PAGE TITLE ROW -->
                <div class="col-lg-12">
                    <form action="" method="GET" enctype="multipart/form-data">
                        <table class="table table-hover">

                            <tr>

                                <td style="width: 15%;">
                                    <select class="form-control" name="id">
                                        <option value="0">Select Business</option>
                                        <?php
                                        foreach ($corporate_business_list as $v) {
                                            ?>
                                            <option value="<?php echo $v['shop_id']; ?>" <?php if (isset($_GET['sbid'])) {
                                                if ($v['shop_id'] == $_GET['sbid']) {
                                                    echo 'selected';
                                                }
                                            } ?>><?php echo $v['shop_name']; ?></option>
                                            <?php
                                        }
                                        ?>
                                    </select>
                                </td>

                                <td>&nbsp;&nbsp;
                                    <button type="submit"  class="btn btn-default">GO</button>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="7" style="border-top: 1px solid #ddd;">&nbsp;</td>
                            </tr>
                        </table>
                    </form>
                </div>
            </div>
        </div>



<?php }?>
        </div>
    <!-- /#wrapper -->

    <script  type="text/javascript">
        $(function () {
            $('#datetimepicker4').datetimepicker({
                format: 'YYYY-MM-DD HH:mm:ss'
            });
            });
    </script>

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

    <!-- PAGE LEVEL PLUGIN SCRIPTS -->

    <!-- THEME SCRIPTS -->
    <script src="<?php echo base_url(); ?>assets/js/flex.js"></script>
    <script src="<?php echo base_url(); ?>assets/js/plugins/bootstrap-tokenfield/bootstrap-tokenfield.min.js"></script>
    <script src="<?php echo base_url(); ?>assets/js/plugins/bootstrap-tokenfield/scrollspy.js"></script>
    <script src="<?php echo base_url(); ?>assets/js/plugins/bootstrap-tokenfield/affix.js"></script>
    <script src="<?php echo base_url(); ?>assets/js/plugins/bootstrap-tokenfield/typeahead.min.js"></script>
    <script src="<?php echo base_url(); ?>assets/js/plugins/bootstrap-maxlength/bootstrap-maxlength.js"></script>
    <script src="<?php echo base_url(); ?>assets/js/plugins/bootstrap-timepicker/bootstrap-timepicker.min.js"></script>
    <script src="<?php echo base_url(); ?>assets/js/plugins/bootstrap-datepicker/bootstrap-datepicker.js"></script>

    <script src="<?php echo base_url(); ?>assets/js/demo/advanced-form-demo.js"></script>

</body>

</html>