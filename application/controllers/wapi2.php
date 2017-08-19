<?php
//test
//http://stackoverflow.com/questions/19023978/should-mysql-have-its-timezone-set-to-utc/19075291#19075291
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

define("RANDY_DEBUG", false);
define("APNS_DEVELOPMENT", "production");
define("APNS_CERT_DEV", "dgg2.pem");
define("APNS_CERT_LIVE", "dgg2.pem");
//define("ANDROID_APIKEY", "AIzaSyAHecYTzBl4YLMUDDRwn-KLEMP5NKAH6Ic");
// define("ANDROID_APIKEY", "AIzaSyALWHjts9TrKNk5304CAPZug0DAeZvjz8Y");
//define("ANDROID_APIKEY", "AIzaSyDV4-IMuP5yoj6WUDlZvpLsVwSv81TrrWk");
//define("ANDROID_APIKEY", "AIzaSyAx07B8kQhHQ7fKRezaoaKv3v02OGzupsk");
define("ANDROID_APIKEY", "AIzaSyDoXJeh3yFSfjLrdd1IMPvzK6PETCqdTf0");

class Wapi extends CI_Controller {

    function Wapi() {
        parent::__construct();
        $this->load->model('appuser');
        $this->load->model('m_common');
        $this->load->model('business');
        $this->load->model('loyaltyprogram');
        $this->load->model('geo');
        $this->load->helper('date');



        $this->result = array();
        $this->msg = '';
        $this->width=isset($this->postvars['width']) ? $this->postvars['width'] : 600;
        $this->is_debug=isset($this->postvars['is_debug']) ? $this->postvars['is_debug'] : 0;
        //header('Content-type: application/json');
        date_default_timezone_set('UTC');
        $this->manage_content_type();
      
    }

    public function set_custom_tz() {
        //$ip = $_SERVER['REMOTE_ADDR']; // the IP address to query
        //$query = @unserialize(file_get_contents('http://ip-api.com/php/' . $ip));
        //if ($query && $query['status'] == 'success') {
        //    @date_default_timezone_set($query['timezone']);
            //echo 'Hello visitor from ' . $query['timezone'] . ', ' . $query['city'] . '!';
        //}
    }
    // $this->apilog(__FUNCTION__, $user_id, $_POST, $data);

    private function apilog($verb, $user_id, $apicall, $data){

        $apicall = json_encode($apicall);
        $q = "insert into apicalls set verb=?, user_id=?, apicall=?, data=?, server_time_in_sec=hour(now())*3600";
        $result = $this->db->query($q, array($verb, $user_id, $apicall, $data ));
    }

    private function apisql($verb, $user_id, $sql, $results, $extended_info=array()){

        $results = json_encode($results);
        $extended_info = json_encode($extended_info);

        $q = "insert into apisql set apicall=?, user_id=?, `sql`=?, sql_results=?,extended_info=?";
        $result = $this->db->query($q, array($verb, $user_id, $sql, $results, $extended_info ));
    }

    public function cityList(){

        $user_id = $this->is_require($this->postvars, 'user_id');
        $user_token = $this->is_require($this->postvars, 'user_token');

        $this->apilog(__FUNCTION__, $user_id, $_POST, '');

        $this->Is_authorised($user_id, $user_token);

        $q = "select distinct concat_ws(', ',tbl_city.city_name, tbl_state.code) as city_name from tbl_city join tbl_state on tbl_city.state_id = tbl_state.sid join tbl_shop on tbl_city.city_id = tbl_shop.city_id where tbl_city.cid=254 order by city_name,state_name";
         $info = $this->db->query($q);

         $final_info=array();

         $info = $info->result_array();

  
         $info = array_values($info);

          $this->result = array(
            'cities' => $info,
            'total_cities' => count($info),
        );
        $this->_sendResponse(1);

    }

    public function getProfile(){

        $user_id = $this->is_require($this->postvars, 'user_id');
        $user_token = $this->is_require($this->postvars, 'user_token');

        $this->apilog(__FUNCTION__, $user_id, $_POST, '');

        $this->Is_authorised($user_id, $user_token);

        $appuser = $this->appuser->getAppUser($user_id, $user_token);

        $this->result = $appuser;
        $status_code = 1;
        $this->_sendResponse($status_code);

    }

    public function getDeviceTokenAndType(){

        $user_id = $this->is_require($this->postvars, 'user_id');
        $user_token = $this->is_require($this->postvars, 'user_token');

        $this->apilog(__FUNCTION__, $user_id, $_POST, '');

        $this->Is_authorised($user_id, $user_token);

        $appuser = $this->appuser->getDeviceTokenAndType($user_id, $user_token);

        $this->result = $appuser;
        $status_code = 1;
        $this->_sendResponse($status_code);

    }

    public function updateProfile(){

        $user_id = $this->is_require($this->postvars, 'user_id');
        $user_token = $this->is_require($this->postvars, 'user_token');

        $name = $this->is_require($this->postvars, 'name');
        $email = $this->is_require($this->postvars, 'email');

        $gender = isset($this->postvars['gender']) ? $this->postvars['gender'] : '';

        $zipcode = isset($this->postvars['zipcode']) ? $this->postvars['zipcode'] : '';

        $age = isset($this->postvars['age']) ? $this->postvars['age'] : '';

        $password = isset($this->postvars['password']) ? $this->postvars['password'] : '';
        $password=trim($password);

        if ($password!=''){

            if(strlen($password) < 8) {
                 $this->_sendResponse(6); //password must be 8 characters
                 exit;
            }

        }


        $p = $_POST;
        $p["password"]="";
        $this->apilog(__FUNCTION__, $user_id, $p, '');

        $this->Is_authorised($user_id, $user_token);


        $t = $this->appuser->checkEmail($user_id, $email);

        $this->apisql( __FUNCTION__, $user_id, $this->db->last_query(),$p);


        if ($t > 0) {
            $this->_sendResponse(2); //email already exists
            exit;

        } else {

            $this->appuser->updateAppUser($user_id, $user_token, $name, $email, $zipcode, $gender, $age);

            $this->apisql( __FUNCTION__, $user_id, $this->db->last_query(),$p);

            if ($password!=''){
                $this->appuser->update_password($user_id, $user_token, $password);
            }

            $status_code = 1;
            $this->_sendResponse($status_code);
        }



         




    }

    public function city_search(){

        $user_id = $this->is_require($this->postvars, 'user_id');
        $user_token = $this->is_require($this->postvars, 'user_token');
        $city = $this->is_require($this->postvars, 'city');
        $this->apilog(__FUNCTION__, $user_id, $_POST, '');

        $this->Is_authorised($user_id, $user_token);

        $sql = "select distinct city_name from tbl_city where city_name like ? order by city_name limit 10";
        $result = $this->db->query($sql, array( "$city%"));

        $ret = $result->result_array();

        $cities = array($ret);
        $this->result = $cities;
        $status_code = 1;
        $this->_sendResponse($status_code);

    }

public function record_deal_view(){

        $user_id = $this->is_require($this->postvars, 'user_id');
        $user_token = $this->is_require($this->postvars, 'user_token');
        $time_in_sec = $this->is_require($this->postvars, 'time_in_sec');// in integer
        $cs = $this->is_require($this->postvars, 'localtime');//in Y-m-d
        $deal_id = $this->is_require($this->postvars, 'deal_id');

        $timezone = isset($_POST['timezone']) ? $_POST['timezone']: 'EST';


        $this->apilog(__FUNCTION__, $user_id, $_POST, '');

            $ins = array(
                'user_id' => $user_id,
                'deal_id' => $deal_id

            );
        $this->m_common->insert_entry('dealviews', $ins);

         $ins = array(
                'isread' => 1
               
            );

         $wh = array(
                'user_id' => $user_id,
                'deal_id' => $deal_id

            );
        $this->m_common->update_entry('push_notes',$ins,$wh);



        $data = array();
        $this->result = $data;
        $status_code = 1;
        $this->_sendResponse($status_code);

    }

    public function record_push(){

        $user_id = $this->is_require($this->postvars, 'user_id');
        $user_token = $this->is_require($this->postvars, 'user_token');
        $deal_id = $this->is_require($this->postvars, 'deal_id');
        $latitude = isset($this->postvars['latitude']) ? $this->postvars['latitude'] : '0';
        $longitude = isset($this->postvars['longitude']) ? $this->postvars['longitude'] : '0';

        $this->apilog(__FUNCTION__, $user_id, $_POST, '');

        $this->Is_authorised($user_id, $user_token);

        $sql = "update push_notes set tsReceived=now(), dblReceivedLat=?, dblReceivedLon=? where user_id=? and deal_id=?";
        $result = $this->db->query($sql, array($latitude, $longitude, $user_id, $deal_id));

        $this->apisql( __FUNCTION__, $user_id, $this->db->last_query(),$_POST);


        $data = array();
        $this->result = $data;
        $status_code = 1;
        $this->_sendResponse($status_code);

    }
    
    public function push_user() {

        //$device_token = $this->is_require($this->postvars, 'device_token');
        $device_token = $_REQUEST['device_token'];
        $t = $this->m_common->db_select('count(*) as cnt', 'tbl_pushtesting', array('device_token' => $device_token), '', '', '', '', 'row');
        //echo "<pre>";print_r($t);exit;
        if ($t->cnt == 0) {
            $ins = array(
                'device_token' => $device_token,
            );
            $this->m_common->insert_entry('tbl_pushtesting', $ins);
        }
    }

  
    public function index() {
        $this->_sendResponse(404);
    }

    private function manage_content_type() {
        $body = file_get_contents("php://input");
        parse_str($body, $postvars);
        $this->postvars = $postvars;
    }

    public function signup_consumer(){

        error_reporting(0);
        ###############  post parameter #######
        $name = $this->is_require($this->postvars, 'name');
        $latitude = isset($this->postvars['latitude']) ? $this->postvars['latitude'] : '0';
        $longitude = isset($this->postvars['longitude']) ? $this->postvars['longitude'] : '0';
        $phone_no = isset($this->postvars['phone_no']) ? $this->postvars['phone_no'] : '';
        $address = isset($this->postvars['address']) ? $this->postvars['address'] : '';
        $device_token = isset($this->postvars['device_token']) ? $this->postvars['device_token'] : '';
        $device_type = isset($this->postvars['device_type']) ? $this->postvars['device_type'] : '0';
        $email = $this->is_require($this->postvars, 'email');
        $password = $this->is_require($this->postvars, 'password');


        $p = $_POST;
        $p["password"]="";
        $this->apilog(__FUNCTION__, 0, $p, '');

        ####################################### 


        $t = $this->m_common->db_select('count(*) as cnt', 'tbl_customer', array('email' => $email), '', '', '', array(1,0), 'row');
        //echo "<pre>";print_r($t);exit;
        if ($t->cnt >= 1) {
            $this->_sendResponse(2); //email already exists
        }

        $user_token = $this->get_random_string();
        $verification_code = $this->get_unique_code('tbl_customer', 'verification_code');
        $ins = array(
            'user_token' => $user_token,
            'password' => $password,
            'name' => $name,
            'email' => $email,
            'latitude' => $latitude,
            'longitude' => $longitude,
            'phone_no' => $phone_no,
            'address' => $address,
            'verification_code' => $verification_code,
        );
        $temp = $this->m_common->insert_entry('tbl_customer', $ins, TRUE);
        if ($temp['stat'] == 1) {


            $dt = date('l jS \of F Y \a\t h:i:s A'); // in mail display time text 
            $ua = $this->get_useragent();
            $link = base_url() . "c_support/?verification_code=" . $verification_code . "&ua=" . $ua;
            $email_message = $this->load->view('email/account_verify', $data = array('link' => $link, 'dt' => $dt, 'name' => $name), TRUE);
            $arr = array(
                'subject' => "Deal on GOGO Network : Verify The Account",
                'message_body' => $email_message,
                'to' => $email,
            );
            $this->send_email($arr);

            $this->appuser->update_password($temp['last_id'], $user_token, $password);

            $user_info = $this->get_user_info($temp['last_id']);
            $user_info["account_type"]="normal";

            $this->result['info'] = $user_info;
            $status_code = 1;
        } else {
            $status_code = 0;
        }
        //echo "<pre>";print_r($result);exit;
        $this->_sendResponse($status_code);
    }

    public function explore(){

        $device_token = isset($this->postvars['device_token']) ? $this->postvars['device_token'] : '';
        $device_type = isset($this->postvars['device_type']) ? $this->postvars['device_type'] : '0';

        $latitude = isset($this->postvars['latitude']) ? $this->postvars['latitude'] : '0';
        $longitude = isset($this->postvars['longitude']) ? $this->postvars['longitude'] : '0';
        



        $p = $_POST;
        //$p["password"]="";
        $this->apilog(__FUNCTION__, 0, $p, '');

        // The logic.  If the device id does not exist, we need to create an account with the device id, device type.  a temporary account.  
        // We also need to make note that this is an explore only login so the app guys can limit functionality.

        //step 1.  Check to see if their is a user account with the device id and type.
        $sql = "
                select 
                        user_id,
                        user_token
                         
                from
                        tbl_customer

                where
                    device_token=?
                    and
                    device_type=?
        ";

         $result = $this->db->query($sql,array($device_token, $device_type));

         $user_info =  $result->result_array();

         if (count($user_info)==0){

            //Add The user

             $user_token = $this->get_random_string();

            $sql="insert into tbl_customer
                  set 
                  device_token=?,
                  device_type=?,
                  user_token=?
                ";
            $result = $this->db->query($sql,array($device_token, $device_type, $user_token));

            $user_id = $this->db->insert_id();


         } else {

            //Return info as well as account type
            //print_r($user_info);
            $user_id = $user_info[0]["user_id"];



         }

         $ins = array(
                    'user_id' => $user_id,
                    'latitude' => $latitude,
                    'longitude' => $longitude,
        );

        $this->geo->updateAppUserLocation($user_id, $latitude, $longitude);

        $this->m_common->insert_entry('customer_location', $ins);


        $user_info = $this->get_user_info($user_id);
        $user_info["password"]="";
        $user_info["password_encrypted"]="";
        $user_info["account_type"]="explore";
        $this->result['info'] = $user_info;
        $status_code = 1;

        $this->_sendResponse($status_code);


        

    }
    public function signupWithFacebook() {
        error_reporting(0);
        ###############  post parameter #######
        $name = $this->is_require($this->postvars, 'name');
        $latitude = isset($this->postvars['latitude']) ? $this->postvars['latitude'] : '0';
        $longitude = isset($this->postvars['longitude']) ? $this->postvars['longitude'] : '0';
        $phone_no = isset($this->postvars['phone_no']) ? $this->postvars['phone_no'] : '';
        $address = isset($this->postvars['address']) ? $this->postvars['address'] : '';
        $password = $this->is_require($this->postvars, 'password');
        $device_token = isset($this->postvars['device_token']) ? $this->postvars['device_token'] : '';
        $device_type = isset($this->postvars['device_type']) ? $this->postvars['device_type'] : '0';
        $email = $this->is_require($this->postvars, 'email');
        $password = $this->is_require($this->postvars, 'password');
        #######################################

        $p = $_POST;
        $p["password"]="";
        $this->apilog(__FUNCTION__, 0, $p, '');


        $aryCheckDup = array(
            'email' => $email,
            'password' => $password,
        );

        $t = $this->m_common->db_select('count(*) as cnt', 'tbl_customer', $aryCheckDup, '', '', '', array(1,0), 'row');
        //echo "<pre>";print_r($t);exit;
        if ($t->cnt >= 1) {
            $this->_sendResponse(2); //email already exists
        }

        $user_token = $this->get_random_string();
        $verification_code = $this->get_unique_code('tbl_customer', 'verification_code');
        $ins = array(
            'user_token' => $user_token,
            'password' => $password,
            'name' => $name,
            'email' => $email,
            'latitude' => $latitude,
            'longitude' => $longitude,
            'phone_no' => $phone_no,
            'address' => $address,
            'verification_code' => $verification_code,
        );
        $temp = $this->m_common->insert_entry('tbl_customer', $ins, TRUE);
        if ($temp['stat'] == 1) {


            $dt = date('l jS \of F Y \a\t h:i:s A'); // in mail display time text
            $ua = $this->get_useragent();
            $link = base_url() . "c_support/?verification_code=" . $verification_code . "&ua=" . $ua;
            $email_message = $this->load->view('email/account_verify', $data = array('link' => $link, 'dt' => $dt, 'name' => $name), TRUE);
            $arr = array(
                'subject' => "Deal on GOGO Network : Verify The Account",
                'message_body' => $email_message,
                'to' => $email,
            );
            $this->send_email($arr);

            $user_info = $this->get_user_info($temp['last_id']);
            $user_info["account_type"]="normal";
            $this->result['info'] = $user_info;
            $status_code = 1;
        } else {
            $status_code = 0;
        }
        //echo "<pre>";print_r($result);exit;
//        $this->_sendResponse($status_code);
    }

    public function facebookLogin() {

        ################ post parameter #######
        $email = $this->is_require($this->postvars, 'email');
        $password = $this->is_require($this->postvars, 'password');
        $device_token = isset($this->postvars['device_token']) ? $this->postvars['device_token'] : '';
        $device_type = isset($this->postvars['device_type']) ? $this->postvars['device_type'] : '0';

        $p = $_POST;
        //$p["password"]="";
        $this->apilog(__FUNCTION__, 0, $p, '');


        ##################################
        $where = array(
            'email' => $email
        );
        $user_tbl = 'tbl_customer';

        $t = $this->m_common->db_select('count(*) as cnt', 'tbl_customer', $where, array(), '', '', array(1,0), 'row');
        //echo "<pre>";print_r($t);
        if ($t->cnt < 1) {
//            $this->_sendResponse(3); // email invalid

            //Input here signup code
            $this->signupWithFacebook();
            $this->facebookLogin();
        }
        $where = array(
            'email' => $email,
            'password' => $password,
        );


        $user_info = $this->m_common->db_select("*", $user_tbl, $where, array(), '', '', '', 'row_array', 0);
//        if($this->is_debug){
//            echo "<pre>";print_r($user_info);
//        }

        if (empty($user_info)) {
//            $this->_sendResponse(4); // password invalid

            //Input here signup code

            $this->signupWithFacebook();
            $this->facebookLogin();
        }

        if(empty($user_info['user_token'])){
            $user_token = $this->get_random_string();
            $up = array(
                'user_token' => $user_token,
            );
            $wh = array(
                'user_id' => $user_info['user_id'],
            );
            $temp = $this->m_common->update_entry($user_tbl, $up, $wh);
        }else{
            $user_token = $user_info['user_token'];
        }



        $this->reset_badge($user_info['user_id']);
        $user_info['user_token'] = $user_token;
        $user_info["account_type"]="normal";

        $this->result = $user_info;

        $status_code = 1;

        //echo "<pre>";print_r($this->result);
        $this->_sendResponse($status_code);

    }

    public function login() {

        ###############  post parameter #######
        $email = $this->is_require($this->postvars, 'email');
        $password = $this->is_require($this->postvars, 'password');
        $device_token = isset($this->postvars['device_token']) ? $this->postvars['device_token'] : '';
        $device_type = isset($this->postvars['device_type']) ? $this->postvars['device_type'] : '0';
        #######################################

        $p = $_POST;
        $p["password"]="";
        $this->apilog(__FUNCTION__, 0, $p, '');


        $where = array(
            'email' => $email
        );
        $user_tbl = 'tbl_customer';

        /*
        $t = $this->m_common->db_select('count(*) as cnt', 'tbl_customer', $where, array(), '', '', array(1,0), 'row');
        //echo "<pre>";print_r($t);
        if ($t->cnt < 1) {
            $this->_sendResponse(3); // email invalid
            exit;
        }*/

        $where = array(
            'email' => $email,
        );


        $user_info = $this->m_common->db_select("*", $user_tbl, $where, array(), '', '', '', 'row_array', 0);
        if($this->is_debug){
            echo "<pre>";print_r($user_info);
        }
        
        if (empty($user_info)) {
            $this->_sendResponse(4); // password invalid
            exit;
        }

        if (password_verify($password,$user_info["password_encrypted"])===false){

            $this->_sendResponse(4); // password invalid
            exit;
        }

        if(empty($user_info['user_token'])){
            $user_token = $this->get_random_string();
            $up = array(
                'user_token' => $user_token,
                'device_token' => $device_token,
                'device_type' => $device_type

            );
            $wh = array(
                'user_id' => $user_info['user_id'],
            );
            $temp = $this->m_common->update_entry($user_tbl, $up, $wh);
        }else{
            $user_token = $user_info['user_token'];
        }

        //gotta update device token

        $up = array(
                'device_token' => $device_token,
                'device_type' => $device_type

            );
            $wh = array(
                'user_id' => $user_info['user_id'],
            );
            $tempupdate = $this->m_common->update_entry($user_tbl, $up, $wh);
        

            $this->reset_badge($user_info['user_id']);
            $user_info['user_token'] = $user_token;

            $user_info["password"]="";
            $user_info["password_encrypted"]="";
            $user_info["account_type"]="normal";

            $this->result = $user_info;

            $status_code = 1;
       
        //echo "<pre>";print_r($this->result);
        $this->_sendResponse($status_code);
    }

    public function is_consumer_verified() {
        ###############  post parameter #######
        $user_id = $this->is_require($this->postvars, 'user_id');
        $user_token = $this->is_require($this->postvars, 'user_token');
        $is_verify = 0;

        $this->apilog(__FUNCTION__, $user_id, $_POST, '');


        #######################################

        $this->Is_authorised($user_id, $user_token);
        #######################################
        $where = array(
            'user_id' => $user_id,
            'is_verify' => 1
        );
        $t = $this->m_common->db_select('count(*) as cnt', 'tbl_customer', $where, array(), '', '', array(1,0), 'row');
        //echo "<pre>";print_r($t);
        if ($t->cnt >= 1) {
            $is_verify = 1;
        }
        $this->result = array(
            "is_verify" => $is_verify,
        );
        $this->_sendResponse(1);
    }


    //Fetch Favorite
    public function fetch_favorites() {

        ################## input parameters ######################
        $user_id = $this->is_require($this->postvars, 'user_id');
        $user_token = $this->is_require($this->postvars, 'user_token');
        $time_in_sec = $this->is_require($this->postvars, 'time_in_sec');
        $cs = $this->is_require($this->postvars, 'localtime');

         $timezone = isset($_POST['timezone']) ? $_POST['timezone']: 'EST';

       
        $this->apilog(__FUNCTION__, $user_id, $_POST, '');

        ##########################################################
        $this->cs = $cs;
        //echo $cs;
        $next_sunday=date('Y-m-d',strtotime('next sunday'));
        #######################################

        $this->Is_authorised($user_id, $user_token);
        //$user_info = $this->get_user_info($user_id, "device_token,device_type,name");
        $selected_cats=array();
        $user_cat = $this->m_common->db_select('user_cat', 'tbl_customer', array("user_id" => $user_id), array(), '', '', '', 'row_array', 0);
        if(!empty($user_cat)){
            $cat_ids = $user_cat['user_cat'];
            $selected_cats = explode(",", $cat_ids);
        }

        //echo "<pre>";print_r($selected_cats);

        $current_day = date("N");

        $q = "
            select t1.*, 
                tbl_shop.shop_cats, 
                tbl_shop.pin,
                tbl_deal.shop_id,
                tbl_deal.deal_title,
                tbl_deal.deal_description, 
                tbl_deal.original_price,
                tbl_deal.offer_price,
                tbl_deal.deal_image,
                tbl_deal.deal_start,
                tbl_deal.deal_end,
                tbl_deal.deal_time,
                tbl_deal.deal_end_time,
                tbl_deal.featured_deal,
                tbl_deal.contact_name,
                tbl_deal.contact_number,
                tbl_deal.website,
                0 as share_count,
                tbl_deal.status,
                tbl_shop.shop_name,
                tbl_shop.shop_cats,
                tbl_shop.shop_description,
                tbl_shop.shop_image,
                tbl_shop.address,
                tbl_shop.zip_code,
                tbl_shop.latitude,
                tbl_shop.longitude,
                1 as isfavorite,
                (select count(*) from loyaltyprograms where loyaltyprograms.shop_id=tbl_shop.shop_id ) as blnHasCL

            from tbl_deal_favorite t1 
            join tbl_shop on t1.shop_id = tbl_shop.shop_id
            join tbl_deal on t1.deal_id = tbl_deal.id

            where 
                is_active =1

                and t1.user_id='$user_id'
                and t1.deal_time_insec <= '$time_in_sec' and t1.deal_endtime_insec >= '$time_in_sec'  order by t1.date desc";

        $info = $this->m_common->select_custom($q);

        $this->apisql( __FUNCTION__, $user_id, $this->db->last_query(), $info);


        $final_info=array();
        foreach ($info as $k => $v) {
            $flag=0;
            $row_cats = explode(",", $v['shop_cats']);
            //echo "<pre>";print_r($row_cats);
            foreach ($row_cats as $rc) {
                if (in_array($rc, $selected_cats)) {
                    $flag = 1;
                    break;
                }
            }
            if(!$flag){

                continue;
            }


            $info[$k]['shop_cats'] = $this->get_shop_cats_names($v['shop_cats']);
           $info[$k]['shop_image'] = $this->get_shop_image_path($v['shop_image']);
           $info[$k]['deal_image'] = $this->get_deal_image_path($v['deal_image'],$this->get_shop_image_path($v['shop_image']));

//
            $info[$k]['deal_start'] = date("F j, Y, g:i a", $info[$k]['deal_time']);
            $info[$k]['deal_end'] = date("F j, Y, g:i a", $info[$k]['deal_end_time']);
            $final_info[]=$info[$k];
        }
        //echo "<pre>";print_r($info);exit;
        $data = array(
            "info" => $final_info,
        );
        $this->result = $data;
        $status_code = 1;
        $this->_sendResponse($status_code);
    }

    // Add Favorite
    public function make_favourite_deal() {
        
        ###############  post parameter #######

        $user_id = $this->is_require($this->postvars, 'user_id');
        $deal_id = $this->is_require($this->postvars, 'deal_id');
        $user_token = $this->is_require($this->postvars, 'user_token');

        $this->apilog(__FUNCTION__, $user_id, $_POST, '');
    

         $query = "select * from tbl_deal where id=$deal_id";
         $deal = $this->m_common->select_custom($query);

         //$date= date('Y-m-d H:i:s');
         $date= date('Y-m-d');

        $ins = array(
            'user_id' => $user_id,
            'deal_id' => $deal_id,
            'deal_start' => $deal["0"]["deal_start"],
            'deal_end' => $deal["0"]["deal_end"],
            'repeat' => $deal["0"]["repeat"],
            'deal_time_insec' => $deal["0"]["deal_time"],
            'deal_endtime_insec' => $deal["0"]["deal_end_time"],
            'date' => $date,
            'shop_id' => $deal["0"]["shop_id"],
            'checkexisting' => "$user_id:$deal_id"
        );
        //echo json_encode($ins);
       
        $this->m_common->insert_entry('tbl_deal_favorite', $ins);

        $status_code = 1;
        $this->_sendResponse($status_code);
       
    }

    public function delete_favorite_deal() {
        ###############  post parameter #######
        $checkexisting = $this->is_require($this->postvars, 'checkexisting_flag');

        $this->apilog(__FUNCTION__, 0, $_POST, '');

        $wh=array(
            'checkexisting'=>$checkexisting,
        );
        //echo json_encode($wh);
        $this->m_common->delete_entry('tbl_deal_favorite',$wh);
//        $this->m_common->insert_entry('tbl_deal_favorite', $ins);
        $status_code = 1;
        $this->_sendResponse($status_code);
    }

        public function delete_deal() {
        ###############  post parameter #######
        $user_id = $this->is_require($this->postvars, 'user_id');
        $deal_id = $this->is_require($this->postvars, 'deal_id');
        $user_token = $this->is_require($this->postvars, 'user_token');

        $this->apilog(__FUNCTION__, 0, $_POST, '');

        $this->Is_authorised($user_id, $user_token);


       $ins = array(
            'user_id' => $user_id,
            'deal_id' => $deal_id
        );

        $sql="insert ignore into tbl_deleted_deals set customer_id=?,deal_id=?";
        $result = $this->db->query($sql,$ins);

        $status_code = 1;
        $this->_sendResponse($status_code);
    }



    public function update_device_token() {
        ###############  post parameter #######
        $user_id = $this->is_require($this->postvars, 'user_id');
        $user_token = $this->is_require($this->postvars, 'user_token');
        $device_type = $this->is_require($this->postvars, 'device_type');
        $device_token = $this->is_require($this->postvars, 'device_token');

        $this->apilog(__FUNCTION__, $user_id, $_POST, '');

        #######################################

        $this->Is_authorised($user_id, $user_token);
        #######################################
        $up = array(
            'device_token' => $device_token,
            'device_type' => $device_type,
        );
        $wh = array(
            'user_id' => $user_id,
        );
        $this->m_common->update_entry("tbl_customer", $up, $wh);
        $this->_sendResponse(1);
    }

    public function category_selected() {
        ###############  post parameter #######
        $user_id = $this->is_require($this->postvars, 'user_id');
        $user_token = $this->is_require($this->postvars, 'user_token');
        $cat_id = $this->is_require($this->postvars, 'cat_id');
        $action = $this->is_require($this->postvars, 'action');

        $this->apilog(__FUNCTION__, $user_id, $_POST, '');


        #######################################
        $user_cats=array();
        $this->Is_authorised($user_id, $user_token);
        #######################################
        $user_cat = $this->m_common->db_select('user_cat', 'tbl_customer', array("user_id" => $user_id), array(), '', '',array(1,0), 'row_array', 0);
        if(!empty($user_cat)){
            $user_cats = (array) explode(",", $user_cat['user_cat']);
        }
        
        //echo "<pre>";print_r($user_cats);
        if ($action) {
            if (!in_array($cat_id, $user_cats)) {
                $user_cats[] = $cat_id;
            }
        } else {
            foreach ($user_cats as $k => $v) {
                if ($v == $cat_id) {
                    unset($user_cats[$k]);
                    $user_cats = array_values(array_unique($user_cats));
                }
            }
        }
        $user_cat_str = implode(",", $user_cats);
        $up = array(
            'user_cat' => $user_cat_str,
        );
        $wh = array(
            'user_id' => $user_id,
        );
        $this->m_common->update_entry("tbl_customer", $up, $wh);
        $this->_sendResponse(1);
    }

    private function write_log($msg = "") {
        $fp = fopen("gogo.log", "a+");
        fwrite($fp, date("Y-m-d h:i:s") . "  :" . $msg . "\r\n");
        fclose($fp);
    }


    public function setup_consumer_location() {
        ###############  post parameter #######

        sleep(2);

        $user_id = $this->is_require($this->postvars, 'user_id');
        $user_token = $this->is_require($this->postvars, 'user_token');
        $time_in_sec = $this->is_require($this->postvars, 'time_in_sec');// in integer
        $cs = $this->is_require($this->postvars, 'localtime');//in Y-m-d
        $dis = isset($_POST['radius']) ? $_POST['radius'] : 5;

        $timezone = isset($_POST['timezone']) ? $_POST['timezone']: 'EST';

        $latitude = isset($this->postvars['latitude']) ? $this->postvars['latitude'] : '0';
        $longitude = isset($this->postvars['longitude']) ? $this->postvars['longitude'] : '0';

        $this->cs=$cs;
        $this->time_in_sec=$time_in_sec;

        $this->apilog(__FUNCTION__, $user_id, $_POST, '');

        #######################################

        $this->Is_authorised($user_id, $user_token);


        $ins = array(
                    'user_id' => $user_id,
                    'latitude' => $latitude,
                    'longitude' => $longitude,
        );

        $this->geo->updateAppUserLocation($user_id, $latitude, $longitude);

        $this->m_common->insert_entry('customer_location', $ins);
        


        $user_info = $this->get_user_info($user_id, "device_token,device_type,name,badge");
        //$q = "select t2.* from tbl_customer t1 join tbl_category t2 on t1.user_cat = t2.cid where t1.user_id = $user_id limit 1";
        $q = "select user_cat from tbl_customer where user_id = $user_id limit 1";
        $cat_info = $this->m_common->select_custom($q);

        if (empty($cat_info)) {
            $this->_sendResponse(12); // please select an category
        }
        $cat_id = $cat_info[0]['user_cat'];
        $user_categories = explode(",", $cat_id);

        

        //Here is where we need to loop through the categories.

        $next_sunday=date('Y-m-d',strtotime('next sunday'));
        $current_day = date("N");

        foreach ($user_categories as $category) {
            
           
            //get the category distance
            $qCat = "select dis from tbl_category where cid = ?";
            $rCat = $this->db->query($qCat, array($category));
            $row = $rCat->row(); 

            if ($rCat->num_rows() > 0) {
                //echo $category." ";
                $category_distance = $row->dis;
                //echo $category_distance . "\n";


                $qDeals = "select 
                        tbl_deal.*,
                        tbl_shop.*,
                        (((acos(sin(($latitude*pi()/180)) * sin((`latitude`*pi()/180))+cos(($latitude*pi()/180)) * cos((`latitude`*pi()/180))
                 * cos((($longitude - `longitude`)*pi()/180))))*180/pi())*60*1.1515) AS `distance` 

                     from 
                        tbl_deal join tbl_shop on tbl_deal.shop_id = tbl_shop.shop_id
                     where 
                        is_active=1
                        and is_off = 0
                        and tbl_shop.shop_cats = $category
                        and tbl_deal.deal_end_time > UNIX_TIMESTAMP() 
                        and tbl_deal.deal_time <= '$time_in_sec' 
                        and tbl_deal.deal_end_time >= '$time_in_sec' 
                        and tbl_deal.id not in (select distinct deal_id from push_notes where user_id = $user_id and deal_id is not null)
                        and tbl_deal.id not in (select distinct deal_id from tbl_deleted_deals where customer_id = $user_id and tbl_deleted_deals.deal_id=tbl_deal.id)
                        having distance <= $category_distance
                ";

                //log_message('error',"$category->$category_distance\n\n");
                //log_message('error',$qDeals);
                $rDeals = $this->db->query($qDeals);

                $this->apisql( __FUNCTION__, $user_id, $this->db->last_query(),$rDeals->result_array());

                if ($rDeals->num_rows() > 0) {
                    $arrDeals = $rDeals->result_array();
                   // print_r($arrDeals);

                    //we have deals, send the pushes

                    foreach ( $arrDeals as $deal) {

                        //conversiondeal
                        $deal['deal_time'] = utc_to_local($deal['deal_start'],$deal['deal_time'],$deal["timezone"]);
                        $deal['deal_end_time'] = utc_to_local($deal['deal_start'],$deal['deal_end_time'],$deal["timezone"]);

                        $a = array();
                        unset($deal["distance"]);
                        $a["info"] = array($deal);

                        $arr = array(
                            "deal_ids" => $deal["id"],
                            "alert" => json_encode($a),
                            "dt" => $user_info['device_token'],
                            "badge" => $user_info['badge'],
                            "sound" => "",
                            "user_id" => $user_id,
                            "join_id" => $deal["shop_id"],
                        );

                       $d = print_r($arr,true);
                       log_message('error',$d);
                        //print_r($user_info);
 

                        if ($user_info['device_type'] == 1) {
                            $this->sendPushNotificationIOS($arr, $user_info['device_token'], 'dgg.pem', '', 'product', "Deal On the GOGO : Deals", $arr['badge'], 'default');
                        } else if ($user_info['device_type'] == 2) {
                            $this->send_android_push($arr);
                        }


                        $nlogarr = array(
                            'user_id' => $user_id,
                            'push_text' => $deal["deal_title"],
                            'join_id' => $deal["shop_id"],
                            'deal_id' =>$deal["id"],
                            'date' => date('Y-m-d'),
                            'jsonDetails' => json_encode($deal)
                        );
                        $this->notification_log($nlogarr);
                    }
                }
            } // if
        } //foreach

        $status_code = 1;
        $this->_sendResponse($status_code);
    }
     public function push_send_user(){
       $user_id=$_POST['user_id'];
       $push_text=$_POST['push_text'];
       $page_id=0;
       if(isset($_GET['page_id'])){
           $page_id=$_GET['page_id'];
       }

       $this->apilog(__FUNCTION__, $user_id, $_POST, json_encode($_GET));


       $user_info = $this->get_user_info($user_id, "name,device_token,device_type,name,badge");
       $arr = array(
                    "alert" => $push_text,
                    "dt" => $user_info['device_token'],
                    "badge" => $user_info['badge'],
                    "sound" => "",
                    "user_id" => $user_id,
                    "join_id" => $user_id,
                );
                if ($user_info['device_type'] == 1) {
                    //$this->send_ios_push($arr);
                    $this->sendPushNotificationIOS($push_arr, $user_info['device_token'], 'push_pro.pem', '123456', 'product', "Deal On the GOGO : Deals", $push_arr['badge'], 'default');
                } else if ($user_info['device_type'] == 2) {
                    $this->send_android_push($arr);
                   
                }
         $message = "Yuo have successfully sent the push message to ".$user_info['name'];
            $this->session->set_userdata('current_message', $message);
        redirect('site/list_consumers/?page_id='.$page_id, 'refresh');
    }

    public function get_deal_info() {
        ###############  post parameter #######

        $user_id = $this->is_require($this->postvars, 'user_id');
        $user_token = $this->is_require($this->postvars, 'user_token');
        $deal_id = $this->is_require($this->postvars, 'deal_id');
        $result = array();
        #######################################

        $this->apilog(__FUNCTION__, $user_id, $_POST, '');

        $this->Is_authorised($user_id, $user_token);
        //$user_info = $this->get_user_info($user_id, "device_token,device_type,name");

        $q = "select t1.*,t2.shop_name,t2.shop_cats,t2.url,t2.shop_description,t2.shop_image,t2.address,t2.email,t2.latitude,t2.longitude 
        from tbl_deal t1 join tbl_shop t2 on t1.shop_id = t2.shop_id where t1.deal_id = $deal_id limit 1";
        $info = $this->m_common->select_custom($q);
        foreach ($info as $k => $v) {
            $result = $v;
            $result['shop_cats'] = $this->get_shop_cats_names($v['shop_cats']);
            $result['shop_image'] = $this->get_shop_image_path($v['shop_image']);
            $result['deal_start'] = date("F j, Y, g:i a", strtotime($v['deal_start']." ".$result['deal_time']));
            $result['deal_end'] = date("F j, Y, g:i a", strtotime($v['deal_end']." ".$result['deal_end_time']));
        }
        $this->result = $result;
        $status_code = 1;
        $this->_sendResponse($status_code);
    }
    public function get_push_deal_info() {
        ###############  post parameter #######
        
        $deal_ids = $this->is_require($this->postvars, 'deal_ids');

        $this->apilog(__FUNCTION__, 0, $_POST, '');

        $result = array();

        #######################################
        if(empty($deal_ids)){
            $deal_ids=0;
        }
  
        $q = "  select 
                    t1.*,
                    t2.shop_name,
                    t2.shop_cats,
                    t2.url,t2.shop_description,
                    t2.shop_image,
                    t2.address,
                    t2.email,
                    t2.latitude,
                    t2.longitude 
                from tbl_deal t1 join tbl_shop t2 on t1.shop_id = t2.shop_id 

                where 
                    t1.id in ($deal_ids) 
                    and t1.id not in (select distinct deal_id from tbl_deleted_deals where customer_id = $user_id and tbl_deleted_deals.deal_id=t1.id)";

        $info = $this->m_common->select_custom($q);
        foreach ($info as $k => $v) {
            $result[$k] = $v;
            $result[$k]['shop_cats'] = $this->get_shop_cats_names($v['shop_cats']);
            $result[$k]['shop_image'] = $this->get_shop_image_path($v['shop_image']);
            $result[$k]['deal_image'] = $this->get_deal_image_path($v['deal_image'],$this->get_shop_image_path($v['shop_image']));
            $result[$k]['deal_start'] = date("F j, Y, g:i a", strtotime($v['deal_start']));
            $result[$k]['deal_end'] = date("F j, Y, g:i a", strtotime($v['deal_end']));

            $result[$k]['deal_start'] = date("F j, Y, g:i a", $result[$k]['deal_time']);
            $result[$k]['deal_end'] = date("F j, Y, g:i a", $result[$k]['deal_end_time']);
        }
        $this->result['info'] = $result;
        $status_code = 1;
        $this->_sendResponse($status_code);
    }

    public function get_shop_info() {
        ###############  post parameter #######

        $user_id = $this->is_require($this->postvars, 'user_id');
        $user_token = $this->is_require($this->postvars, 'user_token');
        $shop_id = $this->is_require($this->postvars, 'shop_id');

        $this->apilog(__FUNCTION__, $user_id, $_POST, '');

        $result = array();
        #######################################

        $this->Is_authorised($user_id, $user_token);
        //$user_info = $this->get_user_info($user_id, "device_token,device_type,name");

        $q = "select t2.shop_name,t2.shop_cats,t2.shop_description,t2.shop_image,t2.address,t2.email,t2.url,t2.latitude,t2.longitude
              from tbl_shop t2 where t1.shop_id = $shop_id limit 1";
        $info = $this->m_common->select_custom($q);
        foreach ($info as $k => $v) {
            $result = $v;
            $result['shop_cats'] = $this->get_shop_cats_names($v['shop_cats']);
            $result['shop_image'] = $this->get_shop_image_path($v['shop_image']);
        }
        $this->result = $result;
        $status_code = 1;
        $this->_sendResponse($status_code);
    }


    public function get_active_deals(){

        $business_id = $this->is_require($this->postvars, 'business_id');
        $user_id = $this->is_require($this->postvars, 'user_id');
        $user_token = $this->is_require($this->postvars, 'user_token');
        $time_in_sec = $this->is_require($this->postvars, 'time_in_sec');// in integer
        $cs = $this->is_require($this->postvars, 'localtime');//in Y-m-d
        $this->cs = $cs;

        $timezone = isset($_POST['timezone']) ? $_POST['timezone']: 'EST';

        $this->apilog(__FUNCTION__, $user_id, $_POST, '');

        $this->Is_authorised($user_id, $user_token);

        $q = "

            select 
                    tbl_deal.id as deal_id,
                    tbl_deal.shop_id,
                    tbl_deal.deal_title,
                    tbl_deal.deal_description, 
                    tbl_deal.original_price,
                    tbl_deal.offer_price,
                    tbl_deal.deal_image,
                    tbl_deal.deal_start,
                    tbl_deal.deal_end,
                    tbl_deal.deal_time,
                    tbl_deal.deal_end_time,
                    tbl_deal.featured_deal,
                    tbl_deal.contact_name,
                    tbl_deal.contact_number,
                    tbl_deal.website,
                    0 as share_count,
                    tbl_deal.status,
                    tbl_shop.shop_name,
                    tbl_shop.pin,
                    tbl_shop.shop_cats,
                    tbl_shop.shop_description,
                    tbl_shop.shop_image,
                    tbl_shop.address,
                    tbl_shop.zip_code,
                    tbl_shop.latitude,
                    tbl_shop.longitude,
                    (select count(*) from tbl_deal_favorite where tbl_deal_favorite.deal_id = tbl_deal.id and tbl_deal_favorite.user_id=$user_id) as isfavorite,
                    (select count(*) from loyaltyprograms where loyaltyprograms.shop_id=tbl_shop.shop_id ) as blnHasCL
                    
            from tbl_deal join tbl_shop on tbl_deal.shop_id = tbl_shop.shop_id

             where 
            tbl_deal.shop_id=$business_id
            and tbl_deal.is_active=1 
            and tbl_deal.is_off=0
            and tbl_deal.deal_end_time > UNIX_TIMESTAMP()
            and tbl_deal.deal_time <= $time_in_sec 
            and tbl_deal.deal_end_time >= $time_in_sec 
            and tbl_deal.id not in (select distinct deal_id from tbl_deleted_deals where customer_id = $user_id and tbl_deleted_deals.deal_id=tbl_deal.id)
        ";

        //log_message('error',$q);

        $info = $this->m_common->select_custom($q);

        $this->apisql( __FUNCTION__, $user_id, $this->db->last_query(), $info);

        //conversiondeal --determine if we need the time conversion code here.
        $data = array(
            "info" => $info,
        );
        $this->result = $data;
        $status_code = 1;
        $this->_sendResponse($status_code);


        
    }

          
    public function get_push_deals(){

        $user_id = $this->is_require($this->postvars, 'user_id');
        $user_token = $this->is_require($this->postvars, 'user_token');
        $time_in_sec = $this->is_require($this->postvars, 'time_in_sec');// in integer
        $cs = $this->is_require($this->postvars, 'localtime');//in Y-m-d
        $this->cs = $cs;

        $timezone = isset($_POST['timezone']) ? $_POST['timezone']: 'EST';


        $this->apilog(__FUNCTION__, $user_id, $_POST, '');

        $this->Is_authorised($user_id, $user_token);

        $q = "

            select 
                    distinct
                    push_notes.deal_id, 
                    push_text as deal_title, 
                    isRead,
                    tbl_deal.shop_id,
                    tbl_deal.deal_description, 
                    tbl_deal.original_price,
                    tbl_deal.offer_price,
                    tbl_deal.deal_image,
                    tbl_deal.deal_start,
                    tbl_deal.deal_end,
                    tbl_deal.deal_time,
                    tbl_deal.deal_end_time,
                    tbl_deal.featured_deal,
                    tbl_deal.contact_name,
                    tbl_deal.contact_number,
                    tbl_deal.website,
                    0 as share_count,
                    tbl_deal.status,
                    tbl_shop.shop_name,
                    tbl_shop.pin,
                    tbl_shop.shop_cats,
                    tbl_shop.shop_description,
                    tbl_shop.shop_image,
                    tbl_shop.address,
                    tbl_shop.zip_code,
                    tbl_shop.latitude,
                    tbl_shop.longitude,
                    (select count(*) from tbl_deal_favorite where tbl_deal_favorite.deal_id = push_notes.deal_id and tbl_deal_favorite.user_id=push_notes.user_id) as isfavorite,
                    (select count(*) from loyaltyprograms where loyaltyprograms.shop_id=tbl_shop.shop_id ) as blnHasCL
                    
            from push_notes join tbl_deal on push_notes.deal_id = tbl_deal.id join tbl_shop on tbl_deal.shop_id = tbl_shop.shop_id

             where 
            push_notes.user_id=$user_id
            and tbl_deal.is_active=1 
            and tbl_deal.is_off=0
            and tbl_deal.deal_end_time > UNIX_TIMESTAMP()
            and tbl_deal.deal_time <= $time_in_sec 
            and tbl_deal.deal_end_time >= $time_in_sec 
            and tbl_deal.id not in (select distinct deal_id from tbl_deleted_deals where customer_id = $user_id and tbl_deleted_deals.deal_id=tbl_deal.id)

            order by push_notes.id desc


        ";


$qtest = "

            select 
                    distinct
                    push_notes.deal_id, 
                    push_text as deal_title, 
                    isRead,
                    tbl_deal.shop_id,
                    tbl_deal.deal_description, 
                    tbl_deal.original_price,
                    tbl_deal.offer_price,
                    tbl_deal.deal_image,
                    tbl_deal.deal_start,
                    tbl_deal.deal_end,
                    tbl_deal.deal_time,
                    tbl_deal.deal_end_time,
                    tbl_deal.featured_deal,
                    tbl_deal.contact_name,
                    tbl_deal.contact_number,
                    tbl_deal.website,
                    0 as share_count,
                    tbl_deal.status,
                    tbl_shop.shop_name,
                    tbl_shop.pin,
                    tbl_shop.shop_cats,
                    tbl_shop.shop_description,
                    tbl_shop.shop_image,
                    tbl_shop.address,
                    tbl_shop.zip_code,
                    tbl_shop.latitude,
                    tbl_shop.longitude,
                    (select count(*) from tbl_deal_favorite where tbl_deal_favorite.deal_id = push_notes.deal_id and tbl_deal_favorite.user_id=push_notes.user_id) as isfavorite,
                    (select count(*) from loyaltyprograms where loyaltyprograms.shop_id=tbl_shop.shop_id ) as blnHasCL
                    
            from push_notes join tbl_deal on push_notes.deal_id = tbl_deal.id join tbl_shop on tbl_deal.shop_id = tbl_shop.shop_id

             where 
            -- push_notes.user_id=$user_id
            -- and tbl_deal.is_active=1 
             tbl_deal.is_off=0
             and tbl_deal.deal_end_time > UNIX_TIMESTAMP()
            
            and tbl_deal.id not in (select distinct deal_id from tbl_deleted_deals where customer_id = $user_id and tbl_deleted_deals.deal_id=tbl_deal.id)

            order by push_notes.id desc

            limit 10;
        ";
        //log_message('error',$q);

        if (isset($_POST["test"])) { 
           $info = $this->m_common->select_custom($qtest);
        } else {
           $info = $this->m_common->select_custom($q); 
        }
        

        $this->apisql( __FUNCTION__, $user_id, $this->db->last_query(), $info);

        foreach($info as $k=>$v) {
            $info[$k]['deal_image'] = $this->get_deal_image_path($v['deal_image'],$this->get_shop_image_path($v['shop_image']));                      
            $info[$k]['deal_start'] = date("F j, Y, g:i a", $info[$k]['deal_time']);
            $info[$k]['deal_end'] = date("F j, Y, g:i a", $info[$k]['deal_end_time']);
        }      

        $data = array(
            "info" => $info,
        );
        $this->result = $data;
        $status_code = 1;
        $this->_sendResponse($status_code);


        
    }

    public function hot_search_radius() {
        ###############  post parameter #######

        $user_id = $this->is_require($this->postvars, 'user_id');
        $user_token = $this->is_require($this->postvars, 'user_token');
        $time_in_sec = $this->is_require($this->postvars, 'time_in_sec');// in integer
        $cs = $this->is_require($this->postvars, 'localtime');//in Y-m-d

        if (!isset($_POST["radius"])) {$_POST["radius"]=5;}

        $radius = $_POST["radius"];

        $timezone = isset($_POST['timezone']) ? $_POST['timezone']: 'EST';

        //echo $timezone;

        $this->apilog(__FUNCTION__, $user_id, $_POST, '');

        $this->cs = $cs; 
        //echo $cs;
        $next_sunday=date('Y-m-d',strtotime('next sunday'));
        #######################################

        $this->Is_authorised($user_id, $user_token);
        //$user_info = $this->get_user_info($user_id, "device_token,device_type,name");
        $selected_cats=array();
        $user_cat = $this->m_common->db_select('user_cat,latitude,longitude', 'tbl_customer', array("user_id" => $user_id), array(), '', '', '', 'row_array', 0);
        if(!empty($user_cat)){
            $cat_ids = $user_cat['user_cat'];
            $selected_cats = explode(",", $cat_ids);
        }

        //echo "<pre>";print_r($selected_cats);

        $latitude = $user_cat['latitude'];
        $longitude = $user_cat['longitude'];

        $current_day = date("N");
        $q = "select 
                    t1.*,
                    t2.shop_name,
                    t2.pin,
                    t2.shop_cats,
                    t2.shop_description,
                    t2.shop_image,
                    t2.address,
                    t2.email,
                    t2.url,
                    t2.latitude,
                    t2.longitude,
                    (select count(*) from tbl_deal_favorite where tbl_deal_favorite.deal_id = t1.id and tbl_deal_favorite.user_id='$user_id') as isfavorite,
                    (select count(*) from loyaltyprograms where loyaltyprograms.shop_id=t2.shop_id ) as blnHasCL,
                    (((acos(sin(($latitude*pi()/180)) * sin((`latitude`*pi()/180))+cos(($latitude*pi()/180)) * cos((`latitude`*pi()/180))
                 * cos((($longitude - `longitude`)*pi()/180))))*180/pi())*60*1.1515) AS `distance`

            from tbl_deal t1 join tbl_shop t2 on t1.shop_id = t2.shop_id 
            
            where 
            
            is_active=1 
            and is_off=0
            and t1.deal_end_time > UNIX_TIMESTAMP()
            and t1.deal_time <= '$time_in_sec' and t1.deal_end_time >= '$time_in_sec'  
            and t1.id not in (select distinct deal_id from tbl_deleted_deals where customer_id = $user_id and tbl_deleted_deals.deal_id=t1.id)
            having distance <= $radius
            order by t1.date desc";

        $info = $this->m_common->select_custom($q);

        $this->apisql( __FUNCTION__, $user_id, $this->db->last_query(), $info);

        //log_message('error',$this->db->last_query());
        //echo $this->db->last_query();


        $final_info=array();
        foreach ($info as $k => $v) {
            $flag=0;
            $row_cats = explode(",", $v['shop_cats']);
            //echo "<pre>";print_r($row_cats);
            foreach ($row_cats as $rc) {
                if (in_array($rc, $selected_cats)) {
                    $flag = 1;
                    break;
                }
            }
            if(!$flag){

                continue;
            }


            $info[$k]['shop_cats'] = $this->get_shop_cats_names($v['shop_cats']);
            $info[$k]['shop_image'] = $this->get_shop_image_path($v['shop_image']);
            $info[$k]['deal_image'] = $this->get_deal_image_path($v['deal_image'],$this->get_shop_image_path($v['shop_image']));

            $info[$k]['deal_start'] = date("F j, Y, g:i a", $info[$k]['deal_time']);
            $info[$k]['deal_end'] = date("F j, Y, g:i a", $info[$k]['deal_end_time']);
            $final_info[]=$info[$k];
        }
        //echo "<pre>";print_r($info);exit;
        $data = array(
            "info" => $final_info,
        );
        $this->result = $data;
        $status_code = 1;
        $this->_sendResponse($status_code);
    }

    public function hot_search_city() {
        ###############  post parameter #######

        $user_id = $this->is_require($this->postvars, 'user_id');
        $user_token = $this->is_require($this->postvars, 'user_token');
        $city_name = $this->is_require($this->postvars, 'city_name');
        $time_in_sec = $this->is_require($this->postvars, 'time_in_sec');// in integer
        $cs = $this->is_require($this->postvars, 'localtime');//in Y-m-d
        //$cs = isset($_POST['localtime']) ? $_POST['localtime'] : "";

        $timezone = isset($_POST['timezone']) ? $_POST['timezone']: 'EST';

       
        $this->apilog(__FUNCTION__, $user_id, $_POST, '');


        $this->cs = $cs;
        //echo $cs;
        $next_sunday=date('Y-m-d',strtotime('next sunday'));
        #######################################

        $this->Is_authorised($user_id, $user_token);
        //$user_info = $this->get_user_info($user_id, "device_token,device_type,name");
        

        $place_array = explode(",", $city_name);

        if (count($place_array)!=2){

            $status_code=0;
            $this->msg = "City Name needs to be in the format City, St";
             $this->_sendResponse($status_code);
             exit;
        }

        $city = trim($place_array[0]);
        $state_code = trim($place_array[1]);

        if (strlen($state_code)!=2){
            $status_code=0;
            $this->msg = "City Name needs to be in the format City, St";
            $this->_sendResponse($status_code);
            exit;

        }







        $selected_cats=array();
        $user_cat = $this->m_common->db_select('user_cat', 'tbl_customer', array("user_id" => $user_id), array(), '', '', '', 'row_array', 0);
        if(!empty($user_cat)){
            $cat_ids = $user_cat['user_cat'];
            $selected_cats = explode(",", $cat_ids);
        }

        //echo "<pre>";print_r($selected_cats);

        $current_day = date("N");
        $q = "select 
                    t1.*,
                    t2.shop_name,
                    t2.pin,
                    t2.shop_cats,
                    t2.shop_description,
                    t2.shop_image,
                    t2.address,
                    t2.email,
                    t2.url,
                    t2.latitude,
                    t2.longitude, 
                    t2.zip_code, 
                    t3.city_name,
                    (select count(*) from tbl_deal_favorite where tbl_deal_favorite.deal_id = t1.id and tbl_deal_favorite.user_id='$user_id') as isfavorite,
                    (select count(*) from loyaltyprograms where loyaltyprograms.shop_id=t2.shop_id ) as blnHasCL
            from tbl_shop t2 
                join tbl_deal t1 on t1.shop_id = t2.shop_id 
                join tbl_city t3 on t2.city_id = t3.city_id 
                join tbl_state t4 on t2.state_id = t4.sid
            where 
                t1.is_active=1 
                and t1.is_off=0
                and t1.deal_end_time > UNIX_TIMESTAMP()
                and t1.id not in (select distinct deal_id from tbl_deleted_deals where customer_id = $user_id and tbl_deleted_deals.deal_id=t1.id)
                and t1.deal_time <= '$time_in_sec' and t1.deal_end_time >= '$time_in_sec'  
                and t3.city_name='$city'
                and t4.code='$state_code'
                order by t1.date desc
             ";


        $info = $this->m_common->select_custom($q);

        $this->apisql( __FUNCTION__, $user_id, $this->db->last_query(), $info);


        $final_info=array();
        foreach ($info as $k => $v) {
            $flag=0;
            $row_cats = explode(",", $v['shop_cats']);
            //echo "<pre>";print_r($row_cats);
            foreach ($row_cats as $rc) {
                if (in_array($rc, $selected_cats)) {
                    $flag = 1;
                    break;
                }
            }
            if(!$flag){

                continue;
            }


            $info[$k]['shop_cats'] = $this->get_shop_cats_names($v['shop_cats']);
            $info[$k]['shop_image'] = $this->get_shop_image_path($v['shop_image']);
            $info[$k]['deal_image'] = $this->get_deal_image_path($v['deal_image'],$this->get_shop_image_path($v['shop_image']));

            $info[$k]['deal_start'] = date("F j, Y, g:i a", $info[$k]['deal_time']);
            $info[$k]['deal_end'] = date("F j, Y, g:i a", $info[$k]['deal_end_time']);
            $final_info[]=$info[$k];
        }

        $g = "select latitude, longitude from geo_postalcodes where place_name=? and state2 =? and country_code=? limit 1";
        $result = $this->db->query($g, array( $city, $state_code, 'US'));


        $geo = $result->row();
        //print_rr($this->db->last_query());
        //print_rr($geo,0);

        //echo "<pre>";print_r($info);exit;
        $data = array(
            "geo" => $geo,
            "info" => $final_info,
        );


       
        $this->result = $data;
        $status_code = 1;
        $this->_sendResponse($status_code);
    }

    public function hot_search() {
        ###############  post parameter #######

        $user_id = $this->is_require($this->postvars, 'user_id');
        $user_token = $this->is_require($this->postvars, 'user_token');
        $zip_code = $this->is_require($this->postvars, 'zip_code');
        $time_in_sec = $this->is_require($this->postvars, 'time_in_sec');// in integer
        $cs = $this->is_require($this->postvars, 'localtime');//in Y-m-d
        //$cs = isset($_POST['localtime']) ? $_POST['localtime'] : "";

        $timezone = isset($_POST['timezone']) ? $_POST['timezone']: 'EST';

        $this->apilog(__FUNCTION__, $user_id, $_POST, '');

        $this->cs = $cs;


        #######################################

        $this->Is_authorised($user_id, $user_token);
        //$user_info = $this->get_user_info($user_id, "device_token,device_type,name");
        $selected_cats=array();
        $user_cat = $this->m_common->db_select('user_cat', 'tbl_customer', array("user_id" => $user_id), array(), '', '', '', 'row_array', 0);
        if(!empty($user_cat)){ 
            $cat_ids = $user_cat['user_cat'];
            $selected_cats = explode(",", $cat_ids);
        }
        
        //echo "<pre>";print_r($selected_cats);

        $current_day = date("N");
        $q = "
            select 
                t1.*,
                t2.shop_name,
                t2.pin,
                t2.shop_cats,
                t2.shop_description,
                t2.shop_image,
                t2.address,
                t2.email,
                t2.url,
                t2.latitude,
                t2.longitude,
                (select count(*) from tbl_deal_favorite where tbl_deal_favorite.deal_id = t1.id and tbl_deal_favorite.user_id='$user_id') as isfavorite,
                (select count(*) from loyaltyprograms where loyaltyprograms.shop_id=t2.shop_id ) as blnHasCL
            from tbl_deal t1 join tbl_shop t2 on t1.shop_id = t2.shop_id 
            where 
                t2.zip_code = '$zip_code'  
                and is_active=1 
                and is_off=0
                and t1.id not in (select distinct deal_id from tbl_deleted_deals where customer_id = $user_id and tbl_deleted_deals.deal_id=t1.id)   
                and t1.deal_end_time > UNIX_TIMESTAMP()
                and t1.deal_time <= '$time_in_sec' 
                and t1.deal_end_time >= '$time_in_sec'  
            order by t1.date desc";
        
        $info = $this->m_common->select_custom($q);

        $this->apisql( __FUNCTION__, $user_id, $this->db->last_query(), $info);


        $final_info=array();
        foreach ($info as $k => $v) {
            $flag=0;
            $row_cats = explode(",", $v['shop_cats']);
            //echo "<pre>";print_r($row_cats);
            foreach ($row_cats as $rc) {
                if (in_array($rc, $selected_cats)) {
                    $flag = 1;
                    break;
                }
            }
            if(!$flag){
                
                continue;
            }
            
        
            $info[$k]['shop_cats'] = $this->get_shop_cats_names($v['shop_cats']);
            $info[$k]['shop_image'] = $this->get_shop_image_path($v['shop_image']);
            $info[$k]['deal_image'] = $this->get_deal_image_path($v['deal_image'],$this->get_shop_image_path($v['shop_image']));

            $info[$k]['deal_start'] = date("F j, Y, g:i a", $info[$k]['deal_time']);
            $info[$k]['deal_end'] = date("F j, Y, g:i a", $info[$k]['deal_end_time']);
            $final_info[]=$info[$k];
        }


        $g = "select latitude, longitude from geo_postalcodes where postal_code=? and country_code=? limit 1";
        $result = $this->db->query($g, array( $zip_code, 'US'));


        $geo = $result->row();
        //print_rr($this->db->last_query());
        //print_rr($geo,0);

        //echo "<pre>";print_r($info);exit;
        $data = array(
            "geo" => $geo,
            "info" => $final_info,
        );
        $this->result = $data;
        $status_code = 1;
        $this->_sendResponse($status_code);
    }

    public function get_catname() {
        $user_id = $this->is_require($this->postvars, 'user_id');

        $this->apilog(__FUNCTION__, $user_id, $_POST, '');


        #######################################
        $user_cats=array();
        $user_cat = $this->m_common->db_select('user_cat', 'tbl_customer', array("user_id" => $user_id), array(), '', '', '', 'row_array', 0);
        if(!empty($user_cat)){
            $user_cats = (array) explode(",", $user_cat['user_cat']);
        }
        
        $q = "select latitude, longitude from customer_location where user_id=? order by id desc limit 1";
        $result = $this->db->query($q, array( $user_id));
        $geo = $result->row();
        $lat = $geo->latitude;
        $lon = $geo->longitude;

        //$info = $this->m_common->db_select('cid,cname,cimage', 'tbl_category', '', array(), 'cname', '', '', 'all', 0);
        $q="

            SELECT
                cid,
                cname,
                cimage,
                (
                    SELECT
                        count(*)
                    FROM
                        tbl_deal
                    JOIN tbl_shop ON tbl_deal.shop_id = tbl_shop.shop_id
                    WHERE
                        shop_cats = tbl_category.cid
                    AND is_active = 1
                    AND is_off = 0
                    AND (((acos(sin(($lat*pi()/180)) * sin((`latitude`*pi()/180))+cos(($lat*pi()/180)) * cos((`latitude`*pi()/180))
                             * cos((($lon - `longitude`)*pi()/180))))*180/pi())*60*1.1515) <=15 and deal_end_time > UNIX_TIMESTAMP() order by id desc limit 1
            ) as livedeals
            FROM
                tbl_category order by cname

        ";
        
        $info = $this->m_common->select_custom($q);

        $this->apisql( __FUNCTION__, $user_id, $this->db->last_query(), $info);



        foreach ($info as $k => $v) {
            $info[$k]['status'] = 0;
            if (in_array($v['cid'], $user_cats)) {
                $info[$k]['status'] = 1;
            }

            if ($info[$k]['cimage']!=''){$info[$k]['cimage']="http://".$_SERVER["SERVER_NAME"]."/uploads/".$info[$k]['cimage'];}

        }
        //echo "<pre>";print_r($info);exit;
        $this->result = array(
            'cat_info' => $info,
            'total_cat' => count($info),
        );
        $this->_sendResponse(1);
    }

    public function push_badge_reset() {
        $user_id = $this->is_require($this->postvars, 'user_id');
        $user_token = $this->is_require($this->postvars, 'user_token');

        $this->apilog(__FUNCTION__, $user_id, $_POST, '');

        $this->Is_authorised($user_id, $user_token);
        #######################################
        $this->reset_badge($user_id);
        $this->_sendResponse(1);
    }

    public function forgot_password(){
        /*
         *  send the reset password link to user by email with secret token that is update for user record.
         */
        $ct = date('Y-m-d H:i:s'); // current time
        $dt = date('l jS \of F Y \a\t h:i:s A'); // in mail display time text 

        ###############  post parameter #######
        $email = $this->is_require($this->postvars, 'email');

        $this->apilog(__FUNCTION__, 0, $_POST, '');

        #######################################

        $where = array(
            "email" => $email,
        );
        $info = $this->m_common->db_select('user_id,email,name,password', "tbl_customer", $where, array(), '', '', '', 'row_array');

        if (empty($info)) {
            $this->_sendResponse(3);
        }
        $secret_token = $this->get_random_string(15);
        $this->m_common->update_entry("tbl_customer", array('secret_token' => $secret_token,), array('user_id' => $info['user_id']));
        $ua = $this->get_useragent();
        $link = base_url() . "c_support/forgot_password?secret_token=" . $secret_token . "&ua=" . $ua."&is_consumer=1";
        $email_message = $this->load->view('email/forgot_password', $data = array('link' => $link, 'dt' => $dt, 'name' => $info['name']), TRUE);


        $arr = array(
            'subject' => "Deal on GOGO Network : Forgot Password",
            'message_body' => $email_message,
            'to' => $email,
        );
        $this->send_email($arr);
        $this->_sendResponse(1);
    }
    public function send_reset_password() {
        $ct = date('Y-m-d H:i:s'); // current time
        $dt = date('l jS \of F Y \a\t h:i:s A'); // in mail display time text 
        $user_id=$_POST['user_id'];

        $this->apilog(__FUNCTION__, $user_id, $_POST, '');
       
        $user_info = $this->get_user_info($user_id);
        $email=$user_info['email'];
        $where = array(
            "email" => $email,
        );
        $info = $this->m_common->db_select('user_id,email,name,password', "tbl_customer", $where, array(), '', '', '', 'row_array');

        $secret_token = $this->get_random_string(15);
        $this->m_common->update_entry("tbl_customer", array('secret_token' => $secret_token,), array('user_id' => $info['user_id']));
        $ua = $this->get_useragent();
        $link = base_url() . "c_support/forgot_password?secret_token=" . $secret_token . "&ua=" . $ua;
        $email_message = $this->load->view('email/forgot_password', $data = array('link' => $link, 'dt' => $dt, 'name' => $info['name']), TRUE);


        $arr = array(
            'subject' => "Deal on GOGO Network : Reset Password",
            'message_body' => $email_message,
            'to' => $email,
        );
        $this->send_email($arr);
        
        //$message = "Reset password link has been successfully sent";
        //$this->session->set_userdata('current_message', $message);
        exit;
        //redirect('site/list_consumers/?page_id='.$page_id, 'refresh');
    }

    public function reset_password() {
        /*
         *  if user forgot the password than after callling forgot password api we call reset password api.
         */
        ###############  post parameter #######
        $secret_token = $this->is_require($this->postvars, 'secret_token');
        $new_password = $this->is_require($this->postvars, 'new_password');

        $p = $_POST;
        $p["new_password"]="";
        $this->apilog(__FUNCTION__, $user_id, $p, '');

        #######################################

        $user_tbl = "tbl_customer";
       
        $where = array(
            'secret_token' => $secret_token,
        );
        $info = $this->m_common->db_select('secret_token,user_id,email,user_token', $user_tbl, $where, array(), '', '', '', 'row');

        if (!empty($info)) {
            $status_code = 1;
            $this->m_common->update_entry($user_tbl, array('password' => $new_password, 'secret_token' => ''), array('user_id' => $info->user_id));
            

            $this->msg="password reset successfully";
        } else {
            $status_code = 0;
            $this->msg = "secret_token not valid";
        }
        $this->_sendResponse($status_code);
    }
    public function deal_share() {
        $user_id = $this->is_require($_POST, 'user_id');
        $user_token = $this->is_require($_POST, 'user_token');
        $deal_id = $this->is_require($_POST, 'deal_id');      
        
        $this->apilog(__FUNCTION__, $user_id, $_POST, '');

        $this->Is_authorised($user_id, $user_token);

        
        $q = "update tbl_deal set `share_count`=`share_count`+1 where id = $deal_id";
        $this->m_common->query_custom($q);
        $this->_sendResponse(1);
    }

    public function deal_check_activated() {

        $deal_id = $this->is_require($_POST, 'deal_id');
        $customer_id = $this->is_require($_POST, 'customer_id');

        $this->apilog(__FUNCTION__, $customer_id, $_POST, '');


        $q = "select t1.*
              from tbl_deals_activated t1 where t1.deal_id = '$deal_id' and t1.customer_id = '$customer_id'";


        $info = $this->m_common->select_custom($q);

        $final_info=array();
        foreach ($info as $k => $v) {

            $final_info[]=$info[$k];
        }
        //echo "<pre>";print_r($info);exit;
        $data = array(
            "info" => $final_info,
        );
        $this->result = $data;
        $status_code = 1;
        $this->_sendResponse($status_code);
    }

    public function activate_deal(){
        $user_id = $this->is_require($_POST, 'user_id');
        $user_token = $this->is_require($_POST, 'user_token');
        $deal_id = $this->is_require($_POST, 'deal_id');
        $business_id = $this->is_require($_POST, 'business_id');
        $lat=0;
        $lng=0;

        $this->apilog(__FUNCTION__, $user_id, $_POST, '');
        $this->Is_authorised($user_id, $user_token);

        $ins = array(
                'customer_id' => $user_id,
                'business_id' => $business_id,
                'deal_id' => $deal_id,
                'lat' => $lat,
                'lng' => $lng,
            );
            $this->m_common->insert_entry('tbl_deals_activated', $ins);

            $this->loyaltyprogram->calculateRewards($user_id, $business_id);

             $status_code = 1;
            $this->_sendResponse($status_code);





    }

    public function deal_activated() {
        $user_id = $this->is_require($_POST, 'user_id');
        $user_token = $this->is_require($_POST, 'user_token');
        $deal_id = $this->is_require($_POST, 'deal_id');      
        $business_id = $this->is_require($_POST, 'business_id');      
        $lat = $this->is_require($_POST, 'lat');      
        $lng = $this->is_require($_POST, 'lng'); 

        $this->apilog(__FUNCTION__, $user_id, $_POST, '');


        $this->Is_authorised($user_id, $user_token);

        $query = "select t2.*
              from tbl_deals_activated t2 where t2.deal_id=$deal_id and t2.customer_id='$user_id'";
        $info = $this->m_common->select_custom($query);


        if (sizeof($info) > 0) {
            foreach ($info as $k => $v) {
//                $info[$k]['is_active'] = '1';
                $final_info[]=$info[$k];
            }
            //echo "<pre>";print_r($info);exit;
            $data = array(
                "info" => $final_info,
            );
            $this->result = $data;
            $status_code = 1;
            $this->_sendResponse($status_code);
        }
        else {

            $ins = array(
                'customer_id' => $user_id,
                'business_id' => $business_id,
                'deal_id' => $deal_id,
                'lat' => $lat,
                'lng' => $lng,
            );
            $this->m_common->insert_entry('tbl_deals_activated', $ins);

            $this->loyaltyprogram->calculateRewards($user_id, $business_id);

            $query = "select t2.*
              from tbl_deals_activated t2 where t2.deal_id = $deal_id";
            $info = $this->m_common->select_custom($query);
            $final_info=array();

            foreach ($info as $k => $v) {
//                $info[$k]['is_active'] = '1';
                $final_info[]=$info[$k];
            }
            //echo "<pre>";print_r($info);exit;
            $data = array(
                "info" => $final_info,
            );
            $this->result = $data;
            $status_code = 1;
            $this->_sendResponse($status_code);
        }
    
    }
    public function change_password() {
        /*
         *  if user wants to change the password
         */
        ###############  post parameter #######
        $user_id = $this->is_require($this->postvars, 'user_id');  // customer id   or business id
        $user_token = $this->is_require($this->postvars, 'user_token');
        $new_password = $this->is_require($this->postvars, 'new_password');
        $old_password = $this->is_require($this->postvars, 'old_password');
        $login_by = $this->is_require($this->postvars, 'login_by');

        $p = $_POST;
        $p["new_password"]="";
        $p["old_password"]="";


        $this->apilog(__FUNCTION__, $user_id, $p, '');


        #######################################

        if ($login_by == "customer") {
            $user_tbl = "tbl_customer";
            $this->Is_authorised($user_id, $user_token);
        } else if ($login_by == "business") {
            $user_tbl = 'tbl_businessuser';
            $this->Is_authorised($user_id, $user_token, "tbl_businessuser");
        } else {
            $this->msg = "The parameter login_by is not valid";
            $this->_sendResponse(500);
        }

        $where = array(
            'user_id' => $user_id,
        );
        $info = $this->m_common->db_select('password', $user_tbl, $where, array(), '', '', '1', 'row');

        if (md5($old_password) == $info->password) {
            $this->m_common->update_entry($user_tbl, array('password' => md5($new_password)), array('user_id' => $user_id));
            $status_code = 200;
            $this->msg = "New Password Updated Succesfully";
        } else {
            $status_code = 800;
            $this->msg = "Old Password Incorrect";
        }
        $this->_sendResponse($status_code);
    }

    public function logout() {
        /*
         *  in case logout the user token is cleared
         */
        ###############  post parameter #######
        $user_id = $this->is_require($this->postvars, 'user_id');  // customer id   or business id
        $user_token = $this->is_require($this->postvars, 'user_token');
        $logib_by = isset($this->postvars['login_by']) ? $this->postvars['login_by'] : 'business';

        $this->apilog(__FUNCTION__, $user_id, $_POST, '');

        #######################################
        if ($logib_by == "business") {
            $this->Is_authorised($user_id, $user_token, 'tbl_businessuser');
            $tbl = 'tbl_businessuser';
        } else {
            $this->Is_authorised($user_id, $user_token);
            $tbl = 'tbl_customer';
        }
        #######################################        
        //$this->m_common->update_entry($tbl, array("device_token" => ""), array('user_id' => $user_id));
        $status_code = 200;
        $this->msg = "You are successfully logout";
        $this->_sendResponse($status_code);
    }

    ################ private functions ###########################

    private function is_require($a, $i) {
        if (!isset($a[$i]) || $a[$i] == '') {
            $this->msg = $i . " parameter missing or it should not null";
            $this->_sendResponse(11);
            exit;
        } else {
            return $a[$i];
        }
    }

    private function Is_authorised($user_id, $user_token, $tbl = 'tbl_customer') {
        $where = array(
            'user_id' => $user_id,
            'user_token' => $user_token,
        );
        $t = $this->m_common->db_select('count(*) as cnt', $tbl, $where, '', '', '', '', 'row', 0);
        if ($t->cnt < 1) {
            $this->_sendResponse(5); //you are not authorised to view this page.
            exit;
        }
    }

    private function get_secret_token($tbl) {
        static $loop = 0;
        $length = 15;
        $alphabet = "abcdefghijklmnopqrstuwxyzABCDEFGHIJKLMNOPQRSTUWXYZ0123456789";
        $token = "";
        $alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
        for ($i = 0; $i < $length; $i++) {
            $n = rand(0, $alphaLength);
            $token.= $alphabet[$n];
        }
        $t = $this->m_common->db_select('secret_token', $tbl, array('secret_token' => $token, 'secret_token !=' => ''), '', '', '', '1', 'row');
        if (!empty($t) && $loop < 5) {
            $loop++;
            $token = $this->get_secret_token($tbl);
        }
        return $token;
    }

    private function get_random_string($length = 10) {
        $alphabet = "abcdefghijklmnopqrstuwxyzABCDEFGHIJKLMNOPQRSTUWXYZ0123456789";
        $token = "";
        $alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
        for ($i = 0; $i < $length; $i++) {
            $n = rand(0, $alphaLength);
            $token.= $alphabet[$n];
        }
        return $token;
    }

    private function get_useragent() {
        $ua = 'web';
        if (isset($_SERVER['HTTP_USER_AGENT'])) {
            $str = $_SERVER['HTTP_USER_AGENT'];
            if (stripos($str, 'iPhone') !== FALSE) {
                $ua = 'iPhone';
            } else if (stripos($str, 'android') !== FALSE) {
                $ua = 'Android';
            } else {
                $ua = 'web';
            }
        }
        return $ua;
    }

    private function save_push($user_id, $pending_pipe, $alert, $sound, $badge_no = 0, $tag = 1) {
        $pending_pipe_arr = array();
        if ($pending_pipe != '') {
            $pending_pipe_arr = $this->myunserialize($pending_pipe);
        }
        $new = array(
            'alert' => $alert, 'sound' => $sound, 'badge' => $badge_no,
        );

        $pending_pipe_arr[$tag][] = $new;
        $pending_pipe = $this->myserialize($pending_pipe_arr);
        $set_array = array('pending_notification' => $pending_pipe);
        $this->model->Update('tbl_user', $set_array, 'user_id=:u', array(':u' => $user_id));
        //echo "<pre>";print_r($pending_pipe_arr);exit;
    }

    private function send_push($arr) {
        if ($arr['dt'] == '1') {
            $this->load->library('apn');
            $this->apn->payloadMethod = 'enhance';
            $this->apn->connectToPush();
            $this->apn->setData(array('someKey' => true));
            $send_result = $this->apn->sendMessage($arr['device_token'], $arr['alert'], $arr['badge'], $arr['sound']);
            if ($send_result)
                echo "send";
            else
                echo "not send";
        }else {
            $this->load->library('android');
            $send_result = $this->android->send_notification($arr);
            if ($send_result)
                echo "send";
            else
                echo "not send";
        }
    }

    private function send_email($p) {


        $this->load->library('email');

        //$p['message_body']="benzatine contact by ".$p['from_name']." with email ".$p['from']."<br /><br />";
        $config = array(
            'protocol' => 'sendmail',
            'smtp_host' => 'ssl://email-smtp.us-east-1.amazonaws.com',
            'smtp_port' => '25',
            'smtp_timeout' => '7',
            'smtp_user' => "AKIAIVRBL7EF4XAH2XFQ",
            'smtp_pass' => "Amjjm+/HaKuolwIFYuOfzzeGXiNk55gBJcBACJBqjsN3",
            'charset' => 'utf-8',
            'newline' => "\r\n",
            'wordwrap' => TRUE,
            'mailtype' => 'html',
            'validation' => TRUE,
            'priority' => 1,
        );
        //echo "<pre>";print_r($config);
        $this->email->initialize($config);
        $this->email->from("info@locallyepic.com","Locally Epic");
        $this->email->to($p['to']);
        $this->email->subject($p['subject']);
        $this->email->message($p['message_body']);
        @$this->email->send();
        //echo "<br>";
        //echo $this->email->print_debugger();exit;
    }

    private function _sendResponse($status_code = 200) {
        if ($this->msg == '') {
            $this->msg = $this->_getStatusCodeMessage($status_code);
        }
        $this->result['msg'] = $this->msg;
        $this->result['status_code'] = $status_code;
        echo json_encode($this->result);
//        die();
    }

    private function _getStatusCodeMessage($status) {

        $codes = Array(
            0 => 'server error',
            1 => 'OK',
            2 => 'Email already exists.',
            3 => 'Email does not exist.',
            4 => 'Invalid email and password combination.',
            5 => 'You must be authorized to view this page.',
            6 => 'Password must be a minimum of 8 characters',
            7 => 'Gender must be M or F.',
            10 => 'Bad Request',
            11 => 'Required field missing',
            12 => 'You do not have select an category',
            21 => 'Ethir Your account is verified or the url is an invalid',
            22 => 'Url validation time over',

        );
        return (isset($codes[$status])) ? $codes[$status] : '';
    }

    private function get_user_info($user_id, $str = "*") {
        return $this->m_common->db_select($str, 'tbl_customer', array('user_id' => $user_id), '', '', '', '', 'row_array', 0);
    }

    private function get_schedule_text($id, $uid) {
        $next_sunday=date('Y-m-d',strtotime('next sunday'));
        $current_day = date("N");
        $cs = isset($this->cs) ? $this->cs : date("Y-m-d");
        $time_in_sec = $this->time_in_sec;
        $deal_ids_info=array();
        $info = array();
        $q = "select t1.*,t2.*
             from tbl_deal t1 join tbl_shop t2 on t1.shop_id = t2.shop_id
             where t1.shop_id = $id
             and t1.deal_start = '$cs'  and t1.deal_time <= '$time_in_sec' and t1.deal_end_time >= '$time_in_sec' ";
        
    
        $infores = $this->m_common->select_custom($q, 1);

        if(RANDY_DEBUG==true && $uid==40 && $id==90){
            $this->write_log($q);
        }

        $icnt = 0;
        foreach ($infores->result_array() as $k => $v) {

            if(RANDY_DEBUG==true && $uid==40 && $id==90){
            $this->write_log("983:".$v['id'].",". $uid);
            }

            if (!$this->check_nlog($v['id'], $uid)) {
                $info[$icnt] = $v;
                $info[$icnt]['shop_cats'] = $this->get_shop_cats_names($v['shop_cats']);
                $info[$icnt]['shop_image'] = $this->get_shop_image_path($v['shop_image']);
                $info[$icnt]['deal_image'] = $this->get_deal_image_path($v['deal_image'],$this->get_shop_image_path($v['shop_image']));
                $info[$icnt]['deal_start'] = date("F j, Y, g:i a", strtotime($v['deal_start']));
                $info[$icnt]['deal_end'] = date("F j, Y, g:i a", strtotime($v['deal_end']));

                //conversiondeal
                $info[$icnt]['deal_time']=utc_to_local($v['deal_start'],$v['deal_time'],$v["timezone"]);
                $info[$icnt]['deal_end_time']=utc_to_local($v['deal_start'],$v['deal_end_time'],$v["timezone"]);
                $icnt++;
                $deal_ids_info[]=$v['id'];
                
            }
        }

        if(RANDY_DEBUG==true && $uid==40 && $id==90){
            $this->write_log($icnt);
            $arraystring = print_r($infores->result_array(), true); 
            $arraystring2 = print_r($info, true); 

            $this->write_log($arraystring);
            $this->write_log($arraystring2);
        }

        if (empty($info)) {
             return array(
                "info"=>json_encode(array()),
                "deal_ids"=> ''
            );
        }
        
        $finfo = array(
            "info" => $info,
        );
        if(RANDY_DEBUG==true && $uid==40){
            $this->write_log(json_encode($finfo));
        }
        return array(
            "info"=>json_encode($finfo),
            "deal_ids"=>  implode(",", $deal_ids_info)
        );
        
    }

    private function check_nlog($did, $uid) {
        $r = 0;
        $w = array(
            'user_id' => $uid,
            'join_id' => $did,
        );
        $ret = $this->m_common->db_select("count(*) as cnt", 'push_notes', $w, '', '', '', '', 'row_array', 0);
        if ($ret['cnt'] > 0) {
            $r = 1;
        }
        return $r;
    }

    private function get_schedule_text_testing($uid)
    {
        $current_day = date("N");
        $cs = isset($this->cs) ? $this->cs : date("Y-m-d H:i:s");
        $info = array();
        $q = "select t1.*,t2.shop_name,t2.shop_cats,t2.shop_description,t2.shop_image,t2.address,t2.email,t2.url,t2.latitude,t2.longitude
             from tbl_deal t1 join tbl_shop t2 on t1.shop_id = t2.shop_id
             
             order by date desc limit 3";
        $infores = $this->m_common->select_custom($q, 1);
        $icnt = 0;
        foreach ($infores->result_array() as $k => $v) {

            if (!$this->check_nlog($v['id'], $uid)) {
                $info[$icnt] = $v;
                $info[$icnt]['shop_cats'] = $this->get_shop_cats_names($v['shop_cats']);
                $info[$icnt]['shop_image'] = $this->get_shop_image_path($v['shop_image']);
                $info[$icnt]['deal_image'] = $this->get_deal_image_path($v['deal_image'],$this->get_shop_image_path($v['shop_image']));
                $info[$icnt]['deal_start'] = $v['deal_start'];
                $info[$icnt]['deal_end'] = $v['deal_end'];

                $info[$icnt]['deal_time'] = utc_to_local($v['deal_start'],$v['deal_time'],$v["timezone"]);
                $info[$icnt]['deal_end_time'] = utc_to_local($v['deal_start'],$v['deal_end_time'],$v["timezone"]);
                $icnt++;

            }
        }
        if (empty($info)) {
            return "";
        }
        $finfo = array(
            "info" => $info,
        );

        return json_encode($finfo);
    }

    private function get_shop_cats_names($str) {
        if ($str) {
            $q = "select group_concat(`cname`) as str from tbl_category where cid in ($str)  limit 1";
            $info = $this->m_common->select_custom($q);
            if (isset($info[0]['str']) && !empty($info[0]['str'])) {
                return $info[0]['str'];
            } else {
                return "";
            }
        } else {
            return "";
        }
    }

    private function get_shop_image_path($str) {
        //$url=base_url()."image/?path".$str."&width=300&height=300";
        
        if (is_image_path_proper($str)) {
            //return base_url() . "uploads/" . $str;
            return base_url() . "image/?path=uploads/user/".$str."&width=".$this->width;
        } else {
            return base_url()."images/no_image.png";
            //return base_url() . "image/?path".$str."&width=".$this->width;
        }
    }
    private function get_deal_image_path($str,$business_logo) {
        //$url=base_url()."image/?path".$str."&width=300&height=300";

        if (strpos($str,'no_image.png')!==false && $business_logo!='') {
            return $business_logo;
        } else {

            return $str;
        }

        
        
      
    }

    private function set_customer_location($arr) {
        $ins = array(
            'user_id' => $arr['user_id'],
            'latitude' => $arr['latitude'],
            'longitude' => $arr['longitude'],
        );
        $this->m_common->insert_entry('customer_location', $ins);
    }

    private function notification_log($arr1) {

        $this->m_common->insert_entry('push_notes', $arr1);
    }

    private function update_badge($id) {
        $q = "update tbl_customer set `badge`=`badge`+1 where user_id = $id";
        $this->m_common->query_custom($q);
    }

    private function reset_badge($id) {
        $q = "update tbl_customer set `badge`= 1 where user_id = $id";
        $this->m_common->query_custom($q);
    }

    private function send_android_push($push_arr, $is_de = 0){

        if (!empty($push_arr['dt'])) {
            $registrationIds = array($push_arr['dt']);
            $headers = array("Content-Type:" . "application/json", "Authorization:" . "key=" . ANDROID_APIKEY);
            $data = array(
                'data' => array(
                    'message' => $push_arr['alert'],
                    'title' => isset($push_arr['title']) ? $push_arr['title'] : "",
                    'badge' => (int) $push_arr['badge'],
                    'sound' => "",
                ),
                'registration_ids' => $registrationIds
            );

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_URL, "https://android.googleapis.com/gcm/send");
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
            $response = curl_exec($ch);
            if ($is_de) {
                //echo "json string". $push_arr['alert'];
                echo "<pre>";
                print_r($data);
                echo "<pre>";
                print_r($response); 

                $d = print_r($response,true);
                log_message('error',$d);
            }
            //echo "<pre>";print_r($response);exit;
            curl_close($ch);
            $this->update_badge($push_arr['user_id']);
        }
        //sleep(1);
    }



function test_ios(){

echo "start push.<br>";

    $user_id = $_GET['user_id'];
    $deal_id = $_GET["deal_id"];

    $user_info = $this->m_common->db_select('*', 'tbl_customer',array("user_id"=>$user_id),array(),'','',array(1,0),'row_array');

    $device_token = $user_info['device_token'];

    echo 'Device Token:'.$device_token."<br>";

    $message = "test push notification";

    $q = "select t1.*,t2.shop_id, t2.shop_name,t2.shop_cats,t2.url,t2.shop_description,t2.shop_image,t2.address,t2.email,t2.latitude,t2.longitude 
        from tbl_deal t1 join tbl_shop t2 on t1.shop_id = t2.shop_id where t1.id = $deal_id limit 1";
        $v = $this->m_common->select_custom($q);

    //$this->sendPushNotification($device_token, 'push_dev.pem', '123456', 'develop', $message, 0, 'default');
    // $this->sendPushNotification($device_token, 'push_pro.pem', '123456', 'product', $message, 0, 'default');
    $arr = array(
                    "deal_ids" => $_GET["deal_id"],
                    "alert" => "test", 
                    "dt" => $user_info['device_token'],
                    "badge" => $user_info['badge'],
                    "sound" => "",
                    "user_id" => $user_id,
                    "join_id" => $v[0]['shop_id'],
                );
    $t = print_r($arr,true);
    log_message('error',$t);

    //$this->sendPushNotificationIOS($arr, $user_info['device_token'], 'push_dev.pem', '123456', 'develop', "Deal On the GOGO : Deals", 1, 'default');
    $this->sendPushNotificationIOS($arr, $user_info['device_token'], 'push_pro.pem', '123456', 'product', "Deal On the GOGO : Deals", 1, 'default');


echo "stop push.<br>";
exit;
}

function sendPushNotification($deviceToken, $certFile, $certPass, $push_method, $alert, $badge, $sound) 
     { 

         $deviceToken = str_replace(" ", "", $deviceToken); 
         $deviceToken = pack('H*', $deviceToken); 
         $tmp = array(); 
         if($alert) 
         { 
            $tmp['alert'] = $alert; 
         } 
         if($badge) 
         { 
            $tmp['badge'] = $badge; 
         } 
         if($sound) 
         { 
            $tmp['sound'] = $sound; 
         } 
         $body['aps'] = $tmp; 
         //$body[$custom_key] = $custom_value; 

        $dir = dirname(__FILE__); 
        $certFile = $dir . '/' .$certFile;

         $ctx = stream_context_create(); 
         stream_context_set_option($ctx, 'ssl', 'local_cert', $certFile); 
         stream_context_set_option($ctx, 'ssl', 'passphrase', $certPass); 
         
         if ( $push_method == 'develop' )
            $ssl_gateway_url = 'ssl://gateway.sandbox.push.apple.com:2195';
         else if ( $push_method == 'product' )
            $ssl_gateway_url = 'ssl://gateway.push.apple.com:2195';
          
         if(isset($certFile) && isset($ssl_gateway_url)) 
         {
            
            $fp = stream_socket_client($ssl_gateway_url, $err, $errstr, 60, STREAM_CLIENT_CONNECT, $ctx); 
            echo "success!";
         }
         if(!$fp) 
         { 
            print "Connection failed $err $errstr\n"; 
            return FALSE; 
         } 
         $payload = json_encode($body); 
         $msg = chr(0).chr(0).chr(32).$deviceToken.chr(0).chr(strlen($payload)).$payload; 
         fwrite($fp, $msg); 
         fclose($fp);      
    
         
         return TRUE;
     }

//$this->sendPushNotificationIOS($push_arr, $user_info['device_token'], 'push_pro.pem', '123456', 'product', "Deal On the GOGO : Deals", $push_arr['badge'], 'default');

function sendOneSignalMessage($push_arr, $deviceToken, $alert){
    $content = array(
      "en" => "$alert"
      );
    
    $fields = array(
      'app_id' => "af2f2a20-ebb4-11e4-abcf-2f74168a7350",
      "isIos" => true,
      "include_ios_tokens"=> ["$deviceToken"],
      'data' => array("user_id" => $push_arr['user_id'], "deal_id"=> $push_arr['deal_id']),
      'contents' => $content
    );
    
    $fields = json_encode($fields);
    //print("\nJSON sent:\n");
    //print($fields);
    log_message('error','--------------------------------------------------------------------------------');
    log_message('error', 'sending push to: '.$push_arr['user_id']);
    log_message('error', 'fields: '.$fields);
    
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, "https://onesignal.com/api/v1/notifications");
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json',
                           'Authorization: Basic af2f2a20-ebb4-11e4-abcf-2f74168a7350'));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($ch, CURLOPT_HEADER, FALSE);
    curl_setopt($ch, CURLOPT_POST, TRUE);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);

    $response = curl_exec($ch);
    curl_close($ch);

    log_message('error','Response:'.$response);
    log_message('error','--------------------------------------------------------------------------------');
    
    //return $response;
    return TRUE;
  }
  
  /*$response = sendMessage();
  $return["allresponses"] = $response;
  $return = json_encode( $return);
  
  print("\n\nJSON received:\n");
  print($return);  */        

function sendPushNotificationIOS($push_arr, $deviceToken, $certFile, $certPass, $push_method, $alert, $badge, $sound) 
     { 
        log_message('error','2805 Right before sendOneSignalMessage');
        sendOneSignalMessage($push_arr, $deviceToken, $alert);
        log_message('error','2805 Right after sendOneSignalMessage');
        exit;

        if ($_SERVER["SERVER_NAME"]=="dev.dealsonthegogo.com"){

            $certFile="Deal_On_Go_Go_dev.pem";
            $certPass="";
            $push_method="develop";
        }

        $certFile="DGG2.pem";
        $certPass="";

        log_message('error',"----------------------------------------------------------------------------------------------");


        $viewable_device_token = $deviceToken;

         log_message('error', 'sending push to: '.$push_arr['user_id']);
         $deviceToken = str_replace(" ", "", $deviceToken); 
         $deviceToken = pack('H*', $deviceToken); 
         $tmp = array(); 
         if($alert) 
         { 
            $tmp['alert'] = $alert; 
         } 
         if($badge) 
         { 
            $tmp['badge'] = $badge; 
         } 
         if($sound) 
         { 
            $tmp['sound'] = $sound; 
         } 

         $body['aps'] = $tmp; 
         //$body[$custom_key] = $custom_value;

         $body['deal_ids']=array("deal_ids"=>$push_arr['deal_ids']);

         $t = print_r($body,true);
         log_message('error',$t);



        $dir = dirname(__FILE__); 
        $certFile = $dir . '/' .$certFile;

         $ctx = stream_context_create(); 
         stream_context_set_option($ctx, 'ssl', 'local_cert', $certFile); 
         stream_context_set_option($ctx, 'ssl', 'passphrase', $certPass); 
         
         if ( $push_method == 'develop' )
            $ssl_gateway_url = 'ssl://gateway.sandbox.push.apple.com:2195';
         else if ( $push_method == 'product' )
            $ssl_gateway_url = 'ssl://gateway.push.apple.com:2195';

        log_message('error','Cert File:'.$certFile);
        log_message('error', 'Cert Pass:'. $certPass);
        log_message('error', 'Gateway URL:'. $ssl_gateway_url);
        log_message('error','Device Token:'.$viewable_device_token);

          
         if(isset($certFile) && isset($ssl_gateway_url)) 
         {
            
            $fp = stream_socket_client($ssl_gateway_url, $err, $errstr, 60, STREAM_CLIENT_CONNECT, $ctx); 
            //echo "success!";
         }
         if(!$fp) 
         { 
            print "Connection failed $err $errstr\n"; 
            return FALSE; 
         } 
         $payload = json_encode($body); 
         $msg = chr(0).chr(0).chr(32).$deviceToken.chr(0).chr(strlen($payload)).$payload; 
         fwrite($fp, $msg); 
         //fclose($fp);      
    
         
         return TRUE;
     }



function randy_ios()
{
    $this->load->library('apn');
    $this->apn->payloadMethod = 'enhance'; // you can turn on this method for debuggin purpose
    $this->apn->connectToPush();

    // adding custom variables to the notification
    $this->apn->setData(array( 'someKey' => true ));

    $user_id = $_GET['user_id'];
    $user_info = $this->m_common->db_select('*', 'tbl_customer',array("user_id"=>$user_id),array(),'','',array(1,0),'row_array');

    $device_token = $user_info['device_token'];

    echo 'Device Token:'.$device_token."<br>";

    $send_result = $this->apn->sendMessage($device_token, 'Test notif #1 (TIME:'.date('H:i:s').')', /*badge*/ 2, /*sound*/ 'default'  );

    if($send_result)
        log_message('debug','Sending successful');
    else
        log_message('error',$this->apn->error);


    $this->apn->disconnectPush();
    echo 'wapi.php 1350: API Error Message: '.$this->apn->error."<br>";
    echo 'send_result:'.$send_result;
    echo '<pre>'.print_r($this->apn).'</pre>';
    exit;
}



    function push_testing_by_user(){
        $user_id = $_GET['user_id'];
        $user_info = $this->m_common->db_select('*', 'tbl_customer',array("user_id"=>$user_id),array(),'','',array(1,0),'row_array');
        
        
        $info = array();
        $q = "select t1.*,t2.*
             from tbl_deal t1 join tbl_shop t2 on t1.shop_id = t2.shop_id limit 1
             ";
        $deal_ids_info=array();
    
        $infores = $this->m_common->select_custom($q, 1);

        $icnt=0;
        foreach ($infores->result_array() as $k => $v) {
                $info[$icnt] = $v;
                $info[$icnt]['shop_cats'] = $this->get_shop_cats_names($v['shop_cats']);
                $info[$icnt]['shop_image'] = $this->get_shop_image_path($v['shop_image']);
                $info[$icnt]['deal_image'] = $this->get_deal_image_path($v['deal_image'],$this->get_shop_image_path($v['shop_image']));

                $info[$icnt]['deal_start'] = date("F j, Y, g:i a", strtotime($v['deal_start']));
                $info[$icnt]['deal_end'] = date("F j, Y, g:i a", strtotime($v['deal_end']));


                $info[$icnt]['deal_time']=utc_to_local($v['deal_start'],$v['deal_time'],$v["timezone"]);
                $info[$icnt]['deal_end_time']=utc_to_local($v['deal_start'],$v['deal_end_time'],$v["timezone"]);
                $deal_ids_info[]=$v['id'];
                $icnt++;
   
        }
            
        $finfo = array(
            "info" => $info,
        );
        
            $deal_ids= implode(",", $deal_ids_info);
        
     
        $alert= $finfo;
        
        
        
        if(!empty($user_info)){
            
            $push_arr=array(
                "dt"=>$user_info['device_token'],
                'alert' => $alert, 
                'badge' => (int)1,
                'sound' => "",
                'user_id' => $user_id,
                'deal_ids' => $deal_ids,
            );

            if($user_info['device_type']==1){
                $this->send_ios_push($push_arr,1);
            }else{
                $this->send_android_push($push_arr,1);
            }
            
        }else{
            echo "The user not avilable";
        }
        
        
    }
    function send_ios_push($push_arr,$is_dev=0) {
        $push_arr['badge']=(int)$push_arr['badge'];
        if (APNS_DEVELOPMENT == "sandbox") {
            $apnsHost = 'ssl://gateway.sandbox.push.apple.com:2195';
            $apnsCert = APNS_CERT_DEV;
        } else {
            $apnsHost = 'ssl://gateway.push.apple.com:2195';
            $apnsCert = APNS_CERT_LIVE;
        }

        $dirName = dirname(__FILE__);
        $certificate = $dirName . '/' . $apnsCert;
        try {
            $streamContext = stream_context_create();
            $passphrase='12345';
            stream_context_set_option($streamContext, 'ssl', 'local_cert', $certificate);
            stream_context_set_option($streamContext, 'ssl', 'passphrase', $passphrase);

            log_message('error', $apnsHost );
            log_message('error',$certificate);
            log_message('error',$passphrase);


            if($is_dev==1){
                $apns = stream_socket_client($apnsHost, $error, $errorString, 60, STREAM_CLIENT_CONNECT, $streamContext);
            }else{
                @$apns = stream_socket_client($apnsHost, $error, $errorString, 60, STREAM_CLIENT_CONNECT, $streamContext);
            }
            
            
        } catch (Exception $e) {

            log_message('error',$e);
            
        }

        $payload['aps'] = array(
            'alert' => "Deal On the GOGO : Deals", 
            'badge' => $push_arr['badge'], 
            'sound' => "default",
        );
        $payload['deal_ids']=array("deal_ids"=>$push_arr['deal_ids']);
        $payload = json_encode($payload);


        try {

            $apnsMessage = chr(0) . chr(0) . chr(32) . pack('H*', str_replace(' ', '', $push_arr['dt'])) . chr(0) . chr(strlen($payload)) . $payload;
           
            if($is_dev==1){
                 $a=fwrite($apns, $apnsMessage);
            }else{
                 $a=@fwrite($apns, $apnsMessage);
            }
        } catch (Exception $e) {
            
        }
        
        if($is_dev==0){
            $this->update_badge($push_arr['user_id']);
        }else{
            echo "<pre>";print_r($push_arr);
            echo "<pre>";print_r($payload);
          
            echo "<pre>";print_r($a);
            echo "<pre>";print_r($apns);exit;
        }

        
    }

    protected function get_unique_code($tbl, $col, $length = 15) {
        static $loop = 0;
        $alphabet = "abcdefghijklmnopqrstuwxyzABCDEFGHIJKLMNOPQRSTUWXYZ0123456789";
        $token = "";
        $alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
        for ($i = 0; $i < $length; $i++) {
            $n = rand(0, $alphaLength);
            $token.= $alphabet[$n];
        }
        $t = $this->m_common->db_select($col, $tbl, array($col => $token, $col . ' !=' => ''), '', '', '', '1', 'row');
        if (!empty($t) && $loop < 100) {
            $loop++;
            $token = $this->get_unique_code($tbl, $col);
        }
        return $token;
    }


    /****************************************************************
        CUSTOMER LOYALTY PROGRAM  CUSTOMER LOYALTY PROGRAM  CUSTOMER LOYALTY PROGRAM
        CUSTOMER LOYALTY PROGRAM  CUSTOMER LOYALTY PROGRAM  CUSTOMER LOYALTY PROGRAM

    ****************************************************************/

    public function customer_loyalty(){

        $user_id = $this->is_require($this->postvars, 'user_id');
        $user_token = $this->is_require($this->postvars, 'user_token');
        $business_id = $this->is_require($this->postvars, 'business_id');

        $is_verify = 0;

        $this->apilog(__FUNCTION__, $user_id, $_POST, '');

        $this->Is_authorised($user_id, $user_token);




        //Get Business information

        $business = $this->loyaltyprogram->getBusiness($business_id);

        //Get the total number of activations
       
        $deals_activated = $this->loyaltyprogram->getActivations($user_id, $business_id);

        //Get Reward Title & Number of Activations

        $sql = "

                select 
                        title,
                        intActivations
                from
                        loyaltyprogramitems
                where
                        shop_id = ?
                        and intActivations > ?
                order by intActivations
                limit 1;
        ";

        $result = $this->db->query($sql, array( $business_id, $deals_activated));

        if ($result->num_rows() == 0) {

            $sql = "

                select 
                        title,
                        intActivations
                from
                        loyaltyprogramitems
                where
                        shop_id = ?
                        and intActivations > ?
                order by intActivations
                limit 1;
        ";

        $result = $this->db->query($sql, array( $business_id, 0));


        }



        $lpitems = $result->row();


        $sqllcmu = "select ifnull((number_activations_for_next_reward - number_current_activations),0) as needed, isFavorite from loyaltyprogramcustomermatchup where user_id=? and business_id=?";
        $resultlcmu = $this->db->query($sqllcmu, array( $user_id, $business_id));


        if ($resultlcmu->num_rows() > 0) {

            $lmcu_row = $resultlcmu->row();
            $needed = $lmcu_row->needed;
            $isfavorite =  $lmcu_row->isFavorite;
        } else {

            $needed = $lpitems->intActivations;
            $isfavorite = 0;
        }



        $number_of_rewards = $this->loyaltyprogram->getRedeemableRewardCount($user_id, $business_id);

        $number_active_deals=1;

        $data = array(

            "business_logo"=>$business->business_logo,
            "business_name"=>$business->business_name,
            "business_description"=>$business->business_description,
            "pin"=>$business->pin,
            "tag_line"=>"Thanks for being a LOYAL CUSTOMER",
            "reward_title"=>$lpitems->title,
            "number_of_rewards"=>$number_of_rewards,
            "activations_until_redemption"=>$needed." more activations until your next reward.",
            "business_phone"=>$business->business_phone,
            "business_website"=>$business->business_website,
            "business_address"=>$business->business_address,
            "number_active_deals"=>$number_active_deals,
            "latitude"=>$business->latitude,
            "longitude"=>$business->longitude,
            "cl_logo"=>"http://".$_SERVER["SERVER_NAME"]."/images/cl.jpg",
            "isfavorite"=>$isfavorite

        );
        $this->result = $data;
        $status_code = 1;
        $this->_sendResponse($status_code);


    }


    public function customer_loyalty_programs(){

        $user_id = $this->is_require($this->postvars, 'user_id');
        $user_token = $this->is_require($this->postvars, 'user_token');
        $filter = isset($this->postvars['filter']) ? $this->postvars['filter'] : '';
        $radius = isset($this->postvars['radius']) ? $this->postvars['radius'] : '5';



        $favorites='';
        $having_distance='';

        if ($filter=='favorites'){

            $favorites = 'AND loyaltyprogramcustomermatchup.isFavorite=1 ';
        }


        if ($filter=='near me'){

            $having_distance = ' having distance <= 5';
        }


       

        $this->apilog(__FUNCTION__, $user_id, $_POST, '');

        $this->Is_authorised($user_id, $user_token);

        $user_cat = $this->m_common->db_select('latitude,longitude', 'tbl_customer', array("user_id" => $user_id), array(), '', '', '', 'row_array', 0);
        $latitude = $user_cat['latitude'];
        $longitude = $user_cat['longitude'];
 
        

        $sql = "
                SELECT DISTINCT
                    loyaltyprogramcustomermatchup.business_id,
                    tbl_shop.shop_name as business_name,
                    if(shop_image <>'', concat('".base_url()."uploads/user/',shop_image),concat('".base_url()."','assets/img/profile-pic.jpg')) as logo,
 

                    ifnull((number_activations_for_next_reward-number_current_activations),0) as number_of_activations,
                    isFavorite,

                    (((acos(sin(($latitude*pi()/180)) * sin((`latitude`*pi()/180))+cos(($latitude*pi()/180)) * cos((`latitude`*pi()/180))
                 * cos((($longitude - `longitude`)*pi()/180))))*180/pi())*60*1.1515) AS `distance`



                   
                FROM
                    loyaltyprogramcustomermatchup
                JOIN tbl_shop ON loyaltyprogramcustomermatchup.business_id = tbl_shop.shop_id
                WHERE
                    loyaltyprogramcustomermatchup.user_id = ?
                    $favorites

                    $having_distance
                ";                





        $result = $this->db->query($sql, array( $user_id, $user_id, $user_id));

        $loyalty_programs = $result->result_array();

    if(!empty($user_info['profile_pic'])){
        $user_info['profile_pic']=  base_url()."uploads/user/".$user_info['profile_pic'];
    }else{
        $user_info['profile_pic']=  base_url()."assets/img/profile-pic.jpg";
    }


        //Get Business information

        

        /*
        $loyalty_programs[0]["business_id"]=264;
        $loyalty_programs[0]["business_name"]="A1 Business";
        $loyalty_programs[0]["number_of_activations"]=1;
        $loyalty_programs[1]["business_id"]=264;
        $loyalty_programs[1]["business_name"]="A2 Business";
        $loyalty_programs[1]["number_of_activations"]=2;
        $loyalty_programs[2]["business_id"]=264;
        $loyalty_programs[2]["business_name"]="A3 Business";
        $loyalty_programs[2]["number_of_activations"]=3;
        */


       

        $data = array(

            "customer_loyalty_programs"=>$loyalty_programs

        );
        $this->result = $data;
        $status_code = 1;
        $this->_sendResponse($status_code);


    }

    function redeemable_rewards(){

        $user_id = $this->is_require($this->postvars, 'user_id');
        $user_token = $this->is_require($this->postvars, 'user_token');
        $business_id = $this->is_require($this->postvars, 'business_id');

        $this->apilog(__FUNCTION__, $user_id, $_POST, '');

        $this->Is_authorised($user_id, $user_token);

        $rewards = $this->loyaltyprogram->getRedeemableRewards($user_id, $business_id);

        $data = array(

            "redeemable_rewards"=>$rewards

        );
        $this->result = $data;
        $status_code = 1;
        $this->_sendResponse($status_code);

    }

    function customer_loyalty_favorite(){

        $user_id = $this->is_require($this->postvars, 'user_id');
        $user_token = $this->is_require($this->postvars, 'user_token');
        $business_id = $this->is_require($this->postvars, 'business_id');
        $isfavorite = $this->is_require($this->postvars, 'isfavorite');  //0 or 1

        $this->apilog(__FUNCTION__, $user_id, $_POST, '');

        $this->Is_authorised($user_id, $user_token);

        $this->loyaltyprogram->updateFavorite($user_id, $business_id, $isfavorite);

        $data = array("status"=>1,"message"=>"Favorite Updated Successfully");
        $this->result = $data;
        $status_code = 1;
        $this->_sendResponse($status_code); 
    }

    function customer_loyalty_program_delete(){

        $user_id = $this->is_require($this->postvars, 'user_id');
        $user_token = $this->is_require($this->postvars, 'user_token');
        $business_id = $this->is_require($this->postvars, 'business_id');

        $this->apilog(__FUNCTION__, $user_id, $_POST, '');

        $this->Is_authorised($user_id, $user_token);

        $this->loyaltyprogram->deleteCustomerLoyaltyProgram($user_id, $business_id);

        $data = array("status"=>1,"message"=>"CL Program Deleted Successfully");
        $this->result = $data;
        $status_code = 1;
        $this->_sendResponse($status_code); 
    }


    function validate_reward_pin(){

        $user_id = $this->is_require($this->postvars, 'user_id');
        $user_token = $this->is_require($this->postvars, 'user_token');
        $reward_id = $this->is_require($this->postvars, 'reward_id');
        //$pin = $this->is_require($this->postvars, 'pin');


        $this->apilog(__FUNCTION__, $user_id, $_POST, '');

        $this->Is_authorised($user_id, $user_token);

        //Get Reward
        $reward = $this->loyaltyprogram->getReward($reward_id);



        //get business pin
        //$stored_pin = $this->business->getBusinessPin($reward->business_id);

        //validate pin

        //if ($stored_pin == $pin){

            if ($reward->blnRewarded==1){

            $data = array("status"=>0,"message"=>"Rewarded was already redeemed on ".$reward->dtRewarded);
            $this->result = $data;
            $status_code = 1;
            $this->_sendResponse($status_code);  
            exit;     
            }

            $this->loyaltyprogram->redeemReward($reward_id, $user_id );
            $data = array("status"=>1,"message"=>"Reward Redeemed Successfully");
            $this->result = $data;
            $status_code = 1;
            $this->_sendResponse($status_code); 


        //} else {

        //    $data = array("status"=>0,"message"=>"Incorrect Pin");
         //   $this->result = $data;
         //   $status_code = 1;
         //   $this->_sendResponse($status_code); 


        //}

    }




}

