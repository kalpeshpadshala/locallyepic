<?php // echo '<pre>'; print_r($info); exit;

?>
 <!-- begin MAIN PAGE CONTENT -->
        <div id="page-wrapper">

            <div class="page-content">

                <!-- begin PAGE TITLE ROW -->
                <div class="row">
                    <div class="col-lg-12">
                        <div class="page-title">
                            <h1>Edit Consumers
                                
                            </h1>
                            <ol class="breadcrumb">
                                <li><i class="fa fa-dashboard"></i>  <a href="<?php echo base_url();?>site/index">Dashboard</a>
                                </li>
                                <li class="active">Edit Consumers</li>
                            </ol>
                        </div>
                    </div>
                    <!-- /.col-lg-12 -->
                </div>
                <!-- /.row -->
                <!-- end PAGE TITLE ROW -->

                <!-- begin MAIN PAGE ROW -->
                <div class="row">


                    <!-- begin RIGHT COLUMN -->
                    <div class="col-lg-12">

                        <div class="row">

                            <!-- Form Controls -->
                            <div class="col-lg-12">
                                <div class="portlet portlet-red">
                                    <div class="portlet-heading">
                                        <div class="portlet-title">
                                            <h4>Edit Consumers</h4>
                                        </div>
                                        <div class="portlet-widgets">
                                            <a data-toggle="collapse" data-parent="#accordion" href="#formControls"><i class="fa fa-chevron-down"></i></a>
                                        </div>
                                        <div class="clearfix"></div>
                                    </div>
                                    <div id="formControls" class="panel-collapse collapse in">
                                        <div class="portlet-body">
                                            <form class="form-horizontal" method="POST" action="<?php echo base_url();?>site/edit_consumers" enctype="multipart/form-data">
                                                <input type="hidden" name="user_id" value="<?php echo $info[0]['user_id']; ?>">
                                                <div class="form-group">
                                                    <label for="name" class="col-sm-2 control-label">Name * : </label>
                                                    <div class="col-sm-10">
                                                        <input type="text" required class="form-control" name="name" id="first_name" value="<?php echo $info[0]['name']; ?>" placeholder="First Name of Sales Manager">
                                                         <?php echo form_error('name'); ?>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label for="email" class="col-sm-2 control-label">Email *: </label>
                                                    <div class="col-sm-10">
                                                        <input type="text" required class="form-control" name="email" id="email" value="<?php echo $info[0]['email']; ?>" placeholder="Email" disabled>                                                        
                                                    </div>
                                                </div>
                                                
                                                
                                                <div class="form-group">
                                                    <label for="address" class="col-sm-2 control-label">Address * : </label>
                                                    <div class="col-sm-10">
                                                        <input type="text" name="address"  placeholder="" class="form-control" value="<?php echo $info[0]['address']; ?>">
                                                    <?php echo form_error('address'); ?>
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <label for="password" class="col-sm-2 control-label">Password : </label>
                                                    <div class="col-sm-10">
                                                        <input type="password" name="password"  placeholder="" class="form-control" value="">
                                                    <?php echo form_error('password'); ?>
                                                    </div>
                                                </div>
                                                
                                                
                                                
                                                <div class="form-group">
                                                    <label for="phone_no" class="col-sm-2 control-label">Phone_no *: </label>
                                                    <div class="col-sm-10">
                                                        <input type="text" required class="form-control" name="phone_no" id="phone_no" value="<?php echo $info[0]['phone_no']; ?>"  placeholder="phone_no">
                                                         <?php echo form_error('phone_no'); ?>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label for="Category" class="col-sm-2 control-label">Category *: </label>
                                                    <div class="col-sm-10">
                                                        <select class="form-control" name="user_cat[]" multiple>
                                                            <option value="">Select Category</option>
                                                            <?php
                                                                $cat1 = explode(",", $info[0]['user_cat']);
                                                            foreach ($cats as $cat) {
                                                                ?>
                                                            <option value="<?php echo $cat['cid']; ?>" <?php if(in_array($cat['cid'], $cat1)){echo 'selected';}?>><?php echo $cat['cname']; ?></option>
                                                                <?php
                                                            }
                                                            ?>
                                                        </select>
                                                        <?php echo form_error('scat'); ?>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="col-sm-2 control-label">Submit :</label>
                                                    <div class="col-sm-10">
                                                        <button type="submit" class="btn btn-default" name="edit_consumers">Edit Consumers</button>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                <!-- /.portlet -->
                            </div>
                            <!-- /.col-lg-12 (nested) -->
                            <!-- End Form Controls -->


                        </div>
                        <!-- /.row (nested) -->

                    </div>
                    <!-- /.col-lg-6 -->
                    <!-- end RIGHT COLUMN -->

                </div>
                <!-- /.row -->
                <!-- end MAIN PAGE ROW -->

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

    <!-- PAGE LEVEL PLUGIN SCRIPTS -->

    <!-- THEME SCRIPTS -->
    <script src="<?php echo base_url(); ?>assets/js/flex.js"></script>

</body>

</html>