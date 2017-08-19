<?php
class Appuser extends CI_Model{
 
	function __construct(){
	  parent::__construct();
	}

	function checkEmail($user_id, $email){

		$sql="select count(*) as thecount from tbl_customer where email=? and user_id !=?";
		$result = $this->db->query($sql, array( $email, $user_id));

        $count = $result->row_array();

        //print_rr($count);

        return $count["thecount"];


	}

    

    function getAppUser($user_id, $user_token){

         $sql = "
                select

                    user_id,
                    IFNULL(name,'') as name,
                    email,
                    IFNULL(gender,1) as gender,
                    IFNULL(zipcode,'') as zipcode,
					IFNULL(age,'') as age
                       
                from
                        tbl_customer
                where
                        user_id=?
                        and
                        user_token=?
            ";

        $result = $this->db->query($sql, array( $user_id, $user_token));

        $appuser = $result->row_array();

        return $appuser;
    }




	function getDeviceTokenAndType($user_id, $user_token){

         $sql = "
                select
                	IFNULL(device_token,'') as device_token,
                    IFNULL(device_type,'') as device_type
                from
                        tbl_customer
                where
                        user_id=?
                        and
                        user_token=?
            ";

        $result = $this->db->query($sql, array( $user_id, $user_token));

        $appuser = $result->row_array();

        return $appuser;
    }


    function updateAppUser($user_id, $user_token, $name, $email, $zipcode, $gender, $age){

		$sql = "

		    		update tbl_customer

		    		set 
		    		name=?,
		    		email=?,
		    		gender=?,
		    		zipcode=?,
            age=?

		    		where

		    			user_id=?
		    			and
		    			user_token=?

		    	";

		$result = $this->db->query($sql, array( $name, $email, $gender, $zipcode, $age, $user_id, $user_token));

		//echo  $this->db->last_query();
    }

    function update_password($user_id, $user_token, $password){


    	$password_encrypted = encrypt_password($password);

    	$sql = "update tbl_customer set password=?, password_encrypted=? where user_id=? and user_token=?";
    	$result = $this->db->query($sql, array( "", $password_encrypted, $user_id, $user_token));

    	//echo  $this->db->last_query();

    }


	

}