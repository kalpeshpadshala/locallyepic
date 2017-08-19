 <!-- Logout Notification Box -->
    <div id="logout">
        <div class="logout-message">
            <?php
                if(!empty($user_info['profile_pic'])){
                    $user_info['profile_pic']=  base_url()."uploads/user/".$user_info['profile_pic'];
                }else{
                    $user_info['profile_pic']=  base_url()."assets/img/profile-pic.jpg";
                }
            ?>
            <img class="img-circle img-logout" src="<?php echo $user_info['profile_pic']; ?>" alt="<?php echo $user_info['first_name']; ?>" width="180" height="auto">
            <!--<img class="img-circle img-logout" src="<?php // echo base_url(); ?>assets/img/profile-pic.jpg" alt="">-->
            <h3>
                <i class="fa fa-sign-out text-green"></i> Ready to go?
            </h3>
            <p>Select "Logout" below if you are ready<br> to end your current session.</p>
            <ul class="list-inline">
                <li>
                    <a href="<?php echo base_url(); ?>site/logout" class="btn btn-green">
                        <strong>Logout</strong>
                    </a>
                </li>
                <li>
                    <button class="logout_close btn btn-green">Cancel</button>
                </li>
            </ul>
        </div>
    </div>
    <!-- /#logout -->
    <!-- Logout Notification jQuery -->