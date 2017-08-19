<!-- begin MAIN PAGE CONTENT -->
        <div id="page-wrapper">

            <div class="page-content page-content-ease-in">

                <!-- begin PAGE TITLE ROW -->
                <div class="row">
                    <div class="col-lg-12">
                        <div class="page-title">
                            <h1>
                                Chat
                                <small>Conversation Widget</small>
                            </h1>
                            <ol class="breadcrumb">
                                <li><i class="fa fa-dashboard"></i>  <a href="index.html">Dashboard</a>
                                </li>
                                <li class="active">Chat</li>
                            </ol>
                        </div>
                    </div>
                    <!-- /.col-lg-12 -->
                </div>
                <!-- /.row -->
                <!-- end PAGE TITLE ROW -->

                <div class="row">
                    <div class="col-lg-4 col-lg-offset-4">
                        <div class="portlet portlet-default">
                            <div class="portlet-heading">
                                <div class="portlet-title">
                                    <h4><i class="fa fa-circle text-green"></i> Jane Smith</h4>
                                </div>
                                <div class="portlet-widgets">
                                    <div class="btn-group">
                                        <button type="button" class="btn btn-white dropdown-toggle btn-xs" data-toggle="dropdown">
                                            <i class="fa fa-circle text-green"></i> Status
                                            <span class="caret"></span>
                                        </button>
                                        <ul class="dropdown-menu" role="menu">
                                            <li><a href="#"><i class="fa fa-circle text-green"></i> Online</a>
                                            </li>
                                            <li><a href="#"><i class="fa fa-circle text-orange"></i> Away</a>
                                            </li>
                                            <li><a href="#"><i class="fa fa-circle text-red"></i> Offline</a>
                                            </li>
                                        </ul>
                                    </div>
                                    <span class="divider"></span>
                                    <a data-toggle="collapse" data-parent="#accordion" href="#chat"><i class="fa fa-chevron-down"></i></a>
                                </div>
                                <div class="clearfix"></div>
                            </div>
                            <div id="chat" class="panel-collapse collapse in">
                                <div class="slimScrollDiv" style="position: relative; overflow: hidden; width: auto; height: 300px;"><div class="portlet-body chat-widget" style="overflow: hidden; width: auto; height: 300px;">
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <p class="text-center text-muted small">January 1, 2014 at 12:23 PM</p>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <div class="media">
                                                <a class="pull-left" href="#">
                                                    <img class="media-object img-circle" src="img/user-profile-1.jpg" alt="">
                                                </a>
                                                <div class="media-body">
                                                    <h4 class="media-heading">Jane Smith
                                                        <span class="small pull-right">12:23 PM</span>
                                                    </h4>
                                                    <p>Hi, I wanted to make sure you got the latest product report. Did Roddy get it to you?</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <div class="media">
                                                <a class="pull-left" href="#">
                                                    <img class="media-object img-circle" src="img/profile-pic-small.jpg" alt="">
                                                </a>
                                                <div class="media-body">
                                                    <h4 class="media-heading">John Smith
                                                        <span class="small pull-right">12:28 PM</span>
                                                    </h4>
                                                    <p>Yeah I did. Everything looks good.</p>
                                                    <p>Did you have an update on purchase order #302?</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <div class="media">
                                                <a class="pull-left" href="#">
                                                    <img class="media-object img-circle" src="img/user-profile-1.jpg" alt="">
                                                </a>
                                                <div class="media-body">
                                                    <h4 class="media-heading">Jane Smith
                                                        <span class="small pull-right">12:39 PM</span>
                                                    </h4>
                                                    <p>No not yet, the transaction hasn't cleared yet. I will let you know as soon as everything goes through. Any idea where you want to get lunch today?</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <hr>
                                </div><div class="slimScrollBar" style="width: 7px; position: absolute; top: 92px; opacity: 0.4; display: block; border-radius: 7px; z-index: 99; right: 1px; height: 208.333333333333px; background: rgb(0, 0, 0);"></div><div class="slimScrollRail" style="width: 7px; height: 100%; position: absolute; top: 0px; display: none; border-radius: 7px; opacity: 0.2; z-index: 90; right: 1px; background: rgb(51, 51, 51);"></div></div>
                                <div class="portlet-footer">
                                    <form role="form">
                                        <div class="form-group">
                                            <textarea class="form-control" placeholder="Enter message..."></textarea>
                                        </div>
                                        <div class="form-group">
                                            <button type="submit" class="btn btn-default pull-right">Send</button>
                                            <div class="clearfix"></div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /.col-lg-4 -->
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