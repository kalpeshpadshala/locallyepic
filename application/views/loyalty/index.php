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
                                <li class="active">Customer Loyalty</li>
                            </ol>
                            
                        </div>
                    </div><!-- /.col-lg-12 -->
                </div>
                <!-- /.row -->
                <!-- end PAGE TITLE ROW -->
                <div class="row">
                 <!-- Hoverable Responsive Table -->
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
                    
               
                    <!-- /.col-lg-6 -->
                </div>


<div class="row">

                    <div class="center-block">
                        <div class="portlet portlet-default">
                            <div class="portlet-heading">
                                <div class="portlet-title">
                                    <h4>Your Customer Loyalty Program Information</h4>
                                </div>
                                <div class="portlet-widgets"><a href="/loyalty/updateProgram"><img src="/assets/images/edit.png"></a>
                                    <a href="/loyalty/updateStatus?status=<?php if ($loyaltyprogram->blnStatus==1){ echo '0';} else {echo "1";}?>"><span class="label <?php if ($loyaltyprogram->blnStatus==1){ echo 'green';} else {echo "red";}?>"><?php if ($loyaltyprogram->blnStatus==1){ echo 'Click to Disable';} else {echo "Click To Enable";}?></span></a>
                                    <span class="divider"></span>
                                    <a data-toggle="collapse" data-parent="#accordion" href="#defaultPortlet"><i class="fa fa-chevron-down"></i></a>
                                </div>
                                <div class="clearfix"></div>
                            </div>
                            <div id="defaultPortlet" class="panel-collapse collapse in">
                                <div class="portlet-body">
                                    <p><?php echo "<b>Title:</b> ".$loyaltyprogram->title; ?></p>
                                    <p><?php echo "<b>Description: </b>".$loyaltyprogram->description; ?></p>
                                </div>
                            </div>
                            <div class="portlet-footer">
                                
                            </div>
                        </div>
                    </div>

                <div class="row">
                 <!-- Hoverable Responsive Table -->
                    <div class="col-lg-12">
                        <div class="portlet portlet-default">
                            <div class="portlet-heading"><div class="pull-right" style="padding-top:5px;"><a  href="/loyalty/updateProgram"><img src="/assets/images/edit.png"></a></div>
                                <div class="portlet-title">
                                    <h4>Loyalty Program Levels</h4> 
                                </div>
                                <div class="clearfix"></div>
                            </div>
                            <div class="portlet-body">
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead>
                                        
                                                 <tr>
                                                    <th>Title</th>
                                                    <th>Description</th>
                                                    <th>Activations</th>
                                                   
                                                </tr>
                                            
                                        </thead>
                                        <tbody>
                                           <?php
                                           error_reporting(0);
                                                     foreach ($loyaltyprogramitems as $v){?>
                                            
                                                        <tr>
                                                            <td><?php echo $v['title']; ?></td>
                                                            <td><?php echo $v['description']; ?></td>
                                                            <td><?php echo $v['intActivations']; ?></td>
                                                            
                                                        </tr>
                                                <?php
                                                    }
                                                ?>
                                        </tbody>
                                    </table>
                          
                                    </div>
                        </div>
                    </div>
                    <!-- /.portlet -->
                </div>

               
                 <!-- Hoverable Responsive Table -->
                    <div class="col-lg-12">
                        <div class="portlet portlet-default">
                            <div class="portlet-heading">
                                <div class="portlet-title">
                                    <h4>Your Most Loyal Customers</h4> 
                                </div>
                                <div class="clearfix"></div>
                            </div>
                            <div class="portlet-body">
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead>
                                        
                                                 <tr>
                                                    <th>Name</th>
                                                    <th>Activations</th>
                                                    
                                                   
                                                </tr>
                                            
                                        </thead>
                                        <tbody>
                                           <?php
                                           error_reporting(0);
                                                     foreach ($loyalcustomers as $v){?>
                                            
                                                        <tr>
                                                            <td><?php echo $v['name']; ?></td>
                                                            <td><?php echo $v['deals_activated']; ?></td>
                                                            
                                                            
                                                        </tr>
                                                <?php
                                                    }
                                                ?>
                                        </tbody>
                                    </table>
                          
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
    <!-- Logout Notification Box -->
   <?php require('application/views/logout.php'); ?>
    <!-- Logout Notification jQuery -->
    <script src="<?php echo base_url(); ?>assets/js/plugins/popupoverlay/logout.js"></script>
    <!-- HISRC Retina Images -->
    <script src="<?php echo base_url(); ?>assets/js/plugins/hisrc/hisrc.js"></script>

    <!-- PAGE LEVEL PLUGIN SCRIPTS -->

    <!-- THEME SCRIPTS -->
    <script src="<?php echo base_url(); ?>assets/js/flex.js"></script>

</body>

</html>