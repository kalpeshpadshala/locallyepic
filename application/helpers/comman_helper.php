<?php

function print_rr($v,$abort=1){

    echo "<pre>";
    print_r($v);
    echo "</pre>";

    if ($abort==1) {
        exit;
    }
}

function get_role_name($r){
$ret="";
    switch ($r) {
        case '1':
            $ret="Super Admin";
            
            break;
        case '2':

            $ret="National Sales Manager";
            break;
        case '3':
            $ret="State Sales Manager";

            break;
        case '4':

            $ret="Area Sales Manager";
            break;
        case '5':

            $ret="Sales People";
            break;
        case '6':
            $ret="Business User";

            break;
        case '7':
            $ret="HR";

            break;
        case '8':
            $ret="Ambassador";

            break;
        case '9':
            $ret="Corporate";
            

            break;

        default:
            break;
    }
    return $ret;
}

function is_permission($role , $cate){
    
    $permission_arr = array(
        "site"=>array(1,2,3,4,5,6,7,8,9),
        "index"=>array(1,2,3,4,5,6,7,8,9),
        "add_sales_manager"=>array(1),
        "list_sales_manager"=>array(1),
        "edit_sales_manager"=>array(1),
        "add_sales_people"=>array(1,2,3,4),
        "list_sales_people"=>array(1,2,3,4),
        "edit_sales_people"=>array(1,2,3,4),
        "manage_category"=>array(1),
        "list_category"=>array(1),
        "manage_shop"=>array(1,6,9),
        "create_deal"=>array(1,6,9),
        "change_profile"=>array(1,2,3,4,5,6,7,8,9),
        "change_password"=>array(2,3,4,5,6,7,8,9),
        "Schedule"=>array(1,2,3,4,5,9),
        "edit_schedule"=>array(1,2,3,4,5,9),
        "edit_category"=>array(1),
        "edit_deal"=>array(1,6),
        "edit_shop"=>array(1,6,9),
        "manage_deal"=>array(1,2,3,4,5,6,8,9),
        "list_shop"=>array(1,2,3,4,5,6,8,9),
        "user_route"=>array(1),
        "select_shop"=>array(1,2,3,4,5,6,8,9),
        "statistics"=>array(1,2,3,4,5,6,8,9),
        "logout"=>array(1,2,3,4,5,6,7,8,9),
        "sm_username_unique"=>array(1,2,3,4,5,6,7,8,9),
        "sm_email_unique"=>array(1,2,3,4,5,6,7,8,9),
        "shop_username_unique"=>array(1,2,3,4,5,6,7,8,9),
        "shop_name_unique"=>array(1,2,3,4,5,6,7,8,9),
        "confirm_password"=>array(1,2,3,4,5,6,7,8,9),
        "cat_distance"=>array(1,2,3,4,5,6,7,8,9),
        "deal_enddate"=>array(1,2,3,4,5,6,7,8,9),
        "old_password_match"=>array(1,2,3,4,5,6,7,8,9),
        "shop_email"=>array(1,2,3,4,5,6,7,8,9),
        "get_country_name"=>array(1,2,3,4,5,6,7,8,9),
        "get_state_name"=>array(1,2,3,4,5,6,7,8,9),
        "get_city_name"=>array(1,2,3,4,5,6,7,8,9),
        "delete_deal"=>array(1,2,3,4,5,6,7,8,9),
        "delete_cat"=>array(1,2,3,4,5,6,7,8,9),
        "delete_shop"=>array(1,2,3,4,5,6,7,8,9),
        "delete_sales_manager"=>array(1,2,3,4,5,6,7,8,9),
        "delete_schedule"=>array(1,2,3,4,5,6,7,8,9),
        "loadData"=>array(1,2,3,4,5,6,7,8,9),
        "add_promocode"=>array(1),
        "list_promocode"=>array(1),
        "edit_promocode"=>array(1),
        "add_ambassador"=>array(1,2,3,4,5,6,7,8),
        "list_ambassador"=>array(1,2,3,4,5,6,7,8),
        "edit_ambassador"=>array(1,2,3,4,5,6,7,8),
        "list_consumers"=>array(1),
        "add_corporate"=>array(1),
        "list_corporate"=>array(1),
        "edit_corporate"=>array(),
        "list_business"=>array(9,1),
        "add_business"=>array(9),
        "edit_business"=>array(1,9),
        "add_user"=>array(9),
        "list_user"=>array(9),
        "edit_user"=>array(9),
        "view_corporate_shop"=>array(1),
        "edit_corporate_user" => array(1),
        "message_consumer" => array(1,6,9,5),
    );
    if (in_array($role, $permission_arr[$cate]))
        {
            return TRUE;
        }
      else
        {
            return FALSE;
        }
    
}

function get_dayname($s){
    switch ($s) {
        case "7":
            return "Sunday";
            break;
        case "1":
            return "Monday";
            break;
        case "2":
            return "Tuesday";
            break;
        case "3":
            return "Wednesday";
            break;
        case "4":
            return "Thursday";
            break;
        case "5":
            return "Friday";
            break;
        case "6":
            return "Saturday";
            break;
   

        default:
             return "";
            break;
    }
}
function secondsToTime($seconds)
{
    if($seconds==86400){
        return "12:00 PM";
    }
    return gmdate ('h:i A', $seconds);
    
//    return date("h:i:s A",$seconds);
//    exit;
    // extract hours
    $hours = floor($seconds / (60 * 60));
 
    // extract minutes
    $divisor_for_minutes = $seconds % (60 * 60);
    $minutes = floor($divisor_for_minutes / 60);
 
    // extract the remaining seconds
    $divisor_for_seconds = $divisor_for_minutes % 60;
    $seconds = ceil($divisor_for_seconds);
    $sff="AM";
    if($hours>12){
        $hours=$hours-12;
        $sff="PM";
    }
    if($hours==12){
        $sff="PM";
    }
    $minutes=(int) $minutes;
    if($minutes==0){
        $minutes='00';
    }
    // return the final array
    $obj = array(
        "h" => (int) $hours,
        "m" => $minutes,
        "s" => (int) $seconds,
        "suff" => $sff,
    );
    //echo "<pre>";print_r($obj);exit;
    return $obj;
}

function user_logout(){
    
    $CI = & get_instance();
    //echo "in";exit;
    $CI->session->sess_destroy();
}
 function is_image_path_proper($str) {

        if(strpos($str,".")>0){
            return 1;
        }else{
            return 0;
        }
    }
 function get_deal_image_src($dp,$sp="") {

        if(is_image_path_proper($dp)){
            return base_url()."uploads/".$dp;
        }else if(is_image_path_proper($sp)){
            return base_url()."uploads/user/".$sp;
        }else{
            return base_url()."images/no_image.png";
        }
    }


 function get_country_name($id){
     $r="--";
     $CI =& get_instance();
     $CI->load->model('m_common');
     if($id>0){
         $arr = $CI->m_common->db_select("name", "tbl_country",array("id"=>$id), array(), '', '', array(1,0), 'row_array');
         if($arr){
             $r=$arr['name'];
         }
     }
    return $r;
 }   
 function get_state_name($id){
     $r="--";
     $CI =& get_instance();
     $CI->load->model('m_common');
     if($id>0){
         $arr = $CI->m_common->db_select("state_name", "tbl_state",array("sid"=>$id), array(), '', '', array(1,0), 'row_array');
         if($arr){
             $r=$arr['state_name'];
         }
     }
    return $r;
 }   
 function get_city_name($id){
     $r="--";
     $CI =& get_instance();
     $CI->load->model('m_common');
     if($id>0){
         $arr = $CI->m_common->db_select("city_name", "tbl_city",array("city_id"=>$id), array(), '', '', array(1,0), 'row_array');
         if($arr){
             $r=$arr['city_name'];
         }
     }
    return $r;
 }   

if (! function_exists('standard_timezone')) {
    /**
     * Convert CodeIgniter's time zone strings to standard PHP time zone strings.
     *
     * @param string $ciTimezone A time zone string generated by CodeIgniter.
     *
     * @return string    A PHP time zone string.
     */
    function standard_timezone($ciTimezone)
    {
        switch ($ciTimezone) {
            case 'UM12':
                return 'Pacific/Kwajalein';
            case 'UM11':
                return 'Pacific/Midway';
            case 'UM10':
                return 'Pacific/Honolulu';
            case 'UM95':
                return 'Pacific/Marquesas';
            case 'UM9':
                return 'Pacific/Gambier';
            case 'UM8':
                return 'America/Los_Angeles';
            case 'UM7':
                return 'America/Boise';
            case 'UM6':
                return 'America/Chicago';
            case 'UM5':
                return 'America/New_York';
            case 'UM45':
                return 'America/Caracas';
            case 'UM4':
                return 'America/Sao_Paulo';
            case 'UM35':
                return 'America/St_Johns';
            case 'UM3':
                return 'America/Buenos_Aires';
            case 'UM2':
                return 'Atlantic/St_Helena';
            case 'UM1':
                return 'Atlantic/Azores';
            case 'UP1':
                return 'Europe/Berlin';
            case 'UP2':
                return 'Europe/Kaliningrad';
            case 'UP3':
                return 'Asia/Baghdad';
            case 'UP35':
                return 'Asia/Tehran';
            case 'UP4':
                return 'Asia/Baku';
            case 'UP45':
                return 'Asia/Kabul';
            case 'UP5':
                return 'Asia/Karachi';
            case 'UP55':
                return 'Asia/Calcutta';
            case 'UP575':
                return 'Asia/Kathmandu';
            case 'UP6':
                return 'Asia/Almaty';
            case 'UP65':
                return 'Asia/Rangoon';
            case 'UP7':
                return 'Asia/Bangkok';
            case 'UP8':
                return 'Asia/Hong_Kong';
            case 'UP875':
                return 'Australia/Eucla';
            case 'UP9':
                return 'Asia/Tokyo';
            case 'UP95':
                return 'Australia/Darwin';
            case 'UP10':
                return 'Australia/Melbourne';
            case 'UP105':
                return 'Australia/LHI';
            case 'UP11':
                return 'Asia/Magadan';
            case 'UP115':
                return 'Pacific/Norfolk';
            case 'UP12':
                return 'Pacific/Fiji';
            case 'UP1275':
                return 'Pacific/Chatham';
            case 'UP13':
                return 'Pacific/Samoa';
            case 'UP14':
                return 'Pacific/Kiritimati';
            case 'UTC':
                // no break;
            default:
                return 'UTC';
        }
    }
}

//convert_to_utc("07/02/15","9:06 PM","UM5");
function convert_to_utc($date, $time, $tz){

    $standard_timezone = standard_timezone($tz);

    $timestamp =  strtotime("$date $time");

    $system_timezone = new DateTimeZone('UTC'); // your timezone
    $user_timezone = new DateTimeZone($standard_timezone); // your user's timezone
    $now = new DateTime(date('Y-m-d H:i:s',$timestamp), $user_timezone);

    $now->setTimezone($system_timezone);

    $utc_datetime = $now->format('Y-m-d H:i:s');


    return array(
            "user_date"=>date("Y-m-d", strtotime($date)),
            "user_time"=>$time,
            "utc_full"=>$utc_datetime,
            "utc_date"=>$now->format('Y-m-d'),
            "utc_time"=>$now->format('h:i A'),
            "user_timezone"=>$tz,
            "user_standard_timezone"=>$standard_timezone,
            "seconds"=>$now->getTimestamp()
        );

 
}

//utc_to_local("07/02/15","9:06 PM","UM5")
function utc_to_local($date, $time, $tz){

    $standard_timezone = standard_timezone($tz);

    //$timestamp =  strtotime("$date $time");
    $timestamp = $time;

    $system_timezone = new DateTimeZone('UTC'); // your timezone
    $user_timezone = new DateTimeZone($standard_timezone); // your user's timezone
    $now = new DateTime(date('Y-m-d H:i:s',$timestamp));

    $now->setTimezone($user_timezone);

    $utc_datetime = $now->format('Y-m-d H:i:s');


    return array(
            "utc_date"=>$date,
            "utc_time"=>$time,
            "user_full"=>$utc_datetime,
            "user_date"=>$now->format('F j, Y'),
            "user_date_calendar"=>$now->format('m/d/y'),

            "user_time"=>$now->format('g:i A'),
            "user_timezone"=>$tz,
            "user_standard_timezone"=>$standard_timezone
        );


}

function convert_app_timezone($timezone){
    $CI =& get_instance();
    $sql = "select timezone from timezones where apptimezone =?";
    $result = $CI->db->query($sql, array($timezone));

    //log_message('error',$CI->db->last_query());

    return $result->row()->timezone;

}


function hms2sec ($hms) {
    //print_rr($hms,0);    
    $arr=explode(" ", $hms);
    $b=end($arr);
    $hms=reset($arr);
    $arr_hms=  explode(":", $hms);
    if( ($b=="PM" || $b=="pm") && $arr_hms[0]!=12) {
        $arr_hms[0]+=12;
    }else{
        if( ($b=="AM" || $b=="am") && $arr_hms[0]==12) {
            $arr_hms[0]=0;
        }
    }

    $seconds = 0;
    $seconds += (intval($arr_hms[0]) * 3600);
    $seconds += (intval($arr_hms[1]) * 60);


    return $seconds;
}

function hms2sec_api ($hms) {
        
    $arr=explode(" ", $hms);
    $b=end($arr);
    $hms=reset($arr);
    $arr_hms=  explode(":", $hms);
    if( ($b=="PM" || $b=="pm") && $arr_hms[0]!=12) {
        $arr_hms[0]+=12;
    }else{
        if($arr_hms[0]==12){
            //$arr_hms[0]=0;
        }
    }

    $seconds = 0;
    $seconds += (intval($arr_hms[0]) * 3600);
    $seconds += (intval($arr_hms[1]) * 60);


    return $seconds;
}

function convert_api_datetime($date,$time,$timezone){

   
    $corrected_timezone = convert_app_timezone($timezone);

    $converted_start_date = convert_to_utc($date,$time,$corrected_timezone);

    return $converted_start_date;


}


function encrypt_password($password){

    return password_hash(trim($password), PASSWORD_DEFAULT);

}


function addScheme($url, $scheme = 'http://')
{
    if (trim($url)!='') {
        return parse_url($url, PHP_URL_SCHEME) === null ?
        $scheme . $url : $url;
    }
  
}




?>