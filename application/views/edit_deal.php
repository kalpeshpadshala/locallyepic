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
                                <li class="active">Edit Offer</li>
                            </ol>
                            
                        </div>
                    </div><!-- /.col-lg-12 -->
                    <div class="col-lg-12">
                        <?php if($message){ ?>
                            <div class="alert alert-success">
                                 <strong><?php echo $message; ?></strong>
                            </div>
                        <?php  } ?>
                    </div>
                    <!-- /.col-lg-12 -->
                </div>
                <!-- /.row -->
                <!-- end PAGE TITLE ROW -->
                <div class="row">
                 <!-- Hoverable Responsive Table -->
                 <div class="col-lg-12">
                                <div class="portlet portlet-red">
                                    <div class="portlet-heading">
                                        <div class="portlet-title">
                                            <h4>Edit Offer</h4>
                                        </div>
                                        <div class="portlet-widgets">
                                            <a data-toggle="collapse" data-parent="#accordion" href="#formControls"><i class="fa fa-chevron-down"></i></a>
                                        </div>
                                        <div class="clearfix"></div>
                                    </div>
                                    <div id="formControls" class="panel-collapse collapse in">
                                        <div class="portlet-body">
                                            <form class="form-horizontal" action="<?php echo base_url(); ?>site/edit_deal" method="POST" enctype="multipart/form-data">  
                                                <input type="hidden" name="deal_id" value="<?php echo $deal['id']; ?>">
                                                 
                                                  <?php if($user_info['role'] !=6){?>

                                                 <div class="form-group">
                                                    <label class="col-sm-2 control-label">Selects</label>
                                                    <div class="col-sm-10">
                                                        <select class="form-control" name="shop_id" >
                                                            <option value="">Select Shop</option>
                                                                <?php
                                                                foreach ($shops as $v) {
                                                                        ?>
                                                                    <option value="<?php echo $v['shop_id']; ?>" <?php if ($deal['shop_id'] == $v['shop_id']) {
                                                                    echo "selected";
                                                                } ?>><?php echo $v['shop_name']; ?></option>
                                                                    <?php
                                                                }
                                                                ?>
                                                        </select>
                                                    <?php echo form_error('shop_id'); ?>
                                                    </div>
                                                </div>
                                                <?php } ?>
                                                <div class="form-group">
                                                    <label for="deal_title" class="col-sm-2 control-label">Offer Title *</label>
                                                    <div class="col-sm-10">
                                                        <input type="text" name="deal_title" id="deal_title" placeholder="" class="form-control" value="<?php echo $deal['deal_title']; ?>">
                                                        <?php echo form_error('deal_title'); ?>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label for="textArea" class="col-sm-2 control-label">Offer Description *</label>
                                                    <div class="col-sm-10">
                                                        <textarea rows="3" class="form-control" name="deal_description"><?php echo $deal['deal_description']; ?></textarea>
                                                       <?php echo form_error('deal_description'); ?>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label for="original_price" class="col-sm-2 control-label">Original Price($) *</label>
                                                    <div class="col-sm-10">
                                                        <input type="text" name="original_price" id="original_price" placeholder="" class="form-control" value="<?php echo $deal['original_price']; ?>">
                                                        <?php echo form_error('original_price'); ?>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label for="offer_price" class="col-sm-2 control-label">Offer Price($) *</label>
                                                    <div class="col-sm-10">
                                                        <input type="text" name="offer_price" id="offer_price" placeholder="" class="form-control" value="<?php echo $deal['offer_price']; ?>">
                                                        <?php echo form_error('offer_price'); ?>
                                                    </div>
                                                </div>
                                                
                                                <div class="form-group">
                                                    <label for="deal_image" class="col-sm-2 control-label">Offer image  : </label>
                                                    <div class="col-sm-4">
                                                        <img src="<?php echo $deal['deal_image']; ?>" style="max-width:800px">
                                                    </div>
                                                </div>
                                               
                                                <div class="form-group">
                                                    <label for="deal_image" class="col-sm-2 control-label">Offer image</label>
                                                    <div class="col-sm-4">
                                                        <input type="file" name="deal_image" id="deal_image" placeholder="Deal Image" class="form-control" value="<?php echo $deal['deal_image']; ?>">(For the best results upload a .jpg photo with the dimensions 790 x 392.)
                                                        <?php echo form_error('deal_image'); ?>
                                                    </div>
                                                </div>
                                                

                                                <div class="form-group">
                                                    <label for="barcode_image" class="col-sm-2 control-label">Barcode image  : </label>
                                                    <div class="col-sm-4">
                                                        <img src="<?php echo $deal['barcode_image']; ?>" style="max-width:800px">
                                                    </div>
                                                </div>
                                               
                                                <div class="form-group">
                                                    <label for="barcode_image" class="col-sm-2 control-label">Barcode image</label>
                                                    <div class="col-sm-4">
                                                        <input type="file" name="barcode_image" id="barcode_image" placeholder="Deal Image" class="form-control" value="<?php echo $deal['barcode_image']; ?>">
                                                        <?php echo form_error('barcode_image'); ?>
                                                    </div>
                                                </div>


                                                <div class="form-group">
                                                    <label for="deal_start" class="col-sm-2 control-label">Offer Date *</label>
                                                    <div id="sandbox-container" class="col-sm-2">
                                                        <input class="form-control" type="text" name="deal_start" value="<?php echo set_value('deal_start',$deal['deal_start']); ?>" id="deal_start">
                                                        <?php echo form_error('deal_start'); ?>
                                                    </div>
                                                </div>


                                                <div class="form-group">
                                                    <label for="timepicker1_edit" class="col-sm-2 control-label">Offer Start Time</label>
                                                    <div class="col-sm-10">
                                                    <div class="input-append bootstrap-timepicker input-group col-sm-2">
                                                        <input id="timepicker1_edit" class="form-control" type="text" name="deal_time" value="<?php echo $deal['deal_time']; ?>" autocomplete="off">
                                                        
                                                        <span class="input-group-btn">
                                                            <button class="btn btn-default add-on" type="button"><i class="fa fa-clock-o"></i>
                                                            </button>
                                                        </span>
                                                       
                                                    </div>
                                                         <?php echo form_error('deal_time'); ?>
                                                    </div>
                                                </div>


                                                <div class="form-group">
                                                    <label for="timepicker2_edit" class="col-sm-2 control-label">Offer End Time</label>
                                                    <div class="col-sm-10">
                                                    <div class="input-append bootstrap-timepicker input-group col-sm-2">
                                                        <input id="timepicker2_edit" class="form-control" type="text" name="deal_end_time" value="<?php echo $deal['deal_end_time']; ?>">
                                                        
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
                           
                                              <!--   <div class="form-group">
                                                    <label for="featured" class="col-sm-2 control-label">Hot Offer: $2</label>
                                                        <div class="col-sm-8">
                                                        <div class="checkbox">
                                                            <label>
                                                                <input type="checkbox" id="featured" name="featured" <?php if($deal['featured_deal']==1){echo "checked";} ?>>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                                <br/>(Your hot Offer can be seen by The Consumer where ever they are)
                                                            </label>
                                                    
                                                        </div>
                                                    </div>
                                                </div> -->
                                               
                                                
                                                <div class="form-group">
                                                    <label class="col-sm-2 control-label"></label>
                                                    <div class="col-sm-10">
                                                        <button type="submit" class="btn btn-default" name="edit_deal">Edit Offer</button>
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

    <script src="<?php echo base_url(); ?>assets/js/demo/advanced-form-demo.js"></script>

</body>

</html>