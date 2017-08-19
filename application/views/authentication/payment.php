<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Locally Epic Payment Form</title>

    <!-- GLOBAL STYLES -->
    <link href="/css/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href='//fonts.googleapis.com/css?family=Ubuntu:300,400,500,700,300italic,400italic,500italic,700italic' rel="stylesheet" type="text/css">
    <link href='//fonts.googleapis.com/css?family=Open+Sans:300italic,400italic,600italic,700italic,800italic,400,300,600,700,800' rel="stylesheet" type="text/css">
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

/* SaaS Pricing Chart CSS: */
ul,ol,li{margin:0;padding:0;}
.attr-col { margin: 110px 0 0; float: left; width: 200px; }
.attr-col ul { background: #f4f4f4; font-weight: bold; font-size: 13px; border: 1px solid #d6d6d6; border-width: 1px 0px 1px 1px; -webkit-border-top-left-radius: 5px; -webkit-border-bottom-left-radius: 5px; -moz-border-radius-topleft: 5px; -moz-border-radius-bottomleft: 5px; border-top-left-radius: 5px; border-bottom-left-radius: 5px; }
.attr-col ul li { text-align: right; padding: 0 10px; border-bottom: 1px solid #d6d6d6; line-height: 45px; display: block; }
.attr-col ul li.last { border-bottom: none; }
.pt-table { padding-left: 200px; display: block; position: relative; }
.pt-body { padding: 10px 0 0; }
.pt-rows li { display: block; overflow: hidden;background: #fff; border-left: 2px solid #ccc; border-right: 2px solid #ccc; border-bottom: 1px solid #d9d9d9;  }
.pt-rows li span { width: 100%; text-align: center; float: left; border-right: 1px solid #d9d9d9; display: block; line-height: 45px; height: 45px; }
.pt-rows li.title { background: #666; font-size: 22px; color: #fff; font-weight: bold; -webkit-border-top-left-radius: 5px; -moz-border-radius-topleft: 5px; border-top-left-radius: 5px; border-bottom: 2px solid #555; border-width: 0 0 2px; }
.pt-rows li.title span { line-height: 50px; height: 50px; border: none; padding: 0 1px; text-shadow: 2px 2px #444; }
.pt-rows li.fees { border-bottom: 1px solid #ccc; }
.pt-rows li.fees span { line-height: 48px; height: 48px; background: #f7f7f7; font-size: 34px; font-weight: 700; font-family: Georgia, Arial, sans-serif; color: #4172a5; text-shadow: 2px 2px #fff; }
.pt-rows li span.pt-yes { background: url(http://demos.devgrow.com/pricechart/yes-no.gif) no-repeat center 12px; }
.pt-rows li span.pt-no { background: url(http://demos.devgrow.com/pricechart/yes-no.gif) no-repeat center -38px; }
.pt-rows li.fin { border-bottom: 2px solid #d9d9d9; -webkit-border-bottom-right-radius: 5px; -webkit-border-bottom-left-radius: 5px; -moz-border-radius-bottomright: 5px; -moz-border-radius-bottomleft: 5px; border-bottom-right-radius: 5px; border-bottom-left-radius: 5px; height: 85px; }
.pt-rows li span.pt-3x { width: 72%; float: left; text-align: center; border: none; }

/* Simple Button CSS: */
.big-button { font-size: 24px; line-height: 50px; font-weight: 700; color: #fff; padding: 10px 20px; background: #4a980d; text-shadow: 2px 2px rgba(0, 0, 0, 0.3); border-radius: 5px; -moz-border-radius: 5px; -webkit-border-radius: 5px; border: 1px solid #407718; text-decoration: none; position: relative; top: 18px; }
.big-button:hover { color: #fff; -moz-box-shadow: 0 0 20px #fffc00; -webkit-box-shadow: 0 0 20px #fffc00; box-shadow: 0 0 20px #fffc00; background: #6fbb2f; }
.big-button:active { position: relative; top: 19px; }

</style>


</head>

<body>
    <pre>
<?php
    if ($_SERVER['REMOTE_ADDR']=='192.168.50.18' || $_SERVER['REMOTE_ADDR']=='70.35.184.247'){
        print_r($this->session->all_userdata());
    }

    //We need to find out if they are expired

    $wh=array("shop_id"=>$this->session->userdata('shop_id'));
    $shop = $this->m_common->db_select("blnexpired,blnPaidActivationFee,monthlyfee", "tbl_shop", $wh, '', '', '', '', 'row_array');

    // echo '<pre>';
    // print_r($shop);
    // echo $this->db->last_query();
    // exit();



    $blnPaidActivation = $shop["blnPaidActivationFee"];
    $blnExpired = $shop["blnexpired"];
    $renewal_rate = $shop["monthlyfee"];


    if ($_SERVER['REMOTE_ADDR']=='192.168.50.18' || $_SERVER['REMOTE_ADDR']=='70.35.184.247'){
    echo "Expired: $blnExpired <br>";
    echo "Paid Activation: $blnPaidActivation <br>";
    }

    if ($blnExpired==1) {
     
        $membership_reactivation_fee = 0;
        $this->session->set_userdata('membership_reactivation_fee', $membership_reactivation_fee);
    }




?>
</pre>
<div class="container">

    <?php if ( $blnExpired==1) {?>

        <div class="alert alert-danger" role="alert" id="suspended">
           <h3> Your Locally Epic membership has expired.  Please update your payment details below to reinstate your Locally Epic membership.</h3>
        </div>

        <?php }?>

    <?php if ( isset($_GET["expired"])) {?>

        <div class="alert alert-danger" role="alert" id="suspended">
           <h3> You do not have an active membership. Some of the common reasons are: </h3><br>
                <h4><ul>
                    <li style="margin-bottom:5px;">You did not complete the payment process of the signup<br></li>
                    <li style="margin-bottom:5px;">Your Locally Epic acount was set up for you automatically through a professional organization</li>
                    <li style="margin-bottom:5px;">There was an issue processing your most recent payment</li>
                </ul></h4>
               <h4> If you have any questions please reach out to <a href="mailto: info@locallyepic.com">info@locallyepic.com</a></h4>
        </div>

        <?php }?>

<div class="row">
    <div class="col-xs-12 col-sm-8 col-md-6 col-sm-offset-2 col-md-offset-3">

<br><br><br>
    	<center><img src="/img/logo-black-trans.png"></center>
    	<br><br>

        <?php

            if (null !==$this->session->flashdata('message') && $this->session->flashdata('message') !=''){?>
                <div class="alert alert-danger" role="alert"><?= "Error: ".$this->session->flashdata('message'); ?></div>
            <?php } 

            if (null !==$error_msg && $error_msg!=''){?>
                <div class="alert alert-danger" role="alert"><?= "Error: ".$error_msg; ?></div>
            <?php }
        ?>

        <form id="paymentform" role="form" method="post" action="<?php echo base_url(); ?>authentication/<?php if ($blnExpired==0){ echo 'payment'; } else {echo 'reactivate';} ?>">
            <input type="hidden" name="hiddenpromocode1" id="hiddenpromocode1" value="<?php echo set_value('hiddenpromocode1',''); ?>">
            <input type="hidden" name="hiddenpromocode2" id="hiddenpromocode2" value="<?php echo set_value('hiddenpromocode2',''); ?>">
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
                                    <tbody>

                                    <tr>
                                        <td>Packages</td>
                                        <td> 
                                            <label class="radio-inline">
                                                <input type="radio" name="packageradio" value='1' <?php echo  set_radio('packageradio', '1'); ?> > 
                                                <span data-toggle="modal" data-target="#package_basic"> Basic </span>
                                            </label>
                                            <label class="radio-inline">
                                              <!-- <input type="radio" name="packageradio" value='2' checked="checked">Standard -->
                                              <input type="radio" name="packageradio" value='2' <?php echo  set_radio('packageradio', '2'); ?>> 
                                              <span data-toggle="modal" data-target="#package_standard" > Standard </span>
                                            </label>
                                            <label class="radio-inline">
                                              <input type="radio" name="packageradio" value='3' <?php echo  set_radio('packageradio', '3'); ?>> 
                                              <span data-toggle="modal" data-target="#package_professional" > Professional </span>
                                            </label>

                                            <span class='error_packageradio'></span>
                                        </td>
                                    </tr>


                                    <?php if ($blnExpired==0){?>
                                        <tr>
                                            <td>Membership Activation Fee</td>
                                            
                                            <td class="price">
                                                <div class="row">
                                                    <div class="col-xs-2">
                                                        <span class="success"
                                                              id="membershipactivationfee">$<?php echo $membership_activation_fee; ?></span>
                                                    </div>
                                                    <div class="col-xs-7">
                                                        <input type="text" name="promocodemaf" value="<?php echo set_value('hiddenpromocode1'); ?>" id="promocodemaf" class="form-control input-sm " placeholder="Enter Promocode" tabindex="3">
                                                        <span class='error_promocodemaf'></span>
                                                    </div>
                                                    <div class="col-xs-2">
                                                        <button id="applypromocodemaf" class="btn btn-default btn-sm ladda-button" data-style="expand-left">
                                                            <span class="ladda-label">Apply</span>
                                                        </button>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php } ?>


                                    <?php if ($blnExpired==1){?>

                                         <tr>
                                            <td>Membership Reactivation Fee <br>(Prorated for current month)</td>
                                            <td class="price" id="membershipactivationfee">$<?php echo $membership_reactivation_fee; ?></td>
                                        </tr>

                                    <?php } ?>





                                    <tr style="">
                                        <td>Monthly Network Fee</td>
                                        <?php if ($blnExpired==0){?>

                                            <td class="price"><span class="success" id="monthlynetworkfee">$0</span></td>
                                        
                                        <?php } else{?>
                                            <td class="price">
                                                <div class="row">
                                                    <div class="col-xs-2">
                                                        <span class="success"
                                                              id="monthlynetworkfee">$0</span>
                                                    </div>
                                                    <div class="col-xs-7">
                                                        <input type="text" name="promocodemnf" value="" id="promocodemnf" class="form-control input-sm " placeholder="Enter Promocode" tabindex="3">
                                                        <span class='error_promocodemnf'></span>
                                                    </div>
                                                    <div class="col-xs-2">
                                                        <button id="applypromocodemnf" class="btn btn-default btn-sm ladda-button" data-style="expand-left">
                                                            <span class="ladda-label">Apply</span>
                                                        </button>
                                                    </div>
                                                </div>
                                            </td>
                                        <?php }?>


                                    </tr>


                                   <!--  <tr class="cart-total-price ">
                                        <td>Promocode</td>
                                        <td class="price">
                                            <div class="row">
                                                <div class="col-xs-8">
                                                    <input type="text" name="promocode" id="promocode" class="form-control input-sm " placeholder="Enter Promocode" tabindex="3">
                                                </div>
                                                    <div class="col-xs-3">

                                                    <button id="applypromocode" class="btn btn-default btn-sm ladda-button" data-style="expand-left"><span class="ladda-label">Apply</span></button>
                                                </div>
                                                <span class='error_promocode'></span>
                                            </div>
                                        </td>
                                    </tr> -->


                                    <tr>
                                      <td> Total Due Today </td>
                                        <td class=" site-color" id="totalduetoday">$
                                            <?php 
                                            if ($blnExpired==0)
                                            { 
                                                echo $amount; 
                                            }else {
                                                echo $membership_reactivation_fee;
                                            } ?>
                                        </td>
                                    </tr>


                                    </tbody><tbody>
                                    </tbody>
                                  </table>

                                  <div class="alert alert-warning" role="alert" id="nextbillblurb">Your Locally Epic monthly network fee will <?php if ($blnExpired==0){ echo 'start'; } else {echo 'resume';} ?> on <?= $date ?> and will recur
on the first of every month thereafter.</div>
                                  </div>

    </div>


<?php

if ($_SERVER["SERVER_NAME"]=='dev.dealsonthegogo.com' || $_SERVER["SERVER_NAME"]=='test.dealsonthegogo.com'  || $_SERVER["SERVER_NAME"]=='randy.dealsonthegogo.com' || $_SERVER["SERVER_NAME"]=='localhost'){
    $testcardname="John Doe";
    $testcardnumber="4111111111111111";
    $testcardcode="123";
    $testbillingzip="28470";
} else {
    $testcardname="";
    $testcardnumber="";
    $testcardcode="";
    $testbillingzip="";
}

?>

        <div class="row">
            <div class="col-md-7"><center><img src="/img/authorizenet-banner.jpg" style="width:350px"></center></div>
            <div class="col-md-5"><center><img src="/img/ssl-certificates2.jpg" style="width:175px;"></center></div>
        </div>

          
            <div class="form-group" style="margin-top:20px; margin-bottom:58px;">
              <label class="col-md-4 control-label" for="payment_method">Payment Detail</label>
              <div class="col-md-8">
                  <input type="hidden" name="payment_method" id="radios-0" value="Credit Card">
              </div>
            </div>


            <div id="paybycc" >
                <div class="form-group">
                    <input value="<?php echo set_value('cardname', $testcardname); ?>" type="text" name="cardname" id="cardname" class="form-control input-lg" placeholder="Name of Card" tabindex="1">
                </div>

                <div class="form-group">
                    <input value="<?php echo set_value('cardNumber', $testcardnumber); ?>" type="text" name="cardNumber" id="cardNumber" class="form-control input-lg" placeholder="Card Number" tabindex="2">
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
                    <input value="<?php echo set_value('cardCode', $testcardcode); ?>" type="text" name="cardCode" id="cardCode" class="form-control input-lg" placeholder="Security Code on Back of Card" tabindex="5">
                </div>
                <div class="form-group">
                    <input value="<?php echo set_value('billingZip', $testbillingzip); ?>" type="text" name="billingZip" id="billingZip" class="form-control input-lg" placeholder="Billing Zip Code" tabindex="6">
                </div>
            </div>




            <div class="row">
                <div class="col-xs-4 col-sm-3 col-md-3">
                    <span class="button-checkbox">
                        <button type="button" class="btn" data-color="info" tabindex="7">I Agree</button>
                        <input type="checkbox" name="t_and_c" id="t_and_c" class="hidden" value="0">
                    </span>
                </div>
                <div class="col-xs-8 col-sm-9 col-md-9">
                     By clicking <strong class="label label-primary">Complete</strong>, you agree to the <a href="#" data-toggle="modal" data-target="#t_and_c_m">Payment Terms and Conditions</a>.
                </div>
                <span class='error_t_and_c'></span>

            </div>

            <hr class="colorgraph">
            <div class="row">
                <div class="col-xs-12 col-md-12"><button type="submit" class="btn btn-success btn-block btn-lg" tabindex="8">Complete</button></div>
            </div>

            <div class="row" style="margin-top:25px">
                <div class="col-xs-12 col-sm-6 col-md-6" >
                    <div class="form-group">
                       <div class="AuthorizeNetSeal pull-right" style="margin-top:5px;"> 
                         <a href="http://www.authorize.net/" id="AuthorizeNetText" target="_blank">Electronic Commerce</a>
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
<div class="modal fade" id="package_standard" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xs">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title" id="myModalLabel">Package : Standard</h4>
            </div>
            <div class="modal-body">
                
                <div class="price-chart">
                    <div class="attr-col">
                        <ul>
                            <li>Campaigns Per Day</li>
                            <li>Offers Sent</li>
                            <li>Campaign Analytics</li>
                            <li>Customer Loyalty</li>
                            <li>Business Categories</li>
                        </ul>
                    </div>
                    <div class="pt-table">  
                        <div class="pt-body">
                            <ul class="pt-rows">
                                <li class="title"><span>Standard</span></li>
                                <li class="fees"><span>$<b>199</b>/<jack style="font-size: 15px;">Monthly</jack></span></li>
                                <li><span><b>3 Per Day</b> (1 Per Time Frame)</span></li>
                                <li><span><b>1000</b> Per Campaign</span></li>
                                <li><span class="pt-yes"></span></li>
                                <li><span class="pt-yes"></span></li>
                                <li><span><b>1</b> Per Business</span></li>
                            </ul>
                        </div>
                    </div>
                </div>

            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->


<!-- Modal -->
<div class="modal fade" id="package_professional" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xs">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title" id="myModalLabel">Package</h4>
            </div>
            <div class="modal-body">
               
                <div class="price-chart">
                    <div class="attr-col">
                        <ul>
                            <li>Campaigns Per Day</li>
                            <li>Offers Sent</li>
                            <li>Campaign Analytics</li>
                            <li>Customer Loyalty</li>
                            <li>Business Categories</li>
                        </ul>
                    </div>
                    <div class="pt-table">  
                        <div class="pt-body">
                            <ul class="pt-rows">
                                <li class="title"><span>Professional</span></li>
                                <li class="fees"><span>$<b>249</b>/<jack style="font-size: 15px;">Monthly</jack></span></li>
                                <li><span><b>Unlimited Per Day</b> (1 Per Time Frame)</span></li>
                                <li><span><b>5000</b> Per Campaign</span></li>
                                <li><span class="pt-yes"></span></li>
                                <li><span class="pt-yes"></span></li>
                                <li><span><b>2</b> Per Business</span></li>
                            </ul>
                        </div>
                    </div>
                </div>

            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->


<!-- Modal -->
<div class="modal fade" id="package_basic" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xs">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title" id="myModalLabel">Package</h4>
            </div>
            <div class="modal-body">

                <div class="price-chart">
                    <div class="attr-col">
                        <ul>
                            <li>Campaigns Per Day</li>
                            <li>Offers Sent</li>
                            <li>Campaign Analytics</li>
                            <li>Customer Loyalty</li>
                            <li>Business Categories</li>
                        </ul>
                    </div>
                    <div class="pt-table">  
                        <div class="pt-body">
                            <ul class="pt-rows">
                                <li class="title"><span>Basic</span></li>
                                <li class="fees"><span>$<b>149</b>/<jack style="font-size: 15px;">Monthly</jack></span></li>
                                <li><span><b>2 Per Day</b> (1 Per Time Frame)</span></li>
                                <li><span><b>500</b> Per Campaign</span></li>
                                <li><span class="pt-no"></span></li>
                                <li><span class="pt-no"></span></li>
                                <li><span><b>1</b> Per Business</span></li>
                            </ul>
                        </div>
                    </div>
                </div>
        
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->


<!-- Modal -->
<div class="modal fade" id="t_and_c_m" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title" id="myModalLabel">Terms & Conditions</h4>
            </div>
            <div class="modal-body">
                <p><b>AG SOLOMO INC and Locally Epic</b></p>
                <p><b>TERMS AND CONDITIONS FOR RECURRING CHARGES OR DEBITS:</b></p>
                <p>I hereby authorize TAG SOLOMO INC and or Locally Epic to initiate a charge or debit entry on my credit card, debit card or deposit account (as applicable) for the total amount due each month for the services provided to me by TAG SOLOMO INC and or Locally Epic. I acknowledge that the origination of a charge or debit entry to my account must comply with the provisions of U.S. law. This authorization will remain in full force and effect until the date upon which TAG SOLOMO INC and or Locally Epic has received an email notification from me to dotgogopayments@gmail.com of my election to terminate this recurring payment program .All recurring payment will be debited on the first of each month unless an email notification is received by the 25th of the month before billing is to take place.</p>


                <p>I agree to maintain balances sufficient to pay all requested payments, and agree that TAG SOLOMO INC and or Locally Epic is not liable for any overdraft or insufficient fund situation or charge (including, but not limited to, finance charges, late fees or similar charges) caused by my failure to maintain funds sufficient to pay all payments issued through this recurring payment program. I further agree that TAG SOLOMO INC and or Locally Epic may charge a $25.00 service fee for any charge or debit transactions that result in a returned debit entry, including, but not limited to, returns resulting from insufficient funds in my account, closure of my account or incorrect account or routing information provided by me. I agree to promptly notify TAG SOLOMO INC and or Locally Epic in writing of any changes to the financial institution account information and hereby grant authority for TAG SOLOMO INC and or Locally Epic to charge or debit such changed account. I agree that TAG SOLOMO INC and or Locally Epic will not be responsible for any expense that I may incur from exceeding my credit limit or overdraft of my account as a result of a charge or debit made pursuant to this recurring payment program. I understand that I should allow up to 30 days after enrollment for automatic payments to begin and that I should continue to pay my TAG SOLOMO INC and or Locally Epic bill as usual until my bill statement includes a statement indicating that automatic bill payment has commenced. </p>

                <p><b>TERMS AND CONDITIONS FOR ONE-TIME CHARGE OR DEBIT:</b></p>
                <p>I hereby authorize TAG SOLOMO INC and or Locally Epic to initiate a charge or debit entry on my credit card, debit card or deposit account (as applicable) in an amount equal to the currently outstanding balance of my TAG SOLOMO INC and or Locally Epic customer account. I acknowledge that the origination of a charge or debit entry to my account must comply with the provisions of U.S. law. I agree to maintain balances sufficient to pay all requested payments, and agree that TAG SOLOMO INC and or Locally Epic is not liable for any overdraft or insufficient fund situation or charge (including, but not limited to, finance charges, late fees or similar charges) caused by my failure to maintain funds sufficient to pay all payments issued through this recurring payment program. I further agree that TAG SOLOMO INC and or Locally Epic may charge a $25.00 service fee for any charge or debit transactions that result in a returned debit entry, including, but not limited to, returns resulting from insufficient funds in my account, closure of my account or incorrect account or routing information provided by me. I agree to promptly notify TAG SOLOMO INC and or Locally Epic in writing of any changes to the financial institution account information and hereby grant authority for TAG SOLOMO INC and or Locally Epic to charge or debit such changed account. I agree that TAG SOLOMO INC and or Locally Epic will not be responsible for any expense that I may incur from exceeding my credit limit or overdraft of my account as a result of a charge or debit made pursuant to this recurring payment program. I understand that I should allow up to 30 days after enrollment for automatic payments to begin and that I should continue to pay my TAG SOLOMO INC and or Locally Epic bill as usual until my bill statement includes a statement indicating that automatic bill payment has commenced. </p>

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



   <?php if ($blnExpired==0){?>

    $("#applypromocodemaf").click(function(e){

        $("body").on('keyup','#membershipactivationfee',function(){

            $("#totalduetoday").html("$"+$('#membershipactivationfee1').val());

        });

        e.preventDefault();
            
        var selected_package_type = $('input[name="packageradio"]:checked').val();
        
        if( selected_package_type == undefined ){
            $(".error_packageradio").html("<br>Please Select Package").css('color','red');
            return false;
        }



        var promocode = $("#promocodemaf").val();
        promocode = promocode.trim();
        
        if( null == promocode || promocode =="" ){
            $(".error_promocodemaf").html("Please Enter Promocode").css('color','red');
            return false;
        }else{
            $(".error_promocodemaf").html("");
        }

        var l = Ladda.create(this);
        l.start();


        if (promocode !==''){
            //console.log(promocode);

            var jqxhr = $.ajax( {url : "/authentication/promocode", method:"post",dataType: 'json', data:{promocode:promocode}} )
                  .done(function(data) {
                    //console.log(data.result);
                    //console.log(data.result.length);
                    if (data.result.length == 0) {
                        $(".error_promocodemaf").html("Invalid Promocode").css('color','red');
                    } else {
                        var d = data.result[0];

                        if (d.promocode_type=='activation fee'){
                            
                            $("#hiddenpromocode1").val(promocode);
                            $("#membershipactivationfee").html("$"+data.membership_activation_fee);
                            $("#totalduetoday").html("$"+data.total_due_today);
                        }else{
                            $("#membershipactivationfee").html("$"+data.membership_activation_fee);
                            $("#totalduetoday").html("$"+data.total_due_today);
                            $(".error_promocodemaf").html("Invalid Promocode").css('color','red');
                        }
                    }


                    //console.log( "success" );
                  })
                  .fail(function() {
                    //console.log( "error" );
                  })
                  .always(function() {
                    //console.log( "complete" );
                     l.stop();
                  });
        }

    });
    <?php } ?>




    <?php if ($blnExpired==1){?>

    $("#applypromocodemnf").click(function(e){

        $("body").on('keyup','#membershipactivationfee',function(){

            $("#totalduetoday").html("$"+$('#membershipactivationfee1').val());

        });

        e.preventDefault();
            
        var selected_package_type = $('input[name="packageradio"]:checked').val();
        
        if( selected_package_type == undefined ){
            $(".error_packageradio").html("<br>Please Select Package").css('color','red');
            return false;
        }



        var promocode = $("#promocodemnf").val();
        promocode = promocode.trim();
        
        if( null == promocode || promocode =="" ){
            $(".error_promocodemnf").html("Please Enter Promocode").css('color','red');
            return false;
        }else{
            $(".error_promocodemnf").html("");
        }

        var l = Ladda.create(this);
        l.start();


        if (promocode !==''){
            //console.log(promocode);

            var jqxhr = $.ajax( {url : "/authentication/promocode", method:"post",dataType: 'json', data:{promocode:promocode,plan_type:selected_package_type}} )
                  .done(function(data) {
                    //console.log(data.result);
                    //console.log(data.result.length);
                    if (data.result.length == 0) {
                        $(".error_promocodemnf").html("Invalid Promocode").css('color','red');
                        $("#monthlynetworkfee").html("$"+data.monthly_network_fee);
                        $("#totalduetoday").html("$"+data.monthly_network_fee);
                    } else {
                        var d = data.result[0];

                        if (d.promocode_type=='monthly fee'){
                            
                            $("#hiddenpromocode2").val(promocode);
                            $("#monthlynetworkfee").html("$"+data.monthly_network_fee);
                            $("#totalduetoday").html("$"+data.monthly_network_fee);
                        }else{
                            $("#monthlynetworkfee").html("$"+data.monthly_network_fee);
                            $("#totalduetoday").html("$"+data.monthly_network_fee);
                            $(".error_promocodemnf").html("Invalid Promocode").css('color','red');
                        }
                    }


                    //console.log( "success" );
                  })
                  .fail(function() {
                    //console.log( "error" );
                  })
                  .always(function() {
                    //console.log( "complete" );
                     l.stop();
                  });
        }

    });
    <?php } ?>



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

                $('#t_and_c').val('1');

                $button
                    .removeClass('btn-default')
                    .addClass('btn-' + color + ' active');
            }
            else {

                $('#t_and_c').val('0');

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

    

var blnExpired = '<?php echo $blnExpired;?>';

// ************************************************************************************************
// ************************************************************************************************
    $('input[name="packageradio"]').on('change', function(e) {

        var package_type = $(this).val();
        $(".error_packageradio").html('');

        if( package_type == 1 ){

            $('#monthlynetworkfee').text('$149');
            if(blnExpired == '1'){ $('#totalduetoday').text('$149'); }

        }else if ( package_type == 2 ) {

            $('#monthlynetworkfee').text('$199');
            if(blnExpired == '1'){ $('#totalduetoday').text('$199'); }

        }else if ( package_type == 3 ) {

            $('#monthlynetworkfee').text('$249');
            if(blnExpired == '1'){ $('#totalduetoday').text('$249'); }

        };

    });

    

    var selected_package_type = $('input[name="packageradio"]:checked').val();
    if( selected_package_type == 1 ){

            $('#monthlynetworkfee').text('$149');
            if(blnExpired == '1'){ $('#totalduetoday').text('$149'); }

    }else if ( selected_package_type == 2 ) {

        $('#monthlynetworkfee').text('$199');
        if(blnExpired == '1'){ $('#totalduetoday').text('$199'); }

    }else if ( selected_package_type == 3 ) {

        $('#monthlynetworkfee').text('$249');
        if(blnExpired == '1'){ $('#totalduetoday').text('$249'); }

    };


    $("#paymentform").submit(function(){

        var isChecked = $("input[name='packageradio']").is(":checked");
        var termandcondition = $('#t_and_c').val();

        if (isChecked===false) {
            $(".error_packageradio").html("<br>Please Select Package").css('color','red');
            $('input[name="packageradio"]').focus();
            return false;
        }
        else if(termandcondition == 0){
            $(".error_t_and_c").html("&nbsp;&nbsp;&nbsp;&nbsp;you agree to the Payment Terms and Conditions? ").css('color','red');
            return false;
        }

    });


    
    </script>

</body>

</html>
