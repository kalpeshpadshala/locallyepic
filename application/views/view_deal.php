<style>
.col-centered{
    float: none;
    margin: 0 auto;
}
</style>
<!-- begin MAIN PAGE CONTENT -->
<div id="page-wrapper">
    <div class="page-content">

        <!-- begin PAGE TITLE ROW -->
        <div class="row">
            <div class="col-lg-12">
                <div class="page-title">
                    <h1>
                        View Offer
                    </h1>
                    <ol class="breadcrumb">
                        <li><i class="fa fa-dashboard"></i>  <a href="index.html">Dashboard</a>
                        </li>
                        <li class="active">View Offer</li>
                    </ol>
                </div>
            </div>
            <!-- /.col-lg-12 -->
        </div>
        <!-- /.row -->
        <!-- end PAGE TITLE ROW -->

        <?php

            $showEdit=true;

            $nowtime = (date("H")*3600)+(date("i")*60);
            $nowdate = date("Y-m-d");

            if ($nowdate == $deal['deal_start'] && $nowtime > $deal['deal_end_time']) {

                $showEdit=false;
            }

            if ($nowdate > $deal['deal_start']) {

                $showEdit=false;
            }
        ?>

        <div class="row">
            <div class="col-lg-8 col-lg-offset-2 col-md-12">
                <div class="portlet portlet-default">
                    <div class="portlet-body">
                        <div class="row">
                            <?php if((is_permission($this->session->userdata['role'], "create_deal")) == TRUE){ ?>
                            <div class="col-md-2">
                                <a class="btn btn-green" href="<?php echo base_url(); ?>site/create_deal<?php if($user_info['role'] != 6){ echo "?id=".$shop->shop_id; }?>">Create Offer</a>
                            </div>
                            <?php } ?>
                            <?php if ($showEdit && (is_permission($this->session->userdata['role'], "edit_deal")) == TRUE) {?>
                            <div class="col-md-1">
                                <a class="btn btn-green" href="<?php echo base_url(); ?>site/edit_deal?id=<?php echo $deal['id']; ?>">Edit</a>
                            </div>
                            <?php } ?>
                            <?php if((is_permission($this->session->userdata['role'], "create_deal")) == TRUE){ ?>
                            <div class="col-md-2">
                                <a class="btn btn-green" href="<?php echo base_url(); ?>site/create_deal?deal_id=<?php echo $deal['id']; ?>">Duplicate</a>
                            </div>
                            <?php } ?>

                             <?php if ($showEdit && (is_permission($this->session->userdata['role'], "edit_deal")) == TRUE) {?>
                            <?php if($deal['is_off'] == 0){ ?>
                            <div class="col-md-2">
                                <a class="btn btn-blue" href="<?php echo base_url(); ?>site/deal_off?deal_id=<?php echo $deal['id']; ?>">Offer Off</a>
                            </div>
                            <?php }else{?>
                            <div class="col-md-2">
                                <a class="btn btn-blue" href="<?php echo base_url(); ?>site/deal_on?deal_id=<?php echo $deal['id']; ?>">Offer On</a>
                            </div>

                            <?php }?>
                            <?php } ?>

                            <?php if((is_permission($this->session->userdata['role'], "create_deal")) == TRUE){ ?>
                            <div class="col-md-2">
                                <a class="btn btn-red" href="<?php echo base_url(); ?>site/deal_delete?deal_id=<?php echo $deal['id']; ?>">Delete</a>
                            </div>
                            <?php } ?>

                             <div class="col-md-3 pull-right" style="text-align:right">
                                Push Count : &nbsp;<span class="label blue"><?php echo $push_count;?></span><br>
                                Offers Activated : &nbsp;<span class="label blue"><?php echo $activation_count;?></span>
                            </div>
                        </div>

                        <hr>
                                <p>
                                  <div style="position:relative;padding-bottom:30%;">
                                    <?php

                                    //echo is_image_path_proper($deal['deal_image']);exit;

                                      $src=$deal['deal_image'];
                                      echo '<img src="/uploads/user/'.$shop->shop_image.'" style="position:absolute;top:0;left:0;right:0;bottom:0;width:100%;height:100%;object-fit:contain;background-color:#151515;" />';
                                    ?>
                                  </div>
                                </p>
                        <div class="row" align="center">
                            <div class="col-md-12">
                                <h1><?php echo $deal['deal_title'];?></h1>
                                <br>


                            <?php

                                $start_date = utc_to_local($deal['deal_start'],$deal['deal_time'],$deal["timezone"]);
                                $end_date = utc_to_local($deal['deal_end'],$deal['deal_end_time'],$deal["timezone"]);



                            ?>
                                <div class="col-md-6 col-centered">
                                <table class="table table-bordered table-hover">
                                    <tr><td>Offer Date</td><td><?php echo $start_date["user_date"] ;?></td></tr>
                                    <?php if ($start_date["user_date"] != $end_date["user_date"]) {?>  <tr><td>Offer End Date</td><td><?php echo $end_date["user_date"] ;?></td></tr><?php } ?>
                                    <tr><td>Offer Start Time</td><td><?php echo $start_date["user_time"] ?></td></tr>
                                    <tr><td>Offer End Time</td><td><?php echo $end_date["user_time"] ?></td></tr>
                                    <tr><td>Original Price($)</td><td><?php echo $deal['original_price'];?></td></tr>
                                    <tr><td>Offer Price($)</td><td><?php echo $deal['offer_price'];?></td></tr>
                                    <tr><td>Offer Description</td><td><?php echo $deal['deal_description'];?></td></tr>
                                    <tr><td>Contact Name</td><td><?php echo $deal['contact_name'];?></td></tr>
                                    <tr><td>Contact Number</td><td><?php echo $deal['contact_number'];?></td></tr>


                                </table>
                            </div>

                                <?php if((is_permission($this->session->userdata['role'], "manage_shop")) == TRUE){ ?>
                                <p><a href="/site/view_shop/?id=<?php echo $shop->shop_id ?>"><?php echo $shop->shop_name ?></a> <a href="/site/edit_shop/?id=<?php echo $shop->shop_id ?>"><img src="/assets/images/edit.png"></a></p>
                                <?php } ?>

                        </div>


                    </div>      <?php if($user_info['role'] == "1"){?>
                                <?php if(isset($data) && !empty($data)){ ?>
                                    <hr>
                                    <div class="row" align="center">
                                                         <!-- Hoverable Responsive Table -->
                    <div class="col-lg-12">
                        <div class="portlet portlet-default">
                            <div class="portlet-heading">
                                <div class="portlet-title">
                                    <h4>Notification list</h4>
                                </div>
                                <div class="clearfix"></div>
                            </div>
                            <div class="portlet-body">
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                 <tr>
                                                            <th>Offer Name</th>
                                                            <th>Customer Name</th>
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
                                    </div>
                        </div>
                    </div>
                    <!-- /.portlet -->
                </div>
                <!-- /.col-lg-12 -->
                                    </div>
                            <?php }?>
                            <?php }?>
                                        <!-- /.row -->
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