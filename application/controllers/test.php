<?php
//test
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

define("RANDY_DEBUG", false);
define("APNS_DEVELOPMENT", "sandbox");
define("APNS_CERT_DEV", "ck1.pem");
define("APNS_CERT_LIVE", "ck1.pem");
//define("ANDROID_APIKEY", "AIzaSyAHecYTzBl4YLMUDDRwn-KLEMP5NKAH6Ic");
define("ANDROID_APIKEY", "AIzaSyDnCJcCcFoLFjE6pLExHtA_nt398_nhkII");

class Test extends CI_Controller {

    function Test() {
        parent::__construct();
        $this->load->model('m_common');
        $this->load->model('business');
        $this->load->model('loyaltyprogram');
        $this->load->helper('date');
        $this->load->model('appuser');


        $this->result = array();
        $this->msg = '';
        $this->width=isset($this->postvars['width']) ? $this->postvars['width'] : 600;
        $this->is_debug=isset($this->postvars['is_debug']) ? $this->postvars['is_debug'] : 0;
        //header('Content-type: application/json');
        date_default_timezone_set('America/New_York');
      
    }

    public function tester() {
       
       //$gmt = local_to_gmt();

       //$human = unix_to_human("07/02/2015 8:00pm UC5");

       //echo convert_to_gmt()


       exit;
    }
    // $this->apilog(__FUNCTION__, $user_id, $_POST, $data);

    private function convert_to_gmt($time = '', $timezone = 'UTC', $dst = FALSE)
{            
    if ($time == '')
    {
        return now();
    }

    $time -= timezones($timezone) * 3600;

    if ($dst == TRUE)
    {
       $time -= 3600;
    }

    return $time;
} 
    
function convert_to_utc($date, $time, $tz){

    $standard_timezone = standard_timezone($tz);

    $timestamp =  strtotime("$date $time");

    $system_timezone = new DateTimeZone('UTC'); // your timezone
    $user_timezone = new DateTimeZone($standard_timezone); // your user's timezone
    $now = new DateTime(date('Y-m-d H:i:s',$timestamp), $user_timezone);

    $now->setTimezone($system_timezone);

    $utc_datetime = $now->format('Y-m-d H:i:s');


    return array(
            "user_date"=>$date,
            "user_time"=>$time,
            "utc_full"=>$utc_datetime,
            "utc_date"=>$now->format('Y-m-d'),
            "utc_time"=>$now->format('H:i A'),
            "user_timezone"=>$tz,
            "user_standard_timezone"=>$standard_timezone
        );


}

function s2t(){

echo secondsToTime($_GET["s"]);
exit;

}

function test2(){

    echo "<pre>";

    $da = $this->convert_to_utc("07/02/15","9:06 PM","UM5");
    print_r($da);
    exit;

}

function test3(){

    echo "<pre>";

    $da = utc_to_local("07/03/15","00:00","UM5");
    print_r($da);
    exit;

}



function test7(){

    $date = "2015-07-06";
    $time = "9:00 PM";
    $timezone = "EST";

    $corrected_timezone = convert_app_timezone($timezone);

    $converted_start_date = convert_to_utc($date,$time,$corrected_timezone);

    print_rr($converted_start_date);


}

function test4(){

    $dt = "2015-07-04 3:58 PM GMT-04:00";

    $dt = "2015-07-04 10:01 PM CST";





    $now =  strtotime("now");

    $dtc = strtotime($dt);




    echo  date('I',$dtc)."<br>";
    echo  date('l jS \of F Y h:i:s A',$dtc)."<br>";

     $gmt = local_to_gmt($dtc);
     echo "<br>".$gmt;
     echo "<br>".unix_to_human($gmt);

}

function test5(){

    phpinfo();

}


function test6(){


$config['image_library'] = 'gd2';
$config['source_image'] = '/var/www/html/uploads/332_1429623048_63Yg3.jpg';
$config['create_thumb'] = FALSE;
$config['maintain_ratio'] = FALSE;
$config['width']    = 75;
$config['height']   = 75;
$config['new_image'] = '/var/www/html/uploads/thumbs/332_1429623048_63Yg3.jpg';

$this->load->library('image_lib', $config); 

$this->image_lib->resize();




}


function test1(){

    $st =  standard_timezone('UM5');

    $d = "07/02/15";
    $t = "9:06 PM";

    $ts =  strtotime("$d $t");

    echo $ts."<br>";



    echo $st."<br>";

    $system_timezone = new DateTimeZone('UTC'); // your timezone
    $user_timezone = new DateTimeZone($st); // your user's timezone

    $now = new DateTime(date('Y-m-d H:i:s',$ts), $user_timezone);

    echo "My Timezone: ".$now->format('Y-m-d H:i:s')."<br>";
    $now->setTimezone($system_timezone);

    echo "UTC: ".$now->format('Y-m-d H:i:s'); // you can see the date and time of the desired timezone

}


function test8(){

$date1="2015-07-09";
$time1="7:44 PM";

$date2="2015-07-09";
$time2="9:44 PM";





$diff = abs(strtotime("$date1 $time1") - strtotime("$date2 $time2"));

echo "<br>Diff:".$diff;
echo "<br>New Time2:".($diff+85440);

$diff = strtotime('2009-10-05 18:11:08') - strtotime('2009-10-05 18:07:13');

echo "<br>$diff";
exit;



}


function encryptusers(){

    exit;

    

    $sql = "select user_id, user_token, password from tbl_customer where password!=''";
    $result = $this->db->query($sql);

    foreach ($result->result_array() as $row)
        {
           $user_id =  $row['user_id'];
           $user_token =  $row['user_token'];
           $password = trim($row['password']);
           
           $this->appuser->update_password($user_id, $user_token, $password);
        }





}


function test9(){

    echo "Deal Start:".date('m/d/Y H:i:s', 1450621980)."<br>";
    echo "Prateek:".date('m/d/Y H:i:s', 1450606680)."<br>";
    echo "Prateek (Added 12 Hours to make it afternoon)):".date('m/d/Y H:i:s', 1450606680+(12*60*60))."<br>";
    echo "<br><br>(".(1450606680+(12*60*60)).")";


    $tz  = 'UM5';
 
    print_rr(utc_to_local('2015-12-20', 1450606680, $tz),0);

    print_rr(convert_to_utc("2015-12-20","12:38 PM","UM5"),0);

    print_rr(convert_to_utc("2015-12-20","11:38 PM","UP55"),0);

    $date = new DateTime();
echo $date->getTimestamp();


}

}

