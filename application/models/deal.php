<?php
class Deal extends CI_Model{
 
	function __construct(){
	  parent::__construct();
	}


	function get_deal_id(){

		$sql = "insert into testdealcounter (thetime) select now();";
		$this->db->query($sql);

		return $this->db->insert_id();
	}

}