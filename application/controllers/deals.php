<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class deals extends CI_Controller {

    private $data;

    function deals(){

        parent::__construct();


        $this->load->model('business');
        $this->load->model('deal');

        $this->load->model('m_common');
        $this->load->helper('date');
        //date_default_timezone_set('America/Los_Angeles');
        $this->check_login();
        $this->user_id = $this->session->userdata('user_id');
        $user_info = $this->m_common->db_select("*", "tbl_users", array("user_id" => $this->user_id), '', '', '', array(1, 0), 'row_array');
        $task_info = $this->m_common->db_select("*", "tbl_task", array("task_by" => $this->user_id), array(), '`task_id` DESC');
        $message_info = $this->msg_info();
        $this->data = array(
            "user_info" => $user_info,
            "task_info"=>$task_info,
            "message_info"=>$message_info,
            "current_page" => $this->uri->segment(2),
        );
        $unread = $this->check_unread();
        $this->data['unread'] = $unread;

    }


    function create_test_deals(){


        if((is_permission($this->data['user_info']['role'], "create_deal")) == FALSE){
            echo "You Don't have permission to access this page";
            exit;
        }

        $deal=array("timezone"=>"UM5");

        if($this->session->userdata('role') != 1){

            echo "You Don't have permission to access this page";
            exit;

        }

        $message = "";
        $where = array();
        $shops=array();

        $deal=array("timezone"=>"UM5", "how_many"=>5, "zip_code"=>28470, "scat"=>63);


        if ($_SERVER["REQUEST_METHOD"]=="POST"){



            $start_date = DateTime::createFromFormat('m/d/y', $_POST['deal_start'])->format('Y-m-d');
            $end_date = DateTime::createFromFormat('m/d/y', $_POST['deal_end'])->format('Y-m-d');

            $converted_start_date = convert_to_utc($start_date,$this->input->post('deal_time'),$this->input->post('timezone'));
            $converted_end_date = convert_to_utc($end_date,$this->input->post('deal_end_time'),$this->input->post('timezone'));

            $deal_time = $converted_start_date["seconds"];
            $deal_end_time = $converted_end_date["seconds"];


            $deal_start_date=$converted_start_date["utc_date"];


            $business= $this->business->getBusinessListByZip($_POST["zip_code"], 1);
            print_rr($business,0);


            for ($c =0; $c < $_POST["how_many"]; $c++){

                echo $c.": <br>";

                $deal_id = $this->deal->get_deal_id();







                $shop_id = $business[0]["shop_id"];
                $deal_title="Test Deal #$deal_id";
                $deal_description = "This is a test description for deal #$deal_id";
                $schedule_text="";
                $original_price=rand(50,100);
                $offer_price=rand(1,49);
                $deal_image="http://db.locallyepic.com/images/no_image.png";
                $deal_start= $start_date;
                $deal_end =$converted_end_date["utc_date"];
                $tsDealStart = strtotime("$deal_start_date ".$converted_start_date["utc_time"]);
                $tsDealEnd = strtotime($converted_end_date["utc_date"]." ".$converted_end_date["utc_time"]);
                $timezone= $this->input->post('timezone');
                $repeat='';
                $featured_deal=1;
                $contact_name = $business[0]["contact_first_name"]." ".$business[0]["contact_last_name"];
                $contact_number = $business[0]["contact_phone"];
                $website = $business[0]["url"];
                $share_count=0;
                $status=1;
                $is_active=1;
                $is_off=0;

                $sql = "
                    insert into tbl_deal
                    set
                    shop_id=?,
                    deal_title=?,
                    deal_description=?,
                    schedule_text=?,
                    original_price=?,
                    offer_price=?,
                    deal_image=?,
                    deal_start=?,
                    deal_end=?,
                    deal_time=?,
                    deal_end_time=?,
                    tsDealStart=?,
                    tsDealEnd=?,
                    timezone=?,
                    `repeat`=?,
                    featured_deal=?,
                    contact_name=?,
                    contact_number=?,
                    website=?,
                    share_count=?,
                    status=?,
                    is_active=?,
                    is_off=?
                ";

                $result = $this->db->query($sql,array(
                                                        $shop_id,
                                                        $deal_title,
                                                        $deal_description,
                                                        $schedule_text,
                                                        $original_price,
                                                        $offer_price,
                                                        $deal_image,
                                                        $deal_start,
                                                        $deal_end,
                                                        $deal_time,
                                                        $deal_end_time,
                                                        $tsDealStart,
                                                        $tsDealEnd,
                                                        $timezone,
                                                        $repeat,
                                                        $featured_deal,
                                                        $contact_name,
                                                        $contact_number,
                                                        $website,
                                                        $share_count,
                                                        $status,
                                                        $is_active,
                                                        $is_off
                                                    ));




            }



            print_rr($_POST,0);
            print_rr($business);
        }


        $cats = $this->m_common->db_select("*", "tbl_category", array(), array(), 'cname ASC', '', '', 'all');

        $this->data['scat']=63;
        $this->data['deal']=$deal;
        $this->data['cats'] = $cats;
        $this->data['shops']=$shops;
        $this->data['message']=$message;
        $this->load->view('header',$this->data);
        $this->load->view('sidebar', $this->data);
        $this->load->view('deals/create_test_deals', $this->data);



    }

    function create_power_deal() {

        if((is_permission($this->data['user_info']['role'], "create_deal")) == FALSE){
            echo "You Don't have permission to access this page";
            exit;
        }

        $deal=array("timezone"=>"UM5");

        if($this->session->userdata('role') != 1){

            echo "You Don't have permission to access this page";
            exit;

        }

        $message = "";
        $where = array();


        if ($_SERVER["REQUEST_METHOD"]=="POST"){

            $start_date = DateTime::createFromFormat('m/d/y', $_POST['deal_start'])->format('Y-m-d');
            $end_date = DateTime::createFromFormat('m/d/y', $_POST['deal_end'])->format('Y-m-d');

            $converted_start_date = convert_to_utc($start_date,$this->input->post('deal_time'),$this->input->post('timezone'));
            $converted_end_date = convert_to_utc($end_date,$this->input->post('deal_end_time'),$this->input->post('timezone'));

            $shop_id = $_POST['shop_id'];

            $deal_time = $converted_start_date["seconds"];
            $deal_end_time = $converted_end_date["seconds"];


            $this->load->library('form_validation');
            $this->form_validation->set_error_delimiters('<span for="required" generated="true" class="help-inline" style="color:red;"> *', '</span>');
            $this->form_validation->set_rules('shop_id', 'Business Name', 'required');
            $this->form_validation->set_rules('deal_title', 'Deal Title', 'required|callback_check_swear');
            $this->form_validation->set_rules('deal_description', 'Deal Description', 'required|callback_check_swear');
            $this->form_validation->set_rules('original_price', 'Original Price', 'required');
            $this->form_validation->set_rules('offer_price', 'Deal Price', 'required');
            $this->form_validation->set_rules('deal_start', 'Deal Start Date', 'required');
            $this->form_validation->set_rules('deal_end', 'Deal End Date', 'required|callback_deal_enddate');
            $this->form_validation->set_rules('deal_repeat[]', 'deal repeat', '');
            $this->form_validation->set_rules('featured', 'Hot Deal', '');
            $this->form_validation->set_rules('deal_time', 'Deal Time', 'required|callback_deal_time');
            $this->form_validation->set_rules('deal_end_time', 'Deal End Time', 'required|callback_deal_end_time');
            $this->form_validation->set_rules('contact_name', 'Contact Name', 'required');
            $this->form_validation->set_rules('contact_number', 'Contact Number', 'required');
            $this->form_validation->set_rules('website', 'Website', 'required');

            if ($this->form_validation->run() == TRUE) {

                $featured = ($this->input->post('featured') == "on") ? 1 : 0;
                $rep_array = (array) $this->input->post('deal_repeat');
                $repeat = implode(",", $rep_array);
                $deal_start_date=$converted_start_date["utc_date"];

                $tsDealStart = strtotime("$deal_start_date ".$converted_start_date["utc_time"]);
                $tsDealEnd = strtotime($converted_end_date["utc_date"]." ".$converted_end_date["utc_time"]);


                $inn = array(
                    "shop_id" => $this->input->post('shop_id'),
                    "deal_title" => $this->input->post('deal_title'),
                    "deal_description" => $this->input->post('deal_description'),
                    "original_price" => $this->input->post('original_price'),
                    "offer_price" => $this->input->post('offer_price'),
                    "deal_start" => $deal_start_date,
                    "deal_end" => $converted_end_date["utc_date"],
                    "deal_time" => $deal_time,
                    "deal_end_time" => $deal_end_time,
                    "featured_deal" => $featured,
                    "repeat" => $repeat,
                    "deal_image" => "http://".$_SERVER['SERVER_NAME']."/images/no_image.png",
                    "contact_name" => $this->input->post('contact_name'),
                    "contact_number" => $this->input->post('contact_number'),
                    "website" => $this->input->post('website'),
                    "timezone" => $this->input->post('timezone')
                );

                if(isset($_POST['deal_image_dup'])){
                    $inn['deal_image']=$_POST['deal_image_dup'];
                }


                $temp = $this->m_common->insert_entry("tbl_deal", $inn, 1);
                if ($temp['last_id'] > 0) {

                    //$message = "Deal " . $this->input->post('deal_title') . " successfully inserted";
                    $message = "Your Deal Was Successfully Created Thank You";
                    if (isset($_FILES['deal_image'])) {
                        $r = $this->file_upload($_FILES['deal_image'], $temp['last_id']);
                        if (!empty($r)) {
                            $up = array(
                                "deal_image" => "http://".$_SERVER['SERVER_NAME']."/uploads/$r",
                            );
                            $this->m_common->update_entry("tbl_deal", $up, array("id" => $temp['last_id']));

                            $config['image_library'] = 'gd2';
                            $config['source_image'] = '/var/www/html/uploads/'.$r;
                            $config['create_thumb'] = FALSE;
                            $config['maintain_ratio'] = FALSE;
                            $config['width']    = 75;
                            $config['height']   = 75;
                            $config['new_image'] = '/var/www/html/uploads/thumbs/'.$r;

                            $this->load->library('image_lib', $config);

                            $this->image_lib->resize();
                        }
                    }


                    $this->session->set_userdata('current_message', $message);
//                    echo "<pre>";print_r($_POST);
//                    echo "<pre>";print_r($_FILES);exit;
//                    exit;

                    //echo "stop";
                    //exit;
                     if($this->data['user_info']['role'] != 6){
                    redirect('/site/manage_deal', 'refresh');
                    }else{
                        redirect('/site/create_deal', 'refresh');
                    }


                }
            }
        }







        $shops = $this->business->getBusinessList();
        $this->data['deal']=$deal;



        $this->data['shops']=$shops;
        $this->data['message']=$message;
        $this->load->view('header',$this->data);
        $this->load->view('sidebar', $this->data);
        $this->load->view('deals/create_power_deal', $this->data);

    }



    /***********************************************
      THESE FUNCTIONS ARE FOR THE SITE TEMPLATE.  NOT PART OF THE DEALS LOGIC
      ***********************************************************************/
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
    private function file_upload($arr, $cid, $is_user = 0) {
        //echo "<pre>";print_r($arr);
        $tmp = rtrim($_SERVER['DOCUMENT_ROOT'], "/");
        if ($_SERVER['HTTP_HOST'] == "localhost") {

            $this->project_path = $tmp . str_replace(basename($_SERVER['SCRIPT_NAME']),"",$_SERVER['SCRIPT_NAME']);
        } else {
            $this->project_path = $tmp . str_replace(basename($_SERVER['SCRIPT_NAME']),"",$_SERVER['SCRIPT_NAME']);
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
                if ($is_user) {
                    $path = $this->project_path . 'uploads/user/';
                } else {
                    $path = $this->project_path . 'uploads/';
                }

                $file_path = $path . $file_name;

                if (move_uploaded_file($arr["tmp_name"], $file_path) > 0) {
                    $r = $file_name;
                }
            }
        }

        return $r;
    }
    private function check_login() {
        if (!$this->session->userdata('logged_in')) {
            redirect('authentication/login', 'refresh');
        }
    }

    function msg_info($limit = 10 , $offset=0){
        $id = $this->data['user_info']['user_id'];
        $wh = "";
        $wh.=" AND t1.message_to LIKE '%$id%'";
        $lim = "LIMIT " . $offset . "," . $limit ;
        $q = "select t1.*,t2.* from tbl_message t1 join tbl_users t2 on t1.message_from = t2.user_id where 1=1 $wh ORDER BY t1.message_id DESC $lim";
        $message_info = $this->m_common->select_custom($q);
        return $message_info;
    }

    function check_unread(){

        $id = $this->data['user_info']['user_id'];
        $wh = "";
        $wh.="message_to LIKE '%$id%'";
        $message_info = $this->m_common->db_select("*","tbl_message",$wh);
        $p =count($message_info);
        $count=0;
        for($i=0;$i<$p;$i++){
             $t = explode(",", $message_info[$i]['who_open']);

             if(in_array($this->data['user_info']['user_id'], $t)){
                 $count;
             }
             else{
                 $count++;
             }
        }
        return $count;
    }
}