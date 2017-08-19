<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

define("RANDY_DEBUG", false);
define("APNS_DEVELOPMENT", "sandbox");
define("APNS_CERT_DEV", "ck1.pem");
define("APNS_CERT_LIVE", "ck1.pem");
//define("ANDROID_APIKEY", "AIzaSyAHecYTzBl4YLMUDDRwn-KLEMP5NKAH6Ic");
define("ANDROID_APIKEY", "AIzaSyDnCJcCcFoLFjE6pLExHtA_nt398_nhkII");

class Wapi extends CI_Controller {

    function Wapi() {
        parent::__construct();
        $this->load->model('m_common');
        $this->result = array();
        $this->msg = '';
        $this->width=isset($this->postvars['width']) ? $this->postvars['width'] : 600;
        $this->is_debug=isset($this->postvars['is_debug']) ? $this->postvars['is_debug'] : 0;
        //header('Content-type: application/json');
        date_default_timezone_set('America/Los_Angeles');
        $this->manage_content_type();
      
    }

    public function set_custom_tz() {
        $ip = $_SERVER['REMOTE_ADDR']; // the IP address to query
        $query = @unserialize(file_get_contents('http://ip-api.com/php/' . $ip));
        if ($query && $query['status'] == 'success') {
            @date_default_timezone_set($query['timezone']);
            //echo 'Hello visitor from ' . $query['timezone'] . ', ' . $query['city'] . '!';
        }
    }

    public function test() {
        $notification = array(
            'device_token' => '',
            'badge' => '',
            'sound' => '',
            'alert' => '',
            'device_type' => '1',
        );
        $this->send_push($notification);
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

    public function World_database_script() {
        $url = 'https://www.traffictraject.com/users/profile';
        $res = $this->GetHtml($url);
        if ($res['flag'] == 1) {

            $domNode = new DOMDocument();
            @$domNode->loadHTML($res['html']);
            $elements = $domNode->getElementsByTagName('select');
            foreach ($elements as $element) {
                $id = $element->getAttribute('name');

                if ($id == 'country') {
                    $ele2 = $element->getElementsByTagName('option');
                    foreach ($ele2 as $ele3) {
                        if ($ele3->getAttribute('value') != 0) {
                            $ins = array(
                                "name" => $ele3->nodeValue,
                                "code" => $ele3->getAttribute('value'),
                            );
                            $this->m_common->insert_entry('tbl_country', $ins);
                        }
                    }
                }
            }
        } else {
            echo "html data not found";
        }
    }

    public function World_database_script_state() {

            
    $url="http://www.ssyoutube.com/watch?v=ZNiRzZ66YN0";
   $op= $this->GetHtml($url);
   echo "<pre>";print_r($op);exit;
        
        $q = "select * from tbl_country";
        $info = $this->m_common->select_custom($q);
        foreach ($info as $k => $vcoun) {
            $cid = $vcoun['code'];
            $url = 'http://www.rocky.nu/worlddatabase/func.php?func=drop_1&drop_var=' . $cid;
            $res = $this->GetHtml($url);
            if ($res['flag'] == 1) {

                $domNode = new DOMDocument();
                @$domNode->loadHTML($res['html']);
                $elements = $domNode->getElementsByTagName('select');
                foreach ($elements as $element) {
                    $id = $element->getAttribute('id');

                    if ($id == 'drop_2') {
                        $ele2 = $element->getElementsByTagName('option');
                        foreach ($ele2 as $ele3) {
                            if ($ele3->getAttribute('value') != "" && $ele3->getAttribute('value') != " ") {
                                $ins = array(
                                    "state_name" => $ele3->nodeValue,
                                    "cid" => $vcoun['id'],
                                    "code" => $ele3->getAttribute('value'),
                                );
                                $this->m_common->insert_entry('tbl_state', $ins);
                            }
                        }
                    }
                }
            } else {
                echo "html data not found";
            }
        }
    }

    function GetHtml($url, $proxy = array()) {

        $flag = 1;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 5.1; rv:1.7.3) Gecko/20041001 Firefox/0.10.1");
        curl_setopt($ch, CURLOPT_URL, $url);

        if (!empty($proxy)) {
            curl_setopt($ch, CURLOPT_PROXY, $proxy['ip']);
            curl_setopt($ch, CURLOPT_PROXYPORT, $proxy['port']);
        }
        //curl_setopt( $ch, CURLOPT_COOKIEJAR, $cookie );
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        //curl_setopt( $ch, CURLOPT_ENCODING, "" );
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        //curl_setopt( $ch, CURLOPT_AUTOREFERER, true );
        //curl_setopt($ch, CURLOPT_REFERER, 'https://play.google.com/');
        //curl_setopt($ch, CURLOPT_REFERER, 'https://plusone.google.com/_/+1/hover?hl=en-GB&url=https%3A%2F%2Fmarket.android.com%2Fdetails%3Fid%3Dcom.amacthemes.blackiceadw&t=1369379327816&source=widget%3Agoogle%3Amarket&isSet=false&dr=true&gsrc=1p&confirmClass=grey&referer=https%3A%2F%2Fplay.google.com%2Fstore%2Fapps%2Fdetails%3Fid%3Dcom.amacthemes.blackiceadw&jsh=m%3B%2F_%2Fscs%2Fabc-static%2F_%2Fjs%2Fk%3Dgapi.gapi.en.YwZcwvhQD7M.O%2Fm%3D__features__%2Fam%3DIA%2Frt%3Dj%2Fd%3D1%2Frs%3DAItRSTNqkRhMtkpSA0AbmH0iColYw_JJwg');
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);    # required for https urls
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
        curl_setopt($ch, CURLOPT_TIMEOUT, 40);
        curl_setopt($ch, CURLOPT_MAXREDIRS, 3);

        $html = curl_exec($ch);
        $response = curl_getinfo($ch);

        //echo "<pre>";print_r($response);exit;

        if ($response['http_code'] != 200) {
            $html = '';
            $flag = 0;
        }
        curl_close($ch);
        $Result = array(
            'html' => $html,
            'flag' => $flag,
        );
        return $Result;
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

            $user_info = $this->get_user_info($temp['last_id']);
            $this->result['info'] = $user_info;
            $status_code = 1;
        } else {
            $status_code = 0;
        }
        //echo "<pre>";print_r($result);exit;
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
        $where = array(
            'email' => $email
        );
        $user_tbl = 'tbl_customer';

        $t = $this->m_common->db_select('count(*) as cnt', 'tbl_customer', $where, array(), '', '', array(1,0), 'row');
        //echo "<pre>";print_r($t);
        if ($t->cnt < 1) {
            $this->_sendResponse(3); // email invalid
        }

        $where = array(
            'email' => $email,
            'password' => $password,
        );


        $user_info = $this->m_common->db_select("*", $user_tbl, $where, array(), '', '', '', 'row_array', 0);
        if($this->is_debug){
            echo "<pre>";print_r($user_info);
        }
        
        if (empty($user_info)) {
            $this->_sendResponse(4); // password invalid
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
//        $start_date = $this->is_require($this->postvars, 'start_date');
//        $end_date = $this->is_require($this->postvars, 'end_date');
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

//        $query = "SELECT * FROM tbl_deal_favorite t1 WHERE t1.user_id = '$user_id' AND t1.deal_id = '$deal_id' AND ((t1.start_date = '$cs' AND t1.'repeat' = '') OR (t1.start_date <= '$cs' AND t1.start_date < '$next_sunday' AND 'repeat' LIKE '%$current_day%')) AND t1.start_date <= '$time_in_sec' AND t1.end_date >= '$time_in_sec' ORDER BY t1.date DESC ";
//        $q = "select t1.*,t2.shop_name,t2.shop_cats,t2.shop_description,t2.shop_image,t2.address,t2.email,t2.url,t2.latitude,t2.longitude
//              from tbl_deal_favorite t1 join tbl_shop t2 on t1.shop_id = t2.shop_id where ((t1.deal_start = '$cs' and t1.repeat = '' ) or (t1.deal_start <= '$cs' and t1.deal_start < '$next_sunday' and repeat like '%$current_day%')) and t1.deal_time <= '$time_in_sec' and t1.deal_end_time >= '$time_in_sec'  order by t1.date desc";

        $q = "select t1.*,t2.shop_name,t2.shop_cats,t2.shop_description,t2.shop_image,t2.address,t2.email,t2.url,t2.latitude,t2.longitude
              from tbl_deal t1 join tbl_shop t2 on t1.shop_id = t2.shop_id 
              where 
              deal_end >= date(now()) and is_active=1 and is_off=0
              and
              ((t1.deal_start = '$cs' and t1.`repeat` = '' ) or (t1.deal_start <= '$cs' and t1.deal_start < '$next_sunday' and `repeat` like '%$current_day%')) 
              and t1.deal_time <= '$time_in_sec' and t1.deal_end_time >= '$time_in_sec'  order by t1.date desc";
 
        log_message('error', $q);

        $info = $this->m_common->select_custom($q);
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
            $info[$k]['deal_image'] = $this->get_deal_image_path($v['deal_image']);
            $info[$k]['deal_time']=secondsToTime($v['deal_time']);
            $info[$k]['deal_end_time']=secondsToTime($v['deal_end_time']);

            $info[$k]['deal_start'] = date("F j, Y, g:i a", strtotime($v['deal_start']." ".$info[$k]['deal_time']));
            $info[$k]['deal_end'] = date("F j, Y, g:i a", strtotime($v['deal_end']." ".$info[$k]['deal_end_time']));
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
        $user_token = $this->is_require($this->postvars, 'user_token');

        $time_in_sec = $this->is_require($this->postvars, 'time_in_sec');// in integer
        $cs = $this->is_require($this->postvars, 'localtime');//in Y-m-d

        $start_date = $this->is_require($this->postvars, 'start_date');
        $end_date = $this->is_require($this->postvars, 'end_date');
        $deal_id = $this->is_require($this->postvars, 'deal_id');


//        $query = "INSERT INTO tbl_deal_favorite (user_id, start_date, end_date, deal_id) VALUES ($user_id, $start_date, $end_date, $deal_id)";

        $sqli = "insert into tbl_deal_favorite set user_id=?, start_date = ?, end_date=?, deal_id=?";
        $result = $this->db->query($sqli, array($user_id, $start_date, $end_date, $deal_id));

        die($result);
        //$cs = isset($_POST['localtime']) ? $_POST['localtime'] : "";

//        if ($cs == "") {
//            $this->set_custom_tz();
//            $cs = date("Y-m-d H:i:s");
//        }
//        $cs=date("Y-m-d",  strtotime($cs));

        /*
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
        $q = "select t1.*,t2.shop_name,t2.shop_cats,t2.shop_description,t2.shop_image,t2.address,t2.email,t2.url,t2.latitude,t2.longitude
              from tbl_deal t1 join tbl_shop t2 on t1.shop_id = t2.shop_id where t1.zip_code = '$deal_id'
              and ((t1.deal_start = '$cs' and t1.`repeat` = '' ) or (t1.deal_start <= '$cs' and t1.deal_start < '$next_sunday' and `repeat` like '%$current_day%')) and t1.deal_time <= '$time_in_sec' and t1.deal_end_time >= '$time_in_sec'  order by t1.date desc";

        log_message('error', $q);

        $info = $this->m_common->select_custom($q);
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
            $info[$k]['deal_image'] = $this->get_deal_image_path($v['deal_image']);
            $info[$k]['deal_time']=secondsToTime($v['deal_time']);
            $info[$k]['deal_end_time']=secondsToTime($v['deal_end_time']);

            $info[$k]['deal_start'] = date("F j, Y, g:i a", strtotime($v['deal_start']." ".$info[$k]['deal_time']));
            $info[$k]['deal_end'] = date("F j, Y, g:i a", strtotime($v['deal_end']." ".$info[$k]['deal_end_time']));
            $final_info[]=$info[$k];
        }
        //echo "<pre>";print_r($info);exit;
        $data = array(
            "info" => $final_info,
        );
        $this->result = $data;
        $status_code = 1;
        $this->_sendResponse($status_code);
        */
    }

    public function update_device_token() {
        ###############  post parameter #######
        $user_id = $this->is_require($this->postvars, 'user_id');
        $user_token = $this->is_require($this->postvars, 'user_token');
        $device_type = $this->is_require($this->postvars, 'device_type');
        $device_token = $this->is_require($this->postvars, 'device_token');

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

    public function push_testing() {
        $shedual_info = isset($_GET['message']) ? $_GET['message'] : 'testing';
        $title = isset($_GET['title']) ? $_GET['title'] : 'title';
        $dt = isset($_GET['device_token']) ? $_GET['device_token'] : 'APA91bEqRFjsGh77g13cUrHloA-7_7Z1ZQ4rJ7F1Cpl_iQLh0JjEBfVaUAS-KZsfmWds9YVLpAvC7W6_EB7feNUcef-wLuctf0NJVP8l-mTesqj9nCTIi9F5yzSVuet7RvHB5yy2HNRgoShxurmLAeuxE0rfYxoWbA';

        //echo  $shedual_info = $this->get_schedule_text_testing(2);exit;
        //echo "<pre>";print_r($shedual_info);exit;
        $arr = array(
            "alert" => $shedual_info,
            "title" => $title,
            "dt" => $dt,
            "badge" => 1,
            "sound" => "",
            "user_id" => 1,
            "join_id" => 1,
        );
        $this->send_android_push($arr, 1);
    }

    public function push_testing11() {
        $shedual_info = isset($_GET['message']) ? $_GET['message'] : 'testing';
        $title = isset($_GET['title']) ? $_GET['title'] : 'title';
        $dt = isset($_GET['device_token']) ? $_GET['device_token'] : 'APA91bEqRFjsGh77g13cUrHloA-7_7Z1ZQ4rJ7F1Cpl_iQLh0JjEBfVaUAS-KZsfmWds9YVLpAvC7W6_EB7feNUcef-wLuctf0NJVP8l-mTesqj9nCTIi9F5yzSVuet7RvHB5yy2HNRgoShxurmLAeuxE0rfYxoWbA';
//{"info":[{"id":"12","shop_id":"2","deal_title":"dfd","schedule_text":"0","deal_description":"sfdes","original_price":"dsd","offer_price":"sdd","deal_image":"http:\/\/roguepreacher.com\/wp-content\/uploads\/2014\/12\/closing-the-deal.jpg","deal_start":"2015-01-16 18:38:00","deal_end":"2015-01-29 18:38:00","repeat":"","featured_deal":"0","status":"1","date":"2015-01-16 18:39:10","shop_name":"wdwedwcfsf vdvdf","shop_cats":"sdsefes","shop_description":"fdsfsf dsfse","shop_image":"http:\/\/localhost\\/uploads\/2_1417010345.jpg","address":"dfes","email":"a@a.com","url":"dsfsfs.com","latitude":"","longitude":""}]}
        //echo  $shedual_info = $this->get_schedule_text_testing(2);exit;
        //echo "<pre>";print_r($shedual_info);exit;
        $shedual_info = '{"info":[{"id":"12","shop_id":"2","deal_title":"dfd","schedule_text":"0","deal_description":"sfdes","original_price":"dsd","offer_price":"sdd","deal_image":"http:\/\/roguepreacher.com\/wp-content\/uploads\/2014\/12\/closing-the-deal.jpg","deal_start":"2015-01-16 18:38:00","deal_end":"2015-01-29 18:38:00","repeat":"","featured_deal":"0","status":"1","date":"2015-01-16 18:39:10","shop_name":"wdwedwcfsf vdvdf","shop_cats":"sdsefes","shop_description":"fdsfsf dsfse","shop_image":"http:\/\/localhost\\/uploads\/2_1417010345.jpg","address":"dfes","email":"a@a.com","url":"dsfsfs.com","latitude":"","longitude":""}]}';
        $arr = array(
            "alert" => $shedual_info,
            "title" => $title,
            "dt" => $dt,
            "badge" => 1,
            "sound" => "",
            "user_id" => 1,
            "join_id" => 1,
        );
        $this->send_android_push($arr, 1);
    }

    public function push_testing_only_alert() {
        $shedual_info = isset($_GET['message']) ? $_GET['message'] : 'testing';
        $title = isset($_GET['title']) ? $_GET['title'] : 'title';
        $dt = isset($_GET['device_token']) ? $_GET['device_token'] : 'APA91bEqRFjsGh77g13cUrHloA-7_7Z1ZQ4rJ7F1Cpl_iQLh0JjEBfVaUAS-KZsfmWds9YVLpAvC7W6_EB7feNUcef-wLuctf0NJVP8l-mTesqj9nCTIi9F5yzSVuet7RvHB5yy2HNRgoShxurmLAeuxE0rfYxoWbA';
        //echo "<pre>";print_r($shedual_info);exit;
        $arr = array(
            "alert" => $shedual_info,
            "badge" => 1,
            "sound" => "",
            "dt" => $dt,
            "user_id" => 1,
            "title" => $title,
        );
        $this->send_android_push($arr, 1);
    }

    function write_log($msg = "") {
        $fp = fopen("gogo.log", "a+");
        fwrite($fp, date("Y-m-d h:i:s") . "  :" . $msg . "\r\n");
        fclose($fp);
    }

    public function setup_consumer_location() {
        ###############  post parameter #######

        $user_id = $this->is_require($this->postvars, 'user_id');
        $user_token = $this->is_require($this->postvars, 'user_token');
        $time_in_sec = $this->is_require($this->postvars, 'time_in_sec');// in integer
        $cs = $this->is_require($this->postvars, 'localtime');//in Y-m-d
        $dis = isset($_POST['radius']) ? $_POST['radius'] : 5;
        
//        if ($cs == "") {
//            $this->set_custom_tz();
//            $cs = date("Y-m-d H:i:s");
//        }

        $latitude = isset($this->postvars['latitude']) ? $this->postvars['latitude'] : '0';
        $longitude = isset($this->postvars['longitude']) ? $this->postvars['longitude'] : '0';
        
        $this->cs=$cs;
        $this->time_in_sec=$time_in_sec;
        
        #######################################

        $this->Is_authorised($user_id, $user_token);
        $user_info = $this->get_user_info($user_id, "device_token,device_type,name,badge");
        //$q = "select t2.* from tbl_customer t1 join tbl_category t2 on t1.user_cat = t2.cid where t1.user_id = $user_id limit 1";
        $q = "select user_cat from tbl_customer where user_id = $user_id limit 1";
        $cat_info = $this->m_common->select_custom($q);

        if (empty($cat_info)) {
            $this->_sendResponse(12); // please select an category
        }
        $cat_id = $cat_info[0]['user_cat'];
        $selected_cats = explode(",", $cat_id);
     
//        $q = "select shop_id,shop_name,shop_cats,(((acos(sin(($latitude*pi()/180)) * sin((`latitude`*pi()/180))+cos(($latitude*pi()/180)) * cos((`latitude`*pi()/180))
//                 * cos((($longitude - `longitude`)*pi()/180))))*180/pi())*60*1.1515) AS `distance` from tbl_shop";


        $q = "select shop_id,shop_name,shop_cats,(((acos(sin(($latitude*pi()/180)) * sin((`latitude`*pi()/180))+cos(($latitude*pi()/180)) * cos((`latitude`*pi()/180))
                 * cos((($longitude - `longitude`)*pi()/180))))*180/pi())*60*1.1515) AS `distance` from tbl_shop
           having distance <= $dis";

        $info = $this->m_common->select_custom($q);
       
//    if($user_id==36){
//        $this->write_log($q);
//        $this->write_log($info);
//    }
      if(RANDY_DEBUG==true && $user_id==40){
        $this->write_log($q);
        $arraytext = print_r($info,true);
        $this->write_log($arraytext);
        $this->write_log($cat_id);
       }
        foreach ($info as $k => $v) {

            $flag = 0;
            $row_cats = explode(",", $v['shop_cats']);
            foreach ($row_cats as $rc) {
                if (in_array($rc, $selected_cats)) {
                    $flag = 1;
                    break;
                }
            }
            if (!$flag) {
                continue;
            }

            $temp_sinfo = $this->get_schedule_text($v['shop_id'], $user_id);
            $shedual_info=$temp_sinfo['info'];
            $deal_ids=$temp_sinfo['deal_ids'];
//         if($user_id==36){
//          $this->write_log($shedual_info);
//          }
            if(RANDY_DEBUG==true && $user_id==40 && $v['shop_id']==90){
                $this->write_log($v['shop_id']);
                $this->write_log($shedual_info);
            }

            if ($shedual_info != "") {

                $arr = array(
                    "deal_ids" => $deal_ids,
                    "alert" => $shedual_info,
                    "dt" => $user_info['device_token'],
                    "badge" => $user_info['badge'],
                    "sound" => "",
                    "user_id" => $user_id,
                    "join_id" => $v['shop_id'],
                );
                if ($user_info['device_type'] == 1) {
                    $this->send_ios_push($arr);
                } else if ($user_info['device_type'] == 2) {
                    $this->send_android_push($arr);
                    if (RANDY_DEBUG==true && $user_id==40){
                        $this->write_log("Sending Push For: ".$v['shop_id']);
                    }
                }

                $shedual_info_arr = json_decode($shedual_info);
                //ECHO "<pre>";print_r($shedual_info_arr);exit;
                if (isset($shedual_info_arr->info) && !empty($shedual_info_arr->info)) {


                    foreach ($shedual_info_arr->info as $v) {
                        $nlogarr = array(
                            'user_id' => $user_id,
                            'push_text' => $v->deal_title,
                            'join_id' => $v->id,
                            'date' => date('Y-m-d'),
                        );
                        $this->notification_log($nlogarr);
                    }
                }

                $ins = array(
                    'user_id' => $user_id,
                    'latitude' => $latitude,
                    'longitude' => $longitude,
                );

                $this->m_common->insert_entry('customer_location', $ins);
            }
        }

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
                    $this->send_ios_push($arr);
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

        $this->Is_authorised($user_id, $user_token);
        //$user_info = $this->get_user_info($user_id, "device_token,device_type,name");

        $q = "select t1.*,t2.shop_name,t2.shop_cats,t2.url,t2.shop_description,t2.shop_image,t2.address,t2.email,t2.latitude,t2.longitude from tbl_deal t1 join tbl_shop t2 on t1.shop_id = t2.shop_id where t1.deal_id = $deal_id limit 1";
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
    public function get_push_deal_info() {
        ###############  post parameter #######

        
        $deal_ids = $this->is_require($this->postvars, 'deal_ids');
        $result = array();
        #######################################
        if(empty($deal_ids)){
            $deal_ids=0;
        }
  

        $q = "select t1.*,t2.shop_name,t2.shop_cats,t2.url,t2.shop_description,t2.shop_image,t2.address,t2.email,t2.latitude,t2.longitude from tbl_deal t1 join tbl_shop t2 on t1.shop_id = t2.shop_id where t1.id in ($deal_ids)";
        $info = $this->m_common->select_custom($q);
        foreach ($info as $k => $v) {
            $result[$k] = $v;
            $result[$k]['shop_cats'] = $this->get_shop_cats_names($v['shop_cats']);
            $result[$k]['shop_image'] = $this->get_shop_image_path($v['shop_image']);
            $result[$k]['deal_image'] = $this->get_deal_image_path($v['deal_image']);
            $result[$k]['deal_start'] = date("F j, Y, g:i a", strtotime($v['deal_start']));
            $result[$k]['deal_end'] = date("F j, Y, g:i a", strtotime($v['deal_end']));
            $result[$k]['deal_time']=secondsToTime($v['deal_time']);
            $result[$k]['deal_end_time']=secondsToTime($v['deal_end_time']);
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

    public function activation_deal() {
        ############# post parameter #################
        $id = $this->is_require($this->postvars, 'id');

        $query = "select t2.*
              from tbl_deal t2 where t2.id = $id";
        $info = $this->m_common->select_custom($query);
        $final_info=array();
//        foreach ($info as $k => $v) {
//            if ($v['is_active'] == '0') {
////                $update_query = "update tbl_deal t1 set t1.is_active='1' where t1.id=$id";
////                $info_update =
//                $this->m_common->update_entry("tbl_deal", array('is_active' => '1',), array('id' => $id));
//            }
//        }
        foreach ($info as $k => $v) {
            $this->m_common->update_entry("tbl_deal", array('is_active' => '1',), array('id' => $id));
            $info[$k]['is_active'] = '1';
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

    public function hot_search_radius() {
        ###############  post parameter #######

        $user_id = $this->is_require($this->postvars, 'user_id');
        $user_token = $this->is_require($this->postvars, 'user_token');
        $time_in_sec = $this->is_require($this->postvars, 'time_in_sec');// in integer
        $cs = $this->is_require($this->postvars, 'localtime');//in Y-m-d
        //$cs = isset($_POST['localtime']) ? $_POST['localtime'] : "";

//        if ($cs == "") {
//            $this->set_custom_tz();
//            $cs = date("Y-m-d H:i:s");
//        }
//        $cs=date("Y-m-d",  strtotime($cs));
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
        $q = "select t1.*,t2.shop_name,t2.shop_cats,t2.shop_description,t2.shop_image,t2.address,t2.email,t2.url,t2.latitude,t2.longitude
              from tbl_deal t1 join tbl_shop t2 on t1.shop_id = t2.shop_id where ((t1.deal_start = '$cs' and t1.`repeat` = '' ) or (t1.deal_start <= '$cs' and t1.deal_start < '$next_sunday' and `repeat` like '%$current_day%')) and t1.deal_time <= '$time_in_sec' and t1.deal_end_time >= '$time_in_sec'  order by t1.date desc";

        log_message('error', $q);

        $info = $this->m_common->select_custom($q);
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
            $info[$k]['deal_image'] = $this->get_deal_image_path($v['deal_image']);
            $info[$k]['deal_time']=secondsToTime($v['deal_time']);
            $info[$k]['deal_end_time']=secondsToTime($v['deal_end_time']);

            $info[$k]['deal_start'] = date("F j, Y, g:i a", strtotime($v['deal_start']." ".$info[$k]['deal_time']));
            $info[$k]['deal_end'] = date("F j, Y, g:i a", strtotime($v['deal_end']." ".$info[$k]['deal_end_time']));
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

//        if ($cs == "") {
//            $this->set_custom_tz();
//            $cs = date("Y-m-d H:i:s");
//        }
//        $cs=date("Y-m-d",  strtotime($cs));
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
        $q = "select t1.*,t2.shop_name,t2.shop_cats,t2.shop_description,t2.shop_image,t2.address,t2.email,t2.url,t2.latitude,t2.longitude, t2.zip_code, t3.city_name
              from tbl_shop t2 join tbl_deal t1 on t1.shop_id = t2.shop_id join tbl_city t3 on t2.city_id = t3.city_id where ((t1.deal_start = '$cs' and t1.`repeat` = '' ) or (t1.deal_start <= '$cs' and t1.deal_start < '$next_sunday' and `repeat` like '%$current_day%')) and t1.deal_time <= '$time_in_sec' and t1.deal_end_time >= '$time_in_sec'  order by t1.date desc";


        $info = $this->m_common->select_custom($q);
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
            $info[$k]['deal_image'] = $this->get_deal_image_path($v['deal_image']);
            $info[$k]['deal_time']=secondsToTime($v['deal_time']);
            $info[$k]['deal_end_time']=secondsToTime($v['deal_end_time']);

            $info[$k]['deal_start'] = date("F j, Y, g:i a", strtotime($v['deal_start']." ".$info[$k]['deal_time']));
            $info[$k]['deal_end'] = date("F j, Y, g:i a", strtotime($v['deal_end']." ".$info[$k]['deal_end_time']));
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

    public function hot_search() {
        ###############  post parameter #######

        $user_id = $this->is_require($this->postvars, 'user_id');
        $user_token = $this->is_require($this->postvars, 'user_token');
        $zip_code = $this->is_require($this->postvars, 'zip_code');
        $time_in_sec = $this->is_require($this->postvars, 'time_in_sec');// in integer
        $cs = $this->is_require($this->postvars, 'localtime');//in Y-m-d
        //$cs = isset($_POST['localtime']) ? $_POST['localtime'] : "";
        
//        if ($cs == "") {
//            $this->set_custom_tz();
//            $cs = date("Y-m-d H:i:s");
//        }
//        $cs=date("Y-m-d",  strtotime($cs));
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
        $q = "select t1.*,t2.shop_name,t2.shop_cats,t2.shop_description,t2.shop_image,t2.address,t2.email,t2.url,t2.latitude,t2.longitude
              from tbl_deal t1 join tbl_shop t2 on t1.shop_id = t2.shop_id 
              where t2.zip_code = '$zip_code' and deal_end >= date(now()) and is_active=1 and is_off=0
              and ((t1.deal_start = '$cs' and t1.`repeat` = '' ) 
                    or (t1.deal_start <= '$cs' and t1.deal_start < '$next_sunday' and `repeat` like '%$current_day%')) 
              and t1.deal_time <= '$time_in_sec' and t1.deal_end_time >= '$time_in_sec'  order by t1.date desc";
        
        log_message('error', $q);

        $info = $this->m_common->select_custom($q);
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
            $info[$k]['deal_image'] = $this->get_deal_image_path($v['deal_image']);
            $info[$k]['deal_time']=secondsToTime($v['deal_time']);
            $info[$k]['deal_end_time']=secondsToTime($v['deal_end_time']);

            $info[$k]['deal_start'] = date("F j, Y, g:i a", strtotime($v['deal_start']." ".$info[$k]['deal_time']));
            $info[$k]['deal_end'] = date("F j, Y, g:i a", strtotime($v['deal_end']." ".$info[$k]['deal_end_time']));
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

    public function get_catname() {
        $user_id = $this->is_require($this->postvars, 'user_id');
        #######################################
        $user_cats=array();
        $user_cat = $this->m_common->db_select('user_cat', 'tbl_customer', array("user_id" => $user_id), array(), '', '', '', 'row_array', 0);
        if(!empty($user_cat)){
            $user_cats = (array) explode(",", $user_cat['user_cat']);
        }
        
        $info = $this->m_common->db_select('cid,cname', 'tbl_category', '', array(), 'cname', '', '', 'all', 0);
        foreach ($info as $k => $v) {
            $info[$k]['status'] = 0;
            if (in_array($v['cid'], $user_cats)) {
                $info[$k]['status'] = 1;
            }
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
        #######################################
//        $q = "select user_id,email,name,password from tbl_customer where email like '$email' limit 1";
//        $info = $this->m_common->select_custom($q);
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

        #######################################

        $user_tbl = "tbl_customer";
       
        $where = array(
            'secret_token' => $secret_token,
        );
        $info = $this->m_common->db_select('secret_token,user_id,email', $user_tbl, $where, array(), '', '', '', 'row');

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
        $this->Is_authorised($user_id, $user_token);
        
        $q = "update tbl_deal set `share_count`=`share_count`+1 where id = $deal_id";
        $this->m_common->query_custom($q);
        $this->_sendResponse(1);
    }

    public function deal_check_activated() {

        $deal_id = $this->is_require($_POST, 'deal_id');
        $customer_id = $this->is_require($_POST, 'customer_id');


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

    public function deal_activated() {
        $user_id = $this->is_require($_POST, 'user_id');
        $user_token = $this->is_require($_POST, 'user_token');
        $deal_id = $this->is_require($_POST, 'deal_id');      
        $business_id = $this->is_require($_POST, 'business_id');      
        $lat = $this->is_require($_POST, 'lat');      
        $lng = $this->is_require($_POST, 'lng');      
        $this->Is_authorised($user_id, $user_token);
//        $wh=array(
//            'consumer_id' => $user_id,
//            'business_id' => $business_id,
//            'deal_id' => $deal_id,
//        );
//        $is_row = $this->m_common->db_select('count(*) as cnt', 'tbl_deals_activated',$wh, array(), '', '', '', 'row_array');
//        if($is_row['cnt']==0){
//            $ins = array(
//                'consumer_id' => $user_id,
//                'business_id' => $business_id,
//                'deal_id' => $deal_id,
//                'lat' => $lat,
//                'lng' => $lng,
//            );
//            $this->m_common->insert_entry('tbl_deals_activated', $ins);
//            $this->msg="The deal has successfully activated";
//            $status_code=1;
//        }else{
//            $this->msg="You have already activated this deal";
//            $status_code=1;
//        }
//
//
//        $this->_sendResponse($status_code);

        $query = "select t2.*
              from tbl_deals_activated t2 where t2.deal_id=$deal_id and t2.customer_id='$user_id'";
        $info = $this->m_common->select_custom($query);

//        foreach ($info as $k => $v) {
//            if ($v['is_active'] == '0') {
////                $update_query = "update tbl_deal t1 set t1.is_active='1' where t1.id=$id";
////                $info_update =
//                $this->m_common->update_entry("tbl_deal", array('is_active' => '1',), array('id' => $id));
//            }
//        }

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
//        foreach ($info as $k => $v) {
//            $this->m_common->update_entry("tbl_deal", array('is_active' => '1',), array('id' => $id));
//            $info[$k]['is_active'] = '1';
//            $final_info[]=$info[$k];
//        }
//        //echo "<pre>";print_r($info);exit;
//        $data = array(
//            "info" => $final_info,
//        );
//        $this->result = $data;
//        $status_code = 1;
//        $this->_sendResponse($status_code);
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

    function send_email($p) {


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
        //$this->email->from("jckhunt4@gmail.com","Deal On GOGO Network");
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
            11 => 'Required field missing',
            1 => 'OK',
            2 => 'email already exist',
            3 => 'email not exists',
            4 => 'password not match',
            0 => 'server error',
            10 => 'Bad Request',
            5 => 'You must be authorized to view this page.',
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
             and ((t1.deal_start = '$cs' and t1.`repeat` = '' ) or (t1.deal_start <= '$cs' and t1.deal_start < '$next_sunday' and t1.`repeat` like '%$current_day%')) and t1.deal_time <= '$time_in_sec' and t1.deal_end_time >= '$time_in_sec' ";
        
    
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
                $info[$icnt]['deal_image'] = $this->get_deal_image_path($v['deal_image']);
                $info[$icnt]['deal_start'] = date("F j, Y, g:i a", strtotime($v['deal_start']));
                $info[$icnt]['deal_end'] = date("F j, Y, g:i a", strtotime($v['deal_end']));
                $info[$icnt]['deal_time']=secondsToTime($v['deal_time']);
            $info[$icnt]['deal_end_time']=secondsToTime($v['deal_end_time']);
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
                $info[$icnt]['deal_image'] = $this->get_deal_image_path($v['deal_image']);
                $info[$icnt]['deal_start'] = $v['deal_start'];
                $info[$icnt]['deal_end'] = $v['deal_end'];
                $info[$icnt]['deal_time'] = secondsToTime($v['deal_time']);
                $info[$icnt]['deal_end_time'] = secondsToTime($v['deal_end_time']);
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
    private function get_deal_image_path($str) {
        //$url=base_url()."image/?path".$str."&width=300&height=300";
        
        if (is_image_path_proper($str)) {
            //return base_url() . "uploads/" . $str;
            return base_url() . "image/?path=uploads/".$str."&width=".$this->width;
        } else {
            return base_url()."images/no_image.png";
            //return base_url() . "image/?path".$str."&width=".$this->width;
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
            }
            //echo "<pre>";print_r($response);exit;
            curl_close($ch);
            $this->update_badge($push_arr['user_id']);
        }
        //sleep(1);
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
    exit;
}



    function push_testing_by_user(){
        $user_id = $_GET['user_id'];
        $user_info = $this->m_common->db_select('*', 'tbl_customer',array("user_id"=>$user_id),array(),'','',array(1,0),'row_array');
        
        
        $info = array();
        $q = "select t1.*,t2.*
             from tbl_deal t1 join tbl_shop t2 on t1.shop_id = t2.shop_id limit 3
             ";
        $deal_ids_info=array();
    
        $infores = $this->m_common->select_custom($q, 1);

        $icnt=0;
        foreach ($infores->result_array() as $k => $v) {
                $info[$icnt] = $v;
                $info[$icnt]['shop_cats'] = $this->get_shop_cats_names($v['shop_cats']);
                $info[$icnt]['shop_image'] = $this->get_shop_image_path($v['shop_image']);
                $info[$icnt]['deal_image'] = $this->get_deal_image_path($v['deal_image']);
                $info[$icnt]['deal_start'] = date("F j, Y, g:i a", strtotime($v['deal_start']));
                $info[$icnt]['deal_end'] = date("F j, Y, g:i a", strtotime($v['deal_end']));
                  $info[$icnt]['deal_time']=secondsToTime($v['deal_time']);
            $info[$icnt]['deal_end_time']=secondsToTime($v['deal_end_time']);
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
            $passphrase='1234';
            stream_context_set_option($streamContext, 'ssl', 'local_cert', $certificate);
           stream_context_set_option($streamContext, 'ssl', 'passphrase', $passphrase);
            if($is_dev==1){
                $apns = stream_socket_client($apnsHost, $error, $errorString, 60, STREAM_CLIENT_CONNECT, $streamContext);
            }else{
                @$apns = stream_socket_client($apnsHost, $error, $errorString, 60, STREAM_CLIENT_CONNECT, $streamContext);
            }
            
            
        } catch (Exception $e) {
            
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

    public function cat_insert() {
        $arr = array(
            "Accommodations",
            "Apparel Mens",
            "Apparel Womens",
            "Apartment Complexes",
            "Attorneys",
            "Automotive Services",
            "Baby Stores",
            "Bars",
            "Beach Stores",
            "Book Stores",
            "Breakfast Diner",
            "Bridal Shops",
            "Chiropractors",
            "Coffee Shops",
            "Consignment Shops",
            "Convenience Stores",
            "Craft Stores",
            "Dentists",
            "Doughnut Shops",
            "Dry Cleaning",
            "Entertainment",
            "Farmers Markets",
            "Flower Shops",
            "Garage Sales",
            "Golf Courses",
            "Grocery Stores",
            "Happy Hour",
            "Health/Fitness",
            "Hobby Shops",
            "Home Furnishings",
            "Home Improvements",
            "Jewelry Stores",
            "Liquor Stores",
            "Music Stores",
            "Pastry Shops",
            "Pet Stores",
            "Plastic Surgeons",
            "Real Estate Commercial",
            "Real Estate New",
            "Real Estate Rental",
            "Real Estate Re-Sale",
            "Restaurants",
            "Salons/Spas",
            "Specialty Health Foods",
            "Tech Stores",
            "Tickets",
            "Tourist Attractions",
        );
        foreach ($arr as $v) {
            $shops = $this->m_common->db_select("count(*) as cnt", "tbl_category", array("cname" => $v,), array(), '', '', '', 'row_array');
            if ($shops['cnt'] == 0) {
                $inn = array(
                    "cname" => $v,
                    "dis" => 10
                );

                $temp = $this->m_common->insert_entry("tbl_category", $inn, 1);
            }
        }
    }

}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */