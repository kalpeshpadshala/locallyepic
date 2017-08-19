<?php
class User extends CI_Model{
 
	function __construct(){
	  parent::__construct();
	}

	function getUser($user_id){

		$sql = "
                select
                       user_id,
                       first_name,
                       last_name
                from
                        tbl_users
                where
                        user_id=?
            ";

        $result = $this->db->query($sql, array( $user_id));

        $row = $result->row();

        return $row;


	}

}
?>