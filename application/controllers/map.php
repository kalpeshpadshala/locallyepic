<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class map extends CI_Controller {

    private $data;
    private $msg;

    function map(){

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

     public function is_require($a, $i) {
        if (!isset($a[$i]) || $a[$i] == '') {
            $msg = $i . " parameter missing or it should not null";
             $this->_sendResponse(11,$msg);
            exit;
        } else {
            return $a[$i];
        }
    }

    

    public function index(){


        if((is_permission($this->data['user_info']['role'], "create_deal")) == FALSE){
            echo "You Don't have permission to access this page";
            exit;
        }

        $deal=array("timezone"=>"UM5");

        if($this->session->userdata('role') != 1){

            echo "You Don't have permission to access this page";
            exit;

        }



        $this->load->view('header',$this->data);
        $this->load->view('sidebar', $this->data);
        $this->load->view('map/index', $this->data);
         $this->load->view('footer',$this->data);


    }

    public function aj_mapdeals_idle(){

      $lat = $this->is_require($_POST, 'lat');
      $lng = $this->is_require($_POST, 'lng');
      $lat1 = $this->is_require($_POST, 'lat1');
      $lng1 = $this->is_require($_POST, 'lng1');
      $time_in_sec = $this->is_require($_POST,'time_in_sec');

           $user_id = $this->session->userdata('user_id');

            $qDeals = "select
                        distinct 
                        customer_location.user_id,
                        customer_location.user_id as id,
                        customer_location.latitude,
                        customer_location.longitude,
                        name


                        from customer_location join tbl_customer on customer_location.user_id = tbl_customer.user_id
                        where   
                            customer_location.latitude < ?
                            AND customer_location.latitude > ?
                            AND customer_location.longitude < ?
                            AND customer_location.longitude > ?
                        group by customer_location.user_id
                            limit 100

                ";
            $result = $this->db->query($qDeals,array($lat,$lat1,$lng,$lng1));

            $this->apisql( __FUNCTION__, $user_id, $this->db->last_query(),$result->result_array());

            $rows = $result->result_array();

         
            $data = array("users"=>$rows);
            $status_code = 1;
             $this->_sendResponse($status_code,'',$data);
    }

    public function aj_latlng(){

        exit;

    }

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




private function _sendResponse($status_code = 200, $a, $data) {
    if ($this->msg == '') {
        $this->msg = $this->_getStatusCodeMessage($status_code);
    }
    $this->result['msg'] = $this->msg;
    $this->result['status_code'] = $status_code;
    $this->result['data'] = $data;
    echo json_encode($this->result);
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