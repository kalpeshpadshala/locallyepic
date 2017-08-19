<script type="text/javascript">
function delete_row(id){
            //alert($(this).attr("id"));
            //alert("hi");
            var r = confirm("Are You sure you want to delete the user");
            if (r == true) {
                ajax("delete_cat", id);
            } 
}

        function ajax(action, id) {


            $.ajax({
                type: "POST",
                url: "<?php echo base_url(); ?>site/"+action,
                data: {id: id},
                success: function(response){
                        if (response == 1) {
                            location.reload();

                        }
     
                }

            });
        }
     
</script>

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
                                <li class="active">List Category</li>
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
                 <!-- Hoverable Responsive Table -->
                    <div class="col-lg-12">
                        <div class="portlet portlet-default">
                            <div class="portlet-heading">
                                <div class="portlet-title">
                                    <h4>List Category</h4>
                                </div>
                                <div class="clearfix"></div>
                            </div>
                            <div class="portlet-body">
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead>
                                        
                                                 <tr>
                                                    <th>Category ID</th>
                                                    <th>Category Name</th>
                                                    <th>Category Image</th>
                                                    <th>Distance</th>
                                                    <th>Action</th>
                                                </tr>
                                            
                                        </thead>
                                        <tbody>
                                           <?php
                                           error_reporting(0);
                                                    foreach($info as $v){
                                                        
                                                       $src=(!empty($v['cimage'])) ? base_url()."uploads/".$v['cimage'] : "-"; 

                                                ?>
                                                        <tr>
                                                            <td><?php echo $v['cid']; ?></td>
                                                            <td><?php echo $v['cname']; ?></td>
                                                           
                                                            <td>
                                                                <?php
                                                                if(!empty($v['cimage'])){
                                                                            echo '<img src="'.$src.'" width="150" height="auto"/>';
                                                                 }else{echo "--";}
                                                                ?>
                                                               
                                                            </td>
                                                            
                                                            
                                                            <td><?php echo $v['dis']; ?></td>
                                                            
                                                             <td>
                                                                <a href="<?php echo base_url(); ?>site/edit_category/?id=<?php echo $v['cid']; ?>"><img src="<?php echo base_url(); ?>assets/images/edit.png" /></a>&nbsp;&nbsp;&nbsp;
                                                                <a href="javascript:void(0);" id="del_<?php echo $v['cid']; ?>" onclick="javascript:delete_row(this.id);"><img src="<?php echo base_url(); ?>assets/images/del.png" /></a>
                                                            </td> 
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