<?php
//test
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

define("RANDY_DEBUG", false);
define("APNS_DEVELOPMENT", "sandbox");
define("APNS_CERT_DEV", "ck1.pem");
define("APNS_CERT_LIVE", "ck1.pem");
//define("ANDROID_APIKEY", "AIzaSyAHecYTzBl4YLMUDDRwn-KLEMP5NKAH6Ic");
define("ANDROID_APIKEY", "AIzaSyDnCJcCcFoLFjE6pLExHtA_nt398_nhkII");

class Randy extends CI_Controller {

	function Randy() {
        parent::__construct();
        $this->load->model('m_common');
        $this->load->model('business');
        $this->load->model('loyaltyprogram');
        $this->load->model('geo');
        $this->load->helper('date');



        $this->result = array();
        $this->msg = '';
        $this->is_debug=isset($this->postvars['is_debug']) ? $this->postvars['is_debug'] : 0;
        //header('Content-type: application/json');
        date_default_timezone_set('America/New_York');
        $this->manage_content_type();

    }

    public function index(){




    }

    public function time(){
        $ts =  "2015-12-06 8:00 pm";
        echo $ts."<br>";

        echo time();

        echo "<br>";


        $htu = human_to_unix($ts);
        $uth = unix_to_human($htu);
        echo $uth."<br>";
        echo "<hr>";

        $date = "2015-12-06";
        $time="8:00 PM";

        $utc = convert_to_utc($date,$time,'UM5');

        print_rr($utc,0);

        echo "<hr>";

        echo unix_to_human($utc["seconds"]);

        echo "<br>";

        $nt = utc_to_local($utc["utc_date"],$utc["utc_time"],"UM5");

        print_rr($nt);
        exit;

    }

    private function manage_content_type() {
        $body = file_get_contents("php://input");
        parse_str($body, $postvars);
        $this->postvars = $postvars;
    }


    public function golfimport() {

        echo "turned off";
        exit;
        $sql =" select * from import_golf";
        $result = $this->db->query($sql);
        $c = 0;
        foreach ($result->result() as $row) {
            $c++;
            echo $row->name;
            echo $row->description;
            echo $row->address;

            $first_name = "store";
            $last_name = "manager";
            $email = "golf$c@proxmob.com";
            $password = md5("12345678");
            $country_id=254;
            $state_id=162;


            $sqlcity = "select city_id,latitude,longitude from tbl_city where cid=? and state_id=? and city_name=? ";
            $rcity = $this->db->query($sqlcity, array($country_id, $state_id, $row->city));
            //$lastq = $this->db->last_query();
            //print_r($lastq);
            $resultcity = $rcity->row();



            $city_id = $resultcity->city_id;

            $zip_code = $row->zip;
            $full_address = $row->address . ', ' . $row->city . ', ' . $row->state . ', United States';
            $contact_num = $row->phone;
            $role=6;

            $qinsert = "
                insert into tbl_users
                set
                first_name = ?,
                last_name = ?,
                email = ?,
                country_id = ?,
                state_id = ?,
                city_id = ?,
                zip_code = ?,
                full_address = ?,
                contact_num = ?,
                password = ?,
                role = ?
            ";
            $rinsert = $this->db->query($qinsert, array($first_name, $last_name, $email, $country_id, $state_id, $city_id, $zip_code, $full_address, $contact_num, $password, $role));

            $user_id =$this->db->insert_id();

            echo $user_id;

            //Now we need to create the shop

            $shop_name = $row->name;
            $shop_cats = 33;
            $shop_description = $row->description;
            $shop_image = '';
            $address = $full_address;
            $business_phone = $contact_num;
            $url = $row->website;
            $contact_first_name = "Store";
            $contact_last_name = "Manager";
            $contact_phone = $contact_num;
            $contact_email = $email;
            $timezone = 'UM5';
            $latitude = $resultcity->latitude;
            $longitude = $resultcity->longitude;
            $is_payment=0;

            $qshop ="insert into tbl_shop set

                user_id=?,
                shop_name=?,
                shop_cats=?,
                shop_description=?,
                shop_image=?,
                country_id=?,
                state_id=?,
                city_id=?,
                address=?,
                zip_code=?,
                email=?,
                first_name=?,
                last_name=?,
                business_phone=?,
                url=?,
                contact_first_name=?,
                contact_last_name=?,
                contact_phone=?,
                contact_email=?,
                timezone=?,
                latitude=?,
                longitude=?,
                is_payment=?,
                is_admin=0,
                add_by=0,
                monthlyfee=199,
                blnPaidActivationFee=0,
                blnexpired=1,
                pin=1234
            ";

            $rshop = $this->db->query($qshop, array(

                                            $user_id,
                                            $shop_name,
                                            $shop_cats,
                                            $shop_description,
                                            $shop_image,
                                            $country_id,
                                            $state_id,
                                            $city_id,
                                            $address,
                                            $zip_code,
                                            $email,
                                            $first_name,
                                            $last_name,
                                            $business_phone,
                                            $url,
                                            $contact_first_name,
                                            $contact_last_name,
                                            $contact_phone,
                                            $contact_email,
                                            $timezone,
                                            $latitude,
                                            $longitude,
                                            $is_payment
                                            ));

        }
        exit;
    }


public function mallimport() {
        // http://db.locallyepic.com/randy/mallimport
        echo "turned off";
        exit;
        $sql =" select * from import_mall";
        $result = $this->db->query($sql);
        $c = 0;
        foreach ($result->result() as $row) {
            $c++;
            echo $row->name;
            echo $row->description;
            echo $row->address;

            $first_name = "store";
            $last_name = "manager";
            $email = $row->email;
            $password = md5($row->password);
            $country_id=254;
            $state_id=162;


            $sqlcity = "select city_id,latitude,longitude from tbl_city where cid=? and state_id=? and city_name=? ";
            $rcity = $this->db->query($sqlcity, array($country_id, $state_id, $row->city));
            //$lastq = $this->db->last_query();
            //print_r($lastq);
            $resultcity = $rcity->row();



            $city_id = $resultcity->city_id;

            $zip_code = $row->zip;
            $full_address = $row->address . ', ' . $row->city . ', ' . $row->state . ', United States';
            $contact_num = $row->phone;
            $role=6;

            $qinsert = "
                insert into tbl_users
                set
                first_name = ?,
                last_name = ?,
                email = ?,
                country_id = ?,
                state_id = ?,
                city_id = ?,
                zip_code = ?,
                full_address = ?,
                contact_num = ?,
                password = ?,
                role = ?
            ";
            $rinsert = $this->db->query($qinsert, array($first_name, $last_name, $email, $country_id, $state_id, $city_id, $zip_code, $full_address, $contact_num, $password, $role));

            $user_id =$this->db->insert_id();

            echo $user_id;

            //Now we need to create the shop

            $shop_name = $row->name;
            $shop_cats = 80;
            $shop_description = $row->description;
            $shop_image = $row->logo;
            $address = $full_address;
            $business_phone = $contact_num;
            $url = $row->website;
            $contact_first_name = $row->first_name;
            $contact_last_name = $row->last_name;
            $contact_phone = $contact_num;
            $contact_email = $email;
            $timezone = 'UM5';
            $latitude = $resultcity->latitude;
            $longitude = $resultcity->longitude;
            $is_payment=0;

            $latitude = 33.6955018;
            $longitude-78.9290297;


            $qshop ="insert into tbl_shop set

                user_id=?,
                shop_name=?,
                shop_cats=?,
                shop_description=?,
                shop_image=?,
                country_id=?,
                state_id=?,
                city_id=?,
                address=?,
                zip_code=?,
                email=?,
                first_name=?,
                last_name=?,
                business_phone=?,
                url=?,
                contact_first_name=?,
                contact_last_name=?,
                contact_phone=?,
                contact_email=?,
                timezone=?,
                latitude=?,
                longitude=?,
                is_payment=?,
                is_admin=0,
                add_by=0,
                monthlyfee=199,
                blnPaidActivationFee=0,
                blnexpired=1,
                pin=1234
            ";

            $rshop = $this->db->query($qshop, array(

                                            $user_id,
                                            $shop_name,
                                            $shop_cats,
                                            $shop_description,
                                            $shop_image,
                                            $country_id,
                                            $state_id,
                                            $city_id,
                                            $address,
                                            $zip_code,
                                            $email,
                                            $first_name,
                                            $last_name,
                                            $business_phone,
                                            $url,
                                            $contact_first_name,
                                            $contact_last_name,
                                            $contact_phone,
                                            $contact_email,
                                            $timezone,
                                            $latitude,
                                            $longitude,
                                            $is_payment
                                            ));

        }
        exit;
    }

/**
 * Used for generating a random string.
 *
 * @param int $_Length  The lengtyh of the random string.
 * @return string The random string.
 */
function gfRandomString($_Length) {
    $alphabet = "abcdefghijklmnopqrstuwxyzABCDEFGHIJKLMNOPQRSTUWXYZ0123456789";
    $pass = array(); //remember to declare $pass as an array
    $alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
    for ($i = 0; $i < $_Length; $i++) {
        $n = rand(0, $alphaLength);
        $pass[] = $alphabet[$n];
    }
    return implode($pass); //turn the array into a string
}


    public function simpsonvilleimport() {

        echo "turned off";
        exit;

        $import_code ="Simpsonville 1";

        $sql =" select * from import_simpsonville where blnProcessed=0";
        $result = $this->db->query($sql);
        $c = 0;


        foreach ($result->result() as $row) {
            $c++;
            echo $row->companyname."|";
            echo $row->primaryrepresentative."|";
            echo $row->fulladdress."|<br>";

            $arrName = explode(" ", $row->primaryrepresentative);
            $first_name = $arrName[0];
            $last_name = $arrName[1];
            $email = $row->email;

            $pass = $this->gfRandomString(8);
            $password = md5($pass);

            $country_id=254;
            $state_id=162;



            $arrAddress = explode(",",$row->fulladdress);

            $city = trim($arrAddress[1]);

            echo "City:".$city."|<br>";

            if (trim($city)=='Raleigh'){

                $state_id=155;
            }

            $sqlcity = "select city_id,latitude,longitude from tbl_city where cid=? and state_id=? and city_name=? ";
            $rcity = $this->db->query($sqlcity, array($country_id, $state_id, $city));
            $lastq = $this->db->last_query();
            print_r($lastq);

            $resultcity = $rcity->row();



            $city_id = $resultcity->city_id;

            $zip_code = $arrAddress[3];
            $full_address = $row->fulladdress . ', United States';
            $contact_num = $row->primaryphone;
            $role=6;


            $sqlcat = "select cid from tbl_category where cname=?";
            $rcat = $this->db->query($sqlcat,array($row->dotgogocategory));

            $rcatresult = $rcat->row();


            $qinsert = "
                insert into tbl_users
                set
                first_name = ?,
                last_name = ?,
                email = ?,
                country_id = ?,
                state_id = ?,
                city_id = ?,
                zip_code = ?,
                full_address = ?,
                contact_num = ?,
                password = ?,
                plain_password=?,
                role = ?,
                import_code=?
            ";
            $rinsert = $this->db->query($qinsert, array($first_name, $last_name, $email, $country_id, $state_id, $city_id, $zip_code, $full_address, $contact_num, $password, $pass, $role, $import_code));

            $user_id =$this->db->insert_id();

            echo "<br>UserId:".$user_id."|<br>";

            //Now we need to create the shop

            $shop_name = $row->companyname;
            $shop_cats = $rcatresult->cid;
            $shop_description = '';
            $shop_image = $row->logo.".jpg";
            $address = $full_address;
            $business_phone = $contact_num;
            $url = $row->website;
            $contact_first_name = "Store";
            $contact_last_name = "Manager";
            $contact_phone = $contact_num;
            $contact_email = $email;
            $timezone = 'UM5';
            $latitude = $resultcity->latitude;
            $longitude = $resultcity->longitude;
            $is_payment=0;

            $qshop ="insert into tbl_shop set

                user_id=?,
                shop_name=?,
                shop_cats=?,
                shop_description=?,
                shop_image=?,
                country_id=?,
                state_id=?,
                city_id=?,
                address=?,
                zip_code=?,
                email=?,
                first_name=?,
                last_name=?,
                business_phone=?,
                url=?,
                contact_first_name=?,
                contact_last_name=?,
                contact_phone=?,
                contact_email=?,
                timezone=?,
                latitude=?,
                longitude=?,
                is_payment=?,
                is_admin=0,
                add_by=899,
                monthlyfee=99,
                blnPaidActivationFee=0,
                blnexpired=0,
                pin=1234,
                expiration_date=?,
                import_code=?
            ";

            $rshop = $this->db->query($qshop, array(

                                            $user_id,
                                            $shop_name,
                                            $shop_cats,
                                            $shop_description,
                                            $shop_image,
                                            $country_id,
                                            $state_id,
                                            $city_id,
                                            $address,
                                            $zip_code,
                                            $email,
                                            $first_name,
                                            $last_name,
                                            $business_phone,
                                            $url,
                                            $contact_first_name,
                                            $contact_last_name,
                                            $contact_phone,
                                            $contact_email,
                                            $timezone,
                                            $latitude,
                                            $longitude,
                                            $is_payment,
                                            '2016-07-31',
                                            $import_code
                                            ));
echo "update import_simpsonville set blnProcessed=1 where logo=".$row->logo."<br>";
        $update = "update import_simpsonville set blnProcessed=1 where logo=? ";
            $rcat = $this->db->query($update,array($row->logo));

        }




    }


public function fountainimport() {



        $import_code ="Fountain Inn 1";

        $sql =" select * from import_fountaininn where blnProcessed=0";
        $result = $this->db->query($sql);
        $c = 0;


        foreach ($result->result() as $row) {
            $c++;
            echo "<hr>";
            echo $row->companyname."|";
            echo $row->primaryrepresentative."|";
            echo $row->streetaddress."|<br>";

            $arrName = explode(" ", $row->primaryrepresentative);
            $first_name = $arrName[0];
            $last_name = $arrName[1];
            $email = $row->email;

            $pass = $this->gfRandomString(8);
            $password = md5($pass);

            $country_id=254;
            $state_id=162;




            $city = trim($row->city);

            echo "City:".$city."|<br>";

            if (trim($city)=='Raleigh'){

                $state_id=155;
            }

            if (trim($city)=='Cincinnati'){

                $state_id=157;
            }

            if (trim($city)=='Arlington Heights'){

                $state_id=135;
            }

            $sqlcity = "select city_id,latitude,longitude from tbl_city where cid=? and state_id=? and city_name=? ";
            $rcity = $this->db->query($sqlcity, array($country_id, $state_id, $city));
            $lastq = $this->db->last_query();
            print_r($lastq);

            $resultcity = $rcity->row();



            $city_id = $resultcity->city_id;

            $zip_code = $row->zip;
            $full_address = $row->streetaddress.", ".$row->city.", ".$row->state."  ".$row->zip . ', United States';
            $contact_num = $row->primaryphone;
            $role=6;


            $sqlcat = "select cid from tbl_category where cname=?";
            $rcat = $this->db->query($sqlcat,array($row->dotgogocategory));

            $rcatresult = $rcat->row();


            $qinsert = "
                insert into tbl_users
                set
                first_name = ?,
                last_name = ?,
                email = ?,
                country_id = ?,
                state_id = ?,
                city_id = ?,
                zip_code = ?,
                full_address = ?,
                contact_num = ?,
                password = ?,
                plain_password=?,
                role = ?,
                import_code=?
            ";
            $rinsert = $this->db->query($qinsert, array($first_name, $last_name, $email, $country_id, $state_id, $city_id, $zip_code, $full_address, $contact_num, $password, $pass, $role, $import_code));

            $user_id =$this->db->insert_id();

            echo "<br>UserId:".$user_id."|<br>";

            //Now we need to create the shop

            $shop_name = $row->companyname;
            $shop_cats = $rcatresult->cid;
            $shop_description = '';
            $shop_image = $row->logo.".jpg";
            $address = $full_address;
            $business_phone = $contact_num;
            $url = $row->website;
            $contact_first_name = "Store";
            $contact_last_name = "Manager";
            $contact_phone = $contact_num;
            $contact_email = $email;
            $timezone = 'UM5';
            $latitude = $resultcity->latitude;
            $longitude = $resultcity->longitude;
            $is_payment=0;

            $qshop ="insert into tbl_shop set

                user_id=?,
                shop_name=?,
                shop_cats=?,
                shop_description=?,
                shop_image=?,
                country_id=?,
                state_id=?,
                city_id=?,
                address=?,
                zip_code=?,
                email=?,
                first_name=?,
                last_name=?,
                business_phone=?,
                url=?,
                contact_first_name=?,
                contact_last_name=?,
                contact_phone=?,
                contact_email=?,
                timezone=?,
                latitude=?,
                longitude=?,
                is_payment=?,
                is_admin=0,
                add_by=900,
                monthlyfee=99,
                blnPaidActivationFee=0,
                blnexpired=0,
                pin=1234,
                expiration_date=?,
                import_code=?
            ";

            $rshop = $this->db->query($qshop, array(

                                            $user_id,
                                            $shop_name,
                                            $shop_cats,
                                            $shop_description,
                                            $shop_image,
                                            $country_id,
                                            $state_id,
                                            $city_id,
                                            $address,
                                            $zip_code,
                                            $email,
                                            $first_name,
                                            $last_name,
                                            $business_phone,
                                            $url,
                                            $contact_first_name,
                                            $contact_last_name,
                                            $contact_phone,
                                            $contact_email,
                                            $timezone,
                                            $latitude,
                                            $longitude,
                                            $is_payment,
                                            '2016-07-31',
                                            $import_code
                                            ));
echo "update import_fountaininn set blnProcessed=1 where logo=".$row->logo."<br>";
        $update = "update import_fountaininn set blnProcessed=1 where logo=? ";
            $rcat = $this->db->query($update,array($row->logo));

        }




    }


}