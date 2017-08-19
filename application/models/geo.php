<?php
class Geo extends CI_Model{
 
	function __construct(){
	  parent::__construct();
	}


	function getLastKnownLocation($user_id){

	}

	function updateAppUserLocation($user_id, $lat, $lon){

		$sql = "update tbl_customer set latitude=?, longitude=? where user_id=?";
		$this->db->query($sql, array($lat, $lon, $user_id));

		//$str = $this->db->last_query();
        //echo $str;
	}

}