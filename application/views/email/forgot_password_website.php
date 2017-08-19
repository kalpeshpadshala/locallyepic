<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>Deal On GOGO Network</title>
</head>

<body style="background:#eee; margin:0px; padding:10px; font-family:Arial, Helvetica, sans-serif; font-size:13px; color:#474653;">
   <strong>Hi <?php echo $firstname; ?>,</strong> 
                <br /><br />
                Please click on the link below to reset your password.<br />
                <br /><a href="https://<?php echo $_SERVER["SERVER_NAME"] ?>/authentication/reset?code=<?= $code?>&email=<?= $email?>">Click Here</a> to reset your password<br />
    
    <br/>
    <br/><br/><span>This request was made on <?php  echo date("D M Y j G:i:s T");?>.</span>	
</body>
</html>
