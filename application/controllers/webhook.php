<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class webhook extends CI_Controller
{

    private $data;

    function webhook()
    {

        parent::__construct();
      
    }


   
    function get_webhook(){
        
        require(APPPATH.'libraries/stripe/init.php');
        \Stripe\Stripe::setApiKey('sk_test_DaNW3aIALBKXT0Jb946QxrAm');

        // Set your secret key: remember to change this to your live secret key in production
        // See your keys here: https://dashboard.stripe.com/account/apikeys
        \Stripe\Stripe::setApiKey("sk_test_DaNW3aIALBKXT0Jb946QxrAm");

        // Retrieve the request's body and parse it as JSON
        $input = @file_get_contents("php://input");
        $event_json = json_decode($input);


        $this->db->insert('tbl_webhook',array('data'=>json_encode($event_json)));

        // Do something with $event_json

        http_response_code(200); // PHP 5.4 or greater

    }

    

} ?>