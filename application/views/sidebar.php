        <?php
        /*
            1=>super admin,
            2 for=>nsm,
            3 =>ssm,
            4=>asm,
            5=>sales people login,
            6=>business login,
            7=>hr login
            8=>Ambassador Login
            9=>Corporate Login
        */?>
        <!-- begin SIDE NAVIGATION -->
        <nav class="navbar-side" role="navigation">
            <div class="navbar-collapse sidebar-collapse collapse">
                <ul id="side" class="nav navbar-nav side-nav">
                    <!-- begin SIDE NAV USER PANEL -->
                    <li class="side-user hidden-xs">
                        <?php
                            if(!empty($user_info['profile_pic'])){
                                $user_info['profile_pic']=  base_url()."uploads/user/".$user_info['profile_pic'];
                            }else{
                                $user_info['profile_pic']=  base_url()."assets/img/profile-pic.jpg";
                            }
                        ?>
                        
                            <img class="img-circle" style="margin-left:0px;" src="<?php echo $user_info['profile_pic']; ?>" alt="<?php echo $user_info['first_name']; ?>" width="180" height="auto">
                        
                        <p class="welcome">
                            <i class="fa fa-key"></i> Logged in as
                        </p>
                        <p class="name tooltip-sidebar-logout">
                            
                            <span class="last-name"><?php echo $user_info['first_name']." ".$user_info['last_name']; ?></span> <a style="color: inherit" class="logout_open" href="#" data-toggle="tooltip" data-placement="top" title="Logout"><i class="fa fa-sign-out"></i></a>
                        </p>
                        <div class="clearfix"></div>
                    </li>

                    <!-- end SIDE NAV USER PANEL -->
                    <?php if($user_info['role'] != 6){ ?>
                    <!-- begin SIDE NAV SEARCH -->
                    <li class="nav-search">
                        <form role="form">
                            <input type="search" class="form-control" placeholder="Search...">
                            <button class="btn">
                                <i class="fa fa-search"></i>
                            </button>
                        </form>
                    </li>
                    <!-- end SIDE NAV SEARCH -->
                    <?php } ?>
                    <!-- begin DASHBOARD LINK -->
					
                    <li>
                        <a <?php if($current_page=="index" || $current_page==""){echo 'class="active"';} ?> href="<?php echo base_url();?>site/index">
                            <i class="fa fa-dashboard"></i> Dashboard
                        </a>
                    </li>
					
                    <!-- end DASHBOARD LINK -->
                    <?php if($user_info['role'] == "1"){?>
                    <li class="panel">
                        <a href="javascript:;" data-parent="#side" data-toggle="collapse" class="accordion-toggle <?php if($current_page=="list_category" || $current_page=="manage_category"){echo "active";} ?>" data-target="#category">
                            <i class="fa fa-tags"></i> Category/Geofencing <i class="fa fa-caret-down"></i>
                        </a>
                        <ul class="collapse nav" id="category">
                            <li>
                                <a href="<?php echo base_url();?>site/list_category">
                                    <i class="fa fa-angle-double-right"></i> List Category
                                </a>
                            </li>
                            <li>
                                <a href="<?php echo base_url();?>site/manage_category">
                                    <i class="fa fa-angle-double-right"></i> Add Category
                                </a>
                            </li>
                        </ul>
                    </li>
                    <?php } ?>
                    <?php if($user_info['role'] == "1"){?>
                    <li class="panel">
                        <a href="javascript:;" data-parent="#side" data-toggle="collapse" class="accordion-toggle <?php if($current_page=="list_sales_manager" || $current_page=="add_sales_manager"){echo "active";} ?>" data-target="#sm">
                            <i class="fa fa-user-md"></i> Sales Manager <i class="fa fa-caret-down"></i>
                        </a>
                        <ul class="collapse nav" id="sm">
                            <li>
                                <a href="<?php echo base_url();?>site/list_sales_manager">
                                    <i class="fa fa-angle-double-right"></i> List Sales Manager
                                </a>
                            </li>
                            <li>
                                <a href="<?php echo base_url();?>site/add_sales_manager">
                                    <i class="fa fa-angle-double-right"></i> Add Sales Manager
                                </a>
                            </li>
                        </ul>
                    </li>
                    <?php } ?>
                    <?php if($user_info['role'] == "1" || $user_info['role'] == "2" || $user_info['role'] == "3" || $user_info['role'] == "4" ){?>
                    <li class="panel">
                        <a href="javascript:;" data-parent="#side" data-toggle="collapse" class="accordion-toggle <?php if($current_page=="list_sales_people" || $current_page=="add_sales_people"){echo "active";} ?>" data-target="#sp">
                            <i class="fa fa-users"></i> Sales People <i class="fa fa-caret-down"></i>
                        </a>
                        <ul class="collapse nav" id="sp">
                            <li>
                                <a href="<?php echo base_url();?>site/list_sales_people">
                                    <i class="fa fa-angle-double-right"></i> List Sales People
                                </a>
                            </li>
                            <li>
                                <a href="<?php echo base_url();?>site/add_sales_people">
                                    <i class="fa fa-angle-double-right"></i> Add Sales People
                                </a>
                            </li>
                        </ul>
                    </li>
                    <?php } ?>
                    <?php if($user_info['role'] == "1" || $user_info['role'] == "2" || $user_info['role'] == "3" || $user_info['role'] == "4" || $user_info['role'] == "5" ){?>
                    <li class="panel">
                        <a href="javascript:;" data-parent="#side" data-toggle="collapse" class="accordion-toggle <?php if($current_page=="manage_shop" || $current_page=="list_shop"){echo "active";} ?>" data-target="#bu">
                            <i class="fa fa-user-md"></i> Business User <i class="fa fa-caret-down"></i>
                        </a>
                        <ul class="collapse nav" id="bu">
                            <li>
                                <a href="<?php echo base_url();?>site/list_shop">
                                    <i class="fa fa-angle-double-right"></i> List Business User
                                </a>
                            </li>
                            <?php if($user_info['role'] == "1" || $user_info['role'] == "9"){?>
                            <li>
                                <a href="<?php echo base_url();?>site/manage_shop">
                                    <i class="fa fa-angle-double-right"></i> Add Business User
                                </a>
                            </li>
                            <?php } ?>
                        </ul>
                    </li>
                    <?php } ?>
                    <?php if($user_info['role'] == "1" || $user_info['role'] == "2" || $user_info['role'] == "3" || $user_info['role'] == "4" || $user_info['role'] == "5" || $user_info['role'] == "6"  || $user_info['role'] == "9"){?>
                    <li class="panel">
                        <a href="javascript:;" data-parent="#side" data-toggle="collapse" class="accordion-toggle <?php if($current_page=="manage_deal" || $current_page=="create_deal"){echo "active";} ?>" data-target="#deal_menu">
                            <i class="fa fa-dollar"></i> Offers <i class="fa fa-caret-down"></i>
                        </a>
                        <ul class="collapse nav" id="deal_menu">
                            <?php if($user_info['role'] == "1" || $user_info['role'] == "2" || $user_info['role'] == "3" || $user_info['role'] == "4" || $user_info['role'] == "5"  || $user_info['role'] == "8" ){?>
                            <li>
                                <a href="<?php echo base_url();?>site/manage_deal">
                                    <i class="fa fa-angle-double-right"></i> List Offers
                                </a>
                            </li>
                            <?php } ?>
                            <?php if($user_info['role'] == "1" ){?>
                            <li>
                                <a href="<?php echo base_url();?>site/create_deal">
                                    <i class="fa fa-angle-double-right"></i> Create Offer
                                </a>
                            </li>
                            <?php } ?>

                             <?php if($user_info['role'] == 1){ ?>

                            <li>
                                <a href="<?php echo base_url();?>deals/create_power_deal">
                                    <i class="fa fa-angle-double-right"></i> Create Power Offer
                                </a>
                            </li>

                            <li>
                                <a href="<?php echo base_url();?>deals/create_test_deals">
                                    <i class="fa fa-angle-double-right"></i> Create Test Offers
                                </a>
                            </li>

                            <?php } ?>

                            <?php if($user_info['role'] == 6 || $user_info['role'] == "9"){ ?>
                            <li>
                                <a href="<?php echo base_url();?>site/view_shop/">
                                    <i class="fa fa-angle-double-right"></i> My Offers
                                </a>
                            </li>
                            <?php } ?>

                        </ul>
                    </li>
                    <?php } ?>

                    <?php //if($user_info['is_corporate_business_user'] != "1"){ ?>
                    <li class="panel">
                        <a href="javascript:;" data-parent="#side" data-toggle="collapse" class="accordion-toggle <?php if($current_page=="change_profile"){echo "active";} ?>" data-target="#my_profile">
                            <i class="fa fa-user-md"></i> My Profile <i class="fa fa-caret-down"></i>
                        </a>
                        <ul class="collapse nav" id="my_profile">
                            <li>
                                <a href="<?php echo base_url();?>site/change_profile">
                                    <i class="fa fa-angle-double-right"></i> Change Profile
                                </a>
                            </li>
                            
                        </ul>
                    </li>
                    <?php // }?>

                    <?php if(!($user_info['role'] == "8") && !($user_info['role'] == "9")){ ?>
                    <li>
                        <a <?php if($current_page=="statistic" || $current_page=="statistics"){echo 'class="active"';} ?> href="<?php echo base_url(); ?>site/statistic">
                            <i class="fa fa-bar-chart-o"></i> Statistics
                        </a>
                    </li>
                    <?php }?>

                    <?php if(($user_info['role'] == "6")){ ?>
                    <li>
                        <a <?php if($current_page=="loyalty" || $current_page=="loyalty"){echo 'class="active"';} ?> href="<?php echo base_url(); ?>loyalty/index">
                            <i class="fa fa-bar-chart-o"></i> Loyalty Program
                        </a>
                    </li>
                    <?php }?>

                   


                    <?php if($user_info['role'] == "1"){?>
                    <li class="panel">
                        <a href="javascript:;" data-parent="#side" data-toggle="collapse" class="accordion-toggle <?php if($current_page=="list_consumers"){echo "active";} ?>" data-target="#consumers_menu">
                            <i class="fa fa-users"></i> Consumers <i class="fa fa-caret-down"></i>
                        </a>
                        <ul class="collapse nav" id="consumers_menu">
                            
                            <li>
                                <a href="<?php echo base_url();?>site/list_consumers">
                                    <i class="fa fa-angle-double-right"></i> List Consumers
                                </a>
                            </li>
                           
                           
                        </ul>
                    </li>
                    <?php 
                    }
                    ?>

                   <?php if(($user_info['role'] == "111118")){  
                   ?>
                    <li class="panel">
                        <a href="javascript:;" data-parent="#side" data-toggle="collapse" class="accordion-toggle <?php if($current_page=="list_message" || $current_page =="add_message" ){echo "active";} ?>" data-target="#message-center">
                            <i class="fa fa-inbox"></i> Message Center <i class="fa fa-caret-down"></i>
                        </a>
                        <ul class="nav collapse" id="message-center">
                            <li>
                                <a href="<?php echo base_url();?>site/list_message">
                                    <i class="fa fa-angle-double-right"></i> List Messages
                                </a>
                            </li>
                            <li>
                                <a href="<?php echo base_url();?>site/add_message">
                                    <i class="fa fa-angle-double-right"></i> Add Message
                                </a>
                            </li>
                         
                        </ul>
                    </li>
                   <?php }
                   ?>
                    <?php if($user_info['role'] == "1"){?>
                    <li class="panel">
                        <a href="javascript:;" data-parent="#side" data-toggle="collapse" class="accordion-toggle <?php if($current_page=="list_promocode" || $current_page=="add_promocode"){echo "active";} ?>" data-target="#promocode_menu">
                            <i class="fa fa-cutlery"></i> Promocodes <i class="fa fa-caret-down"></i>
                        </a>
                        <ul class="collapse nav" id="promocode_menu">
                            
                            <li>
                                <a href="<?php echo base_url();?>site/list_promocode">
                                    <i class="fa fa-angle-double-right"></i> List Promocode
                                </a>
                            </li>
                            <li>
                                <a href="<?php echo base_url();?>site/add_promocode">
                                    <i class="fa fa-angle-double-right"></i> Add Promocode
                                </a>
                            </li>
                           
                           
                        </ul>
                    </li>
                    <?php } ?>
                    
                    <?php if($user_info['role'] == "1"){?>
                    <li class="panel">
                        <a href="javascript:;" data-parent="#side" data-toggle="collapse" class="accordion-toggle <?php if($current_page=="list_ambassador" || $current_page=="add_ambassador"){echo "active";} ?>" data-target="#ambassador_menu">
                            <i class="fa fa-cutlery"></i> Ambassador <i class="fa fa-caret-down"></i>
                        </a>
                        <ul class="collapse nav" id="ambassador_menu">
                            
                            <li>
                                <a href="<?php echo base_url();?>site/list_ambassador">
                                    <i class="fa fa-angle-double-right"></i> List Ambassador
                                </a>
                            </li>
                            <li>
                                <a href="<?php echo base_url();?>site/add_ambassador">
                                    <i class="fa fa-angle-double-right"></i> Add Ambassador
                                </a>
                            </li>
                           
                           
                        </ul>
                    </li>
                    <?php } ?>


                    <?php if($user_info['role'] == "1"){?>
                    <li class="panel">
                        <a href="javascript:;" data-parent="#side" data-toggle="collapse" class="accordion-toggle <?php if($current_page=="list_corporate" || $current_page=="add_corporate"){echo "active";} ?>" data-target="#corporate_menu">
                            <i class="fa fa-github-alt"></i> Corporate User <i class="fa fa-caret-down"></i>
                        </a>
                        <ul class="collapse nav" id="corporate_menu">
                            
                            <li>
                                <a href="<?php echo base_url();?>site/list_corporate">
                                    <i class="fa fa-angle-double-right"></i> List Corporate
                                </a>
                            </li>
                            <li>
                                <a href="<?php echo base_url();?>site/add_corporate">
                                    <i class="fa fa-angle-double-right"></i> Add Corporate
                                </a>
                            </li>
                           
                           
                        </ul>
                    </li>
                    <?php } ?>



                    <?php if($user_info['role'] == "9"){?>
                    <li class="panel">
                        <a href="javascript:;" data-parent="#side" data-toggle="collapse" class="accordion-toggle <?php if($current_page=="list_business" || $current_page=="add_business" || $current_page=="edit_business"){echo "active";} ?>" data-target="#business_menu">
                            <i class="fa fa-github-alt"></i> Business <i class="fa fa-caret-down"></i>
                        </a>
                        <ul class="collapse nav" id="business_menu">
                            
                            <?php 
                            if($user_info['role'] == 9){

                                if( $total_user_business > 1)
                                { ?>
                                    <li>
                                        <a href="<?php echo base_url();?>site/list_business">
                                            <i class="fa fa-angle-double-right"></i> List Business
                                        </a>
                                    </li>
                                <?php 
                                }else{ 
                                ?>
                                   <li>
                                        <a href="<?php echo base_url(); ?>site/edit_business/?id=<?php echo $user_business_id; ?>">
                                            <i class="fa fa-angle-double-right"></i> Edit Business
                                        </a>
                                    </li> 
                                <?php 
                                }
                            }
                            ?>

                            <li>
                                <a href="<?php echo base_url();?>site/add_business">
                                    <i class="fa fa-angle-double-right"></i> Add Business
                                </a>
                            </li>
                        </ul>
                    </li>

                    <li class="panel">
                        <a href="javascript:;" data-parent="#side" data-toggle="collapse" class="accordion-toggle <?php if($current_page=="list_user" || $current_page=="add_user"){echo "active";} ?>" data-target="#users_menu">
                            <i class="fa fa-users"></i> Users <i class="fa fa-caret-down"></i>
                        </a>
                        <ul class="collapse nav" id="users_menu">
                            
                            <li>
                                <a href="<?php echo base_url();?>site/list_user">
                                    <i class="fa fa-angle-double-right"></i> List User
                                </a>
                            </li>
                            <li>
                                <a href="<?php echo base_url();?>site/add_user">
                                    <i class="fa fa-angle-double-right"></i> Add User
                                </a>
                            </li>
                        </ul>
                    </li>
                    <?php } ?>



                     <?php if( ($user_info['role'] == "6") || ($user_info['role'] == "1") || ($user_info['role'] == "9") || ($user_info['role'] == "5")){ ?>
                    <li>
                        <a <?php if($current_page=="message_consumer" || $current_page=="message_consumer"){echo 'class="active"';} ?> href="<?php echo base_url(); ?>site/message_consumer">
                            <i class="fa fa-bar-chart-o"></i> Message Consumer
                        </a>
                    </li>
                    <?php }?>

                   
                </ul>
                <!-- /.side-nav -->
            </div>
            <!-- /.navbar-collapse -->
        </nav>
        <!-- /.navbar-side -->
        <!-- end SIDE NAVIGATION -->
