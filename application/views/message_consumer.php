<link href="<?php echo base_url(); ?>assets/css/plugins/bootstrap-timepicker/bootstrap-timepicker.min.css" rel="stylesheet">
    <link href="//code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css" type="text/css" rel="stylesheet">
    <link href="<?php echo base_url(); ?>assets/css/plugins/bootstrap-tokenfield/tokenfield-typeahead.css" rel="stylesheet">
    <link href="<?php echo base_url(); ?>assets/css/plugins/bootstrap-tokenfield/bootstrap-tokenfield.css" rel="stylesheet">
    <link href="<?php echo base_url(); ?>assets/css/plugins/bootstrap-datepicker/datepicker3.css" rel="stylesheet">

    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.18.1/moment.min.js"></script>
<script>

    function textCounter(field, field2, maxlimit)
    {
        var countfield = document.getElementById(field2);
        if (field.value.length > maxlimit) {
            field.value = field.value.substring(0, maxlimit);
            return false;
        } else {

            var aa = maxlimit - field.value.length;
            $("#" + field2).html(aa);
        }
    }

    function get_prv_deal_info(shop_id)
    {
        
        $.ajax({
            type: "POST",
            url: "<?php echo base_url(); ?>site/get_prv_deal_info",
            data: {shop_id: shop_id},
            beforeSend: function() {
                
            },
            success: function(response){
            
                    var obj = $.parseJSON(response);
                    //alert(obj.contact_name);
                    $("#contact_name").val(obj.contact_name);
                    $("#contact_number").val(obj.contact_number);
                    $("#website").val(obj.website);
                

            }

        });
    }
    $(document).ready(function(){
        if(<?php echo $user_info['role'];?> == 6){
            if(<?php echo $shops[0]['shop_id'];?> != ""){
            get_prv_deal_info(<?php print_r($shops[0]['shop_id']);?>);
            }
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
                            <ol class="breadcrumb">
                                <li><i class="fa fa-dashboard"></i>  <a href="<?php echo base_url();?>site/index">Dashboard</a>
                                </li>
                                <li class="active">Message Consumer</li>
                            </ol>
                            
                        </div>
                    </div><!-- /.col-lg-12 -->
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
                    <!-- /.col-lg-12 -->
                </div>
                <!-- /.row -->
                <!-- end PAGE TITLE ROW -->
                <div class="row">
                 <!-- Hoverable Responsive Table -->
                 <div class="col-lg-12">


                    <?php echo validation_errors('<div class="alert alert-danger" role="alert">', '</div>'); ?>
                   
                                <div class="portlet portlet-default">
                                    <div class="portlet-heading">
                                        <div class="portlet-title">
                                            <h4>Create Message</h4>
                                        </div>
                                        <div class="portlet-widgets">
                                            <a data-toggle="collapse" data-parent="#accordion" href="#formControls"><i class="fa fa-chevron-down"></i></a>
                                        </div>
                                        <div class="clearfix"></div>
                                    </div>
                                    <div id="formControls" class="panel-collapse collapse in">
                                        <div class="portlet-body">
                                            
                                            <?php
                                            //echo "<pre>";print_r($deal);
                                          
                                            if(isset($deal['deal_time'])){
												
                                                $start_date = utc_to_local($deal['deal_start'],$deal['deal_time'],$deal["timezone"]);
                                                $end_date = utc_to_local($deal['deal_start'],$deal['deal_end_time'],$deal["timezone"]);

                                                $deal['deal_time']=$start_date["user_time"];
                                                $deal['deal_end_time']=$end_date["user_time"];

                                                $deal["deal_start"]=$start_date["user_date_calendar"];

												
                                                $deal['featured']=($deal['featured_deal']==1) ? "on" : "off";
                                                $deal['deal_repeat']=explode(",",$deal['repeat']);
                                                //$deal['deal_start']=date("F j, Y, g:i a",strtotime($deal['deal_start']));
                                            } 
                                                // else {


                                            //     if (! isset($deal['deal_time'])) {
                                            //         $deal['deal_time'] = date('g:i A');
                                            //     }

                                            //     if (! isset($deal['deal_end_time'])) {
                                            //         $deal['deal_end_time'] = date('g:i A',time()+60*60);
                                            //     }

                                            // }
                                            function set_value1($col,$deal){
                                            
                                             
                                                if(isset($_POST[$col])){
                                                    return $_POST[$col];
                                                }else if(isset($deal[$col])){
                                                    return $deal[$col];
                                                }else{
                                                    return "";
                                                }
                                            }
                                           
                                            
                                            ?>
                                            

                                            

                                            <!-- <form class="form-horizontal" action="<?php echo base_url(); ?>site/create_deal" method="POST" enctype="multipart/form-data">   -->
                                            <form class="form-horizontal" method="POST" enctype="multipart/form-data">  
                                                <?php if($user_info['role'] !=6 && $user_info['role'] != 9 ){?>
                                                <div class="form-group">
                                                    <label class="col-sm-2 control-label">Selects</label>
                                                    <div class="col-sm-10">
                                                        <select class="form-control" name="shop_id" onchange="get_prv_deal_info(this.value);">
                                                            <option value="">Select Shop</option>
                                                                <?php
                                                                foreach ($shops as $v) {
                                                                        ?>
                                                                    <option value="<?php echo $v['shop_id']; ?>" <?php if (set_value1('shop_id',$deal) == $v['shop_id']) {
                                                                    echo "selected";
                                                                } ?>><?php echo $v['shop_name']; ?></option>
                                                                    <?php
                                                                }
                                                                ?>
                                                        </select>
                                                    <?php echo form_error('shop_id'); ?>
                                                    </div>
                                                </div>
                                                <?php }else{
                                                     if(count( isset($corporate_business_list) ? $corporate_business_list : array() ) > 1){


                                                        // echo "<pre>";
                                                        // print_r($corporate_business_list);
                                                        // exit();
                                                        ?>
                                                        <div class="form-group">
                                                            <label class="col-sm-2 control-label">Select Business</label>
                                                            <div class="col-sm-10">

                                                                <?php
                                                                foreach ($corporate_business_list as $cblkey => $cblvalue) { ?>
                                                                 <div class="checkbox">
                                                                    <label>
                                                                        <input type="checkbox" value="<?php echo $cblvalue['shop_id'];?>" name="offershop_id[]" > <?php echo $cblvalue['shop_name'];?>    &nbsp;&nbsp;(<?php echo $cblvalue['address'];?>)
                                                                    </label>
                                                                </div>
                                                                <?php
                                                                }?>
                                                                <?php echo form_error('offershop_id[]'); ?>
                                                            </div>
                                                        </div>

                                                    <?php 
                                                    }else{

                                                        if(!empty($shops)){?>
                                                            <div class="form-group">
                                                                <label class="col-sm-2 control-label">Shop Name</label>
                                                                <div class="col-sm-10">
                                                                    <div style="padding: 6px 12px; font-size: 14px; line-height: 1.428571429; color: #555; background-color: #fff; background-image: none; border: 1px solid #ccc;">
                                                                    <?php echo $shops[0]['shop_name'];?>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <?php if( $user_info['role'] == 9 ){?>
                                                            <input type="hidden" name="offershop_id[]" value="<?php echo $shops[0]['shop_id'];?>">
                                                            <?php } else {?>
                                                            <input type="hidden" name="shop_id" value="<?php echo $shops[0]['shop_id'];?>">
                                                            <?php }?>
                                                            
                                                        <?php }else{?>
                                                            <div class="form-group">
                                                                <label class="col-sm-2 control-label">Shop Name</label>
                                                                <div class="col-sm-10">
                                                                    <div style="padding: 6px 12px; font-size: 14px; line-height: 1.428571429; color: #555; background-color: #fff; background-image: none; border: 1px solid #ccc;">
                                                                    some detail are missing please further sign up with full detail...
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        <?php 
                                                        }
                                                    }
                                                 } ?>
                                                <div class="form-group">
                                                    <label for="deal_title" class="col-sm-2 control-label">Message Title *</label>
                                                    <div class="col-sm-10">
                                                        <input type="text" name="deal_title" id="deal_title" placeholder="" class="form-control" value="<?php echo set_value1('deal_title',$deal); ?>">
                                                        <?php echo form_error('deal_title'); ?>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label for="textArea" class="col-sm-2 control-label">Message *</label>
                                                    <div class="col-sm-10">
                                                        <textarea rows="3" class="form-control" name="deal_description"><?php echo set_value1('deal_description',$deal); ?></textarea>
                                                       <?php echo form_error('deal_description'); ?>
                                                    </div>
                                                </div>
                                              
                                                
                                                <?php if(isset($deal['deal_image']) && !empty($deal['deal_image'])){
                                                ?>
                                                    <input type="hidden" name="deal_image_dup" value="<?php echo $deal['deal_image']; ?>">
                                                   
                                                    <div class="form-group">
                                                        <label for="deal_image_view" class="col-sm-2 control-label">message image</label>
                                                        <div class="col-sm-4">
                                                            <?php if(isset($deal['deal_image']) && !empty($deal['deal_image'])){?>
                                                            <?php $arr = array("jpg","png","jpeg","gif",'PNG',"JPG"); 
                                                                if(!in_array(pathinfo($deal['deal_image'], PATHINFO_EXTENSION), $arr)){?>
                                                                <img id="deal_image_view" src="<?php echo base_url();?>uploads/user/<?php echo $shop->shop_image?>" width="150" height="auto">
                                                            <?php }else{ ?>
                                                                <img id="deal_image_view" src="<?php echo $deal['deal_image'];?>" width="150" height="auto">
                                                            <?php } }?>
                                                    </div>
                                                    </div> 
                                                <?php
                                                }
                                                ?>
                                                <div class="form-group">
                                                    <label for="deal_image" class="col-sm-2 control-label">message image</label>
                                                    <div class="col-sm-4">
                                                        <input type="file" name="deal_image" id="deal_image" placeholder="message Image" class="form-control" value="<?php echo set_value('deal_image'); ?>">(If no image uploaded your business logo will be used.)(For the best results upload a .jpg photo with the dimensions 790 x 392.)
                                                        <?php echo form_error('deal_image'); ?>
                                                    </div>
                                                </div>

                                               
                                                <div class="form-group">
                                                    <label for="deal_start" class="col-sm-2 control-label">Message Date *</label>
                                                    <div id="sandbox-container" class="col-sm-2">
                                                        <input class="form-control" type="text" name="deal_start" value="<?php echo set_value1('deal_start',$deal); ?>" id="deal_start" autocomplete="off">
                                                        <?php echo form_error('deal_start'); ?>
                                                    </div>
                                                </div>


                                                <div class="form-group">
                                                    <label for="timepicker1" class="col-sm-2 control-label">Message Start Time *</label>
                                                    <div class="col-sm-10">
                                                    <div class="input-append bootstrap-timepicker input-group col-sm-2">
                                                        <input id="timepickercd1" class="form-control" type="text" name="deal_time" value="<?php echo set_value1('deal_time',$deal); ?>">
                                                       
                                                        <span class="input-group-btn">
                                                            <button class="btn btn-default add-on" type="button"><i class="fa fa-clock-o"></i>
                                                            </button>
                                                        </span>
                                                    </div>
                                                        <?php echo form_error('deal_time'); ?>
                                                    </div>
                                                </div>


                                                <div class="form-group">
                                                    <label for="timepicker2" class="col-sm-2 control-label">Message End Time * </label>
                                                    <div class="col-sm-10">
                                                    <div class="input-append bootstrap-timepicker input-group col-sm-2">
                                                        <input id="timepickercd2" class="form-control" type="text" name="deal_end_time" value="<?php echo set_value1('deal_end_time',$deal); ?>">

                                                        <span class="input-group-btn">
                                                            <button class="btn btn-default add-on" type="button"><i class="fa fa-clock-o"></i>
                                                            </button>
                                                        </span>
                                                    </div>
                                                         <?php echo form_error('deal_end_time'); ?>
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <label for="timezone" class="col-sm-2 control-label">Timezone * </label>
                                                    <div class="col-sm-10">
                                                    <div class="input-append input-group">

                                                        <?php 
                                                        if ($deal['timezone']==''){$deal['timezone']="UM5";}
                                                    echo timezone_menu($deal['timezone'],"form-control","timezone");?>
                                                   

                                                        
                                                    </div>
                                                         <?php echo form_error('timezone'); ?>
                                                    </div>
                                                </div>


                                               
                                                <div class="form-group">
                                                    <label class="col-sm-2 control-label">Message Repeat</label>
                                                    <div class="col-sm-10">


                                                        <?php
                                                        $chk_arr=set_value1('deal_repeat',$deal);  
                                                        if(empty($chk_arr)){
                                                            $chk_arr = array();
                                                        } 
                                                        
                                                        // echo '<pre>';
                                                        // print_r($chk_arr);
                                                        // exit();

                                                        for($i=1; $i<=7; $i++){ 
                                                            $day = (date("N", mktime(0, 0, 0, date("m"), date("d")+$i, date("Y"))));
                                                            if($day == 7 ){ $day = 0; }
                                                        ?>

                                                            <div class="checkbox">
                                                            <label>
                                                                <!-- <input type="checkbox" value="<?php echo $i; ?>" name="deal_repeat[]" <?php if(in_array($i, $chk_arr)){echo "checked";} ?>><?php echo (date("l", mktime(0, 0, 0, date("m"), date("d")+$i, date("Y")))); echo ", ".(date("F d, Y", mktime(0, 0, 0, date("m"), date("d")+$i, date("y")))); ?> -->
                                                                <input type="checkbox" value="<?php echo $day; ?>" name="deal_repeat[]" <?php if(in_array($day, $chk_arr)){echo "checked";} ?>> 
                                                                <?php echo 'Repeat Every ' .(date("l", mktime(0, 0, 0, date("m"), date("d")+$i, date("Y")))); ?>
                                                            </label>
                                                        </div>
                                                               
                                                        <div class="form-group">
                                                            <span for="timepicker3" class="col-sm-2 control-label"> Message Start Date  </span>
                                                            <div class="input-append bootstrap-timepicker input-group col-sm-2">
                                                                <input id="datepicker_start_<?php echo $day?>" class="form-control" type="text" name="datepicker_start_<?php echo $day?>" value="<?php echo set_value1('datepicker_start_'.$day,$deal); ?>">
                                                            </div>
                                                            <div class="col-sm-offset-2">
                                                                <?php echo form_error('datepicker_start_'.$day); ?>
                                                            </div>

                                                        </div>
                                                        
                                                        <div class="form-group">
                                                            <span for="timepicker2" class="col-sm-2 control-label">Message End Date  </span>
                                                            <div class="input-append bootstrap-timepicker input-group col-sm-2">
                                                                <input id="datepicker_end_<?php echo $day?>" class="form-control" type="text" name="datepicker_end_<?php echo $day?>" value="<?php echo set_value1('datepicker_end_'.$day,$deal); ?>">
                                                            </div>
                                                            <div class="col-sm-offset-2">
                                                                <?php echo form_error('datepicker_end_'.$day); ?>
                                                            </div>
                                                        </div>
                                                        <?php }


//                                                        if(isset($_POST['deal_repeat'])){
//                                                            $chk_arr=(array)$_POST['deal_repeat'];
//                                                        }else{
//                                                            $chk_arr=array();
//                                                        }
                                                       
                                                            //echo "<pre>";print_r($chk_arr);exit;
                                                        ?>
                                                       
                                                        
                                                        
                                                    </div>
                                                </div>
                                                
                                                <input type="hidden" name="original_price" value="">
                                                <input type="hidden" name="offer_price" value="">
                                               
                                                
                                                <div class="form-group">
                                                    <label class="col-sm-2 control-label"></label>
                                                    <div class="col-sm-10">
                                                        <button type="submit" class="btn btn-default" name="create_deal">Create Message</button>
                                                    </div>
                                                </div>
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

    
    <script src="<?php echo base_url(); ?>assets/js/flex.js"></script>
    <script src="<?php echo base_url(); ?>assets/js/plugins/bootstrap-tokenfield/bootstrap-tokenfield.min.js"></script>
    <script src="<?php echo base_url(); ?>assets/js/plugins/bootstrap-tokenfield/scrollspy.js"></script>
    <script src="<?php echo base_url(); ?>assets/js/plugins/bootstrap-tokenfield/affix.js"></script>
    <script src="<?php echo base_url(); ?>assets/js/plugins/bootstrap-tokenfield/typeahead.min.js"></script>
    <script src="<?php echo base_url(); ?>assets/js/plugins/bootstrap-maxlength/bootstrap-maxlength.js"></script>
    <script src="<?php echo base_url(); ?>assets/js/plugins/bootstrap-timepicker/bootstrap-timepicker.min.js"></script>
    <script src="<?php echo base_url(); ?>assets/js/plugins/bootstrap-datepicker/bootstrap-datepicker.js"></script>
    
    <script>
        $(document).ready(function(){
            var deal_time = "<?php set_value1('deal_time',$deal); ?>";
            var deal_end_time = "<?php set_value1('deal_end_time',$deal); ?>";

            if(deal_time == ''){
                deal_time = moment().format('LT'); 
            }
            
            if( deal_end_time == ''){
                deal_end_time = moment().add(1, 'h').calendar();
            }
            


            $('#timepickercd1').timepicker('setTime', deal_time);
            $('#timepickercd2').timepicker('setTime', deal_end_time);
            $('#datepicker_start_1').datepicker({  autoclose: true,todayHighlight: true,startDate:new Date(),format: "mm/dd/yy",});
            $('#datepicker_start_2').datepicker({autoclose: true,todayHighlight: true,startDate:new Date(),format: "mm/dd/yy",});
            $('#datepicker_start_3').datepicker({autoclose: true,todayHighlight: true,startDate:new Date(),format: "mm/dd/yy",});
            $('#datepicker_start_4').datepicker({autoclose: true,todayHighlight: true,startDate:new Date(),format: "mm/dd/yy",});
            $('#datepicker_start_5').datepicker({autoclose: true,todayHighlight: true,startDate:new Date(),format: "mm/dd/yy",});
            $('#datepicker_start_6').datepicker({autoclose: true,todayHighlight: true,startDate:new Date(),format: "mm/dd/yy",});
            $('#datepicker_start_0').datepicker({autoclose: true,todayHighlight: true,startDate:new Date(),format: "mm/dd/yy",});
            $('#datepicker_end_1').datepicker({autoclose: true,todayHighlight: true,startDate:new Date(),format: "mm/dd/yy",});
            $('#datepicker_end_2').datepicker({autoclose: true,todayHighlight: true,startDate:new Date(),format: "mm/dd/yy",});
            $('#datepicker_end_3').datepicker({autoclose: true,todayHighlight: true,startDate:new Date(),format: "mm/dd/yy",});
            $('#datepicker_end_4').datepicker({autoclose: true,todayHighlight: true,startDate:new Date(),format: "mm/dd/yy",});
            $('#datepicker_end_5').datepicker({autoclose: true,todayHighlight: true,startDate:new Date(),format: "mm/dd/yy",});
            $('#datepicker_end_6').datepicker({autoclose: true,todayHighlight: true,startDate:new Date(),format: "mm/dd/yy",});
            $('#datepicker_end_0').datepicker({autoclose: true,todayHighlight: true,startDate:new Date(),format: "mm/dd/yy",});

            $("select[name='shop_id']").trigger('change');
        });

    </script>

    <script src="<?php echo base_url(); ?>assets/js/demo/advanced-form-demo.js"></script>

</body>

</html>