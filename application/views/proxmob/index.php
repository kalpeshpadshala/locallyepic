<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Flex Admin - Responsive Admin Theme</title>

    <!-- GLOBAL STYLES -->
    <link href="/css/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href='http://fonts.googleapis.com/css?family=Ubuntu:300,400,500,700,300italic,400italic,500italic,700italic' rel="stylesheet" type="text/css">
    <link href='http://fonts.googleapis.com/css?family=Open+Sans:300italic,400italic,600italic,700italic,800italic,400,300,600,700,800' rel="stylesheet" type="text/css">
    <link href="/icons/font-awesome/css/font-awesome.min.css" rel="stylesheet">

    <!-- PAGE LEVEL PLUGIN STYLES -->

    <!-- THEME STYLES -->
    <link href="/css/style.css" rel="stylesheet">
    <link href="/css/plugins.css" rel="stylesheet">

    <!-- THEME DEMO STYLES -->
    <link href="/css/demo.css" rel="stylesheet">

    <link rel="stylesheet" href="/assets/ladda/ladda-themeless.min.css">
    <script src="/assets/ladda/spin.min.js"></script>
    <script src="/assets/ladda/ladda.min.js"></script>

    <!--[if lt IE 9]>
    <script src="js/html5shiv.js"></script>
    <script src="js/respond.min.js"></script>
    <![endif]-->

<style>
     
      #map-canvas {
         width: 100%; 
         height: 300px; 
      }

      @media(min-width:768px) {
    body {
        background: white;
    }
}

.colorgraph {
  height: 5px;
  border-top: 0;
  background: #c4e17f;
  border-radius: 5px;
  background-image: -webkit-linear-gradient(left, #c4e17f, #c4e17f 12.5%, #f7fdca 12.5%, #f7fdca 25%, #fecf71 25%, #fecf71 37.5%, #f0776c 37.5%, #f0776c 50%, #db9dbe 50%, #db9dbe 62.5%, #c49cde 62.5%, #c49cde 75%, #669ae1 75%, #669ae1 87.5%, #62c2e4 87.5%, #62c2e4);
  background-image: -moz-linear-gradient(left, #c4e17f, #c4e17f 12.5%, #f7fdca 12.5%, #f7fdca 25%, #fecf71 25%, #fecf71 37.5%, #f0776c 37.5%, #f0776c 50%, #db9dbe 50%, #db9dbe 62.5%, #c49cde 62.5%, #c49cde 75%, #669ae1 75%, #669ae1 87.5%, #62c2e4 87.5%, #62c2e4);
  background-image: -o-linear-gradient(left, #c4e17f, #c4e17f 12.5%, #f7fdca 12.5%, #f7fdca 25%, #fecf71 25%, #fecf71 37.5%, #f0776c 37.5%, #f0776c 50%, #db9dbe 50%, #db9dbe 62.5%, #c49cde 62.5%, #c49cde 75%, #669ae1 75%, #669ae1 87.5%, #62c2e4 87.5%, #62c2e4);
  background-image: linear-gradient(to right, #c4e17f, #c4e17f 12.5%, #f7fdca 12.5%, #f7fdca 25%, #fecf71 25%, #fecf71 37.5%, #f0776c 37.5%, #f0776c 50%, #db9dbe 50%, #db9dbe 62.5%, #c49cde 62.5%, #c49cde 75%, #669ae1 75%, #669ae1 87.5%, #62c2e4 87.5%, #62c2e4);
}

.input-lg {
  height: 46px;
  padding: 10px 16px;
  font-size: 18px;
  line-height: 1.33;
  border-radius: 6px !important;
}

    </style>


</head>

<body>

<div class="container">

<div class="row">
    <div class="col-xs-12 col-sm-8 col-md-6 col-sm-offset-2 col-md-offset-3">

        <div class="row">
            <div class="col-md-4"><center><img src="/img/dealsonthegogologo.png" style="width:150px;"></center></div>
            <div class="col-md-8"><center><img src="/img/proxmob.jpg" style=""></center></div>
        </div>
        
        <?php

            if (null !==$this->session->flashdata('message') && $this->session->flashdata('message') !=''){?>
                <div class="alert alert-danger" role="alert"><?= "Error: ".$this->session->flashdata('message'); ?></div>
            <?php } ?>

        <form role="form" method="post" action="<?php echo base_url(); ?>proxmob/index">
            <input type="hidden" name="hiddenpromocode" id = "hiddenpromocode" value="">
            <?php if ($this->input->get_post('new')) {?>
                  <input type="hidden" name="new" value="1">
            <?php }?>

            <h3>Please enter your payment information below.</h3>
            <hr class="colorgraph">

            <?php echo validation_errors('<div class="alert alert-danger">', '</div>'); ?>

            <?php if($error!=='' && trim($error)!='Error:') { ?>
                        <div class="alert alert-danger">
                        <?php echo $error; ?>
                        </div>
                        <?php } ?>
            
            <div class="form-group"> 
      
                <div class="">
                            <table id="cart-summary" class="std table">
                                    <tbody><tr>
                                      <td>ProxMob Email Address Monthly Fee</td>
                                      <td class="price" id="membershipactivationfee">$5.00 a month</td>
                                    </tr>
                                   
                                    
                                   
                                      <td> Total Due Today </td>
                                      <td class=" site-color" id="totalduetoday">$5.00</td>
                                    </tr>
                  
                                    </tbody><tbody>
                                    </tbody>
                                  </table> 

                                  <div class="alert alert-warning" role="alert">Your CC will be charged on the 1st of every month.</div>
                                  </div>
      
    </div>


<div class="row">
            <div class="col-md-7"><center><img src="/img/authorizenet-banner.jpg" style="width:350px"></center></div>
            <div class="col-md-5"><center><img src="/img/ssl-certificates2.jpg" style="width:175px;"></center></div>
        </div>

            <!--<div class="form-group">
               <img src="http://i76.imgup.net/accepted_c22e0.png">
            </div>-->

            <div class="form-group">
                <input value="<?php echo set_value('firstname'); ?>" type="text" name="firstname" id="firstname" class="form-control input-lg" placeholder="First Name" tabindex="1">
            </div>

            <div class="form-group">
                <input value="<?php echo set_value('lastname'); ?>" type="text" name="lastname" id="lastname" class="form-control input-lg" placeholder="Last Name" tabindex="1">
            </div>

            <div class="form-group">
                <input value="<?php echo set_value('yourzipcode'); ?>" type="text" name="yourzipcode" id="yourzipcode" class="form-control input-lg" placeholder="Billing Zip Code" tabindex="1">
            </div>

             <div class="form-group">
                If you have your Locally Epic Email, enter that, otherwise enter your personal email address.
                <input value="<?php echo set_value('youremail'); ?>" type="text" name="youremail" id="youremail" class="form-control input-lg" placeholder="Your email address" tabindex="1">
            </div>



            <div class="form-group">
                <input value="<?php echo set_value('cardname'); ?>" type="text" name="cardname" id="cardname" class="form-control input-lg" placeholder="Name of Card" tabindex="1">
            </div>

            <div class="form-group">
                <input value="<?php echo set_value('cardNumber'); ?>" type="text" name="cardNumber" id="cardNumber" class="form-control input-lg" placeholder="Card Number" tabindex="2">
            </div>

            <div class="row">
                <div class="col-xs-12 col-sm-6 col-md-6">
                    <div class="form-group">
                        <select class="form-control input-lg" name="expiry_month" id="expiry_month" tabindex="3">
                            <option value="">Exp Month</option>
                            <option value="01" selected>Jan (01)</option>
                            <option value="02">Feb (02)</option>
                            <option value="03">Mar (03)</option>
                            <option value="04">Apr (04)</option>
                            <option value="05">May (05)</option>
                            <option value="06">June (06)</option>
                            <option value="07">July (07)</option>
                            <option value="08">Aug (08)</option>
                            <option value="09">Sep (09)</option>
                            <option value="10">Oct (10)</option>
                            <option value="11">Nov (11)</option>
                            <option value="12">Dec (12)</option>
                        </select>
                        
                    </div>
                </div>
                <div class="col-xs-12 col-sm-6 col-md-6">
                    <div class="form-group">
                        <select class="form-control input-lg" name="expiry_year" tabindex="4">
                            <option value="">Exp Year</option>
                            <option value="2015">2015</option>
                            <option value="2016">2016</option>
                            <option value="2017">2017</option>
                            <option value="2018">2018</option>
                            <option value="2019">2019</option>
                            <option value="2020">2020</option>
                            <option value="2021">2021</option>
                            <option value="2022">2022</option>
                            <option value="2023">2023</option>
                            <option value="2024">2024</option>
                            <option value="2025" selected>2025</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <input value="<?php echo set_value('cardCode'); ?>" type="text" name="cardCode" id="cardCode" class="form-control input-lg" placeholder="Security Code on Back of Card" tabindex="5">
            </div>
            <div class="form-group">
                <input value="<?php echo set_value('billingZip'); ?>" type="text" name="billingZip" id="billingZip" class="form-control input-lg" placeholder="Billing Zip Code" tabindex="6">
            </div>
            
            
            <div class="row">
                <div class="col-xs-4 col-sm-3 col-md-3">
                    <span class="button-checkbox">
                        <button type="button" class="btn" data-color="info" tabindex="7">I Agree</button>
                        <input type="checkbox" name="t_and_c" id="t_and_c" class="hidden" value="1">
                    </span>
                </div>
                <div class="col-xs-8 col-sm-9 col-md-9">
                     By clicking <strong class="label label-primary">Complete</strong>, you agree to the <a href="#" data-toggle="modal" data-target="#t_and_c_m">Payment Terms and Conditions</a>.
                </div>
            </div>
            
            <hr class="colorgraph">
            <div class="row">
                
                <div class="col-xs-12 col-md-12"><button type="submit" class="btn btn-success btn-block btn-lg" tabindex="8">Complete</button></div>
            </div>

<div class="row" style="margin-top:25px">
                <div class="col-xs-12 col-sm-6 col-md-6" >
                    <div class="form-group">
                       
                       <div class="AuthorizeNetSeal pull-right" style="margin-top:5px;"> <script type="text/javascript" language="javascript">var ANS_customer_id="d541395f-749f-4a9c-87a2-2c1ac9ce3fa5";</script> <script type="text/javascript" language="javascript" src="//verify.authorize.net/anetseal/seal.js" ></script> <a href="http://www.authorize.net/" id="AuthorizeNetText" target="_blank">Electronic Commerce</a> 

                
            </div>
                        
                    </div>
                </div>
                <div class="col-xs-12 col-sm-6 col-md-6">
                    <div class="form-group">
                       <a href="https://www.positivessl.com" style="font-family: arial; font-size: 10px; color: #212121; text-decoration: none;" target="_blank"><img src="https://www.positivessl.com/images-new/PositiveSSL_tl_white.png" alt="SSL Certificate" title="SSL Certificate" border="0" /></a>
                    </div>
                </div>
            </div>

            <?php /* ?>
            <div class="form-group" style="margin-top:100px"><center><h2>Powered By </h2><a href="http://www.proxmob.com/" target="_blank"><img src="/img/proxmob.jpg" style="width:200px;"></a></center></div>
            <?php */ ?>




           
        </form>
    </div>
</div>
<!-- Modal -->
<div class="modal fade" id="t_and_c_m" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">Ã—</button>
                <h4 class="modal-title" id="myModalLabel">Terms & Conditions</h4>
            </div>
            <div class="modal-body">
                <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Similique, itaque, modi, aliquam nostrum at sapiente consequuntur natus odio reiciendis perferendis rem nisi tempore possimus ipsa porro delectus quidem dolorem ad.</p>
                <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Similique, itaque, modi, aliquam nostrum at sapiente consequuntur natus odio reiciendis perferendis rem nisi tempore possimus ipsa porro delectus quidem dolorem ad.</p>
                <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Similique, itaque, modi, aliquam nostrum at sapiente consequuntur natus odio reiciendis perferendis rem nisi tempore possimus ipsa porro delectus quidem dolorem ad.</p>
                <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Similique, itaque, modi, aliquam nostrum at sapiente consequuntur natus odio reiciendis perferendis rem nisi tempore possimus ipsa porro delectus quidem dolorem ad.</p>
                <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Similique, itaque, modi, aliquam nostrum at sapiente consequuntur natus odio reiciendis perferendis rem nisi tempore possimus ipsa porro delectus quidem dolorem ad.</p>
                <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Similique, itaque, modi, aliquam nostrum at sapiente consequuntur natus odio reiciendis perferendis rem nisi tempore possimus ipsa porro delectus quidem dolorem ad.</p>
                <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Similique, itaque, modi, aliquam nostrum at sapiente consequuntur natus odio reiciendis perferendis rem nisi tempore possimus ipsa porro delectus quidem dolorem ad.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-dismiss="modal">I Agree</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
</div>



    <!-- GLOBAL SCRIPTS -->
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
    <script src="/assets/plugins/bootstrap/bootstrap.min.js"></script>
    <script src="/assets/plugins/slimscroll/jquery.slimscroll.min.js"></script>
    <!-- HISRC Retina Images -->
    <script src="/assets/plugins/hisrc/hisrc.js"></script>

    <!-- PAGE LEVEL PLUGIN SCRIPTS -->

    <!-- THEME SCRIPTS -->
    <script src="/assets/flex.js"></script>

    <script>

$(function () {

    

    $('.button-checkbox').each(function () {

        // Settings
        var $widget = $(this),
            $button = $widget.find('button'),
            $checkbox = $widget.find('input:checkbox'),
            color = $button.data('color'),
            settings = {
                on: {
                    icon: 'glyphicon glyphicon-check'
                },
                off: {
                    icon: 'glyphicon glyphicon-unchecked'
                }
            };

        // Event Handlers
        $button.on('click', function () {
            $checkbox.prop('checked', !$checkbox.is(':checked'));
            $checkbox.triggerHandler('change');
            updateDisplay();
        });
        $checkbox.on('change', function () {
            updateDisplay();
        });

        // Actions
        function updateDisplay() {
            var isChecked = $checkbox.is(':checked');

            // Set the button's state
            $button.data('state', (isChecked) ? "on" : "off");

            // Set the button's icon
            $button.find('.state-icon')
                .removeClass()
                .addClass('state-icon ' + settings[$button.data('state')].icon);

            // Update the button's color
            if (isChecked) {
                $button
                    .removeClass('btn-default')
                    .addClass('btn-' + color + ' active');
            }
            else {
                $button
                    .removeClass('btn-' + color + ' active')
                    .addClass('btn-default');
            }
        }

        // Initialization
        function init() {

            updateDisplay();

            // Inject the icon if applicable
            if ($button.find('.state-icon').length == 0) {
                $button.prepend('<i class="state-icon ' + settings[$button.data('state')].icon + '"></i>');
            }
        }
        init();
    });
});

    </script>

</body>

</html>
