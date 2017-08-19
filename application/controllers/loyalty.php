<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Loyalty extends CI_Controller {

   private $data;

    function Loyalty() {

        parent::__construct();
        //echo "<pre>";print_r($this->session->userdata('logged_in'));exit;
        
        $this->load->model('promocode');
        $this->load->model('email');
        $this->load->model('m_common');
        //date_default_timezone_set('America/Los_Angeles');
        $this->set_custom_tz();
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
            "message" =>""
        );
        $unread = $this->check_unread();
        $this->data['unread'] = $unread;
        /*if ($this->session->userdata('logged_in')) {
            redirect('/site/index', 'refresh');
        }*/
    }

    function index(){

        $message = "";
        
        /* if((is_permission($this->data['user_info']['role'], "loyalty")) == FALSE){
            echo "You Don't have permission to access this page";
            //exit;
        }*/

        //check to see if they have entry in loyaltyprograms, if not add it.

        $sqlshop = "select shop_id, shop_name from tbl_shop where user_id=?";
        $resultshop = $this->db->query($sqlshop, array($this->session->userdata('user_id')));
        $shop = $resultshop->row(); 

        $this->initialCheck($shop);

        
        

        $sql = "select * from loyaltyprograms where shop_id = ?";
        $result = $this->db->query($sql, array( $shop->shop_id));

        $this->data["loyaltyprogram"] = $result->row();


        $sql = "select * from loyaltyprogramitems where loyalty_program_id = ?";
        $result1 = $this->db->query($sql, array( $result->row()->id));

        $this->data["loyaltyprogramitems"] = $result1->result_array(); 

        
        $sql = "select tbl_customer.name, count(*) as deals_activated from tbl_customer join tbl_deals_activated on tbl_customer.user_id=tbl_deals_activated.customer_id where business_id=? group by tbl_deals_activated.customer_id order by deals_activated desc";
        $result2 = $this->db->query($sql, array( $shop->shop_id ));

        $this->data["loyalcustomers"] = $result2->result_array(); 

        
        //check to see if they have 1 loyalty program line item, not add one

        //if this was as new entry send them to the edit page.



        $this->load->view('header', $this->data);
        $this->load->view('sidebar', $this->data);
        $this->load->view('loyalty/index', $this->data);


    }

    function initialCheck($shop){


        $sql = "select * from loyaltyprograms where shop_id = ?";
        $result = $this->db->query($sql, array( $shop->shop_id));

        $initial_check =  $result->num_rows();

        if ($initial_check==0){
           
            $sqli = "insert ignore into loyaltyprograms set shop_id = ?, title=?, description=?, blnStatus=0";
            $temp_title = $shop->shop_name." Customer Loyalty Program";
            $temp_description = "The $temp_title is our way to say thank you for being a loyal customer.  It is simple.  We keep track every time you activate a deal.  When you hit certain thresholds you get free stuff!  So what are you waiting for?  Activate your first deal today!";  
            $resulti = $this->db->query($sqli, array($shop->shop_id,$temp_title,$temp_description, 0));

            $loyalty_program_id = $this->db->insert_id();

            

            $sqli = "insert ignore into loyaltyprogramitems set loyalty_program_id=?, shop_id = ?, title=?, description=?, intActivations=5, blnStatus=0";
            $temp_title = "Receive a free gift when you activate 5 deals!";
            $temp_description = "The details: 1 free gift per visit please.";  
            $resulti = $this->db->query($sqli, array($loyalty_program_id,$shop->shop_id,$temp_title,$temp_description));

         
            //redirect them to the page to fill out the info
            redirect('/loyalty/updateProgram', 'redirect');
        }



    }

    function updateStatus(){

        $blnStatus = $_GET['status'];

        $sqlshop = "select shop_id, shop_name from tbl_shop where user_id=?";
        $resultshop = $this->db->query($sqlshop, array($this->session->userdata('user_id')));
        $shop = $resultshop->row(); 




        $data = array(
               'blnStatus' => $blnStatus,
        );

        $this->db->where('shop_id', $shop->shop_id);
        $this->db->update('loyaltyprograms', $data); 



        redirect('loyalty/index', 'refresh');





    }

    function updateProgram(){

            $sqlshop = "select shop_id, shop_name from tbl_shop where user_id=?";
            $resultshop = $this->db->query($sqlshop, array($this->session->userdata('user_id')));
            $shop = $resultshop->row(); 


        if ($_SERVER['REQUEST_METHOD']=='GET') { 

            $this->initialCheck($shop);
        }

        if ($_SERVER['REQUEST_METHOD']=='POST') { 

            //echo "<pre>";
            //print_r($_POST);


           

            $sql = "select * from loyaltyprograms where shop_id = ?";
            $result = $this->db->query($sql, array( $shop->shop_id));
            



            $this->db->trans_start();

             $this->db->query('update loyaltyprograms set title=?, description=?, blnStatus=?, blnShowHelper=0 where shop_id=? and id=?',array($_POST["lptitle"],$_POST["lpdescription"],$_POST["blnStatus"],$shop->shop_id, $result->row()->id));

            $this->db->query('delete from loyaltyprogramitems where shop_id=?',array($shop->shop_id));

            

            foreach($_POST['title'] as $index => $value) {
                if (trim($_POST["title"][$index])!='') {

                    //echo "<br>".$index." ".$_POST["title"][$index];
                    //echo "<br>".$index." ".$_POST["description"][$index];
                    //echo "<br>".$index." ".$_POST["intActivations"][$index];
                    //echo "<hr>";

                    $this->db->query('insert into loyaltyprogramitems set loyalty_program_id = ?, shop_id=?, title =?, description=?, intActivations=?, blnStatus=1',
                            array($result->row()->id,$shop->shop_id, $_POST["title"][$index], $_POST["description"][$index], $_POST["intActivations"][$index]));
                }
                

            }

            $this->db->trans_complete();
            $this->session->set_userdata('current_message', "Loyalty Program Updated Successfully");
            redirect('loyalty/index', 'refresh');

        }


        $sqlshop = "select shop_id, shop_name from tbl_shop where user_id=?";
        $resultshop = $this->db->query($sqlshop, array($this->session->userdata('user_id')));
        $shop = $resultshop->row(); 

        

        $sql = "select * from loyaltyprograms where shop_id = ?";
        $result = $this->db->query($sql, array( $shop->shop_id));

        $this->data["loyaltyprogram"] = $result->row();


        $sql = "select * from loyaltyprogramitems where loyalty_program_id = ?";
        $result1 = $this->db->query($sql, array( $result->row()->id));

        $this->data["loyaltyprogramitems"] = $result1->result_array(); 


        $this->load->view('header', $this->data);
        $this->load->view('sidebar', $this->data);
        $this->load->view('loyalty/updateProgram', $this->data);

        
    }

    function addProgramItem(){


    }

    function updateProgramItem(){


    }

    function deleteProgramItem(){


    }

    public function set_custom_tz() {
        $ip = $_SERVER['REMOTE_ADDR']; // the IP address to query
        $query = @unserialize(file_get_contents('http://ip-api.com/php/' . $ip));
        if ($query && $query['status'] == 'success') {
            @date_default_timezone_set($query['timezone']);
            //echo 'Hello visitor from ' . $query['timezone'] . ', ' . $query['city'] . '!';
        }
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
?>