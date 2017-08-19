<?php
class Promocode extends CI_Model{
 
	function __construct(){
	  parent::__construct();
	}

	function checkpromocode($promocode, $promocode_type){

		$sql = "SELECT * FROM promocodes WHERE promocode =  trim(?) and dtStart <= now() and dtEnd > now() and status='Activated'"; 

        $r = $this->db->query($sql, array($promocode));

        $lastq = $this->db->last_query();

        $result = $r->result();

        $membership_activation_fee = 199;
        $monthly_network_fee = 199;
        $total_due_today = 199;

        //echo $result[0]->promocode; exit;
        //print_r($result);
        if (is_array($result) && count($result) > 0 ) {

        

            switch ($result[0]->promocode_type) {

                case "activation fee":

                    switch ($result[0]->type) {

                        case "Amount Off":

                            $membership_activation_fee= $membership_activation_fee - $result[0]->percent_amount;
                            $total_due_today = $membership_activation_fee;

                        break;

                        case "Set Amount":

                            $membership_activation_fee= $result[0]->percent_amount;
                            $total_due_today = $membership_activation_fee;

                        break;

                        case "Percent Off":

                            $membership_activation_fee = $membership_activation_fee * (1-($result[0]->percent_amount/100));
                            $total_due_today = $membership_activation_fee; 

                        break;

                        case "Free":

                        $membership_activation_fee = 0;
                        $total_due_today = $membership_activation_fee;

                        break;
                    }

                break;

                case "monthly fee":

                    switch ($result[0]->type) {

                        case "Amount Off":

                            $monthly_network_fee= $monthly_network_fee - $result[0]->percent_amount;

                        break;

                        case "Set Amount":

                            $monthly_network_fee= $result[0]->percent_amount;

                        break;

                        case "Percent Off":

                            $monthly_network_fee = $monthly_network_fee * (1-($result[0]->percent_amount/100));

                        break;

                        case "Free":

                        $monthly_network_fee = 0;

                        break;
                    }

                break;

                case "both":

                    switch ($result[0]->type) {

                        case "Amount Off":

                            $membership_activation_fee= $membership_activation_fee - $result[0]->percent_amount;
                            $total_due_today = $membership_activation_fee;
                            $monthly_network_fee= $monthly_network_fee - $result[0]->percent_amount;

                        break;

                        case "Set Amount":

                            $membership_activation_fee= $result[0]->percent_amount;
                            $total_due_today = $membership_activation_fee;
                            $monthly_network_fee= $result[0]->percent_amount;

                        break;

                        case "Percent Off":

                            $membership_activation_fee = $membership_activation_fee * (1-($result[0]->percent_amount/100));
                            $total_due_today = $membership_activation_fee; 
                            $monthly_network_fee = $monthly_network_fee * (1-($result[0]->percent_amount/100));

                        break;

                        case "Free":

                        $membership_activation_fee = 0;
                        $total_due_today = $membership_activation_fee;
                        $monthly_network_fee = 0;

                        break;
                    }

                break;
     
            }
        }


        if ($result[0]->promocode_type=='monthly fee' && $result[0]->intmonthsfree > 0){

        $dateraw = strtotime('now +'.$result[0]->intmonthsfree.' month');
                    
        $date = date("m/d/Y", $dateraw);

        $date = (new DateTime($date))
          ->modify('first day of this month')
          ->format('m/d/Y');

        } else {

            $dateraw = strtotime(date('m', strtotime('+1 month')).'/01/'.date('Y').' 00:00:00');

            $date = date("m/d/Y", $dateraw);
        }

        setlocale(LC_MONETARY,"en_US");
        $data = array(
                        "sql"=>$sql, 
                        "promocode"=>$promocode, 
                        "lastq"=>$lastq, 
                        "result" => $result,
                        // "membership_activation_fee"=>money_format("%i",$membership_activation_fee),
                        // "monthly_network_fee"=>money_format("%i",$monthly_network_fee),
                        // "total_due_today"=>money_format("%i",$total_due_today),

                        "membership_activation_fee"=>$membership_activation_fee,
                        "monthly_network_fee"=>$monthly_network_fee,
                        "total_due_today"=>$total_due_today,

                        
                        "nextbillblurb"=>"Your Locally Epic monthly network fee will start on ".$date." and will recur on the first of every month thereafter.",
                        "type"=>$result[0]->type,
                        "mf"=>$result[0]->intmonthsfree



                    );
        return $data;


	}

}