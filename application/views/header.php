<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>LOCALLY EPIC - Offer Management System <?php //echo $_SERVER["SERVER_NAME"]; ?></title>

    <!-- PACE LOAD BAR PLUGIN - This creates the subtle load bar effect at the top of the page. -->
    <link href="<?php echo base_url(); ?>assets/css/plugins/pace/pace.css" rel="stylesheet">
    <script src="<?php echo base_url(); ?>assets/js/plugins/pace/pace.js"></script>

    <!-- GLOBAL STYLES - Include these on every page. -->
    <link href="<?php echo base_url(); ?>assets/css/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href='//fonts.googleapis.com/css?family=Ubuntu:300,400,500,700,300italic,400italic,500italic,700italic' rel="stylesheet" type="text/css">
    <link href='//fonts.googleapis.com/css?family=Open+Sans:300italic,400italic,600italic,700italic,800italic,400,300,600,700,800' rel="stylesheet" type="text/css">
    <link href="<?php echo base_url(); ?>assets/icons/font-awesome/css/font-awesome.min.css" rel="stylesheet">
    <!-- FAVICON icon-->
    <link rel="icon" href="<?php echo base_url(); ?>img/favicon.png" type="image/x-icon" />
    <!-- PAGE LEVEL PLUGIN STYLES -->
    <link href="<?php echo base_url(); ?>assets/css/plugins/messenger/messenger.css" rel="stylesheet">
    <link href="<?php echo base_url(); ?>assets/css/plugins/messenger/messenger-theme-flat.css" rel="stylesheet">
    <link href="<?php echo base_url(); ?>assets/css/plugins/daterangepicker/daterangepicker-bs3.css" rel="stylesheet">
    <link href="<?php echo base_url(); ?>assets/css/plugins/morris/morris.css" rel="stylesheet">
    <link href="<?php echo base_url(); ?>assets/css/plugins/jvectormap/jquery-jvectormap-1.2.2.css" rel="stylesheet">
    <link href="<?php echo base_url(); ?>assets/css/plugins/datatables/datatables.css" rel="stylesheet">

    <!-- theme summernote -->
    <link href="<?php echo base_url(); ?>assets/css/plugins/summernote/summernote.css" rel="stylesheet">
    <link href="<?php echo base_url(); ?>assets/css/plugins/summernote/summernote-bs3.css" rel="stylesheet">

    <!-- THEME STYLES - Include these on every page. -->
    <link href="<?php echo base_url(); ?>assets/css/style.css" rel="stylesheet">
    <link href="<?php echo base_url(); ?>assets/css/plugins.css" rel="stylesheet">

    <!-- THEME DEMO STYLES - Use these styles for reference if needed. Otherwise they can be deleted. -->
    <link href="<?php echo base_url(); ?>assets/css/demo.css" rel="stylesheet">
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>

    <!--[if lt IE 9]>
      <script src="<?php echo base_url(); ?>assets/js/html5shiv.js"></script>
      <script src="<?php echo base_url(); ?>assets/js/respond.min.js"></script>
    <![endif]-->
    <script>

                $(document).ready(function(){
                    var x =0;

                    $('#messageScroll').scroll(function(){
                        console.log($('#msg_ul').height() - $('#messageScroll').height());
                        console.log($('#messageScroll').scrollTop() + 1);
                      if ($('#messageScroll').scrollTop() + 1 == $('#msg_ul').height() - $('#messageScroll').height()) {
                        $.ajax({
                        method: "POST",
                        url: "<?php echo base_url();?>site/list_message_header_ajax",
                        data: { offset: x += 10 },
                      })
                        .done(function( response ) {
                             console.log(response);
                             $('#msg_ul').append(response);
                        });
                        }
                    });
                });

        </script>
<?php if ($_SERVER["SERVER_NAME"]!='db.locallyepic.com') {?>
<style>
.navbar-top {
  margin-left: 0;
  background-color: greenyellow;
}
</style>
<?php } ?>
</head>

<body>

    <div id="wrapper">

        <!-- begin TOP NAVIGATION -->
        <nav class="navbar-top" role="navigation">

            <!-- begin BRAND HEADING -->
            <div class="navbar-header">
                <button type="button" class="navbar-toggle pull-right" data-toggle="collapse" data-target=".sidebar-collapse">
                    <i class="fa fa-bars fa-2x"></i> Menu
                </button>
                <div class="navbar-brand">
                    <a href="<?php echo base_url();?>" style="position:relative;top:23px;">
                        <img src="<?php echo base_url(); ?>img/logo-white-sm.png" data-1x="<?php echo base_url(); ?>img/logo-white-sm.png" data-2x="<?php echo base_url(); ?>img/logo-white-sm.png" class="hisrc img-responsive" alt="">
                    </a>
                </div>
            </div>
            <!-- end BRAND HEADING -->

            <div class="nav-top">

                <!-- begin LEFT SIDE WIDGETS -->
                <ul class="nav navbar-left">
                    <li class="tooltip-sidebar-toggle">
                        <a href="#" id="sidebar-toggle" data-toggle="tooltip" data-placement="right" title="Sidebar Toggle">
                            <i class="fa fa-bars fa-2x"></i>
                        </a>
                    </li>

                    <!-- You may add more widgets here using <li> -->
                    <?php if ($_SERVER["SERVER_NAME"]!='db.locallyepic.com') {?>
                    <li><span style="color:red; font-weight:bold; font-size:16px;">
                    **DEV/TEST SITE** **DEV/TEST SITE** **DEV/TEST SITE** **DEV/TEST SITE** **DEV/TEST SITE** **DEV/TEST SITE** **DEV/TEST SITE** **DEV/TEST SITE**
                    </span></li>
                    <?php } ?>
                </ul>
                <!-- end LEFT SIDE WIDGETS -->

                <!-- begin MESSAGES/ALERTS/TASKS/USER ACTIONS DROPDOWNS -->
                <ul class="nav navbar-right">

                    <!-- begin MESSAGES DROPDOWN -->
                    <?php if(($user_info['role'] == "111118")){
                   ?>

                    <li class="dropdown">
                        <a href="#" class="messages-link dropdown-toggle" data-toggle="dropdown">
                            <i class="fa fa-envelope"></i>
                            <span class="number"><?php echo $unread;?></span> <i class="fa fa-caret-down"></i>
                        </a>
                        <ul class="dropdown-menu dropdown-scroll dropdown-messages">

                             <!--Messages Dropdown Heading-->
                            <li class="dropdown-header">
                                <i class="fa fa-envelope"></i> <?php echo $unread;?> New Messages
                            </li>

                             <!--Messages Dropdown Body - This is contained within a SlimScroll fixed height box. You can change the height using the SlimScroll jQuery features.-->
                            <li id="messageScroll">
                                <ul class="list-unstyled" id="msg_ul">
                                    <?php foreach ($message_info as $v1) { ?>
                                        <li>
                                            <a href="<?php echo base_url();?>site/list_message">
                                                <div class="row">
                                                    <div class="col-xs-2">
                                                        <img class="img-circle" src="<?php echo base_url(); ?>uploads/user/<?php echo $v1['profile_pic'];?>" alt="" style="width: 190%;">
                                                    </div>
                                                    <div class="col-xs-10">
                                                        <p>
                                                            <strong><?php echo $v1['first_name'];?> <?php echo $v1['last_name'];?></strong>: <?php echo $v1['subject'];?>...
                                                        </p>
                                                        <p class="small">
                                                            <i class="fa fa-clock-o"></i> <?php echo $v1['message_date'];?>
                                                        </p>
                                                    </div>
                                                </div>
                                            </a>
                                        </li>
                                   <?php };?>
                                </ul>
                            </li>

                             <!--Messages Dropdown Footer-->
                            <li class="dropdown-footer">
                                <a href="<?php echo base_url();?>site/list_message">Read All Messages</a>
                            </li>

                        </ul>
                         <!--/.dropdown-menu-->
                    </li><?php }?>
                     <!--/.dropdown-->
                     <!--end MESSAGES DROPDOWN-->
                     <?php if($user_info['role'] == '53456352354235'){ ?>
                     <!--begin ALERTS DROPDOWN-->
                    <li class="dropdown">
                        <a href="#" class="alerts-link dropdown-toggle" data-toggle="dropdown">
                            <i class="fa fa-bell"></i>
                            <span class="number">9</span><i class="fa fa-caret-down"></i>
                        </a>
                        <ul class="dropdown-menu dropdown-scroll dropdown-alerts">

                             <!--Alerts Dropdown Heading-->
                            <li class="dropdown-header">
                                <i class="fa fa-bell"></i> 9 New Alerts
                            </li>

                             <!--Alerts Dropdown Body - This is contained within a SlimScroll fixed height box. You can change the height using the SlimScroll jQuery features.-->
                            <li id="alertScroll">
                                <ul class="list-unstyled">
                                    <li>
                                        <a href="#">
                                            <div class="alert-icon green pull-left">
                                                <i class="fa fa-money"></i>
                                            </div>
                                            Order #2931 Received
                                            <span class="small pull-right">
                                                <strong>
                                                    <em>3 minutes ago</em>
                                                </strong>
                                            </span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#">
                                            <div class="alert-icon blue pull-left">
                                                <i class="fa fa-comment"></i>
                                            </div>
                                            New Comments
                                            <span class="badge blue pull-right">15</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#">
                                            <div class="alert-icon orange pull-left">
                                                <i class="fa fa-wrench"></i>
                                            </div>
                                            Crawl Errors Detected
                                            <span class="badge orange pull-right">3</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#">
                                            <div class="alert-icon yellow pull-left">
                                                <i class="fa fa-question-circle"></i>
                                            </div>
                                            Server #2 Not Responding
                                            <span class="small pull-right">
                                                <strong>
                                                    <em>5:25 PM</em>
                                                </strong>
                                            </span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#">
                                            <div class="alert-icon red pull-left">
                                                <i class="fa fa-bolt"></i>
                                            </div>
                                            Server #4 Crashed
                                            <span class="small pull-right">
                                                <strong>
                                                    <em>3:34 PM</em>
                                                </strong>
                                            </span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#">
                                            <div class="alert-icon green pull-left">
                                                <i class="fa fa-plus-circle"></i>
                                            </div>
                                            New Users
                                            <span class="badge green pull-right">5</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#">
                                            <div class="alert-icon orange pull-left">
                                                <i class="fa fa-download"></i>
                                            </div>
                                            Downloads
                                            <span class="badge orange pull-right">16</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#">
                                            <div class="alert-icon purple pull-left">
                                                <i class="fa fa-cloud-upload"></i>
                                            </div>
                                            Server #8 Rebooted
                                            <span class="small pull-right">
                                                <strong>
                                                    <em>12 hours ago</em>
                                                </strong>
                                            </span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#">
                                            <div class="alert-icon red pull-left">
                                                <i class="fa fa-bolt"></i>
                                            </div>
                                            Server #8 Crashed
                                            <span class="small pull-right">
                                                <strong>
                                                    <em>12 hours ago</em>
                                                </strong>
                                            </span>
                                        </a>
                                    </li>
                                </ul>
                            </li>

                             <!--Alerts Dropdown Footer-->
                            <li class="dropdown-footer">
                                <a href="#">View All Alerts</a>
                            </li>

                        </ul>
                         <!--/.dropdown-menu-->
                    </li>
                    <!-- /.dropdown -->
                    <!-- end ALERTS DROPDOWN -->
                     <?php } ?>
                    <?php if($user_info['role'] != 6){ ?>
                    <!-- begin TASKS DROPDOWN -->
                    <li class="dropdown">
                        <a href="#" class="tasks-link dropdown-toggle" data-toggle=dropdown>
                            <i class="fa fa-tasks"></i>
                            <?php // echo '<pre>'; print_r($task_info); exit;?>
                            <span class=number><?php echo count($task_info);?></span><i class="fa fa-caret-down"></i>
                        </a>
                        <ul class="dropdown-menu dropdown-scroll dropdown-tasks">

                             <!--Tasks Dropdown Header-->
                            <li class="dropdown-header">
                                <i class="fa fa-tasks"></i> <?php echo count($task_info);?> Pending Tasks
                            </li>

                             <!--Tasks Dropdown Body - This is contained within a SlimScroll fixed height box. You can change the height using the SlimScroll jQuery features.-->
                            <li id="taskScroll">
                                <ul class="list-unstyled">
                                    <?php foreach ($task_info as $v) { ?>
                                        <li>
                                            <div>
                                                <a href="<?php echo base_url();?>site/view_shop/?id=<?php echo $v['bussiness_id'];?>">
                                                    <p>
                                                        <?php echo $v['task'];?>
                                                    </p>
                                                    <p>

                                                    </p>
                                                    <div class="progress" style="height: 20px !important;border: none;background: none;-webkit-box-shadow: none;box-shadow: none">
                                                        <span class="pull-right">
                                                           <?php echo date("F j, Y",strtotime($v['end_date']));?>
                                                        </span>
                                                    </div>
                                                </a>
                                            </div>
                                    </li>
                                    <?php }?>
                                </ul>
                            </li>

                             <!--Tasks Dropdown Footer-->
                            <li class="dropdown-footer">
                                <a href="#">View All Tasks</a>
                            </li>

                        </ul>
                         <!--/.dropdown-menu-->
                    </li>
                    <!-- /.dropdown -->
                    <!-- end TASKS DROPDOWN -->
                    <?php } ?>
                    <!-- begin USER ACTIONS DROPDOWN -->
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                            <i class="fa fa-user"></i>  <i class="fa fa-caret-down"></i>
                        </a>
                        <ul class="dropdown-menu dropdown-user">
                            <li>
                                <a href="<?php echo base_url(); ?>site/change_profile">
                                    <i class="fa fa-user"></i> My Profile
                                </a>
                            </li>
                            <li>
                                <a class="logout_open" href="<?php echo base_url(); ?>site/logout">
                                    <i class="fa fa-sign-out"></i> Logout
                                    <strong><?php echo $user_info['first_name']." ".$user_info['last_name']; ?></strong>
                                </a>
                            </li>
                        </ul>
                        <!-- /.dropdown-menu -->
                    </li>
                    <!-- /.dropdown -->
                    <!-- end USER ACTIONS DROPDOWN -->

                </ul>
                <!-- /.nav -->
                <!-- end MESSAGES/ALERTS/TASKS/USER ACTIONS DROPDOWNS -->

            </div>
            <!-- /.nav-top -->
        </nav>

        <!-- /.navbar-top -->
        <!-- end TOP NAVIGATION -->