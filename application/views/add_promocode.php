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
                                <li class="active">Add Promocode</li>
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
                                            <h4>Add Promocode</h4>
                                        </div>
                                        <div class="portlet-widgets">
                                            <a data-toggle="collapse" data-parent="#accordion" href="#formControls"><i class="fa fa-chevron-down"></i></a>
                                        </div>
                                        <div class="clearfix"></div>
                                    </div>
                                    <div id="formControls" class="panel-collapse collapse in">
                                        <div class="portlet-body">
                                            
                                
                                            
                                            <form class="form-horizontal" action="<?php echo base_url(); ?>site/add_promocode" method="POST" enctype="multipart/form-data">  
                                               
                                                <div class="form-group">
                                                    <label for="promocode" class="col-sm-2 control-label">Promocode *</label>
                                                    <div class="col-sm-10">
                                                        <input type="text" name="promocode" id="promocode" placeholder="" class="form-control" value="<?php echo set_value('promocode'); ?>">
                                                        <?php echo form_error('promocode'); ?>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label for="promocode_text" class="col-sm-2 control-label">Promocode Text *</label>
                                                    <div class="col-sm-10">
                                                        <textarea rows="3" class="form-control" name="promocode_text"><?php echo set_value('promocode_text'); ?></textarea>
                                                       <?php echo form_error('promocode_text'); ?>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="col-sm-2 control-label">Promocode Type</label>
                                                    <div class="col-sm-10">
                                                        <select class="form-control" name="promocode_type">
                                                            <option>activation fee</option>
                                                            <option>monthly fee</option>
                                                            <option>sales override</option>
                                                           
                                                        </select>
                                                    <?php echo form_error('promocode_type'); ?>
                                                    </div>
                                                </div>
                                                
                                                <div class="form-group">
                                                    <label for="dtStart" class="col-sm-2 control-label">Start Date *</label>
                                                    <div id="dtStart1" class="col-sm-2">
                                                        <input class="form-control" type="text" name="dtStart" value="<?php echo set_value('dtStart'); ?>" id="dtStart">
                                                        <?php echo form_error('dtStart'); ?>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label for="dtEnd" class="col-sm-2 control-label">End Date *</label>
                                                    <div id="dtEnd1" class="col-sm-2">
                                                        <input class="form-control" type="text" name="dtEnd" value="<?php echo set_value('dtEnd'); ?>" id="dtEnd">
                                                        <?php echo form_error('dtEnd'); ?>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="col-sm-2 control-label">Promocode Status</label>
                                                    <div class="col-sm-10">
                                                        <select class="form-control" name="status">
                                                            <option value="Activated">Activate</option>
                                                            <option value="Deactivated">Deactivate</option>
                                                        </select>
                                                    <?php echo form_error('status'); ?>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="col-sm-2 control-label">Promocode Type</label>
                                                    <div class="col-sm-10">
                                                        <select class="form-control" name="type">
                                                            <option>Percent Off</option>
                                                            <option>Amount Off</option>
                                                            <option>Set Amount</option>
                                                            <option>Free</option>
                                                            
                                                        </select>
                                                    <?php echo form_error('type'); ?>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label for="percent_amount" class="col-sm-2 control-label">Percentage Amount *</label>
                                                    <div class="col-sm-10">
                                                        <input type="text" name="percent_amount" id="percent_amount" placeholder="" class="form-control" value="<?php echo set_value('percent_amount'); ?>">
                                                        <?php echo form_error('percent_amount'); ?>
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <label class="col-sm-2 control-label">Months Free (Only Applies to Monthly Fee) </label>
                                                    <div class="col-sm-10">
                                                        <select class="form-control" name="intmonthsfree">
                                                            <option value="0">0</option>
                                                            <option>1</option>
                                                            <option>3</option>
                                                            <option>6</option>
                                                            <option>12</option>
                                                            
                                                        </select>
                                                    <?php echo form_error('type'); ?>
                                                    </div>
                                                </div>

                                                
                                                <div class="form-group">
                                                    <label class="col-sm-2 control-label"></label>
                                                    <div class="col-sm-10">
                                                        <button type="submit" class="btn btn-default" name="add_promocode">Add Promocode</button>
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
//$('#sandbox-container input').datepicker({
//    autoclose: true,
//    todayHighlight: true,
//    startDate:new Date(),
//    format: "yyyy-mm-dd",
//});
$('#dtStart1 input').datepicker({
    autoclose: true,
    todayHighlight: true,
    format: "yyyy-mm-dd",
    startDate:new Date(),

});
$('#dtEnd').datepicker({
    autoclose: true,
    todayHighlight: true,
    format: "yyyy-mm-dd",
    startDate:new Date(),

});
    </script>

</body>

</html>