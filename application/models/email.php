<?php
class Email extends CI_Model{
 
	function __construct(){
	  parent::__construct();
	}

	function forgotEmail($firstname, $lastname, $email, $code){

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
        $this->email->from("r.johnson@locallyepic.com","Locally Epic");
        $this->email->to("$email","$firstname $lastname");
        
        $this->email->subject("Password Reset Request");
        $var = print_r($_POST, true);

        $data=array(
                        "firstname"=>$firstname,
                        "lastname"=>$lastname,
                        "email"=>$email,
                        "code"=>$code
                    );
        $message_body=$this->load->view('email/forgot_password_website', $data,true);


        $this->email->message($message_body);


        @$this->email->send();
        //echo $this->email->print_debugger();
       // echo "<br>";
        //exit(0);


	}

}