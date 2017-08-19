<!-- begin MAIN PAGE CONTENT -->
        <div id="page-wrapper">

            <div class="page-content page-content-ease-in">

                <!-- begin PAGE TITLE ROW -->
                <div class="row">
                    <div class="col-lg-12">
                        <div class="page-title">
                            <h1>
                                Mailbox
                                <small>Message Center</small>
                            </h1>
                            <ol class="breadcrumb">
                                <li><i class="fa fa-dashboard"></i>  <a href="index.html">Dashboard</a>
                                </li>
                                <li class="active">Mailbox</li>
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
                            <div class="portlet-body">
                                <div id="mailbox">
                                        <?php foreach ($info as $v) { ?>
                                        <div>
                                            <img class="img-circle" src="<?php echo base_url(); ?>uploads/user/<?php echo $v['profile_pic'];?>" alt="" style="width: 7%;">
                                        </div>
                                        <div id="mailbox-wrapper" style="margin-top: -100px;  min-height: 75px; margin-left: 123px;">
                                            <div class="msg-col">
                                                <h1><b>FROM : <?php echo $v['first_name'];?> <?php echo $v['last_name'];?> : <?php echo $v['subject'];?></b></h1>
                                            </div>
                                                
                                        </div>
                                        <div id="mailbox-wrapper" style="margin-left: 21px;">
                                                <br/>
                                                <br/>
                                                <br/>
                                            <div class="msg-col">
                                                <?php echo $v['description'];?>
                                            </div>
                                                <br/>
                                                <br/>
                                                <br/>
                                                <div class="col-xs-6">
                                                    <?php if(isset($reply) && !empty($reply)){?>
                                                    <div>
                                                        <ul style="">
                                                            <?php foreach ($reply as $value) { ?>
                                                            <li style="display: inline;">
                                                                <div class="msg-col" style="background-color: rgb(236, 240, 241);border: 1px solid rgb(204, 204, 204);width: 80%;padding-top: 20px;padding-bottom: 30px;">
                                                                    <div style="width: 80%;margin-left: 20px;"><?php echo $value['description'];?></div>
                                                                    <br/>
                                                                    <div style="float: right;width: 25%">- <?php echo $value['first_name'];?> <?php echo $value['last_name'];?></div>
                                                                </div>
                                                            </li>
                                                            <br/>
                                                            <?php }?>
                                                        </ul>
                                                    </div>
                                                    <?php }?>
                                                <form method="post" action="<?php echo base_url();?>site/message_reply">
                                                    <div class="form-group">
                                                        <input type="hidden" name="message_id" value="<?php echo $info[0]['message_id'];?>">
                                                            <textarea class="form-control" id="textArea" name="reply" placeholder="Add Reply"></textarea>
                                                        
                                                    </div>
                                                    <div class="form-group" style="text-align: right">
                                                        
                                                            <input type="submit" name="add_message" class="btn btn-default" value="Reply">
                                                        
                                                    </div>
                                                </form>
                                                </div>
                                        </div>
                                        <?php }?>
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

    <!-- THEME SCRIPTS -->
    <script src="<?php echo base_url(); ?>assets/js/flex.js"></script>

</body>

</html>