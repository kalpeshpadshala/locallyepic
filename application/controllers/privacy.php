<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Privacy extends CI_Controller {

    function Privacy() {
        parent::__construct();
        $this->load->model('m_common');
        date_default_timezone_set('America/Los_Angeles');
    }

    public function index() {
        $data = array();
        
         $this->load->view('privacy');
       
       
    }

    

    
}

/* End of file welcome.php */
/* Location: ./application/cconcatontrollers/welcome.php */