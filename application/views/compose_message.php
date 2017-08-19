<?php // echo '<pre>'; print_r($info); exit;?>

<!-- begin MAIN PAGE CONTENT -->
        <div id="page-wrapper">

            <div class="page-content page-content-ease-in">

                <!-- begin PAGE TITLE ROW -->
                <div class="row">
                    <div class="col-lg-12">
                        <div class="page-title">
                            <h1>
                                Compose Message
                            </h1>
                            <ol class="breadcrumb">
                                <li><i class="fa fa-dashboard"></i>  <a href="index.html">Dashboard</a>
                                </li>
                                <li class="active">Compose Message</li>
                            </ol>
                        </div>
                    </div>
                    <!-- /.col-lg-12 -->
                </div>
                <!-- /.row -->
                <!-- end PAGE TITLE ROW -->

                <div class="row">
                    <div class="col-lg-12">
                        <div class="portlet portlet-default">
                            <div class="portlet-heading">
                                <div class="portlet-title">
                                    <h4>New Message</h4>
                                </div>
                                <div class="clearfix"></div>
                            </div>
                            
                            <div class="portlet-body">
                                <div class="form-group">
                                    <div class="input-group" style="width : 25%">
                                        <span class="input-group-addon">To:</span>
                                        <select class="form-control" name="sm">
                                            <option>select Super Manager</option>
                                             <?php foreach ($info as $v) {?>
                                                <?php if($v['role'] == 1){?>
                                                    <option value="<?php echo $v['user_id'];?>"><?php echo $v['first_name'] . " " . $v['last_name'];?></option>
                                                <?php } } ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="input-group"  style="width : 25%">
                                        <span class="input-group-addon">To:</span>
                                        <select class="form-control" name="nsm">
                                                <option>select National Sales Manager</option>
                                                <option value="0">All</option>
                                             <?php foreach ($info as $v) {?>
                                                <?php if($v['role'] == 2){?>
                                                <option value="<?php echo $v['user_id'];?>"><?php echo $v['first_name'] . " " . $v['last_name'];?></option>
                                                <?php } } ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="input-group"  style="width : 25%">
                                        <span class="input-group-addon">To:</span>
                                        <select class="form-control" name="ssm">
                                                <option>select State Sales Manager</option>
                                                <option value="0">All</option>
                                             <?php foreach ($info as $v) {?>
                                                <?php if($v['role'] == 3){?>
                                                <option value="<?php echo $v['user_id'];?>"><?php echo $v['first_name'] . " " . $v['last_name'];?></option>
                                                <?php } } ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="input-group"  style="width : 25%">
                                        <span class="input-group-addon">To:</span>
                                        <select class="form-control" name="asm">
                                                <option>select Area Sales Manager</option>
                                                <option value="0">All</option>
                                             <?php foreach ($info as $v) {?>
                                                <?php if($v['role'] == 4){?>
                                                    <option value="<?php echo $v['user_id'];?>"><?php echo $v['first_name'] . " " . $v['last_name'];?></option>
                                            <?php  } } ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="input-group"  style="width : 25%">
                                        <span class="input-group-addon">To:</span>
                                        <select class="form-control" name="sp">
                                                <option>select Sales People</option>
                                                <option value="0">All</option>
                                             <?php foreach ($info as $v) {?>
                                                <?php if($v['role'] == 5){?>
                                                    <option value="<?php echo $v['user_id'];?>"><?php echo $v['first_name'] . " " . $v['last_name'];?></option>
                                                <?php  }} ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="input-group"  style="width : 25%">
                                        <span class="input-group-addon">To:</span>
                                        <select class="form-control" name="bp">
                                                <option>select Business People</option>
                                                <option value="0">All</option>
                                             <?php foreach ($info as $v) {?>
                                                <?php if($v['role'] == 6){?>
                                                    <option value="<?php echo $v['user_id'];?>"><?php echo $v['first_name'] . " " . $v['last_name'];?></option>
                                                <?php  }} ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="input-group">
                                        <span class="input-group-addon">From:</span>
                                        <input type="text" class="form-control" value="<?php echo $user_info['first_name'] . " " . $user_info['last_name'];?> <<?php echo $user_info['email'];?>>" disabled>
                                    </div>
                                </div>
                                <hr>
                                <div class="form-group">
                                    <div class="input-group">
                                        <span class="input-group-addon">Subject:</span>
                                        <input type="text" class="form-control">
                                    </div>
                                </div>
                                <hr>
                                <div id="summernote" style="display: none;">
                                    <br>
                                    <br>--
                                    <br>
                                    <strong>John Smith</strong>
                                    <br>Managing Director, FlexCo
                                    <br>john.smith@website.com
                                </div>
                                
                            </div>
                            <div class="portlet-footer">
                                <div class="btn-toolbar" role="toolbar">
                                    <button class="btn btn-default"><i class="fa fa-envelope"></i> Send</button>
                                    <button class="btn btn-red pull-right"><i class="fa fa-times"></i> Discard</button>
                                    <button class="btn btn-green pull-right"><i class="fa fa-paperclip"></i> Add Attachment</button>
                                </div>
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
    
     <!-- HubSpot Messenger -->
    <script src="<?php echo base_url(); ?>assets/plugins/messenger/messenger.js"></script>
    <script src="<?php echo base_url(); ?>assets/plugins/messenger/messenger.min.js"></script>
    <script src="<?php echo base_url(); ?>assets/plugins/messenger/messenger-theme-flat.js"></script>
    
    
     <!-- theme summernote -->
    <script src="<?php echo base_url(); ?>assets/js/plugins/summernote/summernote.min.js"></script>
    
    <script>
    $(document).ready(function() {
        $('#summernote').summernote({
            height: 300,
            toolbar: [
                ['style', ['bold', 'italic', 'underline', 'clear']],
                ['fontsize', ['fontsize']],
                ['color', ['color']],
                ['para', ['ul', 'ol', 'paragraph']],
            ]

        });
    });
    </script>

    <!-- THEME SCRIPTS -->
    <script src="<?php echo base_url(); ?>assets/js/flex.js"></script>

</body>

</html>