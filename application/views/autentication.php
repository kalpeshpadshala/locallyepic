<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Autentication extends CI_Controller {

    function Autentication() {

        parent::__construct();
        //echo "<pre>";print_r($this->session->userdata('logged_in'));exit;
        $this->load->model('m_common');
        if ($this->session->userdata('logged_in')) {
            redirect('/site/index', 'refresh');
        }
    }

    public function index() {
        
        $message = "";
        $data = array('message' => $message);
           //echo "<pre>";print_r($data);exit;
        if (isset($_POST['login'])) {
            $uname = $_POST['username'];
            $pass = $_POST['password'];

            if($uname=="admin" && $pass=="admin"){
                $this->session->set_userdata('logged_in', 1);
                $this->session->set_userdata('is_admin',1);
                redirect('/site/index', 'refresh');
            }else{
                $wh=array(
                    "username"=>$uname,
                    "password"=>$pass,
                );
                //echo "<pre>";print_r($wh);exit;
                $user = $this->m_common->db_select("shop_id,shop_name", "tbl_shop", $wh, '', '', '', '', 'row_array');
                
                if (!empty($user)) {
                    $this->session->set_userdata('logged_in', 1);
                    $this->session->set_userdata('logged_in_by_shop', 1);
                    $this->session->set_userdata('shop_id',$user['shop_id'] );
                    $this->session->set_userdata('shop_name',$user['shop_name']);
                    $this->session->set_userdata('shop_image',$user['shop_image']);
                    $this->session->set_userdata('is_admin',0);
                    redirect('/site/index', 'refresh');
                } else {
                    $message = "User name or password invalid";
                    $data = array('message' => $message);
                }

            }
            
        }
        $this->load->view('login', $data);
    }

}
