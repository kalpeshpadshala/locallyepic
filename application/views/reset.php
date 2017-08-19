<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Deal On The GOGO Network</title>

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
        <br/>
        
        <center><h1>Reset Password</h1></center>
        <br/>
        <center><img src="/img/dealsonthegogologo.png" style="width:150px;"></center>

         <?php if(!empty($message)){ echo '<div class="alert alert-danger">'.$message.'</div>';} ?>
         <?php echo validation_errors('<div class="alert alert-danger">', '</div>'); ?>

        <form role="form" method="post" action="<?php echo base_url(); ?>authentication/reset">
            <input value="<?php echo $code; ?>" type="hidden" name="code" id="code">
            <input value="<?php echo $email; ?>" type="hidden" name="email" id="email">
            <hr class="colorgraph">
                      
            <div class="form-group">
                <input value="<?php echo set_value('new_password'); ?>" type="password" name="new_password" id="new_password" class="form-control input-lg" placeholder="Ente New Password" tabindex="3" required>
                <?php echo form_error('new_password'); ?>
            </div>
            
                
            <div class="form-group">
                <input value="<?php echo set_value('new_password1'); ?>" type="password" name="new_password1" id="new_password1" class="form-control input-lg" placeholder="Confirm New Password" tabindex="4" required>
                <?php echo form_error('new_password1'); ?>
            </div>
            
       
            <hr class="colorgraph">
            <div class="row">
                <div class="col-xs-12 col-md-12"><input type="submit" value="submit" class="btn btn-primary btn-block btn-lg" tabindex="6"></div>
            </div>
        </form>
    </div>
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


</body>

</html>
