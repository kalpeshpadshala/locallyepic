 <script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
<?php // echo '<pre>'; print_r($info); exit;?>
<script>
//function send_message() {
//
//  
//    var description=$(".note-editable").html();
//    var subject=$("#subject").val();
//    var nsm=$("#nsm").val();
//    var ssm=$("#ssm").val();
//    var asm=$("#asm").val();
//    var sp=$("#sp").val();
//    var bp=$("#bp").val();
//    var super_admin=$("#super_admin").val();
//    
//     var formData = new FormData($(this)[0]);
//     console.log(formData);
//     return false;
//    alert(formData.nsm);
//    alert(formData.files);
//   
//    $.ajax({
//        type: "POST",
//        url: "<?php // echo base_url(); ?>site/add_message",
//        data: {
//            description: description,
//            subject: subject,
//            nsm: nsm,
//            ssm: ssm,
//            asm: asm,
//            sp: sp,
//            bp: bp,
//            super_admin: super_admin,
//            add_message: 1,
//      
//        
//        },
//        beforeSend: function() {
//           // $("#btn_add_note").html('Adding The Notes...');
//        },
//        success: function(response){
//            if(response!=''){
////                //alert("You have successfully post the notes");
////                $("#btn_add_note").html("Add Notes");
////                $('tbody#notes_appand').prepend(response);
//				
//            }
//
//        }
//
//    });
//}
      function upload(){
        var description=$(".note-editable").html();
        document.forms["add_message"]["msg"].value = description;
        
        return true;
        
      } 
</script>
<!-- begin MAIN PAGE CONTENT -->
        <div id="page-wrapper">

            <div class="page-content page-content-ease-in">

                <!-- begin PAGE TITLE ROW -->
                <div class="row">
                    <div class="col-lg-12">
                        <div class="page-title">
                            <h1>
                                Compose Message
                            </h1>
                            <ol class="breadcrumb">
                                <li><i class="fa fa-dashboard"></i>  <a href="<?php echo base_url();?>site/index">Dashboard</a>
                                </li>
                                <li class="active">Compose Message</li>
                            </ol>
                        </div>
                    </div>
                    <!-- /.col-lg-12 -->
                </div>
                <!-- /.row -->
                <!-- end PAGE TITLE ROW -->

                <div class="row">
                    <div class="col-lg-12">
                        
                        <div class="portlet portlet-default">
                            <form action="<?php echo base_url(); ?>site/add_message" name="add_message" onsubmit="upload()" method="POST" enctype="multipart/form-data" >
                            <div class="portlet-heading">
                                <div class="portlet-title">
                                    <h4>New Message</h4>
                                </div>
                                <div class="clearfix"></div>
                            </div>
                            
                            <div class="portlet-body">
                                
                                <?php if($user_info['role'] == "2" || $user_info['role'] == "3" || $user_info['role'] == "4"){?>
                                
                                <div class="form-group">
                                    <div class="input-group" style="width : 25%">
                                        <span class="input-group-addon">To:</span>
                                        <select class="form-control" name="super_admin[]" id="super_admin">
                                            <option value="0"> Select Super Admin</option>
                                             <?php foreach ($info as $v) {?>
                                                <?php if($v['role'] == 1){?>
                                                    <option value="<?php echo $v['user_id'];?>"><?php echo $v['first_name'] . " " . $v['last_name'];?></option>
                                                <?php } } ?>
                                        </select>
                                    </div>
                                </div>
                                <?php
                                }
                                ?>
                                <div class="form-group">
                                    <div class="input-group"  style="width : 25%">
                                        <span class="input-group-addon">To:</span>
                                        <select class="form-control" name="nsm[]" id="nsm" multiple="multiple">
                                             
                                             <option value="0">All National Sales Manager</option>
                                             
                                             <?php foreach ($info as $v) {?>
                                                <?php if($v['role'] == 2){?>
                                                <option value="<?php echo $v['user_id'];?>"><?php echo $v['first_name'] . " " . $v['last_name'];?></option>
                                                <?php } } ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="input-group"  style="width : 25%">
                                        <span class="input-group-addon">To:</span>
                                        <select class="form-control" name="ssm[]" id="ssm" multiple="multiple">
                                             
                                                <option value="0">State Sales Manager</option>
                                             <?php foreach ($info as $v) {?>
                                                <?php if($v['role'] == 3){?>
                                                <option value="<?php echo $v['user_id'];?>"><?php echo $v['first_name'] . " " . $v['last_name'];?></option>
                                                <?php } } ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="input-group"  style="width : 25%">
                                        <span class="input-group-addon">To:</span>
                                        <select class="form-control" name="asm[]" id="asm" multiple="multiple">
                                            
                                                <option value="0">All Area Sales Manager</option>
                                             <?php foreach ($info as $v) {?>
                                                <?php if($v['role'] == 4){?>
                                                    <option value="<?php echo $v['user_id'];?>"><?php echo $v['first_name'] . " " . $v['last_name'];?></option>
                                            <?php  } } ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="input-group"  style="width : 25%">
                                        <span class="input-group-addon">To:</span>
                                        <select class="form-control" name="sp[]" id="sp" multiple="multiple">
                                             
                                                <option value="0">All Sales People</option>
                                             <?php foreach ($info as $v) {?>
                                                <?php if($v['role'] == 5){?>
                                                    <option value="<?php echo $v['user_id'];?>"><?php echo $v['first_name'] . " " . $v['last_name'];?></option>
                                                <?php  }} ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="input-group"  style="width : 25%">
                                        <span class="input-group-addon">To:</span>
                                        <select class="form-control" name="bp[]" id="bp" multiple="multiple">
                                               
                                                <option value="0">All Business People</option>
                                             <?php foreach ($info as $v) {?>
                                                <?php if($v['role'] == 6){?>
                                                    <option value="<?php echo $v['user_id'];?>"><?php echo $v['first_name'] . " " . $v['last_name'];?></option>
                                                <?php  }} ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="input-group">
                                        <span class="input-group-addon">From:</span>
                                        <input type="text" class="form-control" value="<?php echo $user_info['first_name'] . " " . $user_info['last_name'];?> " disabled>
                                    </div>
                                </div>
                                <hr>
                                <div class="form-group">
                                    <div class="input-group">
                                        <span class="input-group-addon">Subject:</span>
                                        <input type="text" class="form-control" name="subject" id="subject">
                                    </div>
                                </div>
                                <hr>
                                <input type="hidden" name="msg" value="">
                                <div id="summernote" style="display: none;">
                                    <div>
                                        
                                    <div><br/>--</div>
                                    <div><strong><?php echo $user_info['first_name'] . " " . $user_info['last_name'];?></strong></div>
                                    <div><?php echo get_role_name($user_info['role']); ?></div>
                                    <div><?php echo $user_info['email']; ?></div>
                                    </div>
                                </div>
                                
                            </div>
                           <div class="form-group">
                                    <div class="input-group">
                                            <input type="file"  name="files[]" multiple="" value="Add Attachment">
                                    </div>
                           </div>
                                <input type="submit" name="add_message" class="btn btn-default" value="Send" />
                            <div class="portlet-footer">
                                <div class="btn-toolbar" role="toolbar">
                                    
<!--                                    <i class="fa fa-envelope"></i>-->
<!--                                    <button class="btn btn-red pull-right"><i class="fa fa-times"></i> Discard</button>-->
<!--                                    <i class="fa fa-paperclip"></i>-->
                                
                                </div>
                            </div>
                        </form>
                             <!--<button  class="btn btn-default" onclick="javascript: void send_message();"><i class="fa fa-envelope"></i>  Senddsfdff</button>-->
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
    
     <!-- HubSpot Messenger -->
    <script src="<?php echo base_url(); ?>assets/plugins/messenger/messenger.js"></script>
    <script src="<?php echo base_url(); ?>assets/plugins/messenger/messenger.min.js"></script>
    <script src="<?php echo base_url(); ?>assets/plugins/messenger/messenger-theme-flat.js"></script>
    
    
     <!-- theme summernote -->
    <script src="<?php echo base_url(); ?>assets/js/plugins/summernote/summernote.min.js"></script>
    
    <script>
    $(document).ready(function() {
        $('#summernote').summernote({
            height: 300,
            toolbar: [
                ['style', ['bold', 'italic', 'underline', 'clear']],
                ['fontsize', ['fontsize']],
                ['color', ['color']],
                ['para', ['ul', 'ol', 'paragraph']],
            ]

        });
    });
    </script>

    <!-- THEME SCRIPTS -->
    <script src="<?php echo base_url(); ?>assets/js/flex.js"></script>

</body>

</html>