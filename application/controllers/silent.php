<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Silent extends CI_Controller {

    function Gogo() {
        parent::__construct();
        $this->load->model('m_common');
        date_default_timezone_set('America/Los_Angeles');
    }

    public function index() {

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
        $this->email->from("r.johnson@proxmob.com","Authorize.net Post Back");
        $this->email->to("r.johnson@proxmob.com","Authorize.net Post Back");
        
        $this->email->subject("Authorize.net Post Back");
        $var = print_r($_POST, true);

        $this->email->message("<pre>$var</pre>");
        @$this->email->send();
        //echo $this->email->print_debugger();
        echo "<br>";

        exit(0);

    }

}