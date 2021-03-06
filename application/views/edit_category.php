
        <!-- begin MAIN PAGE CONTENT -->
        <div id="page-wrapper">

            <div class="page-content">

                <!-- begin PAGE TITLE ROW -->
                <div class="row">
                    <div class="col-lg-12">
                        <div class="page-title">
                            <h1>Edit Category
                                
                            </h1>
                            <ol class="breadcrumb">
                                <li><i class="fa fa-dashboard"></i>  <a href="<?php echo base_url();?>site/index">Dashboard</a>
                                </li>
                                <li class="active">Edit Category</li>
                            </ol>
                        </div>
                    </div>
                    <!-- /.col-lg-12 -->
                </div>
                <!-- /.row -->
                <!-- end PAGE TITLE ROW -->

                <!-- begin MAIN PAGE ROW -->
                <div class="row">
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

                    <!-- begin RIGHT COLUMN -->
                    <div class="col-lg-12">

                        <div class="row">

                            <!-- Form Controls -->
                            <div class="col-lg-12">
                                <div class="portlet portlet-red">
                                    <div class="portlet-heading">
                                        <div class="portlet-title">
                                            <h4>Edit Category</h4>
                                        </div>
                                        <div class="portlet-widgets">
                                            <a data-toggle="collapse" data-parent="#accordion" href="#formControls"><i class="fa fa-chevron-down"></i></a>
                                        </div>
                                        <div class="clearfix"></div>
                                    </div>
                                    <div id="formControls" class="panel-collapse collapse in">
                                        <div class="portlet-body">
                                 <form action="<?php echo base_url(); ?>site/edit_category" method="POST" enctype="multipart/form-data">
                                    <input type="hidden" name="id" value="<?php echo $info['cid']; ?>">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th class="col-md-4">Description</th>
                                                <th class="col-md-8">Form Elements</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                          
                                            <tr>
                                                <td class="col-md-4">Category Name</td>
                                           
                                                 <td class="col-md-8">
                                                    <input type="text" rows="3" class="form-control" name="cname" value="<?php echo $info['cname']; ?>" />
                                                    <?php echo form_error('cname'); ?>
                                                </td>
                                            </tr>
                                             <?php
                                                            if(!empty($info['cimage'])){
                                                        ?>
                                            <tr>
                                                <td class="col-md-4">Category Image:</td>
                                           
                                                 <td class="col-md-8">
                                                   
                                                   
                                                                    <img src="<?php echo base_url(); ?>uploads/<?php echo $info['cimage']; ?>" width="150" height="auto">
                                                          
                                                            
                                                       
                                                </td>
                                            </tr>
                                             <?php
                                                            }
                                                        ?>
                                            <tr>
                                                <td class="col-md-4">Category Image</td>
                                                <td class="col-md-8"><input type="file" name="cfile"></td>
                                            </tr>
                                            <tr>
                                                <td class="col-md-4">Distance</td>
                                           
                                                 <td class="col-md-8">
                                                    <input type="text" rows="3"  name="dis" value="<?php echo $info['dis']; ?>" /> &nbsp; (In mile)
                                                    <?php echo form_error('dis'); ?>
                                                </td>
                                            </tr>
                                           
                                           
                                            <tr>
                                                <td class="col-md-4"></td>
                                                <td class="col-md-8"><button type="submit" name="edit_category" class="btn btn-primary">Submit</button></td>
                                            </tr>


                                        </tbody>
                                    </table>
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