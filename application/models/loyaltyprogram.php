<?php
class Loyaltyprogram extends CI_Model{
 
	function __construct(){
	  parent::__construct();
	}


    function calculateRewards($user_id, $business_id){

        // Is the customer loyalty program for the business active?
        if ($this->isCLActive($business_id)) {



            //Check to see if this user has a matchup entry, if not add it
            if ($this->checkForMatchupEntry($user_id, $business_id)==false){

                //grab the first reward from the program so we can populate the matchup table.
                $reward = $this->getNextReward($business_id, 0);

                //add match up row so we can do normal processing
                $this->addLPCustomerMatchup($user_id, $reward);
            } // end checkForMatchupEntry

            //get the matchup row
            $matchup_row = $this->getLPCMatchupRow($user_id, $business_id);

            // grab the current activations and add 1 to it
            $new_activation_count = $matchup_row->number_current_activations+1;

            //compare the new_activation count to matchup row count and see if we have a reward.

            if ($new_activation_count < $matchup_row->number_activations_for_next_reward) {

                //increment the count.
                $this->updateLPCustomerMatchupActivationCount($user_id, $business_id, $new_activation_count);

            } else {

                //Customer has earned a reward.
                $reward = $this->getNextReward($business_id, $new_activation_count-1);  //-1 because we want the current reward
                $this->addReward($user_id, $business_id, $reward);

                //grab the next reward or pull the first one if no more rewards
                $reward = $this->getNextReward($business_id, $new_activation_count);
                
                if ($reward->intActivations < $new_activation_count ) {$new_activation_count=0;}

                $this->updateLPCustomerMatchup($user_id, $business_id, $reward, $new_activation_count);

            }

        } 

    }


    function updateLPCustomerMatchup($user_id, $business_id, $reward, $number_current_activations){

        $sql = "update loyaltyprogramcustomermatchup
                SET
                reward_title=?,
                number_current_activations=?,
                number_activations_for_next_reward=?,
                loyaltyprogram_id=?,
                loyaltyprogramitem_id=?
                WHERE
                user_id=?
                 AND
                business_id=?
                ";
        $result = $this->db->query($sql, array( 
                                                
                                               
                                                $reward->title,
                                                $number_current_activations,
                                                $reward->intActivations,
                                                $reward->loyalty_program_id,
                                                $reward->id,
                                                $user_id,
                                                $business_id
                                                ));


    }
    

    function addReward($user_id, $business_id, $reward){

        $sql = "insert into loyaltyprogramrewards
                SET

                user_id=?,
                business_id=?,
                loyaltyprogram_id=?,
                loyaltyprogramitem_id=?,
                reward_title=?,
                reward_description=?,
                number_of_activations=?";
               

        $result = $this->db->query($sql, array( 
                                                $user_id,
                                                $business_id,
                                                $reward->loyalty_program_id,
                                                $reward->id,
                                                $reward->title,
                                                $reward->description,
                                                $reward->intActivations
                                                ));


    }

    function updateLPCustomerMatchupActivationCount($user_id, $business_id, $new_activation_count){

        $sql = "UPDATE loyaltyprogramcustomermatchup
                SET number_current_activations = ?
                WHERE
                    user_id =?
                AND business_id =?";

         $result = $this->db->query($sql, array( $new_activation_count, $user_id, $business_id));

    }

    function updateFavorite($user_id, $business_id, $isfavorite){

        $sql = "UPDATE loyaltyprogramcustomermatchup
                SET isFavorite = ?
                WHERE
                    user_id =?
                AND business_id =?";

         $result = $this->db->query($sql, array( $isfavorite, $user_id, $business_id));

    }

    function deleteCustomerLoyaltyProgram($user_id, $business_id){

        $sql = "DELETE FROM loyaltyprogramcustomermatchup

                WHERE
                    user_id =?
                AND business_id =?";

         $result = $this->db->query($sql, array( $user_id, $business_id));

    }

    function addLPCustomerMatchup($user_id, $reward){

        $sql = "INSERT INTO loyaltyprogramcustomermatchup
                SET 
                user_id =?, 
                business_id =?, 
                reward_title =?, 
                number_current_activations =?, 
                number_activations_for_next_reward =?, 
                loyaltyprogram_id =? ,
                loyaltyprogramitem_id =?
               ";

        $result = $this->db->query($sql, array( 
                                                $user_id,
                                                $reward->shop_id,
                                                $reward->title,
                                                0,
                                                $reward->intActivations,
                                                $reward->loyalty_program_id,
                                                $reward->id
                                                ));



    }

    function isCLActive($business_id){
        $sql="select blnStatus from loyaltyprograms where shop_id =?";
        $result = $this->db->query($sql, array( $business_id));
        
        $initial_check =  $result->num_rows();



        if ($initial_check==0){
            return false; 
        } else {
            $blnStatus = $result->row()->blnStatus;
             if ($blnStatus==0){
                return false; 
            } else {
                return true; 
            }
        }







    }

    function checkForMatchupEntry($user_id, $business_id){

        $sql = "select * from loyaltyprogramcustomermatchup where user_id=? and business_id=?";
        $result = $this->db->query($sql, array( $user_id, $business_id));

        $initial_check =  $result->num_rows();
        $entry = $result->row();

        if ($initial_check==0) {

            return false;

        } else {

            return $entry;

        }

    }

    function getLPCMatchupRow($user_id, $business_id){

         $sql = "
                select
                        *
                from
                        loyaltyprogramcustomermatchup
                where
                        user_id = ?
                        and
                        business_id = ?
        ";


        $result = $this->db->query($sql, array( $user_id, $business_id));

        $getLPCMatchupRow = $result->row();;

        return $getLPCMatchupRow;

    }

    function getActivations($user_id, $business_id){

         $sql = "
                select
                        count(*) as deals_activated
                from
                        tbl_deals_activated
                where
                        customer_id = ?
                        and
                        business_id = ?
        ";


        $result = $this->db->query($sql, array( $user_id, $business_id));

        $deals_activated = $result->row()->deals_activated;

        return $deals_activated;

    }

    function getBusiness($business_id){

         $sql = "
                select
                        shop_name as business_name,
                        IFNULL(concat('https://".$_SERVER["SERVER_NAME"]."/uploads/user/',shop_image),'') as business_logo,
                        business_phone,
                        url as business_website,
                        address as business_address,
                        shop_description as business_description,
                        latitude,
                        longitude,
                        pin
                from
                        tbl_shop
                where
                        shop_id=?
            ";

        $result = $this->db->query($sql, array( $business_id));

        $business = $result->row();

        return $business;
    }

    function getNextReward($business_id, $intActivations){

        $sql = "select * from loyaltyprogramitems where shop_id=? and intActivations > ? order by intActivations limit 1";
        $result = $this->db->query($sql, array( $business_id, $intActivations));

        $row = $result->row();
        $initial_check =  $result->num_rows();

        //if no row is found we need to start over.
        if ($initial_check==0){

            $sql = "select * from loyaltyprogramitems where shop_id=? and intActivations > ? order by intActivations limit 1";
            $result = $this->db->query($sql, array( $business_id, 0));

            $row = $result->row();


        } 

        return $row;


    }

    function getRedeemableRewardCount($user_id, $business_id){

         $sql = "
                select
                        count(*) as thecount
                from
                        loyaltyprogramrewards
                where
                        business_id=?
                        and
                        user_id=?
                        and
                        blnRewarded=0
            ";

         $result = $this->db->query($sql, array( $business_id, $user_id));

        $rewards = $result->row();

        return $rewards->thecount;


    }

    function getRedeemableRewards($user_id, $business_id){

        $sql = "
                select
                        id as reward_id,
                        reward_title,
                        reward_description,
                        number_of_activations,
                        blnRewarded,
                        dtRewarded
                from
                        loyaltyprogramrewards
                where
                        business_id=?
                        and
                        user_id=?
                        and
                        blnRewarded=0
            ";

         $result = $this->db->query($sql, array( $business_id, $user_id));

        $rewards = $result->result_array();

        //$str = $this->db->last_query();

        //echo $str;
        //exit;

        return $rewards;
    }

    function getReward($reward_id){

        $sql = "
                select
                        id as reward_id,
                        business_id,
                        blnRewarded,
                        dtRewarded
                from
                        loyaltyprogramrewards
                where
                        id=?
            ";

         $result = $this->db->query($sql, array( $reward_id));

        $reward = $result->row();

        return $reward;
    }

    function redeemReward($reward_id,$user_id){

        $sql = "
                update
                        loyaltyprogramrewards
                set
                        blnRewarded=1,
                        dtRewarded=now()
                where
                        id=?
                        and
                        user_id=?
            ";

         $result = $this->db->query($sql, array( $reward_id, $user_id));

    }


}