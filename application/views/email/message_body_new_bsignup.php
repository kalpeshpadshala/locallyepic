<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>Welcome to Locally Epic</title>
</head>

<body style="background:#eee; margin:0px; padding:10px; font-family:Arial, Helvetica, sans-serif; font-size:13px; color:#474653;">
    Hi admin,<br/>
    
    <p>There is new business signup</p>
    
    <p>Business Name :  <strong><?php echo $shop_name; ?></strong></p>
    <br/>
    <p>########## Business owner info ############</p>
    <br/>
    <?php
        if(!empty($first_name)){echo "<p>First name: ".$first_name."</p>";}
        if(!empty($last_name)){echo "<p>Last name: ".$last_name."</p>";}
    ?>
    <p>Business Username:  <?php echo $username; ?></p>
    <p>Business Password :  <?php echo $password; ?></p>
    <p>Business Email :  <?php echo $email; ?></p>
    <p>Business Url :  <?php echo $url; ?></p>
    
    
    <p>########## Business info ############</p>
    <br/>
    
    <p>Business Category :  <?php echo $shop_cats; ?></p>
    <p>Business Description :  <?php echo $shop_description; ?></p>
    <p>Business Address :  <?php echo $address; ?></p>
    <p>Country , state , city :  <?php echo $country_name.",".$state_name.",".$city_name; ?></p>
    <p>Business zip code :  <?php echo $zip_code; ?></p>
    <p>Business date :  <?php echo $date; ?></p>

	
</body>
</html>