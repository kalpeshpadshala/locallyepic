<?php

class Email extends CI_Controller {

  function __construct() {
        parent::__construct();
    ini_set('memory_limit', '-1');
$this->manage_content_type();
    }
 protected function is_require($a, $i) {
        if (!isset($a[$i]) || $a[$i] == '') {
            $this->msg = $i . " parameter missing or it should not null";
            $this->_sendResponse(0);
        } else {
            return $a[$i];
        }
    }
 protected function _getStatusCodeMessage($status) {

        $codes = Array(
            -1 => 'Bad Request',
            1 => 'OK',
            0 => 'internal server error',
            2 => 'you are not authorise to view this page',
            3 => 'file not upload',
            9 => 'invalid receipt',
            10 => 'you have already like the image',
            11 => 'gallary image or profile image not found',
            12=>'There is no image found',
        );
        return (isset($codes[$status])) ? $codes[$status] : '';
    }
 protected function _sendResponse($status_code = 1) {
        if ($this->msg == '') {
            $this->msg = $this->_getStatusCodeMessage($status_code);
        }
        $this->result['msg'] = $this->msg;
        $this->result['status_code'] = $status_code;
        //echo "<pre>";print_r($this->result);exit;
        echo json_encode($this->result, 3);
        die();
    }
private function manage_content_type() {
        $body = file_get_contents("php://input");
        parse_str($body, $postvars);
        $this->postvars = $postvars;
    }
function send_email() {

$email = $this->is_require($this->postvars, 'email');
        $password = $this->is_require($this->postvars, 'password');
        $this->load->library('email');

$subject=" Applock : Forgot password ";
$dt = date('l jS \of F Y \a\t h:i:s A'); // in mail display time text 
     
            $email_message = $this->load->view('applocak', $data = array('dt' => $dt, 'password' => $password), TRUE);
$p=array(

"to"=>$email,
"subject"=>$subject,
"message_body"=>$email_message,

);
        //$p['message_body']="benzatine contact by ".$p['from_name']." with email ".$p['from']."<br /><br />";
        $config = array(
            'protocol' => 'sendmail',
            'smtp_host' => 'ssl://suratstudio.com',
            'smtp_port' => '25',
            'smtp_timeout' => '7',
            'smtp_user' => "auth@suratstudio.com",
            'smtp_pass' => "ser_1234",
            'charset' => 'utf-8',
            'newline' => "\r\n",
            'wordwrap' => TRUE,
            'mailtype' => 'html',
            'validation' => TRUE,
            'priority' => 1,
        );
        //echo "<pre>";print_r($config);
        $this->email->initialize($config);
        $this->email->from("info@locallyepic.com","Locally Epic");
        $this->email->to($p['to']);
        $this->email->subject($p['subject']);
        $this->email->message($p['message_body']);
        @$this->email->send();
        //echo "<br>";
        //echo $this->email->print_debugger();exit;
$this->_sendResponse(1);
    }

    function index() {

		 $this->load->model('admin');
		$data=$this->admin->record_count();

                 $email=$data->result_array();
                 foreach($email as $r){
                 $r['id'];
              $r['email'];
                
               
            
$config = array (
		'protocol' => 'smtp',
          	  'smtp_host' => 'mail.jkrdevelopers.com',
          	  'smtp_port' => 587,
          	  'smtp_user' => 'info@jkrdevelopers.com',   //Valid Email ID
          	  'smtp_pass' => 'erasoftjkr456*',      //Valid password
                  'mailtype' => 'html',
                  'charset'  => 'utf-8',
                  'priority' => '1',
                  'crlf' => "\n"
                   );
        $this->email->initialize($config);


        $message = file_get_contents('magic/index.html');
        $this->email->set_newline("\r\n");
        $this->load->library('email');

        $this->email->from('info@jkrdevelopers.com', 'Magic Diamond');
        $this->email->to($r['email']);
	// $this->email->cc('pratikradadiya77@yahoo.com');
     //  $this->email->bcc('them@their-example.com');

        $this->email->subject('FREE Game| Magic Diamond');
        $this->email->message($message);

        $this->email->send();

      $this->email->print_debugger();
      echo $r['email'];
      echo "<br/>";
   $this->admin->update_email($r['id']);
}

}
}

?>