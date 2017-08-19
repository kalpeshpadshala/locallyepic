 <?php
    /*
    echo "<pre>";
    print_r($loyaltyprogram);
    echo "<hr>";
    print_r($loyaltyprogramitems);
    echo "</pre>";
    */

 ?>
 <link href="<?php echo base_url(); ?>assets/css/plugins/bootstrap-timepicker/bootstrap-timepicker.min.css" rel="stylesheet">
    <link href="//code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css" type="text/css" rel="stylesheet">
    <link href="<?php echo base_url(); ?>assets/css/plugins/bootstrap-tokenfield/tokenfield-typeahead.css" rel="stylesheet">
    <link href="<?php echo base_url(); ?>assets/css/plugins/bootstrap-tokenfield/bootstrap-tokenfield.css" rel="stylesheet">
    <link href="<?php echo base_url(); ?>assets/css/plugins/bootstrap-datepicker/datepicker3.css" rel="stylesheet">
<script>

    
</script>
<?php // echo '<pre>'; print_r($info); exit;?>
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
                                <li class="active">Edit Customer Loyalty Details</li>
                            </ol>
                            
                        </div>
                    </div><!-- /.col-lg-12 -->
                    <div class="col-lg-12">
                        <?php if($message){ ?>
                            <div class="alert alert-success">
                                 <strong><?php echo $message; ?></strong>
                            </div>
                        <?php  } 

                        if ($loyaltyprogram->blnShowHelper==1) {?>
                        
                        <div class="alert alert-info" role="alert"><b><h4>Welcome to the Locally Epic Customer Loyalty Program</h4></b>
                            <p>(This goes away after the click the update button below)</p>
                            <p> We have created some sample text below to get you started. You can edit this content or delete it and start fresh. If you have any questions, reach out to your Locally Epic Team member....</p>  
                        </div>

                        <?php 

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
                                <div class="portlet portlet-default">
                                    <div class="portlet-heading">
                                        <div class="portlet-title">
                                            <h4>Customer Loyalty Program</h4>
                                        </div>
                                        <div class="portlet-widgets">
                                            <a data-toggle="collapse" data-parent="#accordion" href="#formControls"><i class="fa fa-chevron-down"></i></a>
                                        </div>
                                        <div class="clearfix"></div>
                                    </div>
                                    <div id="formControls" class="panel-collapse collapse in">
                                        <div class="portlet-body">

                                            <div class="alert alert-warning alert-dismissible" role="alert">
                                              <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                              <b>Details of Your Customer Loyalty Program</b><br>
                                              Enter the title and description of your Customer Loyalty Program.  Leave the program off until you have completed both sections.
                                              <ol>
                                                <li><b>Title:</b>  Name your Customer Loyalty Program.</li>
                                                <li><b>Description:</b> Give an overview of what your loyalty program is all about.</li>
                                                <li><b>Active:</b>  Yes turns your loyalty program on.  No turns it off.</li> 
                                            </div>

                                            <form class="form-horizontal" action="<?php echo base_url(); ?>loyalty/updateProgram" method="POST" enctype="multipart/form-data">  
                                                <div class="form-group">
                                                    <label for="promocode" class="col-sm-2 control-label">Title *</label>
                                                    <div class="col-sm-10">
                                                        <input type="text" name="lptitle" id="lptitle" placeholder="" class="form-control" value="<?php echo set_value('lptitle', $loyaltyprogram->title); ?>">
                                                        <?php echo form_error('lptitle'); ?>
                                                    </div>
                                                   
                                                </div>
                                                <div class="form-group">
                                                    <label for="promocode_text" class="col-sm-2 control-label">Description *</label>
                                                    <div class="col-sm-10">
                                                        <textarea rows="3" class="form-control" name="lpdescription"><?php echo set_value('lpdescription', $loyaltyprogram->description); ?></textarea>
                                                       <?php echo form_error('lpdescription'); ?>
                                                    </div>
                                                </div>
                                                <?php $_POST["blnStatus"] = $loyaltyprogram->blnStatus;?>
                                                <div class="form-group">
                                                    <label class="col-sm-2 control-label">Active</label>
                                                    <div class="col-sm-10">
                                                        <select class="form-control" name="blnStatus">
                                                            <option value="1" <?php echo set_select('blnStatus', '1', TRUE); ?>>Yes</option>
                                                            <option value="0" <?php echo set_select('blnStatus', '0', TRUE); ?>>No</option>
                                                        </select>
                                                    <?php echo form_error('blnStatus'); ?>
                                                    </div>
                                                </div>
                                                
                                                 <!-- <div class="form-group">
                                                    <label class="col-sm-2 control-label"></label>
                                                  <div class="col-sm-10">
                                                        <button type="submit" class="btn btn-default" name="edit_promocode">Update</button>
                                                    </div>
                                                </div>-->
                                            
                                        </div>
                                    </div>
                                </div>
                                <!-- /.portlet -->

                                <div class="portlet portlet-default">
                                    <div class="portlet-heading">
                                        <div class="portlet-title">
                                            <h4>Edit Customer Loyalty Program Details</h4>
                                        </div>
                                        <div class="portlet-widgets">
                                            <a data-toggle="collapse" data-parent="#accordion" href="#formControls"><i class="fa fa-chevron-down"></i></a>
                                        </div>
                                        <div class="clearfix"></div>
                                    </div>
                                    <div id="formControls" class="panel-collapse collapse in">
                                        <div class="portlet-body">

                                            <div class="alert alert-warning alert-dismissible" role="alert">
                                              <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                              <b>Setup Your Customer Loyalty Program</b><br>
                                              Here is where you setup the goals of your loyalty program.  We gave you a goal as a starting point.  You can have as few or many goals as you want.
                                              Here is a run down of each of the fields:
                                              <ol>
                                                <li><b>Title:</b>  Explain in a short sentence what your customer will receive when the number of deals have been activated.</li>
                                                <li><b>Description:</b> Provide more information about the goal here.  Also any fine print should go here</li>
                                                <li><b>Number of Activations:</b>  This is the number of deals your customer needs to activate before receiving the reward.</li> 
                                            </div>
                                            <input type="hidden" name="idstodelete" id="idstodelete" values="">
                                                <div class="lpbox">

                                                    <div class="row" id="heading"  style="padding-bottom: 5px;">
                                                        <div class="col-xs-4"><b>Title</b></div>
                                                        <div class="col-xs-5"><b>Description</b></div>
                                                        <div class="col-xs-2"><b>Number of Activations</b></div>
                                                      
                                                    </div>


                                                    <?php
                                                        
                                                        foreach ($loyaltyprogramitems as $v){?>

                                                            <div class="row edit" data-id="<?php echo $v["id"]; ?>" style="padding-bottom: 5px;">
                                                                <input type="hidden" name="id[]" value="<?php echo $v["id"]; ?>">
                                                                <div class="col-xs-4"><input type="text" class="form-control" name="title[]" placeholder="Title" value="<?php echo $v["title"];?>" style="width:100%"></div>
                                                                <div class="col-xs-5"><input type="text" class="form-control" name="description[]" placeholder="Description" value="<?php echo $v["description"]; ?>" style="width:100%"></div>
                                                                <div class="col-xs-2"><input type="text" class="form-control" name="intActivations[]" placeholder="# of Activations" value="<?php echo $v["intActivations"];?>" style="width:100%"></div>
                                                                <a class="btn btn-success"><span class="glyphicon glyphicon-plus" aria-hidden="true"></span></a>
                                                                <a class="btn btn-danger" style="display:;"><span class="glyphicon glyphicon-minus" aria-hidden="true"></span></a>
                                                            </div>

                                                            
                                                        <?php }?>

                                                       



                                                    <div class="row" id="parent"  style="padding-bottom: 5px;">
                                                        <div class="col-xs-4"><input type="text" class="form-control" name="title[]" placeholder="Title" value="" style="width:100%"></div>
                                                        <div class="col-xs-5"><input type="text" class="form-control" name="description[]" placeholder="Description" value="" style="width:100%"></div>
                                                        <div class="col-xs-2"><input type="text" class="form-control" name="intActivations[]" placeholder="# of Activations" value="" style="width:100%"></div>
                                                        <a class="btn btn-success"><span class="glyphicon glyphicon-plus" aria-hidden="true"></span></a>
                                                        <a class="btn btn-danger" style="display:none"><span class="glyphicon glyphicon-minus" aria-hidden="true"></span></a>
                                                    </div>
                                                </div>
                                                <center><button type="submit" class="btn btn-default">Update</button></center>
                                                
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                <!-- /.portlet -->

<script>

$(document).ready(function(){

    $(".lpbox").on('click','.btn-success',function(){

        var nr = $("#parent").clone();

            nr.removeAttr('id');
            nr.find(".btn.btn-danger").css('display','');
            $(".lpbox").append(nr);

    });

    $(".lpbox").on('click','.btn-danger',function(){

            if ($(this).closest("div").hasClass('edit')){

                var ids = $("#idstodelete").val();
                var id = $(this).closest("div").attr('data-id');
                $("#idstodelete").val(ids+","+id);



            }

            $(this).closest("div").remove();

    });




});

</script>


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
    <?php 

    
    require("application/views/logout.php"); ?>
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