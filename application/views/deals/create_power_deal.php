 <link href="<?php echo base_url(); ?>assets/css/plugins/bootstrap-timepicker/bootstrap-timepicker.min.css" rel="stylesheet">
    <link href="//code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css" type="text/css" rel="stylesheet">
    <link href="<?php echo base_url(); ?>assets/css/plugins/bootstrap-tokenfield/tokenfield-typeahead.css" rel="stylesheet">
    <link href="<?php echo base_url(); ?>assets/css/plugins/bootstrap-tokenfield/bootstrap-tokenfield.css" rel="stylesheet">
    <link href="<?php echo base_url(); ?>assets/css/plugins/bootstrap-datepicker/datepicker3.css" rel="stylesheet">
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
                                <li class="active">Create Offer</li>
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
                                            <h4>Create POWER Offer</h4>
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
                                            } else {

                                                if (! isset($deal['deal_time'])) {
                                                    $deal['deal_time'] = date('g:i A');
                                                }

                                                if (! isset($deal['deal_end_time'])) {
                                                    $deal['deal_end_time'] = date('g:i A',time()+60*60);
                                                }


                                            }
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
                                            
                                            <form class="form-horizontal" action="<?php echo base_url(); ?>deals/create_power_deal" method="POST" enctype="multipart/form-data">  
                                                <?php if($user_info['role'] !=6){?>
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
                                                <?php }else{?>
                                                <?php if(!empty($shops)){?>
                                                    <div class="form-group">
                                                        <label class="col-sm-2 control-label">Shop Name</label>
                                                        <div class="col-sm-10">
                                                            <div style="padding: 6px 12px; font-size: 14px; line-height: 1.428571429; color: #555; background-color: #fff; background-image: none; border: 1px solid #ccc;">
                                                            <?php echo $shops[0]['shop_name'];?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <input type="hidden" name="shop_id" value="<?php echo $shops[0]['shop_id'];?>">
                                                <?php }else{?>
                                                    <div class="form-group">
                                                        <label class="col-sm-2 control-label">Shop Name</label>
                                                        <div class="col-sm-10">
                                                            <div style="padding: 6px 12px; font-size: 14px; line-height: 1.428571429; color: #555; background-color: #fff; background-image: none; border: 1px solid #ccc;">
                                                            some detail are missing please further sign up with full detail...
                                                            </div>
                                                        </div>
                                                    </div>
                                                <?php }?>
                                                <?php }?>
                                                <div class="form-group">
                                                    <label for="deal_title" class="col-sm-2 control-label">Offer Title *</label>
                                                    <div class="col-sm-10">
                                                        <input type="text" name="deal_title" id="deal_title" placeholder="" class="form-control" value="<?php echo set_value1('deal_title',$deal); ?>">
                                                        <?php echo form_error('deal_title'); ?>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label for="textArea" class="col-sm-2 control-label">Offer Description *</label>
                                                    <div class="col-sm-10">
                                                        <textarea rows="3" class="form-control" name="deal_description"><?php echo set_value1('deal_description',$deal); ?></textarea>
                                                       <?php echo form_error('deal_description'); ?>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label for="original_price" class="col-sm-2 control-label">Original Price($) *</label>
                                                    <div class="col-sm-10">
                                                        <input type="text" name="original_price" id="original_price" placeholder="" class="form-control" value="<?php echo set_value1('original_price',$deal); ?>">
                                                        <?php echo form_error('original_price'); ?>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label for="offer_price" class="col-sm-2 control-label">Offer Price($) *</label>
                                                    <div class="col-sm-10">
                                                        <input type="text" name="offer_price" id="offer_price" placeholder="" class="form-control" value="<?php echo set_value1('offer_price',$deal); ?>">
                                                        <?php echo form_error('offer_price'); ?>
                                                    </div>
                                                </div>
                                                <?php
                                                
                                                if(isset($deal['deal_image']) && !empty($deal['deal_image'])){
                                                ?>
                                                    <input type="hidden" name="deal_image_dup" value="<?php echo $deal['deal_image']; ?>">
                                                    <div class="form-group">
                                                        <label for="deal_image_view" class="col-sm-2 control-label">Offer image</label>
                                                        <div class="col-sm-4">
                                                            <?php $arr = array("jpg","png","jpeg","gif"); if(in_array(pathinfo($deal['deal_image'], PATHINFO_EXTENSION), $arr)){echo '<img id="deal_image_view" src="' . base_url() . 'uploads/' . $deal['deal_image'] . '" width="150" height="auto">';} else{echo 'No Image';} ?>
                                                        </div>
                                                    </div> 
                                                    <div class="form-group">
                                                        <label for="deal_image_view" class="col-sm-2 control-label">Offer image</label>
                                                        <div class="col-sm-4">
                                                            <?php if(isset($deal['deal_image']) && !empty($deal['deal_image'])){?>
                                                            <?php $arr = array("jpg","png","jpeg","gif"); if(in_array(pathinfo($deal['deal_image'], PATHINFO_EXTENSION), $arr)){?>
                                                        <img id="deal_image_view" src="<?php echo base_url();?>images/no_image.png" width="150" height="auto">
                                                            <?php } }?>
                                                    </div>
                                                    </div> 
                                                <?php
                                                }
                                                ?>
                                                <div class="form-group">
                                                    <label for="deal_image" class="col-sm-2 control-label">Offer image</label>
                                                    <div class="col-sm-4">
                                                        <input type="file" name="deal_image" id="deal_image" placeholder="Offer Image" class="form-control" value="<?php echo set_value('deal_image'); ?>">
                                                        <?php echo form_error('deal_image'); ?>
                                                    </div>
                                                </div>
                                               
                                                <div class="form-group">
                                                    <label for="deal_start" class="col-sm-2 control-label">Offer Start Date *</label>
                                                    <div id="sandbox-container" class="col-sm-2">
                                                        <input class="form-control" type="text" name="deal_start" value="<?php echo set_value1('deal_start',$deal); ?>" id="deal_start">
                                                        <?php echo form_error('deal_start'); ?>
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <label for="deal_start" class="col-sm-2 control-label">Offer End Date *</label>
                                                    <div id="sandbox-container" class="col-sm-2">
                                                        <input class="form-control" type="text" name="deal_end" value="<?php echo set_value1('deal_end',$deal); ?>" id="deal_end">
                                                        <?php echo form_error('deal_end'); ?>
                                                    </div>
                                                </div>


                                                <div class="form-group">
                                                    <label for="timepicker1" class="col-sm-2 control-label">Offer Start Time *</label>
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
                                                    <label for="timepicker2" class="col-sm-2 control-label">Offer End Time * </label>
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
                                                    <label for="featured" class="col-sm-2 control-label">Hot Offer: $2</label>
                                                        <div class="col-sm-8">
                                                        <div class="checkbox">
                                                            <label>
                                                                <input type="checkbox" id="featured" name="featured" <?php if(set_value1('featured',$deal)=="on"){echo "checked";} ?> checked>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;( Free for a limited time. )
                                                                <br/>
                                                            </label>
                                                    
                                                        </div>
                                                    </div>
                                                </div>
                                               
                                                <div class="form-group">
                                                    <label for="contact_name" class="col-sm-2 control-label">Contact Name *</label>
                                                    <div class="col-sm-10">
                                                        <input type="text" name="contact_name" id="contact_name" placeholder="" class="form-control" value="<?php echo set_value1('contact_name',$deal); ?>">
                                                        <?php echo form_error('contact_name'); ?>
                                                    </div>
                                                </div>    
                                                <div class="form-group">
                                                    <label for="contact_number" class="col-sm-2 control-label">Business Number *</label>
                                                    <div class="col-sm-10">
                                                        <input type="text" name="contact_number" id="contact_number" placeholder="" class="form-control" value="<?php echo set_value1('contact_number',$deal); ?>">
                                                        <?php echo form_error('contact_number'); ?>
                                                    </div>
                                                </div>    
                                                <div class="form-group">
                                                    <label for="website" class="col-sm-2 control-label">Website *</label>
                                                    <div class="col-sm-10">
                                                        <input type="text" name="website" id="website" placeholder="" class="form-control" value="<?php echo set_value1('website',$deal); ?>">
                                                        <?php echo form_error('website'); ?>
                                                    </div>
                                                </div>    
                                                    
                                               
                                                
                                                <div class="form-group">
                                                    <label class="col-sm-2 control-label"></label>
                                                    <div class="col-sm-10">
                                                        <button type="submit" class="btn btn-default" name="create_deal">Create Offer</button>
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
    <?php require ($_SERVER["DOCUMENT_ROOT"]."/application/views/logout.php");?>
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

            $('#timepickercd1').timepicker('setTime', '<?php echo set_value1('deal_time',$deal); ?>');
            $('#timepickercd2').timepicker('setTime', '<?php echo set_value1('deal_end_time',$deal); ?>');

            $("select[name='shop_id']").trigger('change');
        });

    </script>

    <script src="<?php echo base_url(); ?>assets/js/demo/advanced-form-demo.js"></script>

</body>

</html>