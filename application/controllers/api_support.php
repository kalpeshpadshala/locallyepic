<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Api_support extends CI_Controller {

    function Api_support() {
        parent::__construct();
        $this->load->model('m_wbapi');
        $this->result = array();
        $this->msg = '';
    }
    function Index(){
        //echo "<pre>";print_r($_GET);exit;	
        if (isset($_GET['secret_token']) && isset($_GET['ua']) && isset($_GET['auth_by'])) {
            
            $auth_by=$_GET['auth_by'];
            $ua=$_GET['ua'];
            $secret_token=$_GET['secret_token'];
    
            if($auth_by=="customer"){
                $user_tbl = "tbl_customer";
            }else if($auth_by=="business"){
                $user_tbl = 'tbl_businessuser';
            }else{
                echo "Request not satisfy";die();
            }
            $where = array(
                'secret_token' => $secret_token,
            );
            $info = $this->m_wbapi->db_select('secret_token,forgot_password,name',$user_tbl, $where, array(), '', '', '1', 'row');
  
            if (!empty($info)) {
    
                if($this->CheckTimeOver($info->forgot_password)==0)
                {
                    $un=$info->name;
                    $this->GetRedirecrt($secret_token,$auth_by,$ua,$un);
                }
                else
                {
                    $this->DisplayMsg("Url Become Invalid Because This Url Works For 1 Week So Please Try Again!!!");
                }
            }
            else
            {
                $this->DisplayMsg("Token Mismatch Please Try Again!!!");
            }
            
        } else {
            $this->DisplayMsg("Required Parameter Missing");
        }
    }
    
    private function DisplayMsg($Msg)
    {
        echo $Msg;exit;
    }
    
    private function CheckTimeOver($t)
    {
        return 0;
        $flag=0;
        $ct=  strtotime(date('Y-m-d H:i:s'));
        $pt=  strtotime($t);
        if(($ct - $pt) >= 604800)
        {
            $flag=1;
        }
        return $flag;
    }
    
    private function GetRedirecrt($secret_token,$auth_by,$ua,$un="")
    {
        $url = "walletbuddy://validatetoken/?secret_token=" . $secret_token . "&auth_by=" . $auth_by."&un=".$un."&code=7";
        header('Location: ' . $url);
        exit;
    }
}

