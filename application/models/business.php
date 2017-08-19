<?php
class Business extends CI_Model{
 
	function __construct(){
	  parent::__construct();
	}
    function getBusiness($business_id){

        $sql = "
                select
                       tbl_shop.*,
                       city_name,
                       state_name
                from
                        tbl_shop left join tbl_city on tbl_shop.city_id = tbl_city.city_id
                                 left join tbl_state on tbl_shop.state_id =  tbl_state.sid
                where
                        shop_id=?
            ";

        $result = $this->db->query($sql, array( $business_id));

        $row = $result->row();

        return $row;


    }

    function getBusinessList(){

        $sql = "
                select 
                        shop_id,
                        shop_name
                from
                        tbl_shop

                order by shop_name; 
        ";

        $result = $this->db->query($sql);


        return $result->result_array();


    }

    function getBusinessListByZip($zip_code, $limit){

        $sql = "
                select 
                        *
                from
                        tbl_shop

                where zip_code = ?

                order by rand()

                limit $limit 
        ";

        $result = $this->db->query($sql,array($zip_code));


        return $result->result_array();


    }

	function getBusinessPin($business_id){

		$sql = "
                select
                       pin
                from
                        tbl_shop
                where
                        shop_id=?
            ";

        $result = $this->db->query($sql, array( $business_id));

        $pin = $result->row()->pin;

        return $pin;


	}

}
?>