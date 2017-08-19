<!DOCTYPE html>
<html>

    <head>
        <title>Business - Sign up</title>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <link rel="icon" href="<?php echo base_url(); ?>assets/images/favicon.ico" type="image/x-icon" />

        <!-- bootstrap -->
        <link href="<?php echo base_url(); ?>assets/gogo/css/bootstrap/bootstrap.css" rel="stylesheet">
        <link href="<?php echo base_url(); ?>assets/gogo/css/bootstrap/bootstrap-responsive.css" rel="stylesheet">
        <link href="<?php echo base_url(); ?>assets/gogo/css/bootstrap/bootstrap-overrides.css" type="text/css" rel="stylesheet">

        <!-- libraries -->
        <link href="<?php echo base_url(); ?>assets/gogo/css/lib/bootstrap-wysihtml5.css" type="text/css" rel="stylesheet">
        <link href="<?php echo base_url(); ?>assets/gogo/css/lib/uniform.default.css" type="text/css" rel="stylesheet">
        <link href="<?php echo base_url(); ?>assets/gogo/css/lib/select2.css" type="text/css" rel="stylesheet">
        <link href="<?php echo base_url(); ?>assets/gogo/css/lib/bootstrap.datepicker.css" type="text/css" rel="stylesheet">
        <link href="<?php echo base_url(); ?>assets/gogo/css/lib/font-awesome.css" type="text/css" rel="stylesheet" />

        <!-- global styles -->
        <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/gogo/css/layout.css">
        <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/gogo/css/elements.css">
        <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/gogo/css/icons.css">

        <!-- this page specific styles -->
        <link rel="stylesheet" href="<?php echo base_url(); ?>assets/gogo/css/compiled/form-wizard.css" type="text/css" media="screen" />
        <link rel="stylesheet" href="<?php echo base_url(); ?>assets/gogo/css/compiled/signup.css" type="text/css" media="screen" />

        <!-- open sans font -->
        <link href='<?php echo base_url(); ?>assets/gogo/css/opensans.css' rel='stylesheet' type='text/css'>

        <!--[if lt IE 9]>
        <script src="<?php echo base_url(); ?>assets/gogo/js/html5.js"></script>
        <![endif]-->
       <style>
    #map-canvas {
         width: 700px; 
         height: 300px;
         display:inline;
    }
    #panel {
        position: absolute;
        top: 5px;
        left:25%;
        margin-top:5px;
        z-index: 5;
        background-color: #fff;
        padding: 5px;
        border: 1px solid #999;
    }
   
</style>
<script src="//maps.google.com/maps?file=api&amp;v=2&amp;key=ABQIAAAAjU0EJWnWPMv7oQ-jjS7dYxSPW5CJgpdgO_s4yyMovOaVh_KvvhSfpvagV18eOyDWu7VytS6Bi1CWxw"
      type="text/javascript"></script>
 <script type="text/javascript">

    var map = null;
    var geocoder = null;

    function initialize() {

      if (GBrowserIsCompatible()) {
        map = new GMap2(document.getElementById("map_canvas"));
        map.setCenter(new GLatLng(37.4419, -122.1419), 1);
        map.setUIToDefault();
        geocoder = new GClientGeocoder();
      }
    }

    function showAddress(address) {
    
      if (geocoder) {
        geocoder.getLatLng(
          address,
          function(point) {
            if (!point) {
              alert(address + " not found");
            } else {
              map.setCenter(point, 15);
              var marker = new GMarker(point, {draggable: true});
              // console.log(marker);
              //console.log(point);
              //console.log(point.k);
              //console.log(marker.getLatLng().D);
              var latitude= marker.getLatLng().k;
              var longitude= marker.getLatLng().D;
              $("#longitude").val(longitude);
              $("#latitude").val(latitude);
              map.addOverlay(marker);
              GEvent.addListener(marker, "dragend", function() {
                marker.openInfoWindowHtml(marker.getLatLng().toUrlValue(6));
                 var latitude= marker.getLatLng().k;
              var longitude= marker.getLatLng().D;
              $("#longitude").val(longitude);
              $("#latitude").val(latitude);
                //console.log(marker.getLatLng().toUrlValue(6));
              });
              GEvent.addListener(marker, "click", function() {
                marker.openInfoWindowHtml(marker.getLatLng().toUrlValue(6));
                 var latitude= marker.getLatLng().k;
              var longitude= marker.getLatLng().D;
              $("#longitude").val(longitude);
              $("#latitude").val(latitude);
                //console.log(marker.getLatLng().toUrlValue(6));
              });
	      GEvent.trigger(marker, "click");
            }    
          }
        );
        
        
      }
     // console.log(geocoder);
     //console.log(map);
   
    }
    
    </script>
    </head>
    <body onload="initialize()" onunload="GUnload()">
        
        <div class="header">
            <a href="<?php echo base_url(); ?>gogo/signup">
                <img src="<?php echo base_url(); ?>assets/gogo/img/logo.png" class="logo" style="width: 44px;height: 40px;"/>
            </a>
        </div>
        <!-- main container -->
        <div class="container-fluid" style="position:relative !important;">
            <div class="row-fluid">
                <div class="span12">
                    
                    <div id="fuelux-wizard" class="wizard row-fluid">
                        <ul class="wizard-steps">
                            <li data-target="#step1" class="active">
                                <span class="step">1</span>
                                <span class="title">Business Owner <br> information</span>
                            </li>
                            <li data-target="#step2">
                                <span class="step">2</span>
                                <span class="title">Business - 1 <br> information</span>
                            </li>
                            <li data-target="#step3">
                                <span class="step">3</span>
                                <span class="title">Business - 2 <br> information</span>
                            </li>
                            <li data-target="#step4">
                                <span class="step">4</span>
                                <span class="title">Business - 3 <br> information</span>
                            </li>
                        </ul>
                    </div>
                    <form id="myForm" name="myForm" method="post" action="<?php echo base_url(); ?>gogo/signup" enctype="multipart/form-data">
                        <input type="hidden" name="oper" value="signup">
                        <div class="step-content">
                            <div class="step-pane active" id="step1">
                                <div class="row-fluid form-wrapper">
                                    <div class="span8">
                                        
                                        <div class="field-box">
                                            <label>First Name:</label>
                                            <input id="first_name" name="first_name" class="span8" type="text" placeholder="Enter first name" />
                                        </div>
                                        <div class="field-box">
                                            <label>Last name:</label>
                                            <input id="last_name" name="last_name" class="span8" type="text" placeholder="Enter last name" />
                                        </div>
                                        <div class="field-box">
                                            <label>Login Name:</label>
                                            <input id="username" name="username" class="span8" type="text" placeholder="Enter Login Name" />
                                            <span style="color:red">(At least 6 characters.)</span>
                                        </div>
                                        <div class="field-box">
                                            <label>Login E-Mail:</label>
                                            <input id="usermail" name="usermail" class="span8" type="text" placeholder="Enter Login E-Mail" />
                                        </div>
                                        <div class="field-box">
                                            <label>Login Password:</label>
                                            <input id="userpwd1" name="userpwd1" class="span8" type="password" />
                                            <span style="color:red">(At least 6 characters.)</span>
                                        </div>
                                        <div class="field-box">
                                            <label>Confirm Password:</label>
                                            <input id="userpwd2" name="userpwd2" class="span8" type="password" />
                                        </div>
                                      
                                        <div class="field-box agree">
                                            <div style="position:relative;">
                                                <input id="agree_here" type="checkbox">
                                                <div class="agree_label">I have read and agree to the <a id="license_term" href="javascript:;"><b>Terms and Conditions</b></a> of this website.</div>
                                            </div>

                                        </div>
                                    </div>
                                    
                                    <div class="span4">
                                        <img src="<?php echo base_url(); ?>assets/gogo/img/signup_right.png"/>
                                    </div>
                                    
                                </div>
                            </div>
                            <div class="step-pane" id="step2">
                                <div class="row-fluid form-wrapper">
                                    <div class="span8">
                                        <div class="field-box">
                                            <label>Category:</label>
                                            <select id="category" name="category" style="width:240px" class="select2">
                                                <option></option>
                                                <?php
                                                foreach ($cats as $v) {
                                                    ?>
                                                    <option value="<?php echo $v['cid']; ?>"><?php echo $v['cname']; ?></option>
                                                    <?php
                                                }
                                                ?>
                                            </select>
                                        </div>
                                        <div class="field-box">
                                            <label>Business Name:</label>
                                            <input id="simtitle" name="simtitle" class="span8" type="text" />
                                            <div class="comment-ex-sign"></div>
                                        </div>

                                        <!--                                        <div class="field-box">
                                                                                    <label>Description:</label>
                                                                                    <div style="float: left;">
                                                                                        <textarea id="description" name="description" class="wysihtml5" rows="5" style="width: 500px;"></textarea>
                                                                                    </div>
                                                                                </div>-->
                                        <input type="hidden" name="description" id="description" >
                                    </div>
                                 
                                    <div class="span4">
                                        <img src="<?php echo base_url(); ?>assets/gogo/img/signup_right.png" />
                                    </div>  
                                </div>
                            </div>
                            <div class="step-pane" id="step3">
                                <div class="row-fluid form-wrapper">
                                    <div class="span8">
                                        <div class="field-box">
                                            <label>Country:</label>

                                            <select id="scountry" name="scountry" class="span8" type="text" onchange="selectState(this.options[this.selectedIndex].value)">
                                                <option value="">Select country</option>
                                                <?php
                                                foreach ($country as $v) {
                                                    ?>
                                                    <option value="<?php echo $v['id']; ?>"><?php echo $v['name']; ?></option>
                                                    <?php
                                                }
                                                ?>
                                            </select>
                                        </div>
                                        <div class="field-box">
                                            <label>State:</label>

                                            <select id="state_dropdown" name="sstate" class="span8" type="text" onchange="selectCity(this.options[this.selectedIndex].value)">
                                                <option value="">Select state</option>
                                            </select>
                                            <span id="state_loader"></span>
                                        </div>
                                        <div class="field-box">
                                            <label>City:</label>

                                            <select id="city_dropdown" name="scity" class="span8" type="text" >
                                                <option value="">Select City</option>
                                            </select>
                                            <span id="city_loader"></span>
                                        </div>
                                        <div class="field-box">
                                            <label>Address:</label>
                                            <input id="address" name="address" onblur="showAddress(this.value); return false" class="span8" type="text" />
                                           
                                        </div>
                                        <div class="field-box">
                                        <div id="map_canvas" style="width: 600px; height: 400px"></div>
                                        </div>
                                        <div class="field-box">
                                            <label>Latitude:</label>
                                            <input id="latitude" name="latitude"  class="span8" type="text" />

                                        </div>
                                        <div class="field-box">
                                            <label>Longitude:</label>
                                            <input id="longitude" name="longitude"  class="span8" type="text" />

                                        </div>
                                        <div class="field-box">
                                            <label>zip code:</label>
                                            <input id="zip_code" name="zip_code"  class="span8" type="text" />

                                        </div>
                                       

                                        <div class="field-box">
                                            <label>URL:</label>
                                            <input id="url" name="url" class="span4" type="text" />
                                        </div>
                                        

                                    </div>
                                      
                                    <div class="span4">
                                        <img src="<?php echo base_url(); ?>assets/gogo/img/signup_right.png"/>
                                    </div>  
                                </div>
                            </div>
                            <div class="step-pane" id="step4">
                                <div class="row-fluid form-wrapper payment-info">
                                    <div class="span8">
                                        <div class="field-box">
                                            <label>Insert Logo:</label>
                                            <div>
                                                <img class="profile" name="profile" src style="width: 120px; height: 76px; border: 2px solid #DDD;">
                                                <div id="remove_picture" class="btn">clear Logo</div>
                                            </div>
                                        </div>
                                        <div class="field-box">    
                                            <label></label>
                                            <input id="picture" name="picture" type="file" />
                                            <input type="hidden" id="picture_data" name="picture_data" value="">
                                            <input type="hidden" id="picture_name" name="picture_name" value="">
                                            <input type="hidden" id="del_picture_flag" name="del_picture_flag" value="false">
                                        </div>
                                    </div>
                                    <div class="span4">
                                        <img src="<?php echo base_url(); ?>assets/gogo/img/signup_right.png" />
                                    </div>  
                                </div>
                            </div>
                        </div>
                    </form>
                    
             
                    
                    <form id="license_form" action="<?php echo base_url(); ?>gogo/privercy_policy" method="post" target="_blank">
                        <input id="src_url" type="hidden" name="src_url" value="signin"/>
                        <input id="license_type" type="hidden" name="license_type"/>
                    </form>
                    
                   
                    
                    <div class="wizard-actions">
                        <button type="button" class="btn-glow success btn-cancel" onclick="historyback();">
                            <i class="icon-chevron-left"></i> Cancel
                        </button>
                        <button type="button" disabled class="btn-glow primary btn-prev" style="display:none;  margin-left: 15px;">
                            <i class="icon-chevron-left"></i> Prev
                        </button>
                        <button id="next_btn" type="button" disabled class="btn-glow primary btn-next" data-last="Finish">
                            Next <i class="icon-chevron-right"></i>
                        </button>
                        <button type="button" class="btn-glow success btn-finish">
                            Setup your account!
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <!-- end main container -->

        <!-- scripts for this page -->
        <script src="<?php echo base_url(); ?>assets/gogo/js/jquery-latest.js"></script>
        <script src="<?php echo base_url(); ?>assets/gogo/js/bootstrap.min.js"></script>
        <script src="<?php echo base_url(); ?>assets/gogo/js/bootbox.min.js"></script>
        <script src="<?php echo base_url(); ?>assets/gogo/js/theme.js"></script>
        <script src="<?php echo base_url(); ?>assets/gogo/js/fuelux.wizard.js"></script>
        <script src="<?php echo base_url(); ?>assets/gogo/js/wysihtml5-0.3.0.js"></script>
        <script src="<?php echo base_url(); ?>assets/gogo/js/bootstrap-wysihtml5-0.0.2.js"></script>
        <script src="<?php echo base_url(); ?>assets/gogo/js/select2.min.js"></script>
        <script src="<?php echo base_url(); ?>assets/gogo/js/googlemap.js"></script>

        <script type="text/javascript">
                                                $(function() {
                                                    // select2 plugin for select elements
                                                    $(".select2").select2({
                                                        placeholder: "Select a Category"
                                                    });

                                                    // wysihtml5 plugin on textarea
                                                    /*$(".wysihtml5").wysihtml5({
                                                     "font-styles": true,
                                                     "emphasis": true,
                                                     "lists": true,
                                                     "html": true,
                                                     "link": true,
                                                     "image": false
                                                     });*/

                                                    var $wizard = $('#fuelux-wizard'),
                                                            $btnPrev = $('.wizard-actions .btn-prev'),
                                                            $btnNext = $('.wizard-actions .btn-next'),
                                                            $btnFinish = $(".wizard-actions .btn-finish");
                                                    $btnCancel = $(".wizard-actions .btn-cancel");

                                                    $wizard.wizard().on('finished', function(e) {
                                                        // wizard complete code
                                                    }).on("changed", function(e) {
                                                        var step = $wizard.wizard("selectedItem");
                                                        // reset states
                                                        $btnNext.removeAttr("disabled");
                                                        $btnPrev.removeAttr("disabled");
                                                        $btnNext.show();
                                                        $btnFinish.hide();

                                                        if (step.step === 1) {
                                                            //$btnPrev.attr("disabled", "disabled");
                                                            $btnPrev.hide();
                                                            $btnCancel.show();
                                                        } else if (step.step === 4) {
                                                            $btnNext.hide();
                                                            $btnFinish.show();
                                                        }

                                                        if (step.step !== 1)
                                                        {
                                                            //       $btnCancel.hide();
                                                            $btnPrev.show();
                                                        }
                                                    });

                                                    $("#picture").change(function() {
                                                        if (this.files[0] != undefined)
                                                        {
                                                            var ftype = this.files[0].type; // get file type

                                                            //allow only valid image file types
                                                            switch (ftype)
                                                            {
                                                                case 'image/png':
                                                                case 'image/gif':
                                                                case 'image/jpeg':
                                                                case 'image/pjpeg':
                                                                    {
                                                                        $("#picture_name").val(this.files[0].name);
                                                                        resizeProfileImg(URL.createObjectURL(this.files[0]));
                                                                        break;
                                                                    }
                                                                default:
                                                                    {
                                                                        message_alert("'" + ftype + "' Unsupported file type!"
                                                                                , ("/var/www/business/signup.php:312")
                                                                                , function() {
                                                                        });
                                                                        this.value = null;
                                                                    }
                                                            }
                                                        }
                                                        else
                                                            $("img.profile").attr("src", "");
                                                    });

                                                    $btnFinish.on('click', function() {
                                                        if ($("#picture_data").val())    //check file type if file was selected.
                                                        {
                                                            //$('#picture')[0].value = null;
                                                            $("#myForm").submit();
                                                        } else {
                                                            bootbox.confirm("Are you sure want to create account with out picture? You can add picture after.", function(result) {
                                                                if (result === true) {
                                                                    $("#myForm").submit();
                                                                }
                                                            });
                                                        }
                                                    });
                                                     $btnPrev.on('click', function() {
                                                      var step = $wizard.wizard("selectedItem");
                                                        $wizard.wizard('previous');
                                                         if (step.step === 3) {
                                                                $(".map_div").css("visibility","visible");
                                                            }else{
                                                                $(".map_div").css("visibility","hidden");
                                                            }
                                                    });
                                                
                                                    
                                                    
//                                                        $wizard.wizard('previous');
//                                                        var step = $wizard.wizard("selectedItem");
//                                                        if (step.step === 3) {
//                                                                $(".map_div").css("visibility","visible");
//                                                            }else{
//                                                                $(".map_div").css("visibility","hidden");
//                                                            }
//                                                            if (step.step === 3) {
//                                                                $("#mapdiv").css("visibility","visible");
//                                                                $("#mapdiv").css("display","block");
//                                                            }else{
//                                                                $("#mapdiv").css("visibility","hidden");
//                                                                $("#mapdiv").css("display","none");
//                                                            }
                                            
                                                    $btnNext.on('click', function(event){
                                                        
                                                       
                                                        event.preventDefault();
                                                        var step = $wizard.wizard("selectedItem");
                                                        
                                                        if (step.step === 1) {
                                                            
                                                        
                                                            $first_name = $("#first_name");
                                                            $last_name = $("#last_name");
                                                            $name = $("#username");
                                                            $mail = $("#usermail");
                                                            $pwd1 = $("#userpwd1");
                                                            $pwd2 = $("#userpwd2");

                                                            if ($name.val().length == 0 || $mail.val().length == 0 || $pwd1.val().length == 0 || $pwd2.val().length == 0 || $first_name.val().length == 0 || $last_name.val().length == 0)
                                                            {
                                                                message_alert("Please fill in the blank."
                                                                        , ("/var/www/business/signup.php:352")
                                                                        , function() {
                                                                });
                                                                return;
                                                            }
                                                            else
                                                            {
                                                                   if ($name.val().length < 6)
                                                                   {
                                                                       message_alert("The Login Name should be at least 6 characters."
                                                                               , ("/var/www/business/signup.php:352")
                                                                               , function() {
                                                                       });
                                                                       return;
                                                                   }
                                                                   if ($pwd1.val().length < 6)
                                                                   {
                                                                       message_alert("The password should be at least 6 characters."
                                                                               , ("/var/www/business/signup.php:352")
                                                                               , function() {
                                                                       });
                                                                       return;
                                                                   }
                                                              
                                                           
                                                           var uvalid=check_username($name.val());
                                                      
                                                           if(uvalid==0){
                                                               return;
                                                           }
                                                                if ($pwd1.val() != $pwd2.val())
                                                                {
                                                                    message_alert("The password does not match. Please enter correct password."
                                                                            , ("/var/www/business/signup.php:362")
                                                                            , function() {
                                                                        $pwd1.val("");
                                                                        $pwd2.val("");
                                                                    });
                                                                    return;
                                                                }
                                                                if ($mail.val() !== "")
                                                                {
                                                                    var regex = /^([\w-]+(?:\.[\w-]+)*)@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$/;
                                                                    if (regex.test($mail.val()) === false) {
                                                                        message_alert("Please enter correct e-mail address."
                                                                                , ("/var/www/business/signup.php:374")
                                                                                , function() {
                                                                        });
                                                                        return;
                                                                    }
                                                                }
                                                               
                                                                
                                                                var evalid=check_emailex($mail.val());
                                                      
                                                                if(evalid==0){
                                                                    return;
                                                                }else{
                                                                $wizard.wizard('next');
                                                                }

                                                            }
                                                        } else if (step.step === 2) {
                                                            $category = $("#category");
                                                            $simtitle = $("#simtitle");

                                                            if (!($category.val() > 0))
                                                            {
                                                                message_alert("Please select at least a category."
                                                                        , ("/var/www/business/signup.php:405")
                                                                        , function() {
                                                                });
                                                                return;
                                                            }
                                                            if ($("#simtitle").val() === 0 || $("#fulltitle").val() === "")
                                                            {
                                                                message_alert("Please fill in the blank."
                                                                        , ("/var/www/business/signup.php:413")
                                                                        , function() {
                                                                });
                                                                $simtitle.focus();
                                                                return;
                                                            }
                                                            //$("#mapdiv").css("visibility","visible");
                                                        } else if (step.step === 3) {
                                                            var regex = /^[\-]?[0-9]*[\.]?[0-9]*$/;
                                                            if ($("#scountry").val() === "")
                                                            {
                                                                message_alert("Please select country"
                                                                        , ("/var/www/business/signup.php:424")
                                                                        , function() {

                                                                });
                                                                return;
                                                            }
                                                            if ($("#state_dropdown").val() === "")
                                                            {
                                                                message_alert("Please select state"
                                                                        , ("/var/www/business/signup.php:424")
                                                                        , function() {

                                                                });
                                                                return;
                                                            }
                                                            if ($("#city_dropdown").val() === "")
                                                            {
                                                                message_alert("Please select city"
                                                                        , ("/var/www/business/signup.php:424")
                                                                        , function() {

                                                                });
                                                                return;
                                                            }
                                                            if ($("#address").val() === "")
                                                            {
                                                                message_alert("Please enter address."
                                                                        , ("/var/www/business/signup.php:424")
                                                                        , function() {

                                                                });
                                                                return;
                                                            }
                                                           // $("#mapdiv").css("visibility","hidden");
                                                            //$("#mapdiv").css("display","none");
//                                                            if ($("#phone").val() !== "")
//                                                            {
//                                                                regex = /^[0-9 \(\)\-]*$/
//                                                                if (regex.test($("#phone").val()) === false)
//                                                                {
//                                                                    message_alert("Please enter correct phone number."
//                                                                            , ("/var/www/business/signup.php:439")
//                                                                            , function() {
//                                                                        $("#phone").val("");
//                                                                    });
//                                                                    return;
//                                                                }
//                                                            }
                                                        }
                                                        
                                                        if (step.step === 2) {
                                                                $(".map_div").css("visibility","visible");
                                                        }else{
                                                                $(".map_div").css("visibility","hidden");
                                                        }
                                                        
                                                        if (step.step !== 1)
                                                            $wizard.wizard('next');
                                                    });
                                                    $("#license_term").click(function() {
                                                        $("#license_type").val("term");
                                                        $("#license_form").submit();
                                                    });
                                                    $("#agree_here").click(function() {
                                                        if ($(this)[0].checked === true) {
                                                            $("#next_btn").removeAttr("disabled");
                                                        } else {
                                                            $("#next_btn").attr("disabled", "disabled");
                                                        }
                                                    });
                                                    initGoogleMap("span8");
                                                });
                                                function historyback() {
                                                    bootbox.confirm("Are you really cancel setup account?"
                                                            , function(result) {
                                                        if (result === true) {
                                                            
                                                            location.reload();
                                                        }
                                                    });
                                                }
                                                function textCounter(field, field2, maxlimit)
                                                {
                                                    var countfield = document.getElementById(field2);
                                                    if (field.value.length > maxlimit) {
                                                        field.value = field.value.substring(0, maxlimit);
                                                        return false;
                                                    } else {

                                                        var aa = maxlimit - field.value.length;
                                                        $("#" + field2).html(aa);
                                                    }
                                                }
        </script>
        <script>
            function selectState(country_id) {
                if (country_id != "") {
                    loadData('state', country_id);
                    $("#city_dropdown").html("<option value='-1'>Select city</option>");
                } else {
                    $("#state_dropdown").html("<option value='-1'>Select state</option>");
                    $("#city_dropdown").html("<option value='-1'>Select city</option>");
                }
            }
            function selectCity(state_id) {
                if (state_id != "-1") {
                    loadData('city', state_id);
                } else {
                    $("#city_dropdown").html("<option value='-1'>Select city</option>");
                }
            }
            function loadData(loadType, loadId) {
                var dataString = 'loadType=' + loadType + '&loadId=' + loadId;
                $("#" + loadType + "_loader").show();
                $("#" + loadType + "_loader").fadeIn(400).html('Please wait... <img src="<?php echo base_url(); ?>assets/images/loading.gif" />');
                $.ajax({
                    type: "POST",
                    url: "<?php echo base_url(); ?>gogo/loadData",
                    data: dataString,
                    cache: false,
                    success: function(result) {
                        $("#" + loadType + "_loader").hide();
                        $("#" + loadType + "_dropdown").html("<option value='-1'>Select " + loadType + "</option>");
                        $("#" + loadType + "_dropdown").append(result);
                        $("#" + loadType + "_dropdown").append("<option value='0'>Others</option>");
                    }
                });
            }
        </script>

 <script type="text/javascript">
  function check_username(usermail){
      
  var r=1;
        $.ajax({
                type: "POST",
                async: false,
                url: "<?php echo base_url(); ?>gogo/busername_check",

                data: {oper: "busername_check", usermail: usermail},
                success: function(result) {

                    if (result === "ok")
                    {
                       

                        message_alert("The Usename is already used by another user."
                                , ("")
                                , function() {
                        });
                        r=0;
                    }
                }
            });
            return r;
  }           
  function check_emailex(usermail){
      
  var r=1;
        $.ajax({
                type: "POST",
                async: false,
                url: "<?php echo base_url(); ?>gogo/email_check",

                data: {oper: "check_mail", usermail: usermail},
                success: function(result) {

                    if (result === "ok")
                    {
                        message_alert("The e-mail address is already used by another user."
                                                                                    , ("/var/www/business/signup.php:388")
                                                                                    , function() {
                                                                            });
                        r=0;
                    }
                }
            });
            return r;
  }           

</script>
       
    </body>

    
</html>
<!-- Localized -->