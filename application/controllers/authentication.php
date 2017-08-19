<?php
//https://github.com/SammyK/codeigniter-authorize.net-arb-api
//http://www.authorize.net/content/dam/authorize/documents/ARB_guide.pdf
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Authentication extends CI_Controller {

    function Authentication() {

        parent::__construct();
        //echo "<pre>";print_r($this->session->userdata('logged_in'));exit;
        $this->load->model('m_common');
        $this->load->model('promocode');
        $this->load->model('email');

        $this->load->model('user');
        $this->load->model('business');
        
        
        /*if ($this->session->userdata('logged_in')) {
            redirect('/site/index', 'refresh');
        }*/

    }

    public function index() {
        exit;
        $message = "";
        $data = array('message' => $message);
           //echo "<pre>";print_r($data);exit;
        if (isset($_POST['login'])) {
            $uname = $_POST['username'];
            $pass = $_POST['password'];
            echo "<pre>";print_r($_POST);exit;
            if($uname=="admin" && $pass=="admin"){
                $this->session->set_userdata('logged_in', 1);
                $this->session->set_userdata('is_admin',1);
                $this->session->set_userdata('shop_id',0);
                $this->session->set_userdata('shop_name',"admin");
                $this->session->set_userdata('shop_image',"");
                redirect('/site/index', 'refresh');
            }else{
                $wh=array(
                    "username"=>$uname,
                    "password"=>$pass,
                );
                //echo "<pre>";print_r($wh);exit;
                $user = $this->m_common->db_select("shop_id,shop_name,shop_image,is_payment", "tbl_shop", $wh, '', '', '', '', 'row_array');
                if (!empty($user)) {
                    $this->session->set_userdata('logged_in_by_shop', 1);
                    $this->session->set_userdata('shop_id',$user['shop_id'] );
                    $this->session->set_userdata('shop_name',$user['shop_name']);
                    $this->session->set_userdata('shop_image',$user['shop_image']);
                    $this->session->set_userdata('is_admin',0);
                    //echo "<pre>";print_r($user);exit;
                    if(!$user['is_payment']){
                        redirect('/authentication/payment', 'refresh');
                    }else{
                        $this->session->set_userdata('logged_in', 1);
                        redirect('/site/index', 'refresh');
                    }
                    
                    
                } else {
                    $message = "User name or password invalid";
                    $data = array('message' => $message);
                }  

            }
            
        }
        
        $this->load->view('login', $data);
    }


    public function payment_annual(){

        $day = intval(date('j'));
        $dateraw = strtotime(date('m', strtotime('+1 month')).'/01/'.date('Y').' 00:00:00');
        $date = date("m/d/Y", $dateraw);

    }


    public function payment() {

        // $this->session->sess_destroy();
        // exit();

        $day = intval(date('j'));

            
        $dateraw = strtotime(date('m', strtotime('+1 month')).'/01/'.date('Y').' 00:00:00');
        $date = date("m/d/Y", $dateraw);
        $next_due_date = date('Y-m-d', strtotime("+30 days"));


        $membership_activation_fee=199.00;
        $amount = 199.00;

        $monthly_network_fee = 0;
        $promocode_activation = 0;
        $promocode_monthly = 0;
        $promocode_activation_id=0;
        $promocode_monthly_id = 0;
        $error_msg = '';
        

        if (isset($_POST["hiddenpromocode1"]) && trim($_POST["hiddenpromocode1"])!='') {

            $promocode_activation = trim($_POST["hiddenpromocode1"]);
        }

        if (isset($_POST["hiddenpromocode2"]) && trim($_POST["hiddenpromocode2"])!=''){
            
            $promocode_monthly = trim($_POST["hiddenpromocode2"]);
        }

        $temp_error = "";

        if ($_SERVER['REQUEST_METHOD']=='POST') 
        {
            // exit();

            $this->load->library(array('form_validation'));

            $this->form_validation->set_rules('payment_method', 'Method of Payment', 'required');

            $payment_method = $this->input->post('payment_method');

            $this->form_validation->set_rules('cardname', 'Card Name', 'trim');
            $this->form_validation->set_rules('cardNumber', 'Card Number', 'required');
            $this->form_validation->set_rules('expiry_month', 'Expiration Month', 'required');
            $this->form_validation->set_rules('expiry_year', 'Expiration Year', 'required');
            $this->form_validation->set_rules('cardCode', 'Security Code', 'required');
            $this->form_validation->set_rules('t_and_c', 'Payment Terms and Conditions', 'required');
            $this->form_validation->set_rules('packageradio', 'Package', 'required');
            $this->form_validation->set_rules('billingZip', 'billingZip', 'trim');
            $hasErrors = FALSE;

            //Form Validation
            
            $this->form_validation->set_rules('hiddenpromocode1', 'hiddenpromocode1', 'trim');
            $this->form_validation->set_rules('hiddenpromocode2', 'hiddenpromocode2', 'trim');

            

            $this->form_validation->set_error_delimiters('<div class="error">', '</div>');

            if ($this->form_validation->run() === FALSE) {
                 $hasErrors = TRUE;
            }

            //activation fee
            //echo "135 ($hasErrors )($promocode_activation)<br>";
            if ($promocode_activation!='0') {

                //echo "Entering Activation Area...<br>";

                //process activation promocode
                $data = $this->promocode->checkpromocode($promocode_activation, 'activation fee');
                $qry_promo_activation = $data;

                if (is_array($data["result"]) and count($data["result"]) > 0 ) {

                    //do nothing for now.
                    $amount = $data["total_due_today"];

                    $membership_activation_fee=$data["membership_activation_fee"];

                    $promocode_activation_id = $data["result"][0]->id;

                } else {

                    $hasErrors= TRUE;

                }

            }


            
            if (
                    $promocode_monthly == $promocode_activation
                    && isset($qry_promo_activation)
                    && isset($qry_promo_monthly)
                    && $qry_promo_activation["result"][0]->promocode_type=='sales override'
                    && $qry_promo_monthly["result"][0]->promocode_type =='sales override'
                    && isset($_POST["membershipactivationfee"])
                    && isset($_POST["monthlynetworkfee"])
                    ) {

                    //echo "<h3>Sales Override!!</h3>";
                    $amount = trim($_POST["membershipactivationfee"]);
                    $membership_activation_fee = trim($_POST["membershipactivationfee"]);
                    $monthly_network_fee = trim($_POST["monthlynetworkfee"]);
                }



                // echo "<pre>";
                // print_r($_POST);
                // echo $hasErrors."<br>";
                // echo is_array($data["result"])."<br>";
                // echo count($data["result"])."<br>";

                // echo $data["total_due_today"]."<br>";
                // echo "Amount:".$amount."<br>";
                // // echo $promocode_id."<br>";
                // echo "membership_activation_fee: $membership_activation_fee <br>";
                // echo "monthly_network_fee: $monthly_network_fee <br>";
                // echo "promocode_activation_id: $promocode_activation_id <br>";
                // echo "promocode_monthly_id: $promocode_monthly_id <br>";
                // echo "promocode_monthly: $promocode_monthly <br>";
                // echo "promocode_activation: $promocode_activation <br>";
                // echo "promocode_activation_type: ".$qry_promo_activation["result"][0]->promocode_type . "<br>";
                // // echo "promocode_monthly_type: ".$qry_promo_monthly["result"][0]->promocode_type . "<br>";
                // echo "<br><b>DEBUG STOP</b>";
                // exit;


            //THE INITIAL CREDIT CARD CHARGE
            if ($membership_activation_fee ==0 && $monthly_network_fee ==0){

                $d = array('expiration_date'=>$next_due_date,'is_payment'=>1, 'subscriptionId'=>'free','promocode_activation_id'=>$promocode_activation_id, 'promocode_monthly_id'=>$promocode_monthly_id, 'blnPaidActivationFee'=>1);
                $this->db->where('shop_id', $this->session->userdata('shop_id'));
                $this->db->update('tbl_shop', $d); 
                $this->session->set_userdata('logged_in', 1);
                redirect('/site/index', 'refresh');
            }


            
            //check already customer
            require(APPPATH.'libraries/stripe/init.php');
            \Stripe\Stripe::setApiKey('sk_test_DaNW3aIALBKXT0Jb946QxrAm');

            $business_info = $this->business->getBusiness($this->session->userdata('shop_id')); 
            $customer_id = $business_info->customer_id;
           
            if( $customer_id == '' )
            {
                try {
                    $result = \Stripe\Customer::create(array(
                      "description" => $business_info->shop_name." (". $business_info->email.")",
                       "metadata" => array(
                                        "email" =>$business_info->email,
                                        "shop_id" =>$business_info->shop_id,
                                        "shop_name" =>$business_info->shop_name,
                                    )
                    ));
                    
                    $customer_id = $result['id'];
                    $d = array('customer_id'=>$customer_id);
                    $this->db->where('shop_id', $this->session->userdata('shop_id'));
                    $this->db->update('tbl_shop', $d); 

                } catch(\Stripe\Error\Customer $e) {
                   $hasErrors= TRUE;
                   $error_msg = "Please Try Later";
                }
            }





            if ($hasErrors == FALSE && $membership_activation_fee > 0) {


                try {
                    $customer = \Stripe\Customer::retrieve($customer_id);
                    $customer->sources->create(
                        array(
                                "card" => array(
                                "name" =>$this->input->post('card_name'),
                                "number" => $this->input->post('cardNumber'),
                                "exp_month" => $this->input->post('expiry_month'),
                                "exp_year" => $this->input->post('expiry_year'),
                                "cvc" => $this->input->post('cardCode')
                                )));
                    

                    // exit();

                    try 
                    {
                        $charge = \Stripe\Charge::create(
                            array(
                                    'amount' => (number_format($membership_activation_fee, 2, '.', '')*100), 
                                    'currency' => 'usd',
                                    'customer' => $customer_id
                            ));
                        if($charge['status']=='succeeded')
                        {
                            $charge_id=$charge['id'];
                            $balance_transaction_id=$charge['balance_transaction'];
                          

                            $info=array(
                                'TransactionShopId'=>$this->session->userdata('shop_id'),
                                'TransactionChargeId'=>$charge_id,
                                'TransactionBalanceTransaction'=>$balance_transaction_id,
                                );
                            $this->db->insert('transaction_history', $info);
                            $plan_type = $this->input->post('packageradio');
                            
                            $d = array('plan_type'=>$plan_type,'is_payment'=>1,'subscriptionId'=>'free','activationfeeid'=>$charge_id,'monthlyfee'=>$monthly_network_fee, 'blnPaidActivationFee'=>1,'promocode_activation_id'=>$promocode_activation_id, 'promocode_monthly_id'=>$promocode_monthly_id,'expiration_date'=>$next_due_date,);
                            $this->db->where('shop_id', $this->session->userdata('shop_id'));
                            $this->db->update('tbl_shop', $d);

                            $this->session->set_userdata('logged_in', 1);
                            redirect('/site/index', 'refresh');

                        }   
                        else
                        {
                            $hasErrors= TRUE;
                            $error_msg = "Invalid Card Detail";
                        }

                    } catch(\Stripe\Error\Card $e){

                        $hasErrors= TRUE;
                        $error_msg = "Invalid Card Detail";
                    }


                } catch(\Stripe\Error\Card $e) {
                        $hasErrors= TRUE;
                        $error_msg = "Invalid Card Detail";
                }

            }

        }



        if ($this->input->get_post('new')) {
            $new = 1;
            $paymentBlurb = "You are almost done.  Just add a payment option before and you are all set.";
        } else {
            $new = 0;
            $paymentBlurb = "You need to have payment information on file before you can access your dashboard.";
        }



        $data = array(
                'day'=>$day,
                'date'=>$date,
                'error_msg' =>$error_msg,
                'new'=>$new,
                'paymentBlurb'=>$paymentBlurb,
                'error'=>$temp_error,
                'monthly_network_fee'=>$monthly_network_fee,
                'membership_activation_fee'=>$membership_activation_fee,
                'amount'=>$amount
            );

         $this->load->view('authentication/payment', $data);
    }









    public function reactivate() {


        $next_due_date = date('Y-m-d', strtotime("+30 days"));


        $membership_activation_fee=0;
        $monthly_network_fee = 0;
        $amount = 0;
        $promocode_activation = 0;
        $promocode_monthly = 0;
        $promocode_activation_id=0;
        $promocode_monthly_id = 0;
        $error_msg = '';

        

        if (isset($_POST["hiddenpromocode2"]) && trim($_POST["hiddenpromocode2"])!=''){
            $promocode_monthly = trim($_POST["hiddenpromocode2"]);
        }

        $temp_error = "";

        if ($_SERVER['REQUEST_METHOD']=='POST') {

            // echo "<pre>";
            // print_r($_POST);
            // exit;

            $this->load->library(array('form_validation'));

            $this->form_validation->set_rules('payment_method', 'Method of Payment', 'required');

            $payment_method = $this->input->post('payment_method');

            //echo "<textarea>".$payment_method."</textarea>";


            
            $this->form_validation->set_rules('cardname', 'Card Name', 'trim');
            $this->form_validation->set_rules('cardNumber', 'Card Number', 'required');
            $this->form_validation->set_rules('expiry_month', 'Expiration Month', 'required');
            $this->form_validation->set_rules('expiry_year', 'Expiration Year', 'required');
            $this->form_validation->set_rules('cardCode', 'Security Code', 'required');
            $this->form_validation->set_rules('billingZip', 'billingZip', 'trim');
            $this->form_validation->set_rules('packageradio', 'Package', 'required');

            $hasErrors = FALSE;
            //Form Validation
            
            $this->form_validation->set_rules('hiddenpromocode2', 'hiddenpromocode2', 'trim');

            

            $this->form_validation->set_error_delimiters('<div class="error">', '</div>');

            if ($this->form_validation->run() === FALSE) {
                 $hasErrors = TRUE;
            }


            $plan_type = $this->input->post('packageradio');
             if( $plan_type == '1'){

                $monthly_network_fee = 149;
            }else if( $plan_type == '3'){

                $monthly_network_fee = 249;
            }else{

                $monthly_network_fee = 199;
            }


            //monthly fee
            //echo "161 ($hasErrors )($promocode_monthly)<br>";
            if ( $promocode_monthly!='0') {


                $data = $this->promocode->checkpromocode($promocode_monthly, 'monthly fee',$plan_type);
                $qry_promo_monthly=$data;

                if (is_array($data["result"]) and count($data["result"]) > 0 ) {

                    //do nothing for now.
                    $monthly_network_fee = $data["monthly_network_fee"];

                    $promocode_monthly_id = $data["result"][0]->id;

                } else {

                    $hasErrors= TRUE;

                }
            }




            if ($membership_activation_fee ==0 && $monthly_network_fee ==0){

                $d = array('is_payment'=>1, 'subscriptionId'=>'free','promocode_activation_id'=>$promocode_activation_id, 'promocode_monthly_id'=>$promocode_monthly_id, 'blnPaidActivationFee'=>1);
                $this->db->where('shop_id', $this->session->userdata('shop_id'));
                $this->db->update('tbl_shop', $d); 
                $this->session->set_userdata('logged_in', 1);
                redirect('/site/index', 'refresh');
            }



            //check already customer
            require(APPPATH.'libraries/stripe/init.php');
            \Stripe\Stripe::setApiKey('sk_test_DaNW3aIALBKXT0Jb946QxrAm');

            $business_info = $this->business->getBusiness($this->session->userdata('shop_id')); 
            $customer_id = $business_info->customer_id;
           
            if( $customer_id == '' )
            {
                try {
                    $result = \Stripe\Customer::create(array(
                      "description" => $business_info->shop_name." (". $business_info->email.")",
                       "metadata" => array(
                                        "email" =>$business_info->email,
                                        "shop_id" =>$business_info->shop_id,
                                        "shop_name" =>$business_info->shop_name,
                                    )
                    ));
                    
                    $customer_id = $result['id'];
                    $d = array('customer_id'=>$customer_id);
                    $this->db->where('shop_id', $this->session->userdata('shop_id'));
                    $this->db->update('tbl_shop', $d); 

                } catch(\Stripe\Error\Customer $e) {
                   $hasErrors= TRUE;
                   $error_msg = "Please Try Later";
                }
            }




            //echo "Has Errors: $hasErrors";

            if ($hasErrors == FALSE) {


                try {
                    $customer = \Stripe\Customer::retrieve($customer_id);
                    $customer->sources->create(
                        array(
                                "card" => array(
                                "name" =>$this->input->post('card_name'),
                                "number" => $this->input->post('cardNumber'),
                                "exp_month" => $this->input->post('expiry_month'),
                                "exp_year" => $this->input->post('expiry_year'),
                                "cvc" => $this->input->post('cardCode')
                                )));
                    

                    // exit();

                    try 
                    {
                        $charge = \Stripe\Charge::create(
                            array(
                                    'amount' => (number_format($monthly_network_fee, 2, '.', '')*100), 
                                    'currency' => 'usd',
                                    'customer' => $customer_id
                            ));
                        if($charge['status']=='succeeded')
                        {
                            $charge_id=$charge['id'];
                            $balance_transaction_id=$charge['balance_transaction'];
                          

                            $info=array(
                                'TransactionShopId'=>$this->session->userdata('shop_id'),
                                'TransactionChargeId'=>$charge_id,
                                'TransactionBalanceTransaction'=>$balance_transaction_id,
                                );
                            $this->db->insert('transaction_history', $info);

                            
                            $d = array(
                                'is_payment'=>1, 
                                'subscriptionId'=>'monthly',
                                'promocode_activation_id'=>$promocode_activation_id, 
                                'promocode_monthly_id'=>$promocode_monthly_id, 
                                'blnPaidActivationFee'=>1,
                                'blnexpired'=>0,
                                'expiration_date'=>$next_due_date,
                                'monthlyfee'=> number_format($monthly_network_fee, 2, '.', ''),
                                'plan_type'=>$plan_type
                                );

                            $this->db->where('shop_id', $this->session->userdata('shop_id'));
                            $this->db->update('tbl_shop', $d); 
                            $this->session->set_userdata('logged_in', 1);
                            redirect('/site/index', 'refresh');

                        }   
                        else
                        {
                            $hasErrors= TRUE;
                            $error_msg = "Invalid Card Detail";
                        }

                    } catch(\Stripe\Error\Card $e){

                        $hasErrors= TRUE;
                        $error_msg = "Invalid Card Detail";
                    }


                } catch(\Stripe\Error\Card $e) {
                        $hasErrors= TRUE;
                        $error_msg = "Invalid Card Detail";
                }
                    
            }

        }




        if ($this->input->get_post('new')) {
            $new = 1;
            $paymentBlurb = "You are almost done.  Just add a payment option before and you are all set.";
        } else {
            $new = 0;
            $paymentBlurb = "You need to have payment information on file before you can access your dashboard.";
        }



        $data = array(
                'day'=>$day,
                'date'=>$date,
                'error_msg' =>$error_msg,
                'new'=>$new,
                'paymentBlurb'=>$paymentBlurb,
                'error'=>$temp_error,
                'monthly_network_fee'=>$monthly_network_fee,
                'membership_activation_fee'=>$membership_activation_fee,
                'amount'=>$amount
            );

         $this->load->view('authentication/payment', $data);
    }









    public function promocode(){

        $plan_type = isset($_POST['plan_type']) ? $_POST['plan_type'] : 0;
       
        sleep(2);
        $data = $this->promocode->checkpromocode($_POST["promocode"],'',$plan_type);

        echo json_encode($data);



    }
    

     // change 22/12/2014
    public function thank(){
        if(!isset($_REQUEST['username'])){
            redirect('/authentication/index', 'refresh');
        }
        $name = $_REQUEST['username'];
        $message = "";
        $data = array('message' => $message, 'name'=>$name);
        $this->load->view('gogo/thank_u', $data);
    }

    public function forgot(){
       
       $message = "";
        
           //echo "<pre>";print_r($data);exit;
        if (!empty($_POST)){
            $uname = $_POST['username'];

                $wh=array(
                    "email"=>$uname
                );
                //echo "<pre>";print_r($wh);exit;
                $user = $this->m_common->db_select("*", "tbl_users", $wh, '', '', '', '', 'row_array');

                $this->session->set_userdata('thank_message','If your email address is in our system you will receive an email with a link to reset your password. Be sure to check your spam folder just in case.');
                if (!empty($user)){
                    $code = random_string('unique').random_string('unique');
                    $d = array(
                            'forgot_token'=>$code,
                            'dtForgot'=>date('Y-m-d H:i:s')
                        );

                    $this->db->where('email', $uname);
                    $this->db->update('tbl_users', $d);    

                    $this->email->forgotEmail($user["first_name"], $user["last_name"], $user["email"], $code );      
                    redirect('/authentication/forgotdone', 'refresh');
                } else {
                    redirect('/authentication/forgotdone', 'refresh');
                }



                

        }

        $data = array();
        $this->load->view('forgot', $data);
    }

    public function forgotdone(){

        $data = array();
        $this->load->view('forgotdone', $data);
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
            $this->form_validation->set_message('confirm_password', 'The confirm password does not match');
            return FALSE;
        } else {
            return TRUE;
        }
    }


    public function reset(){

        $message = "";
        if (!empty($_POST)){ 

            $this->load->library(array('form_validation'));
            $hasErrors = FALSE;

            //Form Validation
            $this->form_validation->set_error_delimiters('<span for="required" generated="true" class="help-inline" style="color:red;"> *', '</span>');
            $this->form_validation->set_rules('new_password', 'Password 1', 'trim|required|min_length[8]|max_length[32]');
            $this->form_validation->set_rules('new_password1', 'Password 2', 'trim|required|min_length[8]|max_length[32]|callback_confirm_password');

            $this->form_validation->set_rules('code', 'code', 'trim|required');
            $this->form_validation->set_rules('email', 'email', 'trim|required');

            if ($this->form_validation->run() === FALSE) {
                 $hasErrors = TRUE;
                 echo "errors";
            } 


            if ($hasErrors===FALSE){

                //echo "<pre>".print_r($_POST)."</pre>";

                 $wh=array(
                    "forgot_token"=>$this->input->post('code'),
                    "email"=>$this->input->post('email'),
                );
                //echo "<pre>";print_r($wh);exit;
                $user = $this->m_common->db_select("user_id,dtForgot", "tbl_users", $wh, '', '', '', '', 'row_array',0);
                //print_r($user);
                if (!empty($user)){

                    //Update the password
                     $d = array(
                            'password'=>md5($this->input->post('new_password')),
                            'forgot_token'=>''
                        );

                    $this->db->where('email', $this->input->post('email'));
                    $this->db->where('forgot_token', $this->input->post('code'));

                    $this->db->update('tbl_users', $d);  

                    $this->session->set_userdata('thank_message',"Password Updated Successfully! <a href='/authentication/login'>Click Here</a> to login.");
                    redirect('/authentication/forgotdone', 'refresh');
                } else {
                    $message ="Link Expired.  <a href='/authentication/forgot'>Go Here</a> to start the reset process over.";
                }


            }



        }

        $data = array(
                "code"=>$this->input->get_post('code'),
                "email"=>$this->input->get_post('email'),
                "message"=>$message
            );
        $this->load->view('reset', $data);
    }



    public function login() {

        if ($_SERVER['SERVER_NAME']=='app.dealsonthegogo.com'){
            $this->load->helper('url');
            redirect('https://db.locallyepic.com', 'refresh');
        }

        $message = "";
        
           //echo "<pre>";print_r($data);exit;
        if (!empty($_POST)){
            $uname = $_POST['username'];
            $pass = $_POST['password'];

                $wh=array(
                    "email"=>$uname,
                    "password"=>md5($pass),
                );
                //echo "<pre>";print_r($wh);exit;

                if ($this->input->ip_address()=='68.80.62.233'){

                    echo "<pre>";print_r($wh);
                }
                $user = $this->m_common->db_select("*", "tbl_users", $wh, '', '', '', '', 'row_array');
                if (!empty($user)){
                    
                    // echo "<pre>";
                    // print_r($user);
                    // exit();
                    if( $user['user_is_active'] == 'Yes'){

                    $this->session->set_userdata('logged_in', 1);
                    $this->session->set_userdata('role',$user['role'] );
                    $this->session->set_userdata('email',$user['email']);
                    $this->session->set_userdata('user_id',$user['user_id']);

                     $wh=array(
                        "user_id"=>$user['user_id']
                    );
                    //echo "<pre>";print_r($wh);exit;
                    if( $user['role']==6){   

                        // echo "<pre>";
                        // print_r($user);
                        // exit();

                        if($user['is_corporate_business_user'] == 1)
                        {
                            $this->db->select('t2.shop_id,t2.is_payment, t2.blnexpired');
                            $this->db->from('tbl_users_shops t1');
                            $this->db->join('tbl_shop t2','t2.shop_id = t1.shop_id');
                            $this->db->where('t1.user_id',$user['user_id']);
                            $shop = $this->db->get()->row_array();
                        }
                        else
                        {
                            // $shop = $this->m_common->db_select("shop_id,is_payment, blnexpired", "tbl_shop", $wh, '', '', '', '', 'row_array');
                            $this->db->select('t2.shop_id,t2.is_payment, t2.blnexpired');
                            $this->db->from('tbl_users_shops t1');
                            $this->db->join('tbl_shop t2','t2.shop_id = t1.shop_id');
                            $this->db->where('t1.user_id',$user['user_id']);
                            $shop = $this->db->get()->row_array();
                        }
                        
                     
                        $this->session->set_userdata('shop_id',$shop['shop_id']);
                        
                        // echo "<pre>";
                        // print_r($shop);
                        // echo $this->db->last_query();
                        // exit();



                        if($shop['is_payment']==0 || $shop['blnexpired']==1){ 
                            redirect('/authentication/payment?expired=true', 'refresh');
                        } else {

                            redirect('/site/index', 'refresh');
                        }

                    } else {

                        redirect('/site/index', 'refresh');
                    }

				}else{
                    $message="Your Account Blocked.";              
                }
                       
                    
                    //echo "ok";exit;
                } else {

                        $message="Usename and Password combination not match";              
                }

        } else {
            $this->session->sess_destroy();
        }
        $data = array('message' => $message);
        $this->load->view('login', $data);
    }
    

}