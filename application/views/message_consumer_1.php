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
                                            

                                            

                                            <form class="form-horizontal" method="POST" enctype="multipart/form-data">  
                                                <div class="form-group">
                                                    <label for="deal_title" class="col-sm-2 control-label">Offer Title *</label>
                                                    <div class="col-sm-10">
                                                        <input type="text" name="deal_title" id="deal_title" placeholder="" class="form-control" value="">
                                                        <?php echo form_error('deal_title'); ?>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label for="textArea" class="col-sm-2 control-label">Offer Description *</label>
                                                    <div class="col-sm-10">
                                                        <textarea rows="3" class="form-control" name="deal_description"></textarea>
                                                       <?php echo form_error('deal_description'); ?>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label for="original_price" class="col-sm-2 control-label">Original Price($) *</label>
                                                    <div class="col-sm-10">
                                                        <input type="text" name="original_price" id="original_price" placeholder="" class="form-control" value="">
                                                        <?php echo form_error('original_price'); ?>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label for="offer_price" class="col-sm-2 control-label">Offer Price($) *</label>
                                                    <div class="col-sm-10">
                                                        <input type="text" name="offer_price" id="offer_price" placeholder="" class="form-control" value="">
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
                                                    <label for="deal_image" class="col-sm-2 control-label">Offer image</label>
                                                    <div class="col-sm-4">
                                                        <input type="file" name="deal_image" id="deal_image" placeholder="Offer Image" class="form-control" value="<?php echo set_value('deal_image'); ?>">(If no image uploaded your business logo will be used.)(For the best results upload a .jpg photo with the dimensions 790 x 392.)
                                                        <?php echo form_error('deal_image'); ?>
                                                    </div>
                                                </div>


                                                <?php if(isset($deal['barcode_image']) && !empty($deal['barcode_image'])){
                                                ?>
                                                    <input type="hidden" name="deal_image_dup" value="">
                                                   
                                                    <div class="form-group">
                                                        <label for="barcode_image_view" class="col-sm-2 control-label">Barcode image</label>
                                                        <div class="col-sm-4">
                                                            <img id="barcode_image_view" src=">" width="150" height="auto">
                                                    </div>
                                                    </div> 
                                                <?php
                                                }
                                                ?>

                                                <div class="form-group">
                                                    <label for="barcode_image" class="col-sm-2 control-label">Barcode image</label>
                                                    <div class="col-sm-4">
                                                        <input type="file" name="barcode_image" id="barcode_image" placeholder="Offer Image" class="form-control" value="<?php echo set_value('barcode_image'); ?>">
                                                        <?php echo form_error('barcode_image'); ?>
                                                    </div>
                                                </div>
                                               
                                                <div class="form-group">
                                                    <label for="deal_start" class="col-sm-2 control-label">Offer Date *</label>
                                                    <div id="sandbox-container" class="col-sm-2">
                                                        <input class="form-control" type="text" name="deal_start" value="" id="deal_start" autocomplete="off">
                                                        <?php echo form_error('deal_start'); ?>
                                                    </div>
                                                </div>


                                                <div class="form-group">
                                                    <label for="timepicker1" class="col-sm-2 control-label">Offer Start Time *</label>
                                                    <div class="col-sm-10">
                                                    <div class="input-append bootstrap-timepicker input-group col-sm-2">
                                                        <input id="timepickercd1" class="form-control" type="text" name="deal_time" value="">
                                                       
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
                                                        <input id="timepickercd2" class="form-control" type="text" name="deal_end_time" value="">

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

                                                        
                                                    </div>
                                                         <?php echo form_error('timezone'); ?>
                                                    </div>
                                                </div>


                                               
                                               <!--  <div class="form-group">
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
                                                -->
                                               <!--  <div class="form-group">
                                                    <label for="contact_name" class="col-sm-2 control-label">Contact Name *</label>
                                                    <div class="col-sm-10">
                                                        <input type="text" name="contact_name" id="contact_name" placeholder="" class="form-control" value="<?php echo set_value1('contact_name',$deal); ?>">
                                                        <?php echo form_error('contact_name'); ?>
                                                    </div>
                                                </div> -->    
                                                <!-- <div class="form-group">
                                                    <label for="contact_number" class="col-sm-2 control-label">Business Number *</label>
                                                    <div class="col-sm-10">
                                                        <input type="text" name="contact_number" id="contact_number" placeholder="" class="form-control" value="<?php echo set_value1('contact_number',$deal); ?>">
                                                        <?php echo form_error('contact_number'); ?>
                                                    </div>
                                                </div> -->    
                                                <!-- <div class="form-group">
                                                    <label for="website" class="col-sm-2 control-label">Website *</label>
                                                    <div class="col-sm-10">
                                                        <input type="text" name="website" id="website" placeholder="" class="form-control" value="<?php echo set_value1('website',$deal); ?>">
                                                        <?php echo form_error('website'); ?>
                                                    </div>
                                                </div>  -->   
                                                    
                                               
                                                
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