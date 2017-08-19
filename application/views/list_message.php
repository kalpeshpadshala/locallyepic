<?php // echo '<pre>'; print_r($inbox); exit;?>
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
<style>

.mailbox-messages{
    margin: 50px;
    margin-top: 0px;
}
#loader{
    display: hidden
}
#loader{
       
	position: absolute;
	top: 0;
	left: 0;
	width: 100%;
        height: 100%;
	background: rgba(0, 0, 0, 0.10);
	z-index: 999;
	display:none;
}
#loader img {
	margin-top: 20%;
	margin-left: 52%;
}
.white{
    color:#fff;
    border: 1px solid black;
}
#attachment_ul li{
    display: inline;
}
</style>
<script>
            var url = window.location.href; 
            var pathname = url.split("/");
            var filename = pathname[pathname.length-1];
//            alert(filename);
            if(filename == "list_message"){
                open_inbox();
            }else if(filename == "list_message#inbox"){
                open_inbox();
            }else if(filename == "list_message#sentbox"){
                open_sendbox();
            }else if(filename == "list_message#draft"){
                open_draft();
            }
    function read_message(id){
        $('tr#' + id).removeClass('unread-message');
        open_msg(id);
        $.ajax({
                type: "POST",
                url: "<?php echo base_url(); ?>site/read_message",
                data: {id: id},
                success: function(response){
                       console.log(response);
                }

            });
    }
    
    
    
    function message_ajax(load_type,box){
        $("#loader").show();
        $("#loader").fadeIn(400).html('<img src="<?php echo base_url(); ?>assets/loader.gif" />');
        var count = 0;
        var c_msg = $("#count_msg_"+ box).html();
        var offset = (c_msg*1) + 9 ;
        var limit = 0;
        var totle = $("#totle_count_pagi_"+ box).html();
        var count_next_pre = $("#count_next_pre_"+ box).html();
        if(load_type == "pre"){
            count = (c_msg*1) - 10;
            
            if(count < 1){
                count = 1;
                offset = offset;
            }
            else{
                offset = offset - 10;
            }
            $("#count_msg_"+ box).html(count);
            limit = limit - (count-1);
            
        }
        else{
            count = (c_msg*1) +10;
            if(count_next_pre == totle){
                count = c_msg;
            }
            $("#count_msg_"+ box).html(count);
            limit = limit + (count-1);
            offset = offset + 10;
            
        }
        
        if(offset > totle){ offset = totle;}
        $("#count_next_pre_"+ box).html(offset);
//            alert("limit" + limit + "count" + count);
        $.ajax({
                type: "POST",
                url: "<?php echo base_url(); ?>site/message_ajax_" + box,
                data: {limit: limit,offset: (count-1),load_type:load_type},
                success: function(response){
                       console.log(response);
                       $("#"+ box + "_tr").html(" ");
                       $("#"+ box + "_tr").append(response);
                       $("#loader").hide();
                }
            });
            
    }
    function open_msg(id){
        list_reply(id);
        $("#loader").show();
        $("#loader").fadeIn(400).html('<img src="<?php echo base_url(); ?>assets/loader.gif" />');
        $.ajax({
            type : "GET",
            url : "<?php echo base_url();?>site/view_message",
            data : {id:id},
            success : function(response1){
                var response = $.parseJSON(response1);
                $("li#inb").removeClass("active");
                $("li#sen").removeClass("active");
                $("li#draf").removeClass("active");
                
                $("div#open_message").css("display" , "block");
                $(".mailbox-nav").css("display" , "none");
                $("div#send_email").css("display" , "none");
                $("div#draft_email").css("display" , "none");
                $("div#email_list").css("display" , "none");
                
                $("#img").html('<img class="img-circle" src="<?php echo base_url(); ?>uploads/user/' + response.profile_pic + '" alt="" style="width: 7%;">');
                $("#fist_name").html(response.first_name);
                $("#last_name").html(response.last_name);
                $("#subject").html(response.subject);
                $("#description").html(response.description);
                if(response.attachment != null){
                $("#attachment_box").css("display" , "block");
                $("#attachment_ul").html('<li style="display: inline;"><div class="msg-col" style="background-color: rgb(236, 240, 241);border: 1px solid rgb(204, 204, 204);width: 80%;padding-top: 20px;padding-bottom: 30px;"><div style="width: 80%;margin-left: 20px;"><span id="attachment_link"><a href="#" download="<?php  echo base_url();?>uploads/attachment/' + response.attachment + '">Atteachment : ' + response.attachment + '</a></span></div></div><br/><li>');
                }
                else{
                    $("#attachment_box").css("display" , "none");
                }
                $("#put_id").val(response.message_id);
                $("#msg_to").val(response.user_id);
                $("#loader").hide();
            }
        });
    }
    function list_reply(id){
        $.ajax({
            type : "GET",
            url : "<?php echo base_url();?>site/list_reply",
            data : {id:id},
            success : function(response1){
                var response = $.parseJSON(response1);
                
                if( response1 != 0 ) {
                    $("#reply_ul").html(" ");
                    console.log(response);
                    var lenght = response.length;
                    for(var i = 0; i<lenght; i++){
                    $("#reply_ul").append('<li style="display: inline;"><div class="msg-col" style="background-color: rgb(236, 240, 241);border: 1px solid rgb(204, 204, 204);width: 80%;padding-top: 20px;padding-bottom: 30px;"><div style="width: 80%;margin-left: 20px;"><span id="reply_description">' + response[i].description + '</span></div><br/><div style="float: right;width: 25%">- <span id="reply_firstname">' + response[i].first_name +'</span> <span id="reply_lastname">' + response[i].last_name + '</span></div></div><br/></li>');
                    }
                    $("#reply_box").css("display","block");
                }
                else{
                    $("#reply_box").css("display","none");
                }
                }
            });
    }
    
    function ajax(action, id) {


            $.ajax({
                type: "POST",
                url: "<?php echo base_url(); ?>site/"+action,
                data: {id: id},
                success: function(response){
                       console.log(response);
                       location.reload();
                }

            });
        }
        function open_inbox(){
            $("li#inb").addClass("active");
            $("li#sen").removeClass("active");
            $("li#draf").removeClass("active");
            $("div#email_list").css("display" , "block");
            $(".mailbox-nav").css("display" , "block");
            $("div#send_email").css("display" , "none");
            $("div#draft_email").css("display" , "none");
            $("div#lable_message").css("display" , "none");
            $("div#open_message").css("display" , "none");
        }
        function open_sendbox(){
            $("li#sen").addClass("active");
            $("li#inb").removeClass("active");
            $("li#draf").removeClass("active");
            $("div#email_list").css("display" , "none");
            $(".mailbox-nav").css("display" , "block");
            $("div#send_email").css("display" , "block");
            $("div#lable_message").css("display" , "none");
            $("div#draft_email").css("display" , "none");
            $("div#open_message").css("display" , "none");
        }
        function open_draft(){
            $("li#draf").addClass("active");
            $("li#inb").removeClass("active");
            $("li#sen").removeClass("active");
            $("div#lable_message").css("display" , "none");
            $("div#email_list").css("display" , "none");
            $(".mailbox-nav").css("display" , "block");
            $("div#send_email").css("display" , "none");
            $("div#draft_email").css("display" , "block");
            $("div#open_message").css("display" , "none");
        }
        function check_all(){
            if($('li#inb').hasClass('active')){
                if($("input#selectall").prop("checked") == true){
                    $("input.selectedId_inb").prop('checked', true);
                }
                else if($("input#selectall").prop("checked") == false){
                    $("input.selectedId_inb").prop('checked', false);
                }
            }
            else if($('li#sen').hasClass('active')){
                if($("input#selectall").prop("checked") == true){
                    $("input.selectedId_sen").prop('checked', true);
                }
                else if($("input#selectall").prop("checked") == false){
                    $("input.selectedId_sen").prop('checked', false);
                }
            }
            else if($("li#draf").hasClass('active')){
                if($("input#selectall").prop("checked") == true){
                    $("input.selectedId_draf").prop('checked', true);
                }
                else if($("input#selectall").prop("checked") == false){
                    $("input.selectedId_draf").prop('checked', false);
                }
            }
        }
        function delete_msg(){
            
            if($('li#inb').hasClass('active')){
                if($("input.selectedId_inb").is(':checked')) {
                    var val1 = [];
                        $(':checkbox:checked').each(function(i){
                          val1[i] = $(this).val();
                          $("#" + $(this).val()).css("display" , "none");
                          console.log(val1);
                        });
                         ajax("delete_msg" , val1);
                } 
            }
            else if($('li#sen').hasClass('active')){
                if($("input.selectedId_sen").is(':checked')) {
                var val2 = [];
                    $(':checkbox:checked').each(function(i){
                      val2[i] = $(this).val();
                      $("#" + $(this).val()).css("display" , "none");
                      console.log(val2);
                    });
                    ajax("delete_msg" , val2);
                }
            }
            else if($("li#draf").hasClass('active')){
                if($("input.selectedId_draf").is(':checked')) {
                var val3 = [];
                    $(':checkbox:checked').each(function(i){
                      val3[i] = $(this).val();
                      $("#" + $(this).val()).css("display" , "none");
                      console.log(val3);
                    });
                    ajax("delete_msg_draf" , val3);
                }
            }
        }
        function draft_msg(){
            
            if($('li#inb').hasClass('active')){
                if($("input.selectedId_inb").is(':checked')) {
                    var val1 = [];
                        $(':checkbox:checked').each(function(i){
                          val1[i] = $(this).val();
                          $("#" + $(this).val()).css("display" , "none");
                          
                          console.log(val1);
                        });
                         ajax("draft_msg" , val1);
                         
                } 
            }
            else if($('li#sen').hasClass('active')){
                if($("input.selectedId_sen").is(':checked')) {
                var val2 = [];
                    $(':checkbox:checked').each(function(i){
                      val2[i] = $(this).val();
                      $("#" + $(this).val()).css("display" , "none");
                      console.log(val2);
                    });
                    ajax("draft_msg" , val2);
                }
            }
            else if($("li#draf").hasClass('active')){
                if($("input.selectedId_draf").is(':checked')) {
                alert("This Msg Is Already In Draft!!!");
                }
            }
        }
        function make_imp(){
            if($('li#inb').hasClass('active')){
                if($("input.selectedId_inb").is(':checked')) {
                    var val1 = [];
                        $(':checkbox:checked').each(function(i){
                          val1[i] = $(this).val();
                          console.log(val1);
                        });
                         ajax("make_imp" , val1);
                         
                } 
            }
            else if($('li#sen').hasClass('active')){
                if($("input.selectedId_sen").is(':checked')) {
                var val2 = [];
                    $(':checkbox:checked').each(function(i){
                      val2[i] = $(this).val();
                      console.log(val2);
                    });
                    ajax("make_imp" , val2);
                    
                }
            }
            else if($("li#draf").hasClass('active')){
                if($("input.selectedId_draf").is(':checked')) {
                var val3 = [];
                    $(':checkbox:checked').each(function(i){
                      val3[i] = $(this).val();
                      console.log(val3);
                    });
                    ajax("make_imp" , val3);
                     
                }
            }
        
        }
        
        function message_lable(action){
            
            if($('li#inb').hasClass('active')){
                if($("input.selectedId_inb").is(':checked')) {
                    var val1 = [];
                        $(':checkbox:checked').each(function(i){
                          val1[i] = $(this).val();
                          $('i#' + $(this).val()).addClass('unread-message');
                          console.log(val1);
                        });
                        $.ajax({
                            type: "POST",
                            url: "<?php echo base_url(); ?>site/message_lable",
                            data: {id: val1,action:action},
                            success: function(response){
                                    location.reload();
                                   console.log(response);
                            }
                        });
                         
                } 
            }
            else if($('li#sen').hasClass('active')){
                if($("input.selectedId_sen").is(':checked')) {
                var val2 = [];
                    $(':checkbox:checked').each(function(i){
                      val2[i] = $(this).val();
                      $('i#' + $(this).val()).addClass('unread-message');
                      console.log(val2);
                    });
                    $.ajax({
                        type: "POST",
                        url: "<?php echo base_url(); ?>site/message_lable",
                        data: {id: val2,action:action},
                        success: function(response){
                            location.reload();
                               console.log(response);
                        }

                    });
                    
                }
            }
            else if($("li#draf").hasClass('active')){
                if($("input.selectedId_draf").is(':checked')) {
                var val3 = [];
                    $(':checkbox:checked').each(function(i){
                      val3[i] = $(this).val();
                      $('i#' + $(this).val()).addClass('unread-message');
                      console.log(val3);
                    });
                    $.ajax({
                        type: "POST",
                        url: "<?php echo base_url(); ?>site/message_lable",
                        data: {id: val3,action:action},
                        success: function(response){
                            location.reload();
                               console.log(response);
                        }

                    });
                    
                }
            }
        }
        function purchase(){
            message_ajax("1","lable");
            $("div#lable_message").css("display" , "block");
            $("li#draf").removeClass("active");
            $("li#inb").removeClass("active");
            $("li#sen").removeClass("active");
            $("div#email_list").css("display" , "none");
            $(".mailbox-nav").css("display" , "none");
            $("div#send_email").css("display" , "none");
            $("div#draft_email").css("display" , "none");
            $("div#open_message").css("display" , "none");
            
        }
        function current(){
            message_ajax("2","lable");
            $("div#lable_message").css("display" , "block");
            $("li#draf").removeClass("active");
            $("li#inb").removeClass("active");
            $("li#sen").removeClass("active");
            $("div#email_list").css("display" , "none");
            $(".mailbox-nav").css("display" , "none");
            $("div#send_email").css("display" , "none");
            $("div#draft_email").css("display" , "none");
            $("div#open_message").css("display" , "none");
        }
        function work(){
            message_ajax("3","lable");
            $("div#lable_message").css("display" , "block");
            $("li#draf").removeClass("active");
            $("li#inb").removeClass("active");
            $("li#sen").removeClass("active");
            $("div#email_list").css("display" , "none");
            $(".mailbox-nav").css("display" , "none");
            $("div#send_email").css("display" , "none");
            $("div#draft_email").css("display" , "none");
            $("div#open_message").css("display" , "none");
        }
        function personal(){
            message_ajax("4","lable");
            $("div#lable_message").css("display" , "block");
            $("li#draf").removeClass("active");
            $("li#inb").removeClass("active");
            $("li#sen").removeClass("active");
            $("div#email_list").css("display" , "none");
            $(".mailbox-nav").css("display" , "none");
            $("div#send_email").css("display" , "none");
            $("div#draft_email").css("display" , "none");
            $("div#open_message").css("display" , "none");
        }
        function none(){
            message_ajax("0","lable");
            $("div#lable_message").css("display" , "block");
            $("li#draf").removeClass("active");
            $("li#inb").removeClass("active");
            $("li#sen").removeClass("active");
            $("div#email_list").css("display" , "none");
            $(".mailbox-nav").css("display" , "none");
            $("div#send_email").css("display" , "none");
            $("div#draft_email").css("display" , "none");
            $("div#open_message").css("display" , "none");
        }
        $(document).ready(function(){
            
            $("input.selectedId_inb").change(function(){
                   var p = $('input.selectedId_inb:checked').length;
                   if(p == 0){
                       $("input#selectall").prop('checked', false);
                   }
                   if(p == $('input.selectedId_inb').length){
                        $("input#selectall").prop('checked', true);
                   }
            });
            $("input.selectedId_sen").change(function(){
                   var p = $('input.selectedId_sen:checked').length;
                   if(p == 0){
                       $("input#selectall").prop('checked', false);
                   }
                   if(p == $('input.selectedId_sen').length){
                        $("input#selectall").prop('checked', true);
                   }
            });
            $("input.selectedId_draf").change(function(){
                   var p = $('input.selectedId_draf:checked').length;
                   if(p == 0){
                       $("input#selectall").prop('checked', false);
                   }
                   if(p == $('input.selectedId_draf').length){
                        $("input#selectall").prop('checked', true);
                   }
            });
        });
</script>
<!-- begin MAIN PAGE CONTENT -->
<div id="loader"></div>
        <div id="page-wrapper">
            
            <div class="page-content page-content-ease-in">

                <!-- begin PAGE TITLE ROW -->
                <div class="row">
                    <div class="col-lg-12">
                        <div class="page-title">
                            <h1>
                                Mailbox
                                <small>Message Center</small>
                            </h1>
                            <ol class="breadcrumb">
                                <li><i class="fa fa-dashboard"></i>  <a href="<?php echo base_url();?>">Dashboard</a>
                                </li>
                                <li class="active">Mailbox</li>
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
                            <div class="portlet-body">

                                <nav class="navbar mailbox-topnav" role="navigation">
                                    <!-- Brand and toggle get grouped for better mobile display -->
                                    <div class="navbar-header">
                                        <a class="navbar-brand" href="<?php echo base_url();?>site/list_message"><i class="fa fa-inbox"></i> Inbox</a>
                                    </div>

                                    <!-- Collect the nav links, forms, and other content for toggling -->
                                    <div class="mailbox-nav">
                                        <ul class="nav navbar-nav button-tooltips">
                                            <li class="checkall">
                                                <input type="checkbox" id="selectall" class="no_selected" onclick="check_all()" data-toggle="tooltip" data-placement="bottom" title="Select All">
                                            </li>
                                            <li class="message-actions">
                                                <div class="btn-group navbar-btn">
                                                    <button type="button" class="btn btn-white" data-toggle="tooltip" data-placement="bottom"  title="Move To Draft" onclick="draft_msg()"><i class="fa fa-archive"></i>
                                                    </button>
                                                    <button type="button" class="btn btn-white" data-toggle="tooltip" data-placement="bottom" title="Mark as Important" onclick ="make_imp()"><i class="fa fa-exclamation-circle"></i>
                                                    </button>
                                                    <button type="button" class="btn btn-white" data-toggle="tooltip" data-placement="bottom" title="Delete Message" onclick="delete_msg()" data-original-title="Trash"><i class="fa fa-trash-o"></i>
                                                    </button>
                                                </div>
                                            </li>
                                            <li class="dropdown message-label">
                                                <button type="button" class="btn btn-white navbar-btn dropdown-toggle" data-toggle="dropdown"><i class="fa fa-tag"></i>  <i class="fa fa-caret-down text-muted"></i>
                                                </button>
                                                <ul class="dropdown-menu">
                                                    <li onclick="message_lable('purchase')"><a href="#"><i class="fa fa-square text-green"></i> Purchase Orders</a>
                                                    </li>
                                                    <li onclick="message_lable('current')"><a href="#"><i class="fa fa-square text-orange"></i> Current Projects</a>
                                                    </li>
                                                    <li onclick="message_lable('work')"><a href="#"><i class="fa fa-square text-purple"></i> Work Groups</a>
                                                    </li>
                                                    <li onclick="message_lable('personal')"><a href="#"><i class="fa fa-square text-blue"></i> Personal</a>
                                                    </li>
                                                    <li onclick="message_lable('none')"><a href="#"><i class="fa fa-square-o"></i> None</a>
                                                    </li>
                                                </ul>
                                            </li>
                                        </ul>
<!--                                        <form class="navbar-form navbar-right visible-lg" role="search">
                                            <div class="form-group">
                                                <input type="text" class="form-control" placeholder="Search Mail...">
                                            </div>
                                            <button type="submit" class="btn btn-default"><i class="fa fa-search"></i>
                                            </button>
                                        </form>-->
                                    </div>
                                </nav>

                                <div id="mailbox">

                                    <ul class="nav nav-pills nav-stacked mailbox-sidenav">
                                        <li><a class="btn btn-white" href="<?php echo base_url();?>site/add_message"><i class="fa fa-edit"></i> Compose Message</a>
                                        </li>
                                        <li class="nav-divider"></li>
                                        <li class="mailbox-menu-title text-muted">Folder</li>
                                        
                                        <li class="active" id="inb" onclick="open_inbox()"><a href="#inbox">Inbox</a>
                                        </li>
                                        <li id="sen" onclick="open_sendbox()"><a href="#sentbox">Sent</a>
                                        </li>
                                        <li id="draf" onclick="open_draft()"><a href="#draft">Drafts</a>
                                        </li>
<!--                                        <li><a href="#">Spam</a>
                                        </li>
                                        <li><a href="#">Trash</a>
                                        </li>-->
                                       <li class="nav-divider"></li>
                                        <li class="mailbox-menu-title text-muted">Labels</li>
                                        <li><a href="#" onclick="purchase()"><i class="fa fa-square text-green"></i> Purchase Orders</a>
                                        </li>
                                        <li><a href="#" onclick="current()"><i class="fa fa-square text-orange"></i> Current Projects</a>
                                        </li>
                                        <li><a href="#" onclick="work()"><i class="fa fa-square text-purple"></i> Work Groups</a>
                                        </li>
                                        <li><a href="#" onclick="personal()"><i class="fa fa-square text-blue"></i> Personal</a>
                                        </li>
                                        <li><a href="#" onclick="none()"><i class="fa fa-square-o"></i> None</a>
                                        </li>
                                    </ul>

                                    <div id="mailbox-wrapper">
                                        
                                        <div id="email_list" class="inbox" style="display:block">
                                            <div class="table-responsive mailbox-messages">
                                                <table class="table table-bordered table-striped table-hover">
                                                    <tbody id="inbox_tr">
                                                        <?php if(isset($inbox) && !empty($inbox)){ $c=0;?>
                                                        <?php foreach ($inbox as $v) { ?>
                                                        <?php if($v['status'] == 0){?>
                                                        <?php $draft = explode( ",",$v['draft']); ?>
                                                        <?php if(!(in_array($user_info['user_id'], $draft))){?>
                                                        <?php $t = explode(",", $v['who_open']);?>
                                                        <?php if(in_array($user_info['user_id'], $t)){?>
                                                        <tr class="clickableRow" id="<?php echo $v['message_id'];?>" >
                                                            <td class="checkbox-col">
                                                                <input type="checkbox" class="selectedId_inb" id="<?php echo $v['message_id'];?>" value="<?php echo $v['message_id'];?>" name="selectedId_inb[]">
                                                            </td>
                                                            <td class="from-col" onclick="open_msg(<?php echo $v['message_id'];?>)">
                                                                    <?php if($v['is_imp'] == 1){ echo '<i class="fa fa-exclamation-circle"></i>'; }?>
                                                                    <?php echo $v['first_name'];?> <?php echo $v['last_name'];?>
                                                            </td>
                                                            <td class="msg-col" onclick="open_msg(<?php echo $v['message_id'];?>)">
                                                                <i id="<?php echo $v['message_id'];?>" class="fa  <?php if($v['lable'] == 1){echo "fa-square text-green";}else if($v['lable'] == 2){echo "fa-square text-orange";}else if($v['lable'] == 3){echo "fa-square text-purple";}else if($v['lable'] == 4){echo "fa-square text-blue";}else if($v['lable'] == 0){echo "fa-square-o";}?>"></i>
                                                                <?php echo $v['subject'];?>
                                                                <span class="text-muted">
                                                                </span>
                                                            </td>
                                                            <td class="date-col"><?php if(!empty($v['attachment'])){?><i class="fa fa-paperclip"></i><?php }?> <?php echo $v['message_date'];?></td>
                                                        </tr>
                                                        <?php $c++; }else{?>
                                                        
                                                        <tr class="unread-message clickableRow" id="<?php echo $v['message_id'];?>" >
                                                            <td class="checkbox-col">
                                                                <input type="checkbox" class="selectedId_inb" id="<?php echo $v['message_id'];?>" value="<?php echo $v['message_id'];?>" name="selectedId_inb[]">
                                                            </td>
                                                            <td class="from-col" onclick="read_message(<?php echo $v['message_id'];?>)">
                                                                    <?php if($v['is_imp'] == 1){ echo '<i class="fa fa-exclamation-circle"></i>'; }?>
                                                                    <?php echo $v['first_name'];?> <?php echo $v['last_name'];?>
                                                            </td>
                                                            <td class="msg-col" onclick="read_message(<?php echo $v['message_id'];?>)">
                                                                <i class="fa  <?php if($v['lable'] == 1){echo "fa-square text-green";}else if($v['lable'] == 2){echo "fa-square text-orange";}else if($v['lable'] == 3){echo "fa-square text-purple";}else if($v['lable'] == 4){echo "fa-square text-blue";}else if($v['lable'] == 0){echo "fa-square-o";}?> "></i>
                                                                <?php echo $v['subject'];?>
                                                                <span class="text-muted">
                                                                </span>
                                                            </td>
                                                            <td class="date-col"><?php if(!empty($v['attachment'])){?><i class="fa fa-paperclip"></i><?php }?> <?php echo $v['message_date'];?></td>
                                                        </tr>
                                                        
                                                        <?php $c++; }?>
                                                        <?php }?>
                                                        <?php }?>
                                                        <?php }?>
                                                        <?php if($c != 0){?>
                                                        <ul class="list-inline pull-right">
                                                            <li><strong><span id="count_msg_inbox">1</span>-<span id="count_next_pre_inbox"><?php echo $c;?></span> of <span id="totle_count_pagi_inbox"><?php echo $count_inbox;?></span></strong>
                                                            </li>
                                                            <li>
                                                                <div class="btn-group">
                                                                    <button type="button" class="btn btn-white" id="pre_msg_inbox" onclick="message_ajax('pre','inbox');"><i class="fa fa-chevron-left"></i>
                                                                    </button>
                                                                    <button type="button" class="btn btn-white" id="next_msg_inbox" onclick="message_ajax('next','inbox');"><i class="fa fa-chevron-right"></i>
                                                                    </button>
                                                                </div>
                                                            </li>
                                                        </ul>
                                                        <?php } ?>
                                                        <?php } ?>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                        
                                        <div id="send_email" class="send_box" style="display:none">
                                            <div class="table-responsive mailbox-messages">
                                                <table class="table table-bordered table-striped table-hover">
                                                    <tbody id="sendbox_tr">
                                                        <?php if(isset($sendbox) && !empty($sendbox)){ $c1=0;?>
                                                        <?php foreach ($sendbox as $v) { ?>
                                                        <?php $draft = explode( ",",$v['draft']); ?>
                                                        <?php if(!(in_array($user_info['user_id'], $draft))){?>
                                                        <?php if($v['status'] == 0){?>
                                                        <?php if($v['message_from'] == $user_info['user_id']){?>
                                                        <?php $t = explode(",", $v['who_open']);?>
                                                        <?php if(in_array($user_info['user_id'], $t)){?>
                                                        <tr class="clickableRow" id="<?php echo $v['message_id'];?>" >

                                                            <td class="checkbox-col">
                                                                <input type="checkbox" class="selectedId_sen" id="<?php echo $v['message_id'];?>" value="<?php echo $v['message_id'];?>" name="selectedId_sen[]">
                                                            </td>

                                                            <td class="from-col" onclick="open_msg(<?php echo $v['message_id'];?>)">
                                                                <?php if($v['is_imp'] == 1){ echo '<i class="fa fa-exclamation-circle"></i>'; }?>
                                                                <?php echo $v['first_name'];?> <?php echo $v['last_name'];?>
                                                            </td>
                                                            <td class="msg-col" onclick="open_msg(<?php echo $v['message_id'];?>)">
                                                                <i class="fa  <?php if($v['lable'] == 1){echo "fa-square text-green";}else if($v['lable'] == 2){echo "fa-square text-orange";}else if($v['lable'] == 3){echo "fa-square text-purple";}else if($v['lable'] == 4){echo "fa-square text-blue";}else if($v['lable'] == 0){echo "fa-square-o";}?> "></i>
                                                                <?php echo $v['subject'];?>
                                                                <span class="text-muted">
                                                                </span>
                                                            </td>
                                                            <td class="date-col"><?php if(!empty($v['attachment'])){?><i class="fa fa-paperclip"></i><?php }?> <?php echo $v['message_date'];?></td>
                                                        </tr>
                                                        <?php $c1++; }else{?>
                                                        <tr class="unread-message clickableRow" id="<?php echo $v['message_id'];?>" >
                                                            <td class="checkbox-col">
                                                                <input type="checkbox" class="selectedId_sen" id="<?php echo $v['message_id'];?>" value="<?php echo $v['message_id'];?>" name="selectedId_sen[]">
                                                            </td>

                                                            <td class="from-col" onclick="read_message(<?php echo $v['message_id'];?>)">
                                                                    <?php if($v['is_imp'] == 1){ echo '<i class="fa fa-exclamation-circle"></i>'; }?>
                                                                    <?php echo $v['first_name'];?> <?php echo $v['last_name'];?>
                                                            </td>
                                                            <td class="msg-col" onclick="read_message(<?php echo $v['message_id'];?>)">
                                                                <i class="fa  <?php if($v['lable'] == 1){echo "fa-square text-green";}else if($v['lable'] == 2){echo "fa-square text-orange";}else if($v['lable'] == 3){echo "fa-square text-purple";}else if($v['lable'] == 4){echo "fa-square text-blue";}else if($v['lable'] == 0){echo "fa-square-o";}?> "></i>
                                                                <?php echo $v['subject'];?>
                                                                <span class="text-muted">
                                                                </span>
                                                            </td>
                                                            <td class="date-col"><?php if(!empty($v['attachment'])){?><i class="fa fa-paperclip"></i><?php }?> <?php echo $v['message_date'];?></td>
                                                        </tr>
                                                        <?php $c1++; }?>
                                                        <?php }?>
                                                        <?php }?>
                                                        <?php }?>
                                                        <?php }?>
                                                        <?php if($c1 != 0){?>
                                                        <ul class="list-inline pull-right">
                                                            <li><strong><span id="count_msg_sendbox">1</span>-<span id="count_next_pre_sendbox"><?php echo $c1;?></span> of <span id="totle_count_pagi_sendbox"><?php echo $count_sentbox;?></span></strong>
                                                            </li>
                                                            <li>
                                                                <div class="btn-group">
                                                                    <button type="button" class="btn btn-white" id="pre_msg_sendbox" onclick="message_ajax('pre','sendbox');"><i class="fa fa-chevron-left"></i>
                                                                    </button>
                                                                    <button type="button" class="btn btn-white" id="next_msg_sendbox" onclick="message_ajax('next','sendbox');"><i class="fa fa-chevron-right"></i>
                                                                    </button>
                                                                </div>
                                                            </li>
                                                        </ul>
                                                        <?php } ?>
                                                        <?php } ?>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                        <div id="draft_email" class="draft_box" style="display:none">
                                            <div class="table-responsive mailbox-messages">
                                                <table class="table table-bordered table-striped table-hover">
                                                    <tbody>
                                                        <?php if(isset($All_msg) && !empty($All_msg)){ $c2 = 0;?>
                                                        <?php foreach ($All_msg as $v) { ?>
                                                        <?php $draft = explode( ",",$v['draft']); ?>
                                                        <?php if(in_array($user_info['user_id'], $draft)){?>
                                                        <?php $t = explode(",", $v['who_open']);?>
                                                        <?php if(in_array($user_info['user_id'], $t)){?>
                                                        <tr class="clickableRow" id="<?php echo $v['message_id'];?>">

                                                            <td class="checkbox-col">
                                                                <input type="checkbox" class="selectedId_draf" id="<?php echo $v['message_id'];?>" value="<?php echo $v['message_id'];?>" name="selectedId_draf">
                                                            </td>

                                                            <td class="from-col" onclick="open_msg(<?php echo $v['message_id'];?>)">
                                                                    <?php if($v['is_imp'] == 1){ echo '<i class="fa fa-exclamation-circle"></i>'; }?>
                                                                    <?php echo $v['first_name'];?> <?php echo $v['last_name'];?>
                                                            </td>
                                                            <td class="msg-col" onclick="open_msg(<?php echo $v['message_id'];?>)">
                                                                <i class="fa  <?php if($v['lable'] == 1){echo "fa-square text-green";}else if($v['lable'] == 2){echo "fa-square text-orange";}else if($v['lable'] == 3){echo "fa-square text-purple";}else if($v['lable'] == 4){echo "fa-square text-blue";}else if($v['lable'] == 0){echo "fa-square-o";}?> "></i>
                                                                <?php echo $v['subject'];?>
                                                                <span class="text-muted">
                                                                </span>
                                                            </td>
                                                            <td class="date-col"><?php if(!empty($v['attachment'])){?><i class="fa fa-paperclip"></i><?php }?> <?php echo $v['message_date'];?></td>
                                                        </tr>
                                                        <?php $c2++; }else{?>
                                                        <tr class="unread-message clickableRow" id="<?php echo $v['message_id'];?>" >

                                                            <td class="checkbox-col">
                                                                <input type="checkbox" class="selectedId_draf" id="<?php echo $v['message_id'];?>" value="<?php echo $v['message_id'];?>" name="selectedId_draf">
                                                            </td>

                                                            <td class="from-col" onclick="read_message(<?php echo $v['message_id'];?>)">
                                                                    <?php if($v['is_imp'] == 1){ echo '<i class="fa fa-exclamation-circle"></i>'; }?>
                                                                    <?php echo $v['first_name'];?> <?php echo $v['last_name'];?>
                                                            </td>
                                                            <td class="msg-col" onclick="read_message(<?php echo $v['message_id'];?>)">
                                                                <a href ="<?php echo base_url();?>site/view_message?id=<?php echo $v['message_id'];?>">
                                                                    <i class="fa  <?php if($v['lable'] == 1){echo "fa-square text-green";}else if($v['lable'] == 2){echo "fa-square text-orange";}else if($v['lable'] == 3){echo "fa-square text-purple";}else if($v['lable'] == 4){echo "fa-square text-blue";}else if($v['lable'] == 0){echo "fa-square-o";}?> "></i>
                                                                <?php echo $v['subject'];?>
                                                                <span class="text-muted">
                                                                </span>
                                                                </a>
                                                            </td>
                                                            <td class="date-col"><?php if(!empty($v['attachment'])){?><i class="fa fa-paperclip"></i><?php }?> <?php echo $v['message_date'];?></td>
                                                        </tr>
                                                        <?php $c2++; }?>
                                                        <?php }?>
                                                        <?php }?>
                                                        <?php if($c2 != 0){?>
                                                        <ul class="list-inline pull-right">
                                                            <li><strong><span id="count_msg">1</span>-<?php echo $c2;?> of <?php echo $c2;?></strong>
                                                            </li>
                                                        </ul>
                                                        <?php }?>
                                                        <?php } ?>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                        <div id="open_message" class="message_box" style="display:none">
                                            <div class="table-responsive mailbox-messages">
                                                <div id="mailbox">
                                                    <div id="img">
                                                    </div>
                                                    <div id="mailbox-wrapper" style="margin-top: -100px;  min-height: 75px; margin-left: 123px;">
                                                        <div class="msg-col">
                                                            <h1><b>FROM : <span id="fist_name"></span> <span id="last_name"></span> : <span id="subject"></span></b></h1>
                                                        </div>
                                                    </div>
                                                    <div id="mailbox-wrapper" style="margin-left: 21px;">
                                                            <br/>
                                                            <br/>
                                                            <br/>
                                                        <div class="msg-col">
                                                            <div id="description">
                                                            </div>
                                                        </div>
                                                            <br/>
                                                            <br/>
                                                            <br/>
                                                            <div class="col-xs-6">
                                                                <div id="attachment_box" style="display:none">
                                                                    <ul style="" id="attachment_ul">
                                                                        
                                                                    </ul>
                                                                </div>
                                                                <div id="reply_box" style="display:none">
                                                                    <ul style="" id="reply_ul">
                                                                        
                                                                    </ul>
                                                                </div>
                                                            <form method="post" action="<?php echo base_url();?>site/message_reply">
                                                                <div class="form-group">
                                                                    <input type="hidden" name="message_id" id="put_id" value="">
                                                                    <input type="hidden" name="to" id="msg_to" value="">
                                                                        <textarea class="form-control" id="textArea" name="reply" placeholder="Add Reply"></textarea>
                                                                </div>
                                                                <div class="form-group" style="text-align: right">
                                                                        <input type="submit" name="add_message" class="btn btn-default" value="Reply">
                                                                </div>
                                                            </form>
                                                            </div>
                                                    </div>
                                                    
                                            </div>
                                            </div>
                                        </div>
                                        <div id="lable_message" class="lable_box" style="display:none">
                                            <div class="table-responsive mailbox-messages">
                                                <table class="table table-bordered table-striped table-hover">
                                                    <tbody id="lable_tr">
                                                        
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
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

    <!-- THEME SCRIPTS -->
    <script src="<?php echo base_url(); ?>assets/js/flex.js"></script>

</body>

</html>