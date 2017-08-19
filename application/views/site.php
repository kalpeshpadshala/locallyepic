<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Site extends CI_Controller {

    function Site() {
        parent::__construct();
        $this->load->model('m_common');
        $this->check_login();
    }

    public function index() {
        $data = array();
        $this->load->view('header');
        $this->load->view('sidebar', $data);
        $this->load->view('home', $data);
        $this->load->view('footer');
    }

    public function manage_category() {
        $this->is_access_permission();
        $message = "";
        if (isset($_POST['add_cat'])) {

            $this->load->library('form_validation');
            $this->form_validation->set_error_delimiters('<span for="required" generated="true" class="help-inline" style="color:red;"> *', '</span>');
            $this->form_validation->set_rules('cname', 'Category Name', 'required|is_unique[tbl_category.cname]');
            $this->form_validation->set_rules('cdis', 'Distance', 'required');

            if ($this->form_validation->run() == TRUE) {

                $inn = array(
                    "cname" => $this->input->post('cname'),
                    "dis" => $this->input->post('cdis'),
                );

                $temp = $this->m_common->insert_entry("tbl_category", $inn, 1);
                if ($temp['last_id'] > 0) {
                    $message = "category " . $this->input->post('cname') . " successfully inserted";
                    if (isset($_FILES['cfile'])) {
                        $r = $this->file_upload($_FILES['cfile'], $temp['last_id']);
                        if (!empty($r)) {
                            $up = array(
                                "cimage" => $r,
                            );
                            $this->m_common->update_entry("tbl_category", $up, array("cid" => $temp['last_id']));
                        }
                    }
                }
            }
        }

        $data = array(
            'message' => $message,
        );
        $this->load->view('header');
        $this->load->view('sidebar', $data);
        $this->load->view('manage_category', $data);
        $this->load->view('footer');
    }
    
    public function manage_shop(){
        $this->is_access_permission();
        $message = "";
        if (isset($_POST['add_shop'])) {

            $this->load->library('form_validation');
            $this->form_validation->set_error_delimiters('<span for="required" generated="true" class="help-inline" style="color:red;"> *', '</span>');
            $this->form_validation->set_rules('sname', 'Business Name', 'required');
            $this->form_validation->set_rules('scat[]', 'Business Category', 'required');
            $this->form_validation->set_rules('sdesp', 'Business Description', 'required');
            $this->form_validation->set_rules('scountry', 'Country', 'required');
            $this->form_validation->set_rules('sstate', 'State', 'required');
            $this->form_validation->set_rules('scity', 'City', 'required');
            $this->form_validation->set_rules('sadd', 'Business Address', 'required');
            $this->form_validation->set_rules('semail', 'Business Email', 'required|valid_email');
            $this->form_validation->set_rules('suname', 'Business Username', 'required|is_unique[tbl_shop.username]|min_length[6]|max_length[32]');
            $this->form_validation->set_rules('spass', 'Business Password', 'required|min_length[6]|max_length[32]|callback_business_password');
            $this->form_validation->set_rules('spass1', 'Confirm Password', 'required|callback_confirm_password');

            if ($this->form_validation->run() == TRUE) {
                
                $arr=(array)$this->input->post('scat');
                $cats= implode(",",$arr);
                
                $inn = array(
                    "shop_name" => $this->input->post('sname'),
                    "shop_cats" => $cats,
                    "shop_description" => $this->input->post('sdesp'),
                    "country_id" => $this->input->post('scountry'),
                    "state_id" => $this->input->post('sstate'),
                    "city_id" => $this->input->post('scity'),
                    "address" => $this->input->post('sadd'),
                    "email" => $this->input->post('semail'),
                    "url" => $this->input->post('burl'),
                    "username" => $this->input->post('suname'),
                    "password" => $this->input->post('spass'),
                    "latitude" => $this->input->post('latitude'),
                    "longitude" => $this->input->post('longitude'),
                );

                $temp = $this->m_common->insert_entry("tbl_shop", $inn, 1);
                if ($temp['last_id'] > 0) {
                    $message = "Shop " . $this->input->post('sname') . " successfully inserted";
                    if (isset($_FILES['sfile'])) {
                        $r = $this->file_upload($_FILES['sfile'], $temp['last_id']);
                        if (!empty($r)) {
                            $up = array(
                                "shop_image" => $r,
                            );
                            $this->m_common->update_entry("tbl_shop", $up, array("shop_id" => $temp['last_id']));
                        }
                    }
                }
            }
        }
        $cats = $this->m_common->db_select("*", "tbl_category", array(), array(), 'cid', '', '', 'all');
        $country = $this->m_common->db_select("*", "tbl_country", array(), array(), 'id', '', '', 'all');
        $data = array(
            'message' => $message,
            'cats' => $cats,
            'country' => $country,
        );
        $this->load->view('header');
        $this->load->view('sidebar', $data);
        $this->load->view('manage_shop', $data);
        $this->load->view('footer');
    }
    
   


    public function create_deal(){
        $message = "";
        $where=array();
        if (isset($_POST['create_deal'])) {
//echo "<pre>";print_r($_POST);exit;
            $this->load->library('form_validation');
            $this->form_validation->set_error_delimiters('<span for="required" generated="true" class="help-inline" style="color:red;"> *', '</span>');
            $this->form_validation->set_rules('shop_id', 'Business Name', 'required');
            $this->form_validation->set_rules('deal_title', 'Deal Title', 'required');
            $this->form_validation->set_rules('deal_description', 'Deal Description', 'required');
            $this->form_validation->set_rules('original_price', 'Original Price', 'required');
            $this->form_validation->set_rules('offer_price', 'Offer Price', 'required');
            $this->form_validation->set_rules('deal_start', 'Deal Start Date', 'required');
            $this->form_validation->set_rules('deal_end', 'Deal End Date', 'required|callback_deal_enddate');


            if ($this->form_validation->run() == TRUE) {
               
                $featured= ($this->input->post('featured')=="on") ? 1 : 0;
                
                $inn = array(
                    "shop_id" => $this->input->post('shop_id'),
                    "deal_title" => $this->input->post('deal_title'),
                    "deal_description" => $this->input->post('deal_description'),
                    "original_price" => $this->input->post('original_price'),
                    "offer_price" => $this->input->post('offer_price'),
                    "deal_start" => strtotime($this->input->post('deal_start')),
                    "deal_end" => strtotime($this->input->post('deal_end')),
                    "featured_deal" => $featured,
                
                );

                $temp = $this->m_common->insert_entry("tbl_deal", $inn, 1);
                if ($temp['last_id'] > 0) {
                    $message = "Deal " . $this->input->post('deal_title') . " successfully inserted";
                    if (isset($_FILES['file'])) {
                        $r = $this->file_upload($_FILES['file'], $temp['last_id']);
                        if (!empty($r)) {
                            $up = array(
                                "deal_image" => $r,
                            );
                            $this->m_common->update_entry("tbl_deal", $up, array("id" => $temp['last_id']));
                        }
                    }
                }
            }
        }
        
        if ($this->session->userdata('is_admin')==0) {
           $where['shop_id']=$this->session->userdata('shop_id');
        }
        
        $shops = $this->m_common->db_select("shop_id,shop_name", "tbl_shop",$where, array(), 'shop_id desc', '', '', 'all');

        $data = array(
            'message' => $message,
            'shops' => $shops,

        );
        $this->load->view('header');
        $this->load->view('sidebar', $data);
        $this->load->view('create_deal', $data);
        $this->load->view('footer');
    }
    public function change_profile(){
        
        if (!$this->session->userdata('logged_in_by_shop')) {
            redirect('site/warning?msg=you need to login as a business', 'refresh');
        }else{
            $shop_id=$this->session->userdata('shop_id');
        }
        
        $message = "";
        if (isset($_POST['change_profile'])){
//echo "<pre>";print_r($_POST);exit;
            $this->load->library('form_validation');
            $this->form_validation->set_error_delimiters('<span for="required" generated="true" class="help-inline" style="color:red;"> *', '</span>');
            $this->form_validation->set_rules('fname', 'First Name', 'required');
            $this->form_validation->set_rules('lname', 'Last Name', 'required');
            $this->form_validation->set_rules('email', 'Email', 'required|valid_email');
            $this->form_validation->set_rules('sadd', 'Address', 'required');
     
            if ($this->form_validation->run() == TRUE) {
          
                $inn = array(
                    "first_name" => $this->input->post('fname'),
                    "last_name" => $this->input->post('lname'),
                    "email" => $this->input->post('email'),
                    "address" => $this->input->post('sadd'),              
                );
                $up=array(
                    "shop_id"=>$shop_id,
                );
                $this->m_common->update_entry("tbl_shop", $inn, $up);
           
            }
        }
        
        $info = $this->m_common->db_select("*", "tbl_shop", array("shop_id"=>$shop_id), array(), 'shop_id desc', '', '', 'row_array');
        
        $data = array(
            'message' => $message,
            'info' => $info,
        );
        
        $this->load->view('header');
        $this->load->view('sidebar', $data);
        $this->load->view('change_profile', $data);
        $this->load->view('footer');
    }
    public function warning(){
        $msg=isset($_GET['msg']) ? $_GET['msg'] : "";
        $data=array(
            "msg"=>$msg,
        );
        $this->load->view('header');
        $this->load->view('sidebar');
        $this->load->view('warning', $data);
        $this->load->view('footer');
    }
    public function edit_deal(){
        $message = "";
        if (isset($_POST['edit_deal'])) {
        //echo "<pre>";print_r($_POST);exit;
            if(!isset($_POST['id'])){
                
                redirect('/site/index', 'refresh');
            }else{
                $id=$_POST['id'];
            }
            $this->load->library('form_validation');
            $this->form_validation->set_error_delimiters('<span for="required" generated="true" class="help-inline" style="color:red;"> *', '</span>');
            $this->form_validation->set_rules('shop_id', 'Business Name', 'required');
            $this->form_validation->set_rules('deal_title', 'Deal Title', 'required');
            $this->form_validation->set_rules('deal_description', 'Deal Description', 'required');
            $this->form_validation->set_rules('original_price', 'Original Price', 'required');
            $this->form_validation->set_rules('offer_price', 'Offer Price', 'required');
            $this->form_validation->set_rules('deal_start', 'Deal Start Date', 'required');
            $this->form_validation->set_rules('deal_end', 'Deal End Date', 'required|callback_deal_enddate');


            if ($this->form_validation->run() == TRUE) {
               
                $featured= ($this->input->post('featured')=="on") ? 1 : 0;
                
                $inn = array(
                    "shop_id" => $this->input->post('shop_id'),
                    "deal_title" => $this->input->post('deal_title'),
                    "deal_description" => $this->input->post('deal_description'),
                    "original_price" => $this->input->post('original_price'),
                    "offer_price" => $this->input->post('offer_price'),
                    "deal_start" => strtotime($this->input->post('deal_start')),
                    "deal_end" => strtotime($this->input->post('deal_end')),
                    "featured_deal" => $featured,
                
                );

                $this->m_common->update_entry("tbl_deal", $inn, array("id"=>$id));
              
                $message = "Deal " . $this->input->post('deal_title') . " successfully updated";
               //echo "<pre>";print_r($_FILES);
                if (isset($_FILES['file'])) {
                    $r = $this->file_upload($_FILES['file'], $id);
                    if (!empty($r)) {
                        $up = array(
                            "deal_image" => $r,
                        );
                        $this->m_common->update_entry("tbl_deal", $up, array("id" => $id));
                    }
                }
                
            }
        }else{
        
            if(isset($_GET['id'])&& $_GET['id'] > 0){
                $id=$_GET['id'];
            }else{
                redirect('/site/index', 'refresh');
            }
        }
        
        $deal = $this->m_common->db_select("*", "tbl_deal", array("id"=>$id), array(), '', '', '', 'row_array');
        $shops = $this->m_common->db_select("shop_id,shop_name", "tbl_shop", array(), array(), 'shop_id desc', '', '', 'all');
//echo "<pre>";print_r($deal);exit;
        $data = array(
            'message' => $message,
            'shops' => $shops,
            'deal' => $deal,

        );
        $this->load->view('header');
        $this->load->view('sidebar', $data);
        $this->load->view('edit_deal', $data);
        $this->load->view('footer');
    }
    public function edit_shop(){
        
        $message = "";
        if (isset($_POST['edit_shop'])){
            
            //echo "<pre>";print_r($_POST);exit;
            if(!isset($_POST['id'])){
                
                redirect('/site/index', 'refresh');
            }else{
                $id=$_POST['id'];
            }
            $this->shop_id=$id;
            $this->load->library('form_validation');
            $this->form_validation->set_error_delimiters('<span for="required" generated="true" class="help-inline" style="color:red;"> *', '</span>');
            $this->form_validation->set_rules('sname', 'Business Name', 'required');
            $this->form_validation->set_rules('scat[]', 'Business Category', 'required');
            $this->form_validation->set_rules('sdesp', 'Business Description', 'required');
            $this->form_validation->set_rules('scountry', 'Country', 'required');
            $this->form_validation->set_rules('sstate', 'State', 'required');
            $this->form_validation->set_rules('scity', 'City', 'required');
            $this->form_validation->set_rules('sadd', 'Business Address', 'required');
            $this->form_validation->set_rules('semail', 'Business Email', 'required|valid_email');
            $this->form_validation->set_rules('suname', 'Business Username', 'required|callback_shop_username_unique|min_length[6]|max_length[32]');
            $this->form_validation->set_rules('spass', 'Business Password', 'required|min_length[6]|max_length[32]|callback_business_password');

            if ($this->form_validation->run() == TRUE) {
                
                $arr=(array)$this->input->post('scat');
                $cats= implode(",",$arr);
                
                $inn = array(
                    "shop_name" => $this->input->post('sname'),
                    "shop_cats" => $cats,
                    "shop_description" => $this->input->post('sdesp'),
                    "country_id" => $this->input->post('scountry'),
                    "state_id" => $this->input->post('sstate'),
                    "city_id" => $this->input->post('scity'),
                    "address" => $this->input->post('sadd'),
                    "email" => $this->input->post('semail'),
                    "url" => $this->input->post('burl'),
                    "username" => $this->input->post('suname'),
                    "password" => $this->input->post('spass'),
                    "latitude" => $this->input->post('latitude'),
                    "longitude" => $this->input->post('longitude'),
                );
                $wh=array(
                    "shop_id"=>$id,
                );
                $this->m_common->update_entry("tbl_shop", $inn, $wh);
                
                    $message = "Shop " . $this->input->post('sname') . " successfully Updated";
                    if (isset($_FILES['sfile'])) {
                        $r = $this->file_upload($_FILES['sfile'], $id);
                        if (!empty($r)) {
                            $up = array(
                                "shop_image" => $r,
                            );
                            $this->m_common->update_entry("tbl_shop", $up, array("shop_id" => $id));
                        }
                    }
                
            }
        }else{
      
        if($this->session->userdata('is_admin')==0) {
            $id=$this->session->userdata('shop_id');
        }else{
            if(isset($_GET['id'])&& $_GET['id'] > 0){
                $id=$_GET['id'];
            }else{
                redirect('/site/index', 'refresh');
            }
        }   
           
        }
         
        $shop= $this->m_common->db_select("*", "tbl_shop", array("shop_id"=>$id), array(), '', '', '', 'row_array');
       
        $cats = $this->m_common->db_select("*", "tbl_category", array(), array(), 'cid', '', '', 'all');
        
        $country = $this->m_common->db_select("*", "tbl_country", array(), array(), 'id', '', '', 'all');
        
        $state = $this->m_common->db_select("*", "tbl_state", array("cid"=>$shop['country_id']), array(), '', '', '', 'all');
         
        $city = $this->m_common->db_select("*", "tbl_city", array("state_id"=>$shop['state_id']), array(), '', '', '', 'all');
      
        
        $data = array(
            'message' => $message,
            'shop' => $shop,
            'cats' => $cats,
            'country' => $country,
            'state' => $state,
            'city' => $city,

        );
        //echo "<pre>";print_r($data);exit;
        $this->load->view('header');
        $this->load->view('sidebar', $data);
        $this->load->view('edit_shop', $data);
        $this->load->view('footer');
    }
    
    public function manage_deal(){
        $message = "";
        $page_no=1;
        $wh="";
        
        $page_row_limit = isset($_GET['perpage']) ? $_GET['perpage'] : 25;
        if(isset($_GET['search']) && !empty($_GET['search'])){
            $search=$_GET['search'];
            $wh.=" and ( t1.deal_title like '$search%' or address like '$search%' )";
        }
        
        if ($this->session->userdata('is_admin')==0) {
            $shop_id=$this->session->userdata('shop_id');
            $wh.=" and t1.shop_id = $shop_id ";
        }
        
        
        $q = "select count(t1.id) as cnt from tbl_deal t1 join tbl_shop t2 on t1.shop_id = t2.shop_id where 1=1 $wh";
        $res_tb = $this->m_common->select_custom($q);
        // echo "<pre>";print_r($res_tb);exit;
        $tot_rows = $res_tb[0]['cnt'];
        
        
        
        $tot_page = ceil($tot_rows / $page_row_limit);
        if(isset($_GET['page_no']) && $_GET['page_no'] > 0){
            $page_no=$_GET['page_no'];
        }
        $offset = ($page_no * $page_row_limit) - $page_row_limit;

        
        
        $q="select t1.*,t2.shop_name,t2.address from tbl_deal t1 join tbl_shop t2 on t1.shop_id = t2.shop_id where 1=1 $wh group by t1.id  order by id desc limit $offset,$page_row_limit";
        $info = $this->m_common->select_custom($q);
        
        //echo "<pre>";print_r($info);exit;
        
        $prev = ($page_no - 6);
        if ($prev <= 0) {
            $prev = 1;
        }
        $next = ($page_no + 6);
        if ($next >= $tot_page) {
            $next = $tot_page;
        }
        
        $data = array(
            'info' => $info,
            'tot_page' => $tot_page,
            'curr_page' => $page_no,
            "prev" => $prev,
            "next" => $next,

        );
        $this->load->view('header');
        $this->load->view('sidebar', $data);
        $this->load->view('manage_deal', $data);
        $this->load->view('footer');
    }
    public function list_shop(){
        $message = "";
        $page_no=1;
        $wh="";
        
        $page_row_limit = isset($_GET['perpage']) ? $_GET['perpage'] : 25;
        if(isset($_GET['search']) && !empty($_GET['search'])){
            $search=$_GET['search'];
            $wh.=" and ( t1.shop_name like '$search%' or t1.address like '$search%' )";
        }
        
        $q = "select count(t1.shop_id) as cnt from tbl_shop t1 where 1=1 $wh";
        $res_tb = $this->m_common->select_custom($q);
        // echo "<pre>";print_r($res_tb);exit;
        $tot_rows = $res_tb[0]['cnt'];
        
        
        
        $tot_page = ceil($tot_rows / $page_row_limit);
        if(isset($_GET['page_no']) && $_GET['page_no'] > 0){
            $page_no=$_GET['page_no'];
        }
        $offset = ($page_no * $page_row_limit) - $page_row_limit;

        
        
        $q="select t1.* from tbl_shop t1 where 1=1 $wh group by t1.shop_id  order by t1.shop_id desc limit $offset,$page_row_limit";
        $info = $this->m_common->select_custom($q);
        
        //echo "<pre>";print_r($info);exit;
        
        $prev = ($page_no - 6);
        if ($prev <= 0) {
            $prev = 1;
        }
        $next = ($page_no + 6);
        if ($next >= $tot_page) {
            $next = $tot_page;
        }
        
        $data = array(
            'info' => $info,
            'tot_page' => $tot_page,
            'curr_page' => $page_no,
            "prev" => $prev,
            "next" => $next,

        );
        $this->load->view('header');
        $this->load->view('sidebar', $data);
        $this->load->view('list_shop', $data);
        $this->load->view('footer');
    }
    
    public function logout(){
       $array_items = array('logged_in' => '');
       $this->session->unset_userdata($array_items);
       $this->session->userdata = array();
       $this->session->sess_destroy();
       redirect('/site/index', 'refresh');
   
    }
    public function business_password($str){
        if($_POST['suname']==$str){
          $this->form_validation->set_message('business_password', 'The password must be diffrent from the username'); 
          return FALSE;
        } else {
            return TRUE;
        }
   
    }
    public function shop_username_unique($str){
        $shop_id=$this->shop_id;
        $shops = $this->m_common->db_select("count(shop_id) as cnt", "tbl_shop", array("username"=>$str,'shop_id !='=>$shop_id), array(), '', '', '', 'row_array');
        if($shops['cnt']>0){
          $this->form_validation->set_message('shop_username_unique', 'The username already exists'); 
          return FALSE;
        } else {
            return TRUE;
        }
   
    }
    public function shop_name_unique($str){
        $shop_id=$this->shop_id;
        $shops = $this->m_common->db_select("count(shop_id) as cnt", "tbl_shop", array("shop_name"=>$str,'shop_id !='=>$shop_id), array(), '', '', '', 'row_array');
        if($shops['cnt']>0){
          $this->form_validation->set_message('shop_username_unique', 'The shop name already exists'); 
          return FALSE;
        } else {
            return TRUE;
        }
   
    }
    public function confirm_password($str){
        
        if($str != $_POST['spass']){
         $this->form_validation->set_message('confirm_password', 'The confirm password not match');
          return FALSE;
        } else {
            return TRUE;
        }
   
    }
    public function deal_enddate($str){
        $s=strtotime($_POST['deal_start']);
        $e=strtotime($str);
        if($e<=$s){
          $this->form_validation->set_message('deal_enddate', 'The deal end date must be greater than the deal start date.'); 
          return FALSE;
        } else {
            return TRUE;
        }
   
    }
    public function delete_deal(){
       
        if (isset($_POST['id'])) {

            $id = $_POST['id'];
            $id=  trim(str_replace("del_", "", $id));
            $this->m_common->delete_entry("tbl_deal", array('id' => $id));

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
                $ftoken=$this->get_random_string(5);
                $file_name = $cid . '_' . time() . '_' . $ftoken. '.' . $extention;

                $path = $this->project_path . 'uploads/';
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
            redirect('autentication/index', 'refresh');
        }
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
    private function is_access_permission(){
        if ($this->session->userdata('is_admin')==0) {
            redirect('site/warning?msg=you need to login as a admin', 'refresh');
        }
    }


}

/* End of file welcome.php */
/* Location: ./application/cconcatontrollers/welcome.php */