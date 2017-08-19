 <!-- Right Section Header Start -->
            <header>
                <!-- User Section Start -->
                <div class="user">
                    <figure>
                        <?php
                                    if($this->session->userdata('is_admin')){
                                        $src=base_url()."assets/images/user.png";
                                    }else{
                                        $upath=$this->session->userdata('shop_image');
                                        if(empty($upath)){
                                            $src=base_url()."assets/images/user.png";
                                        }else{
                                            $src=base_url()."uploads/".$upath;
                                        }
                                        
                                    }
                        ?>
                        <a href="#"><img src="<?php echo $src; ?>" alt="Web admin" /></a>
                    </figure>
                    <div class="welcome">
                        <p>Welcome - <?php echo $this->session->userdata('shop_name'); ?></p>
                      
                    </div>
                </div>
                <!-- User Section End -->
                <!-- Search Section Start -->
<!--                <div class="search-box">
                    <input type="text" placeholder="Search Anything" />
                    <input type="submit" value="go" />
                </div>-->
                <!-- Search Section End -->
                <!-- Header Top Navigation Start -->
                <nav class="topnav">
                    <ul id="nav1">

                        <li class="settings">
<!--                        	<a href="#"><i class="glyphicon glyphicon-wrench"></i>Settings</a>-->
                                <a href="<?php echo base_url(); ?>site/logout"><i class="glyphicon glyphicon-log-out"></i>Sign out</a>

                        </li>
                    </ul>
                </nav>
                <!-- Header Top Navigation End -->
                <div class="clearfix"></div>
            </header>
            <!-- Right Section Header End -->