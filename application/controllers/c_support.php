<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class c_support extends CI_Controller {

    function c_support() {
        parent::__construct();
        $this->load->model('m_common');
        $this->load->model('appuser');
        $this->schema="dealsongogo";
    }

    public function test(){
        echo "<pre>";print_r($_GET);exit;
        $this->GetRedirecrt(1,900,'45rg54654t54',"fhg@'rg gdhj");
    }
    public function index() {
        $ua = isset($_GET['ua']) ? $_GET['ua'] : 'web';
        
        if (isset($_GET['verification_code'])){
            
            $verification_code = $_GET['verification_code'];
            $wh = array(
                'verification_code' => $verification_code,
                'is_verify' => 0,
            );
            $temp = $this->m_common->db_select("*", 'tbl_customer', $wh, $group = array(), $order = '', $having = '', $limit = '', 'row');
            if (!empty($temp)) {
                if ($this->is_week_validation($temp->date) == 1) {
                    //$secret_token = $this->get_unique_code('tbl_customer', 'user_token', 10);
                    $wh=array(
                        'user_id'=>$temp->user_id,
                    );
                    $up = array(
                        'verification_code' => '',
                        'is_verify' => 1,
                        //'user_token' => $secret_token,
                    );
                    $this->m_common->update_entry('tbl_customer',$up,$wh);
                   
                        $message = "You have successfully verified";
                        //$user_info = $this->get_user_info(" where user_id = $temp->user_id ");
                        //$this->GetRedirecrt($ua,1,$message); 
                        redirect('/c_support/verify_done', 'refresh');
                    
                } else {
                    $message = "Url validation time over";
                    $this->model->delete_entry('tbl_customer',$wh);
                    $this->GetRedirecrt($ua,22,$message);
                }
            } else {
                
                $message = "Ethir Your account is verified or the url is an invalid";
                $this->GetRedirecrt($ua,21,$message);
                
            }
        } else {
            
            $message = "Bad Request";
            $this->GetRedirecrt($ua,10,$message);
            
        }
    }
    public function forgot_password() {
        $ua = $this->get_useragent();
        $un='';
        if (isset($_GET['secret_token'])) {
            
            $secret_token = $_GET['secret_token'];
            $wh = array(
                'secret_token' => $secret_token,
            );
            $temp = $this->m_common->db_select("*", 'tbl_customer', $wh, $group = array(), $order = '', $having = '', $limit = '', 'row');
            if (!empty($temp)) {
                $un=$temp->name;
                 
                    $message = "Reset Password";
                    $this->GetRedirecrt($ua,1,$message,$un,$secret_token);// redirect to reset password link

            } else {
                $message = "The Url is already visited";
                $this->GetRedirecrt($ua,21);
                
            }
        } else {
            $message = "Bad Request";
            $this->GetRedirecrt($ua,0);
        }
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
    private function GetRedirecrt($ua,$code,$mes="",$un="",$sec_token=0)
    {   
        //if($ua=="iPhone"){
            //$url = $this->schema."://validatetoken/?code=".$code."&mes=".$mes."&un=".$un."&secret_token=".$sec_token;
        //}else if($ua=="Android"){
            //$url = $this->schema."://validatetoken/?code=".$code."&mes=".$mes."&un=".$un."&secret_token=".$sec_token;
        //}else{
            //$url="http://www.recafriends.com?code=".$code."&secret_token=".$token."&un=".$un;
            $url=  base_url()."c_support/reset_password/?secret_token=".$sec_token."&ua=$ua";
        //}        
        header('Location: ' . $url);
        exit;
    }
     public function reset_password() {

        if(isset($_GET['secret_token'])){
            $secret_token=$_GET['secret_token'];
            $ua=$_GET['ua'];
            $user_tbl = "tbl_customer";
       
            $where = array(
                'secret_token' => $secret_token,
            );
            $info = $this->m_common->db_select('secret_token,user_id,email', $user_tbl, $where, array(), '', '', '', 'row');
            if(!empty($info)){
                 $where['ua']=$ua;
                 $this->load->view('reset_password_view',$where);
            }else{
                 echo "Your reset url has been used already.  <a href='http://www.locallyepic.com/'>Locally Epic home page.</a>";exit;
            }
        }else{
            echo "there is an invalid url. You need to open the url from email";exit;
        }
     
    }
    public function business_password($str) {
        if ($_POST['new_password'] == $str) {
            $this->form_validation->set_message('new_password', 'The password must be diffrent from the username');
            return FALSE;
        } else {
            return TRUE;
        }
    }
     public function confirm_password($str) {
        if ($str != $_POST['new_password']) {
            $this->form_validation->set_message('confirm_password', 'The confirm password not match');
            return FALSE;
        } else {
            return TRUE;
        }
    }
     public function thank_you() {
        $this->load->view('thank_you');
    }
     public function reset_password_submit(){
         
        
        if (isset($_POST['secret_token'])){
             
            $ua=$this->input->post('ua');
            $secret_token=$this->input->post('secret_token');
            $this->load->library('form_validation');
            $this->form_validation->set_error_delimiters('<span for="required" generated="true" class="help-inline" style="color:red;"> *', '</span>');
            $this->form_validation->set_rules('new_password', 'Password', 'required|min_length[4]|max_length[32]');
            $this->form_validation->set_rules('new_password1', 'Confirm Password', 'required|callback_confirm_password');

            $user_tbl = "tbl_customer";
       
            $where = array(
                'secret_token' => $secret_token,
            );
            $info = $this->m_common->db_select('secret_token,user_id,email, user_token', $user_tbl, $where, array(), '', '', '', 'row');


            // "password" => $this->input->post('new_password'),

            $up = array(
                
                    "password" => "",
                   
            );
            //
             
            if ($this->form_validation->run() == TRUE) {

                $this->m_common->update_entry("tbl_customer",$up, array("secret_token" => $secret_token));
                $this->m_common->update_entry("tbl_customer",array("secret_token" => ""), array("secret_token" => $secret_token));
                $this->appuser->update_password($info->user_id, $info->user_token, $this->input->post('new_password'));
                //print_rr($this->db->last_query(),0);
                //print_rr($info);
                
                if($ua=="web"){
                    $message = "Your password has been reset. Go to the app and login with your new password.";
                    $this->session->set_userdata('thank_message', $message);
                    redirect('c_support/thank_you', 'refresh');
                    //redirect('authentication/login', 'refresh');
                }else{
                    $message = "Your password has been reset. Go to the app and login with your new password.";
                    $this->session->set_userdata('thank_message', $message);
                    redirect('c_support/thank_you', 'refresh');
                }
                
                
            }
            $data=array(
                "secret_token"=>$secret_token,
            );
            $this->load->view('reset_password_view',$data);
        }else{
             redirect('/c_support/reset_password', 'refresh');
        }
     
    }

    public function verify_done(){
        $this->load->view('verify_done');
    }
    private function is_week_validation($s) {
        return 1;// beacuse currently we do not want date validation
        $flag = 0;
        $e = date('Y-m-d H:i:s'); 
        $sub = strtotime($e) - strtotime($s);
        if ($sub < 518400) {
            $flag = 1;
        }
        return $flag;
    }

}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
