<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Gogo extends CI_Controller {

    function Gogo() {
        parent::__construct();
        $this->load->model('m_common');
        date_default_timezone_set('America/Los_Angeles');
    }

    public function index() {
        $data = array();
        $this->load->view('header');
        $this->load->view('sidebar', $data);
        $this->load->view('home', $data);
        $this->load->view('footer');
    }
    public function privercy_policy() {
        $data = array();
      
        $this->load->view('gogo/privercy_policy');
       
    }

    

    public function busername_check(){
        $str=$_POST['usermail'];

        $shops = $this->m_common->db_select("count(shop_id) as cnt", "tbl_shop", array("username" => $str,), array(), '', '', '', 'row_array');
        if ($shops['cnt'] > 0) {
            echo "ok";exit;
          
        } 
    }
    public function email_check(){
        $str=$_POST['usermail'];

        $shops = $this->m_common->db_select("count(shop_id) as cnt", "tbl_shop", array("email" => $str,), array(), '', '', '', 'row_array');
        if ($shops['cnt'] > 0) {
            echo "ok";exit;
          
        } 
    }
     // change 22/12/2014
     public function address_check(){
        $str=$_POST['useraddress'];

        $shops = $this->m_common->db_select("count(shop_id) as cnt", "tbl_shop", array("address" => $str,), array(), '', '', '', 'row_array');
        if ($shops['cnt'] > 0) {
            echo "ok";exit;
          
        } 
    }
    
     public function term_condition(){
            $this->load->view('gogo/term_condition', $data);
    }

    //NEW JOIN FORM

    public function join(){

        //echo "<pre>";print_r($_POST);
        //echo "<pre>";print_r($_FILES);exit;

        $message="";

        if (!empty($_POST)) { 

            $this->load->library('form_validation');
            $this->form_validation->set_rules('first_name', 'First Name', 'required');
            $this->form_validation->set_rules('last_name', 'Last Name', 'required');
            $this->form_validation->set_rules('email', 'Email', 'required|valid_email|is_unique[tbl_users.email]');
            $this->form_validation->set_rules('password', 'Password', 'required|min_length[8]|max_length[32]');

            if ($this->form_validation->run() == TRUE) {

                $inn = array(
                    "first_name" => $this->input->post('first_name'),
                    "last_name" => $this->input->post('last_name'),
                    "email" => $this->input->post('email'),
                    "password" => md5($this->input->post('password')),
                    "role" => 6,
                    "country_id" => 0,
                    "state_id" => 0,
                    "city_id" => 0,
                    "zip_code" => 0,
                    "sales_ambassador_name"=>$this->input->post('salesperson')
                );

                //need to upgrade to this before going live
                //"password" => password_hash($this->input->post('password'), PASSWORD_DEFAULT),

                //insert into the user table.
                $temp = $this->m_common->insert_entry("tbl_users", $inn, 1);

                //set this to use it on join 2 and join 3
                $this->session->set_userdata('user_id', $temp['last_id']);

                redirect("gogo/join2", 'refresh');




            } //end form validation





        } else {

            $this->session->sess_destroy();
        }


       
        $data = array('message' => $message);
        $this->load->view('gogo/join', $data);
        
    }
    public function join2(){

        $message="";
        $cats = $this->m_common->db_select("*", "tbl_category", array(), array(), 'cname ASC', '', '', 'all');



        if (!empty($_POST)) { 

            $this->load->library('form_validation');
            $this->form_validation->set_rules('shop_name', 'Business Name', 'required');
            $this->form_validation->set_rules('contact_number', 'Business Contact Number', 'required');

            $this->form_validation->set_rules('category', 'Category', 'required');
            $this->form_validation->set_rules('address', 'Address', 'required');

            //$this->form_validation->set_rules('street_number', 'Your address must be selected from the Google Autocomplete List', 'required');
            //$this->form_validation->set_rules('route', 'Your address must be selected from the Google Autocomplete List', 'required');
            $this->form_validation->set_rules('locality', 'Your address must be selected from the Google Autocomplete List', 'required');
            //$this->form_validation->set_rules('administrative_area_level_1', 'Your address must be selected from the Google Autocomplete List', 'required');
            //$this->form_validation->set_rules('postal_code', 'Your address must be selected from the Google Autocomplete List', 'required');
            //$this->form_validation->set_rules('country', 'Your address must be selected from the Google Autocomplete List', 'required');


            if ($this->form_validation->run() == TRUE) {

                //echo "<pre>";print_r($_POST);
                //echo "<pre>";print_r($_FILES);
                //exit;

                $country_id = 0;
                $state_id = 0;
                $city_id = 0;

                $country_id = $this->m_common->getCountryId( $this->input->post('country'));
                $state_id = $this->m_common->getStateId( $this->input->post('administrative_area_level_1'), $country_id);
                $city_id = $this->m_common->getCityId($country_id, $state_id, $this->input->post('locality'));

                //echo "<br>CountryID:".$country_id;
                //echo "<br>StateID:".$state_id;
                //echo "<br>CityID:".$city_id;

                $qUser = $this->m_common->getUser($this->session->userdata('user_id'));

                $sql = "select user_id  from tbl_users where role in (2,3,4,5) and concat_ws(' ', first_name, last_name) = ?";
                $result = $this->db->query($sql, array( $qUser->sales_ambassador_name ));

                $add_by = 0;

                if ($result->num_rows() > 0)
                {
                   $row = $result->row(); 

                   $add_by =  $row->user_id;

                }



                //print_r($qUser);

                $inn = array(
                    "user_id" => $this->session->userdata('user_id'),
                    "shop_name" => $this->input->post('shop_name'),
                    "shop_cats" =>$this->input->post('category'),
                    "country_id" => $country_id,
                    "state_id" => $state_id,
                    "city_id" => $city_id,
                    "zip_code" => $this->input->post('postal_code'),
                    "address" => $this->input->post('address'),
                    "email" => $qUser->email,
                    "first_name" => $qUser->first_name,
                    "last_name" => $qUser->last_name,
                    "contact_first_name" => $qUser->first_name,
                    "contact_last_name" => $qUser->last_name,
                    "contact_email" => $qUser->email,
                    "latitude" => $this->input->post('latitude'),
                    "longitude" => $this->input->post('longitude'),
                    "is_payment" => 0,
                    "add_by"=>$add_by,
                    "contact_phone"=> $this->input->post('contact_number'),
                    "url"=>addScheme($this->input->post('website'))

                    
                );

                //insert into the user table.
                $temp = $this->m_common->insert_entry("tbl_shop", $inn, 1);



                 $info_users_shops = array(
                    "user_id" => $this->session->userdata('user_id'),
                    "shop_id"=>$temp['last_id'],
                );

                $this->m_common->insert_entry("tbl_users_shops", $info_users_shops, 1);

                //set this to use it on join 2 and join 3
                $this->session->set_userdata('shop_id', $temp['last_id']);

                //Lets update the user profile with the info we now know.
                 $update = array(
                    "country_id" => $country_id,
                    "state_id" => $state_id,
                    "city_id" => $city_id,
                    "zip_code" => $this->input->post('postal_code')
                );

                $this->db->where('user_id', $this->session->userdata('user_id'));
                $this->db->update('tbl_users', $update); 
                
                //email

                     $dt = date('l jS \of F Y \a\t h:i:s A'); // in mail display time text 
                    $data=array(
                        "name"=>$qUser->first_name,
                        "dt"=>$dt,
                    );
                    $message_body=$this->load->view('email/welcome_signup', $data,true);

                     $message_body1=$this->load->view('email/welcome_email1', $data,true);

                    $full_shop_info=$this->get_full_shop_info($this->session->userdata('shop_id'));
                    //echo "<pre>";print_r($full_shop_info);
                   
            
                    $message_body_new_bsignup=$this->load->view('email/message_body_new_bsignup', $full_shop_info,true);

                    $mail_p=array(
                        "to"=>$qUser->email,
                        "message_body"=>$message_body1,
                        "subject"=>"Welcome to Locally Epic",
                        
                    );

                    $mail_randy=array(
                        "to"=>'r.johnson@locallyepic.com',
                        "message_body"=>$message_body1,
                        "subject"=>"Welcome to Locally Epic",
                        
                    );


                    $m_new_bsignup=array(
                        "to"=>"info@locallyepic.com",
                        "bcc"=>"r.johnson@locallyepic.com",

                        "message_body"=>$message_body_new_bsignup,
                        "subject"=>"Locally Epic: New Business Signup",
                        
                    );
                    $this->sent_email($mail_p);
                    //$this->sent_email($mail_randy);
                    // $this->sent_email($m_new_bsignup);

                    //echo "stop";
                    //exit;
                //end email


                $this->session->set_userdata('logged_in_by_shop', 1);
                $this->session->set_userdata('role',6 );
                $this->session->set_userdata('shop_id',$this->session->userdata('shop_id'));
                $this->session->set_userdata('shop_name',$this->input->post('shop_name'));
                $this->session->set_userdata('shop_image',''); //leave blank for now 
                $this->session->set_userdata('is_admin',0);


    
                redirect("authentication/payment?new=1", 'refresh');

            }

            
        }



        
        $data = array(
            'message' => $message,
            'cats'=>$cats
        );
        $this->load->view('gogo/join2', $data);
        
    }

    public function join3(){

        $data = array();
        $this->load->view('gogo/join3', $data);
        
    }



    //END NEW JOIN FORM
 

    public function signup() {

        redirect("gogo/join", 'refresh');
        //700 7th Ave. S Myrtle Beach SC
        $state=array();
        $city=array();
        $message = "";
        if (!empty($_POST)) {
   
            //echo "<pre>";print_r($_POST);
            //echo "<pre>";print_r($_FILES);exit;


                $arr = (array) $this->input->post('category');
                $cats = implode(",", $arr);

                $inn = array(
                    "shop_name" => $this->input->post('simtitle'),
                    "first_name" => $this->input->post('first_name'),
                    "last_name" => $this->input->post('last_name'),
                    "shop_cats" => $cats,
                    "shop_description" => $this->input->post('description'),
                    "country_id" => $this->input->post('scountry'),
                    "state_id" => $this->input->post('sstate'),
                    "city_id" => $this->input->post('scity'),
                    "address" => $this->input->post('address'),
                    "zip_code" => $this->input->post('zip_code'),
                    "email" => $this->input->post('usermail'),
                    "url" => $this->input->post('url'),
                    "username" => $this->input->post('username'),
                    "password" => $this->input->post('userpwd1'),
                    "latitude" => $this->input->post('latitude'),
                    "longitude" => $this->input->post('longitude'),
                );
                
                $chk_info= $this->m_common->db_select("shop_id", "tbl_shop", array("username" => $this->input->post('username'),), array(), '', '', '', 'row_array');
                if (empty($chk_info)){
                    $temp = $this->m_common->insert_entry("tbl_shop", $inn, 1);
                    $dt = date('l jS \of F Y \a\t h:i:s A'); // in mail display time text 
                    $data=array(
                        "name"=>$inn['first_name'],
                        "dt"=>$dt,
                    );
                    $message_body=$this->load->view('email/welcome_signup', $data,true);
                    $full_shop_info=$this->get_full_shop_info($temp['last_id']);
                    //echo "<pre>";print_r($full_shop_info);
            
                    $message_body_new_bsignup=$this->load->view('email/message_body_new_bsignup', $full_shop_info,true);
                    $mail_p=array(
                        "to"=>$this->input->post('usermail'),
                        "message_body"=>$message_body,
                        "subject"=>"Welcome to Locally Epic",
                        
                    );
                    $m_new_bsignup=array(
                        "to"=>"info@locallyepic.com",
                        "message_body"=>$message_body_new_bsignup,
                        "subject"=>"Deal on gogo network : New Business Signup",
                        
                    );
                    $this->sent_email($mail_p);
                    $this->sent_email($m_new_bsignup);
                }else{
                    $temp['last_id']=$chk_info['shop_id'];
                    $this->m_common->update_entry("tbl_shop", $inn, array("shop_id"=>$chk_info['shop_id']));
                }

                
                if ($temp['last_id'] > 0) {
                    //$message = "Shop " . $this->input->post('simtitle') . " successfully inserted";
                    if (isset($_FILES['picture'])) {
                        $r = $this->file_upload($_FILES['picture'], $temp['last_id']);
                        if (!empty($r)) {
                            $up = array(
                                "shop_image" => $r,
                            );
                            $this->m_common->update_entry("tbl_shop", $up, array("shop_id" => $temp['last_id']));
                            
                        }
                    }
                     // change 22/12/2014
                    $name = $_POST['first_name'];

                    // set the login variables for this user.
                    $this->session->set_userdata('logged_in_by_shop', 1);
                    $this->session->set_userdata('shop_id',$temp['last_id'] );
                    $this->session->set_userdata('shop_name',$this->input->post('simtitle'));
                    $this->session->set_userdata('shop_image',''); //leave blank for now 
                    $this->session->set_userdata('is_admin',0);


    
                    redirect("autentication/payment?new=1", 'refresh');
                    
                }
           
        }
        
        $cats = $this->m_common->db_select("*", "tbl_category", array(), array(), 'cname ASC', '', '', 'all');
        $country = $this->m_common->db_select("*", "tbl_country", array(), array(), 'order DESC,id asc', '', '', 'all');
        
        $data = array(
            'message' => $message,
            'cats'=>$cats,
            'country'=>$country,

        );

        $this->load->view('gogo/signup', $data);

    }






    public function warning() {
        $msg = isset($_GET['msg']) ? $_GET['msg'] : "";
        $data = array(
            "msg" => $msg,
        );
        $this->load->view('header');
        $this->load->view('sidebar');
        $this->load->view('warning', $data);
        $this->load->view('footer');
    }

 



    public function logout() {
        $array_items = array('logged_in' => '');
        $this->session->unset_userdata($array_items);
        $this->session->userdata = array();
        $this->session->sess_destroy();
        redirect('/site/index', 'refresh');
    }

    public function business_password($str) {
        if ($_POST['suname'] == $str) {
            $this->form_validation->set_message('business_password', 'The password must be diffrent from the username');
            return FALSE;
        } else {
            return TRUE;
        }
    }

    public function shop_username_unique($str) {
        $shop_id = $this->shop_id;
        $shops = $this->m_common->db_select("count(shop_id) as cnt", "tbl_shop", array("username" => $str, 'shop_id !=' => $shop_id), array(), '', '', '', 'row_array');
        if ($shops['cnt'] > 0) {
            $this->form_validation->set_message('shop_username_unique', 'The username already exists');
            return FALSE;
        } else {
            return TRUE;
        }
    }

    public function shop_name_unique($str) {
        $shop_id = $this->shop_id;
        $shops = $this->m_common->db_select("count(shop_id) as cnt", "tbl_shop", array("shop_name" => $str, 'shop_id !=' => $shop_id), array(), '', '', '', 'row_array');
        if ($shops['cnt'] > 0) {
            $this->form_validation->set_message('shop_username_unique', 'The shop name already exists');
            return FALSE;
        } else {
            return TRUE;
        }
    }

    public function confirm_password($str) {

        if ($str != $_POST['spass']) {
            $this->form_validation->set_message('confirm_password', 'The confirm password not match');
            return FALSE;
        } else {
            return TRUE;
        }
    }

    public function cat_distance($str) {

        if (!preg_match('/[0-9.]+/', $str)) {
            $this->form_validation->set_message('cat_distance', 'Please enter distance in integer or float. (e.g 1,1.5 or 2.0)');
            return FALSE;
        } else {
            return TRUE;
        }
    }

    public function deal_enddate($str) {
        $s = strtotime($_POST['deal_start']);
        $e = strtotime($str);
        if ($e <= $s) {
            $this->form_validation->set_message('deal_enddate', 'The deal end date must be greater than the deal start date.');
            return FALSE;
        } else {
            return TRUE;
        }
    }

    public function old_password_match($str) {
        $shop_id = $this->session->userdata('shop_id');
        $shops = $this->m_common->db_select("count(shop_id) as cnt", "tbl_shop", array("password" => $str, 'shop_id' => $shop_id), array(), '', '', '', 'row_array');
        if ($shops['cnt'] == 0) {
            $this->form_validation->set_message('old_password_match', 'The Old password not match');
            return FALSE;
        } else {
            return TRUE;
        }
    }

    public function delete_deal() {

        if (isset($_POST['id'])) {

            $id = $_POST['id'];
            $id = trim(str_replace("del_", "", $id));
            $this->m_common->delete_entry("tbl_deal", array('id' => $id));

            echo '1';
            exit;
        }
    }

    public function delete_cat() {

        if (isset($_POST['id'])) {

            $id = $_POST['id'];
            $id = trim(str_replace("del_", "", $id));
            $this->m_common->delete_entry("tbl_category", array('cid' => $id));

            echo '1';
            exit;
        }
    }

    public function delete_schedule() {

        if (isset($_POST['id'])) {

            $id = $_POST['id'];
            $id = trim(str_replace("del_", "", $id));
            $this->m_common->delete_entry("tbl_schedule", array('id' => $id));

            echo '1';
            exit;
        }
    }

    public function loadData() {
        $loadType = $_POST['loadType'];
        $loadId = $_POST['loadId'];

        $result = $this->m_common->getData($loadType, $loadId);
        $HTML = "";

        if ($result->num_rows() > 0) {
            foreach ($result->result() as $list) {
                $HTML.="<option value='" . $list->id . "'>" . $list->name . "</option>";
            }
        }
        echo $HTML;
    }

    private function file_upload($arr, $cid) {
        //echo "<pre>";print_r($arr);
        $tmp = rtrim($_SERVER['DOCUMENT_ROOT'], "/");
        if ($_SERVER['HTTP_HOST'] == "localhost") {

            $this->project_path = $tmp . "/";
        } else {
            $this->project_path = $tmp . "/";
        }

        $r = "";

        if (!empty($arr)) {
            if ($arr["error"] == 0) {
                $temp = explode('.', $arr['name']);
                $extention = end($temp);
                $file_name = reset($temp);
                if (empty($file_name)) {
                    $file_name = $temp[1];
                }
                $ftoken = $this->get_random_string(5);
                $file_name = $cid . '_' . time() . '_' . $ftoken . '.' . $extention;

                $path = $this->project_path . 'uploads/';
                $file_path = $path . $file_name;
                @$ups=move_uploaded_file($arr["tmp_name"], $file_path);
                if ($ups > 0) {
                    $r = $file_name;
                }
            }
        }

        return $r;
    }

    private function check_login() {
        if (!$this->session->userdata('logged_in')) {
            redirect('autentication/index', 'refresh');
        }
    }

    private function sent_email($p) {
         $this->load->library('email');

        //$p['message_body']="benzatine contact by ".$p['from_name']." with email ".$p['from']."<br /><br />";
        $config = array(
            'protocol' => 'smtp',
            'smtp_host' => 'ssl://email-smtp.us-east-1.amazonaws.com',
            'smtp_port' => '465',
            'smtp_timeout' => '7',
            'smtp_user' => "AKIAJOY3AJZ62KMGFOUQ",
            'smtp_pass' => "AirMJyt3GRT3YGtxsxCqzlc9TqbgmbrdXq7J+gMQyWP5",
            'charset' => 'utf-8',
            'newline' => "\r\n",
            'wordwrap' => TRUE,
            'mailtype' => 'html',
            'validation' => TRUE,
            'priority' => 1,
            'smtp_crypto' =>'tls'
        );
        //echo "<pre>";print_r($config);
        $this->email->initialize($config);
        $this->email->from("info@locallyepic.com","Locally Epic");
        $this->email->to($p['to']);
        if (isset($p['bcc'])) {$this->email->bcc($p['bcc']);}
        
        $this->email->subject($p['subject']);
        $this->email->message($p['message_body']);
        @$this->email->send();
        //echo $this->email->print_debugger();
        echo "<br>";
    }


    public function ajax_salesperson(){

        //1=>super admin,2 for=>nsm,3 =>ssm,4=>asm,5=>sales people login,6=>business login,7=>hr login

        $sql = "select concat_ws(' ', first_name, last_name) as `value` from tbl_users where role in (2,3,4,5) and (first_name like ? or last_name like ?) order by `value` limit 10";
        $result = $this->db->query($sql, array($_GET["q"].'%', $_GET["q"].'%', ));

        $r = $result->result_array();

        echo json_encode($r);

        exit;


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

    private function is_access_permission() {
        if ($this->session->userdata('is_admin') == 0) {
            redirect('site/warning?msg=you need to login as a admin', 'refresh');
        }
    }

    private function get_line_graph($arr,$type){
        $cat_key="xval";
        if($type=="Daily"){
            $cat_key="xval";
        }
        $data="";
        $cats="";
        foreach($arr as $v){
            $data.=$v['cnt'].",";
            if($type=="Daily"){
                $c=date("j M",strtotime($v['date']));
            }else{
                $c=$v['xval'];
            }
            $cats.="'".$c."',";
        }
        $r = "";
        $r = "{
        title: {
            text: 'Push Message',
            x: -20 //center
        },
        subtitle: {
            text: '',
            x: -20
        },
        xAxis: {
            categories: [".$cats."]
        },
        yAxis: {
            title: {
                text: 'Count'
            },
            plotLines: [{
                value: 0,
                width: 1,
                color: '#808080'
            }]
        },
        tooltip: {
            valueSuffix: ''
        },
        legend: {
            layout: 'vertical',
            align: 'right',
            verticalAlign: 'middle',
            borderWidth: 0
        },
        series: [{
            name: 'Push Notes',
            data: [".$data."]
        }]
    }";

        return $r;
    }
    private function get_col_graph($arr,$type) {
        $cat_key="xval";
        if($type=="Daily"){
            $cat_key="xval";
        }
        $data="";
        $cats="";
        foreach($arr as $v){
            $data.=$v['cnt'].",";
            if($type=="Daily"){
                $c=date("j M",strtotime($v['date']));
            }else{
                $c=$v['xval'];
            }
            $cats.="'".$c."',";
        }
        $r = "";
        $r = "{
        chart: {
            type: 'column'
        },
        title: {
            text: 'Push Message'
        },
        subtitle: {
            text: ''
        },
        xAxis: {
            categories: [".$cats."]
        },
        yAxis: {
            min: 0,
            title: {
                text: 'Count'
            }
        },
        tooltip: {
            headerFormat: '<span >{point.key}</span><table>',
            pointFormat: '<tr><td >{series.name}: </td>' +
                '<td><b>{point.y:.1f} </b></td></tr>',
            footerFormat: '</table>',
            shared: true,
            useHTML: true
        },
        plotOptions: {
            column: {
                pointPadding: 0.2,
                borderWidth: 0
            }
        },
        series: [{
            name: 'Push Notes',
             data: [".$data."]

        },]
    }";

        return $r;
    }

    private function get_full_shop_info($id) {
        $result = array();

        $q = "select * from tbl_shop  where shop_id = $id limit 1";
        $info = $this->m_common->select_custom($q);
        foreach ($info as $k => $v) {
            $result = $v;
            $result['shop_cats'] = $this->get_shop_cats_names($v['shop_cats']);
            $result['shop_image'] = $this->get_shop_image_path($v['shop_image']);
            $result['country_name'] = $this->get_country_name($v['country_id']);
            $result['state_name'] = $this->get_state_name($v['state_id']);
            $result['city_name'] = $this->get_city_name($v['city_id']);
        }
        return $result;
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
        return base_url() . "uploads/" . $str;
    }
     private function get_country_name($id) {
        $r="";
        $a = $this->m_common->db_select("name", "tbl_country", array("id" => $id), array(), '', '', '', 'row_array');
        if(!empty($a)){
            $r=$a['name'];
        }
        return $r;
    }
    private function get_state_name($id) {
        $r="";
        $a = $this->m_common->db_select("state_name as name", "tbl_state", array("sid" => $id), array(), '', '', '', 'row_array');
        if(!empty($a)){
            $r=$a['name'];
        }
        return $r;
    }
    private function get_city_name($id) {
        $r="";
        $a = $this->m_common->db_select("city_name as name", "tbl_city", array("city_id" => $id), array(), '', '', '', 'row_array');
        if(!empty($a)){
            $r=$a['name'];
        }
        return $r;
    }
}

/* End of file welcome.php */
/* Location: ./application/cconcatontrollers/welcome.php */