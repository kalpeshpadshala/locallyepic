<!-- begin MAIN PAGE CONTENT -->
        <div id="page-wrapper">

            <div class="page-content">

                <!-- begin PAGE TITLE ROW -->
                <div class="row">
                    <div class="col-lg-12">
                        <div class="page-title">
                            <ol class="breadcrumb">
                                <li><i class="fa fa-dashboard"></i>  <a href="index.html">Dashboard</a>
                                </li>
                                <li class="active">Shop List</li>
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
                                    <h4>Shop List</h4>
                                </div>
                                <div class="clearfix"></div>
                            </div>
                            <div class="portlet-body">
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                 <tr>
                                                    <th>Business Name</th>
                                                    <th>Shop Image</th>
                                                    <th>Address</th>
                                                    <th>Email</th>
                                                    <th>Date</th>
                                                    <th>Notification</th>
                                                </tr>
                                            </tr>
                                        </thead>
                                        <tbody>
                                           <?php
                                                if(!empty($info)){
                                                    foreach($info as $v){
                                                        
                                                        $src=(!empty($v['shop_image'])) ? base_url()."uploads/".$v['shop_image'] : base_url()."assets/images/shop_def.png";

                                                ?>
                                                        <tr>
                                                            <td><?php echo $v['shop_name']; ?></td>
                                                            <td>
                                                                <?php
                                                                    if(!empty($v['shop_image'])){
                                                                            echo '<img src="'.$src.'" style="width:128px;height:128px;" />';
                                                                    }else{
                                                                        echo "--";
                                                                    }
                                                                ?>
                                                               
                                                            </td>
                                                            <td><?php echo $v['address']; ?></td>
                                                            <td><?php echo $v['email']; ?></td>
                                                            <td><?php echo $v['date']; ?></td>
                                                            <td><a href="<?php echo base_url();?>site/statistics?id=<?php echo $v['shop_id']; ?>"><img src="<?php echo base_url(); ?>images/notification.png" width="10%" /></a></td> 
                                                        </tr>
                                                <?php
                                                }}
                                                else{?>
                                                    <tr>
                                                        <td colspan="5">No SHOP FOUND!!!</td>
                                                    </tr>
                                                <?php 
                                                
                                                }
                                                ?>
                                                
                                                
                                        </tbody>
                                    </table>
                                    <ul class="pagination pagination-sm">
                                            <?php if(isset($data)){?>
                                            <li><a href="<?php echo base_url(); ?>site/select_shop/?scat=<?php echo $data['shop_cats'];?>&scountry=<?php echo $data['country_id'];?>&sstate=<?php echo $data['state_id'];?>&scity=<?php echo $data['city_id'];?>&add_shop=&page_no=<?php echo $prev; ?>">«</a></li>
                                            <?php
                                                for($i=$prev;$i<=$next;$i++){  
                                            ?>
                                            <li <?php if($curr_page==$i){echo 'class="active"';} ?>><a href="<?php echo base_url(); ?>site/select_shop/?scat=<?php echo $data['shop_cats'];?>&scountry=<?php echo $data['country_id'];?>&sstate=<?php echo $data['state_id'];?>&scity=<?php echo $data['city_id'];?>&add_shop=&page_no=<?php echo $i; ?>" <?php if($curr_page==$i){echo 'class="active"';} ?>><?php echo $i; ?></a></li>
                                            <?php 
                                                }
                                            ?>
                                            <li><a href="<?php echo base_url(); ?>site/select_shop/?scat=<?php echo $data['shop_cats'];?>&scountry=<?php echo $data['country_id'];?>&sstate=<?php echo $data['state_id'];?>&scity=<?php echo $data['city_id'];?>&add_shop=&page_no=<?php echo $next; ?>">»</a></li>
                                            <?php }?>
                                        </ul>
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
<!--<div class="right-sec">
             Right Section Header Start 
            <?php // $this->load->view('top'); ?>
             Right Section Header End 
             Content Section Start 
            <div class="content-section">
                <div class="container-liquid">
                    <div class="row">
                        
                    
                      
                        <div class="col-xs-12">
                            <div class="sec-box">
                                <a class="closethis">Close</a>
                                <header>
                                    <h2 class="heading">Shop List</h2>
                                </header>
                                <div class="contents">
                                    <a class="togglethis">Toggle</a>
                                    <section>
                                        <table class="table table-condensed">
                                            <thead>
                                                <tr>
                                                    <th>Business Name</th>
                                                    <th>Shop Image</th>
                                                    <th>Address</th>
                                                    <th>Email</th>
                                                    <th>Date</th>
                                                    <th>Notification</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                               
                                                <?php
//                                                if(!empty($info)){
//                                                    foreach($info as $v){
//                                                        
//                                                        $src=(!empty($v['shop_image'])) ? base_url()."uploads/".$v['shop_image'] : base_url()."assets/images/shop_def.png";

                                                ?>
                                                        <tr>
                                                            <td><?php // echo $v['shop_name']; ?></td>
                                                            <td>
                                                                <?php
//                                                                    if(!empty($v['shop_image'])){
//                                                                            echo '<img src="'.$src.'" style="width:128px;height:128px;" />';
//                                                                    }else{
//                                                                        echo "--";
//                                                                    }
                                                                ?>
                                                               
                                                            </td>
                                                            <td><?php // echo $v['address']; ?></td>
                                                            <td><?php // echo $v['email']; ?></td>
                                                            <td><?php // echo $v['date']; ?></td>
                                                            <td><a href="<?php // echo base_url();?>site/statistics?id=<?php // echo $v['shop_id']; ?>"><img src="<?php // echo base_url(); ?>images/notification.png" width="10%" /></a></td> 
                                                        </tr>
                                                <?php
//                                                }}
//                                                else{?>
                                                    <tr>
                                                        <td colspan="5">No SHOP FOUND!!!</td>
                                                    </tr>
                                                <?php 
                                                
//                                                }
                                                ?>
                                                
                                                
                                                
                                            </tbody>
                                        </table>
                                        
                                        <ul class="pagination pagination-sm">
                                            <?php // if(isset($data)){?>
                                            <li><a href="<?php // echo base_url(); ?>site/select_shop/?scat=<?php // echo $data['shop_cats'];?>&scountry=<?php // echo $data['country_id'];?>&sstate=<?php // echo $data['state_id'];?>&scity=<?php // echo $data['city_id'];?>&add_shop=&page_no=<?php // echo $prev; ?>">«</a></li>
                                            <?php
//                                                for($i=$prev;$i<=$next;$i++){  
                                            ?>
                                            <li <?php // if($curr_page==$i){echo 'class="active"';} ?>><a href="<?php // echo base_url(); ?>site/select_shop/?scat=<?php // echo $data['shop_cats'];?>&scountry=<?php // echo $data['country_id'];?>&sstate=<?php // echo $data['state_id'];?>&scity=<?php // echo $data['city_id'];?>&add_shop=&page_no=<?php // echo $i; ?>" <?php // if($curr_page==$i){echo 'class="active"';} ?>><?php // echo $i; ?></a></li>
                                            <?php 
//                                                }
                                            ?>
                                            <li><a href="<?php // echo base_url(); ?>site/select_shop/?scat=<?php // echo $data['shop_cats'];?>&scountry=<?php // echo $data['country_id'];?>&sstate=<?php // echo $data['state_id'];?>&scity=<?php // echo $data['city_id'];?>&add_shop=&page_no=<?php // echo $next; ?>">»</a></li>
                                            <?php // }?>
                                        </ul>
                                        
                                        
                                    </section>
                                </div>
                            </div>
                        </div>
                       
                    </div>
                     Row End 
                </div>
            </div>
             Content Section End 
        </div>-->