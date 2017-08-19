<script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
<link type="text/css" href="<?php echo base_url(); ?>assets/dtpicker/jquery.simple-dtpicker.css" rel="stylesheet" />
<script type="text/javascript" src="<?php echo base_url(); ?>assets/dtpicker/jquery.simple-dtpicker.js"></script>
<!-- begin MAIN PAGE CONTENT -->
        <div id="page-wrapper">

            <div class="page-content">

                <!-- begin PAGE TITLE ROW -->
                <div class="row">
                    <div class="col-lg-12">
                        <div class="page-title">
                            <ol class="breadcrumb">
                                <li><i class="fa fa-dashboard"></i>  <a href="/site/index">Dashboard</a>
                                </li>
                                <li class="active">Statistics</li>
                            </ol>
                            
                        </div>
                    </div><!-- /.col-lg-12 -->
                </div>
                <!-- /.row -->
                <!-- end PAGE TITLE ROW -->
                <div class="row">
                 <!-- Hoverable Responsive Table -->
                    <div class="col-lg-12">
                        <div class="portlet portlet-default">
                            <div class="portlet-heading">
                                <div class="portlet-title">
                                    <h4>Statistics</h4>
                                </div>
                                <div class="clearfix"></div>
                            </div>
                            <div class="portlet-body">
                                <div class="table-responsive">
                                    <form action="<?php echo base_url(); ?>site/statistics/" method="POST" >
                                        <table>
                                            <tr><td>&nbsp;</td></tr>
                                            <input type="hidden" name="id" value="<?php echo $id;?>">
                                            <tr>
                                                <td>&nbsp;&nbsp;Start Time :&nbsp;</td>
                                                <td>
                                                    <input type="text"  name="deal_start" id="deal_start" value="<?php if(isset($start_date)){ print_r($start_date);}?>" />
                                                </td>
                                                <td>&nbsp;&nbsp;End Time :&nbsp;</td>
                                                <td>
                                                    <input type="text"  name="deal_end" id="deal_end" value="<?php if(isset($end_date)){ print_r($end_date);}?>"/>
                                                </td>
                                                <td>&nbsp;&nbsp;<button type="submit" class="btn btn-primary">GO</button></td>
                                            </tr>
                                        </table>
                                        </form>
                                </div>
                            </div>
                        </div>
                        <!-- /.portlet -->
                    </div>
                    <!-- /.col-lg-6 -->
                </div>
                 <?php if(isset($data) && !empty($data)){?>
                <div class="row">
                 <!-- Hoverable Responsive Table -->
                    <div class="col-lg-12">
                        <div class="portlet portlet-default">
                            <div class="portlet-heading">
                                <div class="portlet-title">
                                    <h4>Statistics</h4>
                                </div>
                                <div class="clearfix"></div>
                            </div>
                            <div class="portlet-body">
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                 <tr>
                                                            <th>Deal Name</th>
                                                            <th>User Name</th>
                                                            <th>Shop Name</th>
                                                            <th>Category Name</th>
                                                            <th>Text</th>
                                                            <th>Date</th>
                                                        </tr>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                                            foreach($data as $v){

                                                        ?>
                                                                <tr>
                                                                    <td><?php echo $v['deal_title']; ?></td>
                                                                    <td><?php echo $v['name']; ?></td>
                                                                    <td><?php echo $v['shop_name']; ?></td>
                                                                    <td><?php echo $v['cname']; ?></td>
                                                                    <td><?php echo $v['push_text']; ?></td>
                                                                    <td><?php echo $v['date']; ?></td>
                                                                </tr>
                                                        <?php
                                                            }
                                                        ?>
                                        </tbody>
                                    </table>
                                    <ul class="pagination pagination-sm">
                                            
                                                    <li><a href="<?php echo base_url(); ?>site/statistics/?page_no=<?php echo $prev; ?>&id=<?php echo $id?>">«</a></li>
                                                    <?php
                                                        for($i=$prev;$i<=$next;$i++){  
                                                    ?>
                                                    <li <?php if($curr_page==$i){echo 'class="active"';} ?>><a href="<?php echo base_url(); ?>site/statistics/?page_no=<?php echo $i; ?>&id=<?php echo $id?>" <?php if($curr_page==$i){echo 'class="active"';} ?>><?php echo $i; ?></a></li>
                                                    <?php 
                                                        }
                                                    ?>
                                                    <li><a href="<?php echo base_url(); ?>site/statistics/?page_no=<?php echo $next; ?>&id=<?php echo $id?>">»</a></li>

                                                </ul>
                                    </div>
                        </div>
                    </div>
                    <!-- /.portlet -->
                </div>
                <!-- /.col-lg-6 -->
            </div>
                <?php } ?>
            </div>
            <!-- /.page-content -->
        </div>
        <!-- /#page-wrapper -->
        <!-- end MAIN PAGE CONTENT -->
<script type="text/javascript">
    $(function() {
        $('*[name=deal_start]').appendDtpicker({'dateFormat' : 'YYYY-MM-DD',"closeOnSelected": true,"futureOnly" : false,"todayButton": false,"dateOnly": true,"autodateOnStart": false});
        $('*[name=deal_end]').appendDtpicker({'dateFormat' : 'YYYY-MM-DD',"closeOnSelected": true,"futureOnly" : false,"todayButton": false,"dateOnly": true,"autodateOnStart": false});
    });
</script>
</div>
    <!-- /#wrapper -->

    <!-- GLOBAL SCRIPTS -->
    
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