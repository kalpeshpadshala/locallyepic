<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Proxmob extends CI_Controller {

	public function index() {
        $day = intval(date('j'));

            
        $dateraw = strtotime(date('m', strtotime('+1 month')).'/01/'.date('Y').' 00:00:00');
        $date = date("m/d/Y", $dateraw);
        $temp_error="";

        if ($_SERVER['REQUEST_METHOD']=='POST') {

            $this->load->library(array('form_validation'));
            $hasErrors = FALSE;

            //Form Validation
            $this->form_validation->set_rules('cardNumber', 'Card Number', 'required');
            $this->form_validation->set_rules('expiry_month', 'Expiration Month', 'required');
            $this->form_validation->set_rules('expiry_year', 'Expiration Year', 'required');
            $this->form_validation->set_rules('cardCode', 'Security Code', 'required');

            $this->form_validation->set_error_delimiters('<div class="error">', '</div>');

            if ($this->form_validation->run() == FALSE) {
                 $hasErrors = TRUE;
            }

            
            //THE INITIAL CREDIT CARD CHARGE

            if ($hasErrors == FALSE) {
                
                $this->load->library('authorize_net');

                //Credit Card Processing
                $this->authorize_net->startData('create');

                $subscription_data = array (

                        'transactionType'=>'authCaptureTransaction',
                        'amount'=>5.00,
                        'payment' => array(
                            'creditCard' => array(
                                'cardNumber' => $this->input->post('cardNumber'),
                                'expirationDate' => $this->input->post('expiry_year').'-'.$this->input->post('expiry_month'),
                                'cardCode' => $this->input->post('cardCode')
                            ),
                        ),
                        'customer' => array(
                        'id' => 0,
                        'email' => $this->input->post('youremail'),
                        ),

                        'billTo' => array(
                        'firstName' => $this->input->post('firstname'),
                        'lastName' => $this->input->post('lastname'),
                        'company' => '',
                        'address' => '',
                        'city' => '',
                        'state' => '',
                        'zip' => $this->input->post('yourzipcode'),
                        'country' => 'US',
                        ),

                        'customerIP'=>$this->input->ip_address()
                );


                
                //$this->authorize_net->addData('refId', '');
                $this->authorize_net->addData('transactionRequest', $subscription_data);
            
                // Send request
                if( $this->authorize_net->send() )
                {
                    $response_code = $this->authorize_net->getResponseCode();
                    $response_error = $this->authorize_net->getResponseError();
                    //echo "<br>Response Code: ". $response_code;
                    //echo "<br>Response Error: ". $response_error;
                    //echo "<br>Here";

                    if ($response_code !='1') {

                        $hasErrors= TRUE;
                        $this->session->set_flashdata('message', (string)$response_error);
                        //echo "207"."($response_code)";
                    } else {
                        
                    }

                }
                else
                {
                    $temp_error =  $this->authorize_net->getError();
                    //echo $temp_error;
                    $hasErrors= TRUE;
                    $this->session->set_flashdata('message', $temp_error);
                }

                //$this->authorize_net->debug();

                //echo "stop after initial charge"; 
            }

// STOP THE INITIAL CREDIT CARD CHARGE







            //echo "Has Errors: $hasErrors";

            if ($hasErrors == FALSE) {

                

                //Credit Card Processing
                $this->load->library('authorize_arb');
                $this->authorize_arb->startData('create');
                $subscription_data = array(
                    'name' => 'ProxMob Email Address ',
                    'paymentSchedule' => array(
                        'interval' => array(
                            'length' => 1,
                            'unit' => 'months',
                            ),
                        'startDate' => date('Y-m-d', $dateraw),
                        'totalOccurrences' => 9999,
                        'trialOccurrences' => 0,
                        ),
                    'amount' => 5.00,
                    'trialAmount' => 0.00,
                    'payment' => array( 
                        'creditCard' => array(
                            'cardNumber' => $this->input->post('cardNumber'),
                            'expirationDate' => $this->input->post('expiry_year').'-'.$this->input->post('expiry_month'),
                            'cardCode' => $this->input->post('cardCode'),
                            ),
                        ),
                    
                    'customer' => array(
                        'id' => 0,
                        'email' => $this->input->post('youremail'),
                        ),

                    'billTo' => array(
                        'firstName' => $this->input->post('firstname'),
                        'lastName' => $this->input->post('lastname'),
                        'company' => '',
                        'address' => '',
                        'city' => '',
                        'state' => '',
                        'zip' => $this->input->post('yourzipcode'),
                        'country' => 'US',
                        )
                );
                
                //print_r($subscription_data);
                //exit;
                $this->authorize_arb->addData('subscription', $subscription_data);
                
                
                
                // Send request
                if( $this->authorize_arb->send() )
                {


                    
                    redirect('/proxmob/thanks', 'refresh');

                }
                else
                {
                    $temp_error =  $this->authorize_arb->getError();
                }
                
                // Show debug data
                //$this->authorize_arb->debug();
            }
//stop for until we see if subscriptions are running correctly.
        //exit(0);

        }



        $data = array(
                'day'=>$day,
                'date'=>$date,
                
                
                
                'error'=>$temp_error
            );

         $this->load->view('proxmob/index', $data);
    }

    public function thanks(){

        $data = array();

        $this->load->view('proxmob/thanks', $data);
    }

}