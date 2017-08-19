<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class site extends CI_Controller
{

    private $data;

    function site()
    {

        parent::__construct();


        $this->load->model('m_common');
        $this->load->helper('date');
        $this->load->model('appuser');
        //date_default_timezone_set('America/Los_Angeles');
        $this->set_custom_tz();
        $this->check_login();
        $this->user_id = $this->session->userdata('user_id');
        $user_info = $this->m_common->db_select("*", "tbl_users", array("user_id" => $this->user_id), '', '', '', array(1, 0), 'row_array');
        $task_info = $this->m_common->db_select("*", "tbl_task", array("task_by" => $this->user_id), array(), '`task_id` DESC');
        $message_info = $this->msg_info();
        $this->data = array(
            "user_info" => $user_info,
            "task_info" => $task_info,
            "message_info" => $message_info,
            "current_page" => $this->uri->segment(2),
        );
        $unread = $this->check_unread();
        $this->data['unread'] = $unread;


        if($this->data['user_info']['role'] == 9){
            $q_result= $this->m_common->db_select("*,shop_id", "tbl_users_shops", array("corporate_user_id" => $this->data['user_info']['user_id']), 'shop_id', '', '', '', 'rows_array');
            $this->data['total_user_business'] = count($q_result);
            $this->data['user_business_id'] = $q_result[0]['shop_id'];

            // $q_result= $this->m_common->db_select("*,shop_id", "tbl_users_shops", array("corporate_user_id" => $this->data['user_info']['user_id']), '', '', '', array(1, 0), 'row_array');
            // $this->data['total_user_business'] = $q_result['total_business'];
            // $this->data['user_business_id'] = $q_result['shop_id'];

            // echo "<pre>";
            // print_r($q_result);
            // exit();


        }



    }

    function msg_info($limit = 10, $offset = 0)
    {
        $id = $this->data['user_info']['user_id'];
        $wh = "";
        $wh .= " AND t1.message_to LIKE '%$id%'";
        $lim = "LIMIT " . $offset . "," . $limit;
        $q = "select t1.*,t2.* from tbl_message t1 join tbl_users t2 on t1.message_from = t2.user_id where 1=1 $wh ORDER BY t1.message_id DESC $lim";
        $message_info = $this->m_common->select_custom($q);
        return $message_info;
    }

    function list_message_header_ajax()
    {
        $id = $this->data['user_info']['user_id'];
        $limit = 10;
        $offset = $_POST['offset'];
        $lim = "LIMIT " . $offset . "," . $limit;
        $wh = "";
        $str = "";
        $wh .= " AND t1.message_to LIKE '%$id%'";
        $q = "select t1.*,t2.* from tbl_message t1 join tbl_users t2 on t1.message_from = t2.user_id where 1=1 $wh ORDER BY t1.message_id DESC $lim";

        $message_info = $this->m_common->select_custom($q);
        if (!empty($message_info)) {
            foreach ($message_info as $v) {
                $str .= '<li><a href="' . base_url() . 'site/list_message"><div class="row"><div class="col-xs-2"><img class="img-circle" src="' . base_url() . 'uploads/user/' . $v["profile_pic"] . '" alt="" style="width: 190%;"></div><div class="col-xs-10"><p><strong>' . $v["first_name"] . " " . $v["last_name"] . '</strong>: ' . $v["subject"] . '...</p><p class="small"><i class="fa fa-clock-o"></i> ' . $v["message_date"] . '</p></div></div></a></li>';

            }
            $str . "<li class='last_msg'>Loading...</li>";
        }
        echo $str;
    }

    function check_unread()
    {

        $id = $this->data['user_info']['user_id'];
        $wh = "";
        $wh .= "message_to LIKE '%$id%'";
        $message_info = $this->m_common->db_select("*", "tbl_message", $wh);
        $p = count($message_info);
        $count = 0;
        for ($i = 0; $i < $p; $i++) {
            $t = explode(",", $message_info[$i]['who_open']);

            if (in_array($this->data['user_info']['user_id'], $t)) {
                $count;
            } else {
                $count++;
            }
        }
        return $count;
    }

    public function index()
    {
        if ((is_permission($this->data['user_info']['role'], "index")) == FALSE) {
            echo "You Don't have permission to access this page";
            exit;
        }
        //echo "<pre>";print_r($user_info);exit;
        //echo $current_day = date("D");exit;
        $country = $this->m_common->db_select("*", "tbl_country", array(), array(), '`order` DESC,`name` asc', '', '', 'all');
        $state = array();
        $city = array();
        $this->wh_serch = array();
        $this->wh_in_search = array();
        $filter_role = 0;
        $filter_by_user = 0;

        if (isset($_GET['filter_by_user']) && isset($_GET['id']) && $_GET['filter_by_user'] == 1) {

            $user_id = $_GET['id'];
            if ($user_id == 0) {
                $user_id = 1;
            }
            $filter_user = $this->m_common->db_select("country_id,state_id,city_id,zip_code,user_id,role", "tbl_users", array("user_id" => $user_id), array(), '', '', array(1, 0), 'row_array');
            $filter_role = $filter_user['role'];
            $filter_by_user = $filter_user['user_id'];
            if ($filter_user['role'] == 2) {

                $this->wh_serch['country_id'] = $filter_user['country_id'];
            } else if ($filter_user['role'] == 3) {
                $this->wh_serch['state_id'] = $filter_user['state_id'];

            } else if ($filter_user['role'] == 4) {
                $this->wh_serch['zip_code'] = $filter_user['zip_code'];
            } else if ($filter_user['role'] == 5) {
                $this->wh_serch['add_by'] = $filter_user['user_id'];
            } else if ($filter_user['role'] == 6) {
                $this->wh_serch['user_id'] = $filter_user['user_id'];
            }

        } else {

            if (isset($_GET['scountry']) && $_GET['scountry'] > 0) {
                $state = $this->m_common->db_select("*", "tbl_state", array("cid" => $_GET['scountry']));
                $this->wh_serch['country_id'] = $_GET['scountry'];
            }
            if (isset($_GET['sstate']) && $_GET['sstate'] > 0) {
                $this->wh_serch['state_id'] = $_GET['sstate'];
                $city = $this->m_common->db_select("*", "tbl_city", array("state_id" => $_GET['sstate']));
            }
            if (isset($_GET['scity']) && $_GET['scity'] > 0) {
                $this->wh_serch['city_id'] = $_GET['scity'];
            }
            if (isset($_GET['zip_code']) && $_GET['zip_code'] > 0) {
                $this->wh_serch['zip_code'] = $_GET['zip_code'];
            }
            if ($this->data['user_info']['role'] == 2) {

                $this->wh_serch['country_id'] = $this->data['user_info']['country_id'];
            } else if ($this->data['user_info']['role'] == 3) {
                $this->wh_serch['state_id'] = $this->data['user_info']['state_id'];

            } else if ($this->data['user_info']['role'] == 4) {
                $this->wh_serch['zip_code'] = $this->data['user_info']['zip_code'];
            } else if ($this->data['user_info']['role'] == 5) {
                $this->wh_serch['add_by'] = $this->data['user_info']['user_id'];
            } else if ($this->data['user_info']['role'] == 6) {

                if($this->data['user_info']['is_corporate_business_user'] == 1){
                    $user_shop_id = $this->get_user_shop_id($this->data['user_info']['user_id']);
                    $this->wh_serch['shop_id'] = $user_shop_id->shop_id;
                }else{
                    $this->wh_serch['user_id'] = $this->data['user_info']['user_id'];
                }

            }
            else if ($this->data['user_info']['role'] == 9) {
                
                if (isset($_GET['sbid']) && $_GET['sbid'] != '') {

                    if($_GET['sbid'] == 0){
                        $this->wh_in_search['shop_id'] = $this->get_corporate_business_id($this->data['user_info']['user_id']);
                    }else{
                        $this->wh_serch['shop_id'] = $_GET['sbid'];
                    }
                }

            }


        }

        //role : 9 =corporate User
        if ($this->data['user_info']['role'] == 9) {

            $user_id = $this->data['user_info']['user_id'];

            if (!isset($_GET['sbid'])) {
               $this->wh_in_search['shop_id'] = $this->get_corporate_business_id($user_id);
            }

            // $q = "SELECT t1.* FROM tbl_users_shops t1 WHERE t1.corporate_user_id = '$user_id'";
            // $this->data['corporate_business_list'] = $this->m_common->select_custom($q);

            $this->data['corporate_business_list'] = $this->get_corporate_business($user_id);
        }

//        echo "<pre>";
//        print_r($this->data['corporate_business_list']);
//        exit();


        $id = $this->data['user_info']['user_id'];
        $date = date('Y-m-d');

        $q = "SELECT * FROM tbl_task WHERE `end_date` LIKE  '$date%' AND  `bussiness_id` =$id OR `end_date` LIKE  '$date%' AND `task_by` =$id";
        $this->data['task_to_do'] = $this->m_common->select_custom($q);

        $this->data['country'] = $country;
        $this->data['state'] = $state;
        $this->data['city'] = $city;

        $this->data['bsignup_today'] = $this->get_tot_bsignup('today');
        $this->data['bsignup_mtd'] = $this->get_tot_bsignup('MTD');
        $this->data['bsignup_ytd'] = $this->get_tot_bsignup('YTD');

        $this->data['pnotes_today'] = $this->get_tot_pnotes('today');
        $this->data['pnotes_mtd'] = $this->get_tot_pnotes('MTD');
        $this->data['pnotes_ytd'] = $this->get_tot_pnotes('YTD');

        $this->data['deal_created_today'] = $this->get_tot_deal_created('today');
        $this->data['deal_created_mtd'] = $this->get_tot_deal_created('MTD');
        $this->data['deal_created_ytd'] = $this->get_tot_deal_created('YTD');

        $this->data['deal_activated_today'] = $this->get_tot_deal_activated('today');
        $this->data['deal_activated_mtd'] = $this->get_tot_deal_activated('MTD');
        $this->data['deal_activated_ytd'] = $this->get_tot_deal_activated('YTD');


        $this->data['newapp_today'] = $this->get_tot_newapp('today');
        $this->data['newapp_mtd'] = $this->get_tot_newapp('YTD');
        $this->data['newapp_ytd'] = $this->get_tot_newapp('MTD');

        $this->data['deal_used'] = $this->get_tot_deal_used();
        $this->data['deal_shared'] = $this->get_tot_deal_shared();

        $this->data['messages_sent_today'] = $this->get_tot_messagesent('today');
        $this->data['messages_sent_mtd'] = $this->get_tot_messagesent('MTD');
        $this->data['messages_sent_ytd'] = $this->get_tot_messagesent('YTD');

        $this->data['filter_by_user'] = $filter_by_user;
        $this->data['filter_role'] = $filter_role;

        $q = "select first_name,last_name,user_id,role from tbl_users where role in (2,3,4,5)";
        $this->data['users'] = $this->m_common->select_custom($q);
        //echo "<pre>";print_r($this->data);exit;
        $this->load->view('header', $this->data);
        $this->load->view('sidebar', $this->data);
        $this->load->view('home', $this->data);
    }


    public function test()
    {
        echo "<pre>";
        print_r($_SERVER);
        echo '<br/>';
        print_r(phpinfo());
        exit;
    }

    public function password()
    {
        $password = '$2y$04$usesomesillystringfore7hnbRJHxXVLeakoG8K30oukPsA.ztMG';
        $test = "hello";
        $hash = password_hash($password, PASSWORD_BCRYPT);
        echo $test;
        echo '<br/>';
        echo $hash;
        echo "<br/>";
        $reuse = password_verify($password, $hash);
        echo $reuse;
        echo "\n";
    }

    public function set_custom_tz()
    {
        /*

        $ip = $_SERVER['REMOTE_ADDR']; // the IP address to query
        $query = @unserialize(file_get_contents('http://ip-api.com/php/' . $ip));
        if ($query && $query['status'] == 'success') {
            @date_default_timezone_set($query['timezone']);
            //echo 'Hello visitor from ' . $query['timezone'] . ', ' . $query['city'] . '!';
        }
        */

    }


    public function add_sales_manager()
    {
        if ((is_permission($this->data['user_info']['role'], "add_sales_manager")) == FALSE) {
            echo "You Don't have permission to access this page";
            exit;
        }

        $message = "";

        if (isset($_POST['add_sales_manager'])) {
//            echo "<pre>";print_r($_POST);exit;
            $this->load->library('form_validation');
            $this->form_validation->set_error_delimiters('<span for="required" generated="true" class="help-inline" style="color:red;"> *', '</span>');
            $this->form_validation->set_rules('first_name', 'First Name', 'required');
            $this->form_validation->set_rules('last_name', 'Last Name', 'required');
            $this->form_validation->set_rules('email', 'Email', 'required|valid_email|is_unique[tbl_users.email]');
            $this->form_validation->set_rules('scountry', 'Country', 'required');
            $this->form_validation->set_rules('sstate', 'State', 'required');
            $this->form_validation->set_rules('scity', 'City', 'required');
            $this->form_validation->set_rules('zip_code', 'zip code', 'trim|required');
            $this->form_validation->set_rules('password', 'Password', 'required|min_length[8]|max_length[32]');
            $this->form_validation->set_rules('password1', 'Password', 'required|callback_confirm_password');
            $this->form_validation->set_rules('role', 'Manager Role', 'required');
            $this->form_validation->set_rules('profile_pic', 'Profile Image', '');


            if ($this->form_validation->run() == TRUE) {

                if (!isset($_POST['perent_id'])) {
                    $_POST['perent_id'] = 0;
                }
                if (!isset($_POST['birthday'])) {
                    $_POST['birthday'] = "00-00";
                }
                if (!isset($_POST['bank_acount_num'])) {
                    $_POST['bank_acount_num'] = 0;
                }
                if (!isset($_POST['bank_routing_num'])) {
                    $_POST['bank_routing_num'] = 0;
                }
                if (!isset($_POST['full_address'])) {
                    $_POST['full_address'] = " ";
                }
                if (!isset($_POST['contact_num'])) {
                    $_POST['contact_num'] = 0;
                }


                $inn = array(
                    "first_name" => $this->input->post('first_name'),
                    "last_name" => $this->input->post('last_name'),
                    "birthday" => "0000-" . $_POST['birthday'],
                    "contact_num" => $_POST['contact_num'],
                    //"user_name" => $this->input->post('user_name'),
                    "country_id" => $this->input->post('scountry'),
                    "state_id" => $this->input->post('sstate'),
                    "city_id" => $this->input->post('scity'),
                    "bank_acount_num" => $_POST['bank_acount_num'],
                    "bank_routing_num" => $_POST['bank_routing_num'],
                    "full_address" => $_POST['full_address'],
                    "zip_code" => $this->input->post('zip_code'),
                    "email" => $this->input->post('email'),
                    "password" => md5($this->input->post('password')),
                    "role" => $this->input->post('role'),
                    "perent_id" => $_POST['perent_id']
                );
                $temp = $this->m_common->insert_entry("tbl_users", $inn, 1);
                if ($temp['last_id'] > 0) {
                    //$message = "Deal " . $this->input->post('deal_title') . " successfully inserted";
                    $message = "Sales Manager  Successfully Added";
                    if (isset($_FILES['profile_pic'])) {
                        $r = $this->file_upload($_FILES['profile_pic'], $temp['last_id'], 1);
                        if (!empty($r)) {
                            $up = array(
                                "profile_pic" => $r,
                            );
                            $this->m_common->update_entry("tbl_users", $up, array("user_id" => $temp['last_id']));
                        }
                    }
                    $message = "Sales Manager " . $inn['first_name'] . " " . $inn['last_name'] . " has successfully added";
                    $this->session->set_userdata('current_message', $message);
                    redirect('site/list_sales_manager', 'refresh');
                }
            }
        }

        $data = array(
            'message' => $message,
        );
        $cats = $this->m_common->db_select("*", "tbl_category", array(), array(), 'cname ASC', '', '', 'all');
        $country = $this->m_common->db_select("*", "tbl_country", array(), array(), '`order` DESC,`name` asc', '', '', 'all');
        $state = array();
        $city = array();
        //echo "<pre>";print_r($country);exit;
        // change 22/12/2014
        if ((isset($_POST['scountry']) && $_POST['scountry'] > 0)) {
            $state = $this->m_common->db_select("*", "tbl_state", array("cid" => $_POST['scountry']));
            if ((isset($_POST['sstate']) && $_POST['sstate'] > 0)) {
                $city = $this->m_common->db_select("*", "tbl_city", array("state_id" => $_POST['sstate']));
            }
        }

        $this->data['cats'] = $cats;
        $this->data['country'] = $country;
        $this->data['state'] = $state;
        $this->data['city'] = $city;
        $this->load->view('header', $this->data);
        $this->load->view('sidebar', $this->data);
        $this->load->view('add_sales_manager', $this->data);
    }

    public function list_sales_manager()
    {
        if ((is_permission($this->data['user_info']['role'], "list_sales_manager")) == FALSE) {
            echo "You Don't have permission to access this page";
            exit;
        }

        $page_no = 1;
        $wh = "";
        $search = "";
        $country = array();
        $state = array();
        $city = array();
        $wh_state = array();
        $wh_city = array();
        $wh_country = array();

        if (isset($_GET['scountry']) && $_GET['scountry'] > 1) {
            $wh = " and country_id = " . $_GET['scountry'];
            $wh_country = array("id" => $_GET['scountry']);
        }
        if (isset($_GET['sstate']) && $_GET['sstate'] > 1) {

            $wh_state = array("sid" => $_GET['sstate']);
            $wh = " and state_id = " . $_GET['sstate'];
        }
        if (isset($_GET['scity']) && $_GET['scity'] > 1) {
            $wh_city = array("city_id" => $_GET['scity']);
            $wh = " and city_id = " . $_GET['scity'];
        }

        $country = $this->m_common->db_select("*", "tbl_country", $wh_country, array(), '`order` DESC,`name` asc', '', '', 'all');
        //$state = $this->m_common->db_select("*", "tbl_state", $wh_state,array(), 'state_name asc', '', '', 'all');
        //$city = $this->m_common->db_select("*", "tbl_city", $wh_city,array(), 'city_name asc', '', '', 'all');

        if (isset($_GET['role']) && $_GET['role'] > 1) {
            $wh .= " and role = " . $_GET['role'];
        } else {
            $wh .= " and role in (2,3,4) ";
        }


        $page_row_limit = isset($_GET['perpage']) ? $_GET['perpage'] : 25;
        if (isset($_GET['search']) && !empty($_GET['search'])) {
            $search = $_GET['search'];
            if (str_word_count($search) < 2) {
                $wh .= " and ( email like '%$search%' ) ";
            } else {
                $exsearch = explode(" ", $search);
                $first_name = $exsearch[0];
                $lastname = $exsearch[1];
                $wh .= " and ( first_name like '%$first_name%' or  last_name like '%$lastname%' ) ";
            }
        }

        $q = "select count(user_id) as cnt from tbl_users where 1=1 $wh limit 1";
        $res_tb = $this->m_common->select_custom($q);
        // echo "<pre>";print_r($res_tb);exit;
        $tot_rows = $res_tb[0]['cnt'];


        $tot_page = ceil($tot_rows / $page_row_limit);
        if (isset($_GET['page_no']) && $_GET['page_no'] > 0) {
            $page_no = $_GET['page_no'];
        }
        $offset = ($page_no * $page_row_limit) - $page_row_limit;


        $q = "select * from tbl_users where 1=1 $wh group by user_id order by user_id ASC limit $offset,$page_row_limit";
        $info = $this->m_common->select_custom($q);


        $prev = ($page_no - 6);
        if ($prev <= 0) {
            $prev = 1;
        }
        $next = ($page_no + 6);
        if ($next >= $tot_page) {
            $next = $tot_page;
        }

        $this->data['search'] = $search;
        $this->data['country'] = $country;
        $this->data['state'] = $state;
        $this->data['city'] = $city;

        $this->data['info'] = $info;
        $this->data['tot_page'] = $tot_page;
        $this->data['curr_page'] = $page_no;
        $this->data['prev'] = $prev;
        $this->data['next'] = $next;
        $this->data['page_row_limit'] = $page_row_limit;

        $this->load->view('header', $this->data);
        $this->load->view('sidebar', $this->data);
        $this->load->view('list_sales_manager', $this->data);
    }

    function edit_sales_manager()
    {
        if ((is_permission($this->data['user_info']['role'], "edit_sales_manager")) == FALSE) {
            echo "You Don't have permission to access this page";
            exit;
        }

        $id = 0;
        $message = "";
        if (isset($_POST['edit_sales_manager'])) {
            $id = $this->input->post('user_id');
            $this->userid = $id;
            //echo "<pre>";print_r($_POST);exit;
            $this->load->library('form_validation');
            $this->form_validation->set_error_delimiters('<span for="required" generated="true" class="help-inline" style="color:red;"> *', '</span>');


            $this->form_validation->set_rules('user_id', 'User Identification', 'required');
            $this->form_validation->set_rules('first_name', 'First Name', 'required');
            $this->form_validation->set_rules('last_name', 'Last Name', 'required');
            $this->form_validation->set_rules('email', 'Email', 'required|valid_email|callback_sm_email_unique');
            $this->form_validation->set_rules('scountry', 'Country', 'required');
            $this->form_validation->set_rules('sstate', 'State', 'required');
            $this->form_validation->set_rules('scity', 'City', 'required');
            $this->form_validation->set_rules('zip_code', 'zip code', 'trim|required');
            if (!empty($_POST['password'])) {
                $this->form_validation->set_rules('password', 'Password', 'required|min_length[8]|max_length[32]');
            }
            $this->form_validation->set_rules('password1', 'Password', 'callback_confirm_password');
            $this->form_validation->set_rules('role', 'Manager Role', 'required');
            $this->form_validation->set_rules('profile_pic', 'Profile Image', '');


            if ($this->form_validation->run() == TRUE) {

                if (!isset($_POST['perent_id'])) {
                    $_POST['perent_id'] = 0;
                }
                if (!isset($_POST['birthday'])) {
                    $_POST['birthday'] = "00-00";
                }
                if (!isset($_POST['bank_acount_num'])) {
                    $_POST['bank_acount_num'] = 0;
                }
                if (!isset($_POST['bank_routing_num'])) {
                    $_POST['bank_routing_num'] = 0;
                }
                if (!isset($_POST['full_address'])) {
                    $_POST['full_address'] = " ";
                }
                if (!isset($_POST['contact_num'])) {
                    $_POST['contact_num'] = 0;
                }
                $up = array(
                    "first_name" => $this->input->post('first_name'),
                    "last_name" => $this->input->post('last_name'),
                    "birthday" => "0000-" . $_POST['birthday'],
                    "bank_acount_num" => $_POST['bank_acount_num'],
                    "bank_routing_num" => $_POST['bank_routing_num'],
                    "full_address" => $_POST['full_address'],
                    "contact_num" => $_POST['contact_num'],
                    //"user_name" => $this->input->post('user_name'),
                    "country_id" => $this->input->post('scountry'),
                    "state_id" => $this->input->post('sstate'),
                    "city_id" => $this->input->post('scity'),
                    "zip_code" => $this->input->post('zip_code'),
                    "email" => $this->input->post('email'),
                    "role" => $this->input->post('role'),
                    "perent_id" => $_POST['perent_id']
                );

                if (!empty($_POST['password'])) {
                    $up['password'] = md5($this->input->post('password'));
                }
                //echo "<pre>";print_r($_FILES);
                if (isset($_FILES['profile_pic'])) {
                    $r = $this->file_upload($_FILES['profile_pic'], $id, 1);
                    if (!empty($r)) {

                        $up['profile_pic'] = $r;
                    }
                }
                $this->m_common->update_entry("tbl_users", $up, array("user_id" => $id));
                $message = "Sales Manager " . $up['first_name'] . " " . $up['last_name'] . " has successfully updated";
                $this->session->set_userdata('current_message', $message);
                redirect('site/list_sales_manager', 'refresh');
            }
        }
        if (!$id) {
            if (isset($_GET['id'])) {
                $id = $_GET['id'];
            } else {
                redirect('/site/index', 'refresh');
            }
        }

        $info = $this->m_common->db_select("*", "tbl_users", array("user_id" => $id), array(), '', '', array(1, 0), 'row_array');
        $country = $this->m_common->db_select("*", "tbl_country", array(), array(), '`order` DESC,`name` asc', '', '', 'all');

        $state = $this->m_common->db_select("*", "tbl_state", array("cid" => $info['country_id']), array(), '', '', '', 'all');

        $city = $this->m_common->db_select("*", "tbl_city", array("state_id" => $info['state_id']), array(), '', '', '', 'all');

        $this->data['country'] = $country;
        $this->data['state'] = $state;
        $this->data['city'] = $city;
        //echo "<pre>";print_r($info);exit;
        $this->data['info'] = $info;

        $this->load->view('header', $this->data);
        $this->load->view('sidebar', $this->data);
        $this->load->view('edit_sales_manager', $this->data);
    }

    public function add_sales_people()
    {
        if ((is_permission($this->data['user_info']['role'], "add_sales_people")) == FALSE) {
            echo "You Don't have permission to access this page";
            exit;
        }
        $message = "";

        if (isset($_POST['add_sales_people'])) {
            //echo "<pre>";print_r($_POST);exit;
            $this->load->library('form_validation');
            $this->form_validation->set_error_delimiters('<span for="required" generated="true" class="help-inline" style="color:red;"> *', '</span>');


            $this->form_validation->set_rules('first_name', 'First Name', 'required');
            $this->form_validation->set_rules('last_name', 'Last Name', 'required');
            $this->form_validation->set_rules('email', 'Email', 'required|valid_email|is_unique[tbl_users.email]');
            $this->form_validation->set_rules('scountry', 'Country', 'required');
            $this->form_validation->set_rules('sstate', 'State', 'required');
            $this->form_validation->set_rules('scity', 'City', 'required');
            $this->form_validation->set_rules('zip_code', 'zip code', 'trim|required');
            $this->form_validation->set_rules('password', 'Password', 'required|min_length[8]|max_length[32]');
            $this->form_validation->set_rules('password1', 'Password', 'required|callback_confirm_password');
            $this->form_validation->set_rules('role', 'Manager Role', 'required');
            $this->form_validation->set_rules('profile_pic', 'Profile Image', '');


            if ($this->form_validation->run() == TRUE) {

                if (!isset($_POST['perent_id'])) {
                    $_POST['perent_id'] = 0;
                }
                if (!isset($_POST['birthday'])) {
                    $_POST['birthday'] = "00-00";
                }
                if (!isset($_POST['bank_acount_num'])) {
                    $_POST['bank_acount_num'] = 0;
                }
                if (!isset($_POST['bank_routing_num'])) {
                    $_POST['bank_routing_num'] = 0;
                }
                if (!isset($_POST['full_address'])) {
                    $_POST['full_address'] = " ";
                }
                if (!isset($_POST['contact_num'])) {
                    $_POST['contact_num'] = 0;
                }

                $inn = array(
                    "first_name" => $this->input->post('first_name'),
                    "last_name" => $this->input->post('last_name'),
                    "birthday" => "0000-" . $_POST['birthday'],
                    "bank_acount_num" => $_POST['bank_acount_num'],
                    "bank_routing_num" => $_POST['bank_routing_num'],
                    "full_address" => $_POST['full_address'],
                    "contact_num" => $_POST['contact_num'],
                    //"user_name" => $this->input->post('user_name'),
                    "email" => $this->input->post('email'),
                    "password" => md5($this->input->post('password')),
                    "role" => $this->input->post('role'),
                    "country_id" => $this->input->post('scountry'),
                    "state_id" => $this->input->post('sstate'),
                    "city_id" => $this->input->post('scity'),
                    "zip_code" => $this->input->post('zip_code'),
                    "perent_id" => $_POST['perent_id']
                );

                $temp = $this->m_common->insert_entry("tbl_users", $inn, 1);
                if ($temp['last_id'] > 0) {
                    //$message = "Deal " . $this->input->post('deal_title') . " successfully inserted";
                    $message = "Sales Manager  Successfully Added";
                    if (isset($_FILES['profile_pic'])) {
                        $r = $this->file_upload($_FILES['profile_pic'], $temp['last_id'], 1);
                        if (!empty($r)) {
                            $up = array(
                                "profile_pic" => $r,
                            );
                            $this->m_common->update_entry("tbl_users", $up, array("user_id" => $temp['last_id']));
                        }
                    }
                    $message = "Sales Manager " . $inn['first_name'] . " " . $inn['last_name'] . " has successfully added";
                    $this->session->set_userdata('current_message', $message);
                    redirect('site/list_sales_people', 'refresh');
                }
            }
        }
        $country = $this->m_common->db_select("*", "tbl_country", array(), array(), '`order` DESC,`name` asc', '', '', 'all');

        $state = array();
        $city = array();
        //echo "<pre>";print_r($country);exit;
        // change 22/12/2014
        if ((isset($_POST['scountry']) && $_POST['scountry'] > 0)) {
            $state = $this->m_common->db_select("*", "tbl_state", array("cid" => $_POST['scountry']));
            if ((isset($_POST['sstate']) && $_POST['sstate'] > 0)) {
                $city = $this->m_common->db_select("*", "tbl_city", array("state_id" => $_POST['sstate']));
            }
        }

        $q_am = "select user_id,first_name,last_name from tbl_users where role in (3,4) order by first_name";
        $manager = $this->m_common->select_custom($q_am);

        $this->data['country'] = $country;
        $this->data['manager'] = $manager;
        $this->data['state'] = $state;
        $this->data['city'] = $city;
        $data = array(
            'message' => $message,
        );
        $this->load->view('header', $this->data);
        $this->load->view('sidebar', $this->data);
        $this->load->view('add_sales_people', $this->data);
    }

    public function list_sales_people()
    {
        if ((is_permission($this->data['user_info']['role'], "list_sales_people")) == FALSE) {
            echo "You Don't have permission to access this page";
            exit;
        }

        $page_no = 1;
        $wh = " and role=5 ";


        $country = array();
        $state = array();
        $city = array();
        $wh_state = array();
        $wh_city = array();
        $wh_country = array();


        if ($this->data['user_info']['role'] == 2) {
            $wh .= " and country_id = " . $this->data['user_info']['country_id'];
            $wh_country = array("id" => $this->data['user_info']['country_id']);
            $country = $this->m_common->db_select("*", "tbl_country", $wh_country, array(), '`order` DESC,`name` asc', '', '', 'all');
        } else if ($this->data['user_info']['role'] == 3) {
            $wh .= " and country_id = " . $this->data['user_info']['country_id'] . " and state_id = " . $this->data['user_info']['state_id'];
            $wh_country = array("id" => $this->data['user_info']['country_id']);
            $wh_state = array("sid" => $this->data['user_info']['state_id']);
            $wh_city = array("state_id" => $this->data['user_info']['state_id']);
            $country = $this->m_common->db_select("*", "tbl_country", $wh_country, array(), '`order` DESC,`name` asc', '', '', 'all');
            $state = $this->m_common->db_select("*", "tbl_state", $wh_state, array(), 'state_name asc', '', '', 'all');
        } else if ($this->data['user_info']['role'] == 4) {
            $wh .= " and country_id = " . $this->data['user_info']['country_id'] . " and state_id = " . $this->data['user_info']['state_id'] . " and zip_code = " . $this->data['user_info']['zip_code'];
            $wh_country = array("id" => $this->data['user_info']['country_id']);
            $wh_state = array("sid" => $this->data['user_info']['state_id']);
            $wh_city = array("city_id" => $this->data['user_info']['city_id']);
            $country = $this->m_common->db_select("*", "tbl_country", $wh_country, array(), '`order` DESC,`name` asc', '', '', 'all');
            $state = $this->m_common->db_select("*", "tbl_state", $wh_state, array(), 'state_name asc', '', '', 'all');
            $city = $this->m_common->db_select("*", "tbl_city", $wh_city, array(), 'city_name asc', '', '', 'all');
        } else {
            $country = $this->m_common->db_select("*", "tbl_country", array(), array(), '`order` DESC,`name` asc', '', '', 'all');
        }


        if (isset($_GET['zip_code']) && !empty($_GET['zip_code'])) {
            $zip_code = $_GET['zip_code'];
            $wh .= " and ( zip_code = $zip_code ) ";
        }
        if (isset($_GET['scountry']) && ($_GET['scountry'] > 0)) {
            $scountry = $_GET['scountry'];
            $wh .= " and ( country_id = $scountry ) ";
        }
        if (isset($_GET['sstate']) && ($_GET['sstate'] > 0)) {
            $sstate = $_GET['sstate'];
            $wh .= " and ( state_id = $sstate ) ";
        }
        if (isset($_GET['scity']) && ($_GET['scity'] > 0)) {
            $scity = $_GET['scity'];
            $wh .= " and ( city_id = $scity ) ";
        }
        if (isset($_GET['by_area_manager']) && ($_GET['by_area_manager'] > 0)) {
            $by_area_manager = $_GET['by_area_manager'];
            $wh .= " and ( perent_id= $by_area_manager ) ";
        }

        $page_row_limit = isset($_GET['perpage']) ? $_GET['perpage'] : 25;
        if (isset($_GET['search']) && !empty($_GET['search'])) {

            $search = $_GET['search'];
            if (str_word_count($search) > 2) {
                $wh .= " and ( email like '%$search%' ) ";
            } else {
                $exsearch = explode(" ", $search);
                $first_name = $exsearch[0];
                $lastname = $exsearch[1];
                $wh .= " and ( first_name like '%$first_name%' or  last_name like '%$lastname%' ) ";
            }
        }

        $q = "select count(user_id) as cnt from tbl_users where 1=1 $wh limit 1";
        $res_tb = $this->m_common->select_custom($q);
        // echo "<pre>";print_r($res_tb);exit;
        $tot_rows = $res_tb[0]['cnt'];


        $tot_page = ceil($tot_rows / $page_row_limit);
        if (isset($_GET['page_no']) && $_GET['page_no'] > 0) {
            $page_no = $_GET['page_no'];
        }
        $offset = ($page_no * $page_row_limit) - $page_row_limit;


        $q = "select * from tbl_users where 1=1 $wh group by user_id order by user_id ASC limit $offset,$page_row_limit";
        $info = $this->m_common->select_custom($q);

        //echo "<pre>";print_r($info);exit;

        $prev = ($page_no - 6);
        if ($prev <= 0) {
            $prev = 1;
        }
        $next = ($page_no + 6);
        if ($next >= $tot_page) {
            $next = $tot_page;
        }

        $q_am = "select user_id,first_name,last_name from tbl_users where role in (3,4) order by first_name";
        $area_manager = $this->m_common->select_custom($q_am);

        $this->data['country'] = $country;
        $this->data['state'] = $state;
        $this->data['city'] = $city;
        $this->data['area_manager'] = $area_manager;

        $this->data['info'] = $info;
        $this->data['tot_page'] = $tot_page;
        $this->data['curr_page'] = $page_no;
        $this->data['prev'] = $prev;
        $this->data['next'] = $next;
        $this->data['page_row_limit'] = $page_row_limit;

        $this->load->view('header', $this->data);
        $this->load->view('sidebar', $this->data);
        $this->load->view('list_sales_people', $this->data);
    }

    function edit_sales_people()
    {
        if ((is_permission($this->data['user_info']['role'], "edit_sales_people")) == FALSE) {
            echo "You Don't have permission to access this page";
            exit;
        }

        $id = 0;
        $message = "";
        if (isset($_POST['edit_sales_people'])) {
            $id = $this->input->post('user_id');
            $this->userid = $id;
            //echo "<pre>";print_r($_POST);exit;
            $this->load->library('form_validation');
            $this->form_validation->set_error_delimiters('<span for="required" generated="true" class="help-inline" style="color:red;"> *', '</span>');


            $this->form_validation->set_rules('user_id', 'User Identification', 'required');
            $this->form_validation->set_rules('first_name', 'First Name', 'required');
            $this->form_validation->set_rules('last_name', 'Last Name', 'required');
            $this->form_validation->set_rules('email', 'Email', 'required|valid_email|callback_sm_email_unique');
            $this->form_validation->set_rules('scountry', 'Country', 'required');
            $this->form_validation->set_rules('sstate', 'State', 'required');
            $this->form_validation->set_rules('scity', 'City', 'required');
            $this->form_validation->set_rules('zip_code', 'zip code', 'trim|required');
            if (!empty($_POST['password'])) {
                $this->form_validation->set_rules('password', 'Password', 'required|min_length[8]|max_length[32]');
            }
            $this->form_validation->set_rules('password1', 'Password', 'callback_confirm_password');
            $this->form_validation->set_rules('role', 'Manager Role', 'required');
            $this->form_validation->set_rules('profile_pic', 'Profile Image', '');


            if ($this->form_validation->run() == TRUE) {

                if (!isset($_POST['perent_id'])) {
                    $_POST['perent_id'] = 0;
                }
                if (!isset($_POST['birthday'])) {
                    $_POST['birthday'] = "00-00";
                }
                if (!isset($_POST['bank_acount_num'])) {
                    $_POST['bank_acount_num'] = 0;
                }
                if (!isset($_POST['bank_routing_num'])) {
                    $_POST['bank_routing_num'] = 0;
                }
                if (!isset($_POST['full_address'])) {
                    $_POST['full_address'] = " ";
                }
                if (!isset($_POST['contact_num'])) {
                    $_POST['contact_num'] = 0;
                }
                $up = array(
                    "first_name" => $this->input->post('first_name'),
                    "last_name" => $this->input->post('last_name'),
                    "birthday" => "0000-" . $_POST['birthday'],
                    "bank_acount_num" => $_POST['bank_acount_num'],
                    "bank_routing_num" => $_POST['bank_routing_num'],
                    "full_address" => $_POST['full_address'],
                    "contact_num" => $_POST['contact_num'],
                    //"user_name" => $this->input->post('user_name'),
                    "country_id" => $this->input->post('scountry'),
                    "state_id" => $this->input->post('sstate'),
                    "city_id" => $this->input->post('scity'),
                    "zip_code" => $this->input->post('zip_code'),
                    "email" => $this->input->post('email'),
                    "role" => $this->input->post('role'),
                    "perent_id" => $_POST['perent_id']
                );

                if (!empty($_POST['password'])) {
                    $up['password'] = md5($this->input->post('password'));
                }
                //echo "<pre>";print_r($_FILES);
                if (isset($_FILES['profile_pic'])) {
                    $r = $this->file_upload($_FILES['profile_pic'], $id, 1);
                    if (!empty($r)) {

                        $up['profile_pic'] = $r;
                    }
                }
                $this->m_common->update_entry("tbl_users", $up, array("user_id" => $id));
                $message = "Sales Manager " . $up['first_name'] . " has successfully updated";
                $this->session->set_userdata('current_message', $message);
                redirect('site/list_sales_people', 'refresh');
            }
        }
        if (!$id) {
            if (isset($_GET['id'])) {
                $id = $_GET['id'];
            } else {
                redirect('/site/index', 'refresh');
            }
        }

        $info = $this->m_common->db_select("*", "tbl_users", array("user_id" => $id), array(), '', '', array(1, 0), 'row_array');
        //echo "<pre>";print_r($info);exit;
        $country = $this->m_common->db_select("*", "tbl_country", array(), array(), '`order` DESC,`name` asc', '', '', 'all');
        $manager = array();
        $manager1 = $this->m_common->db_select("user_id , first_name , last_name", "tbl_users", array("role" => 4));
        if (!empty($manager1)) {
            foreach ($manager1 as $value1) {
                array_push($manager, $value1);
            }
        }

        $state = $this->m_common->db_select("*", "tbl_state", array("cid" => $info['country_id']), array(), '', '', '', 'all');

        $city = $this->m_common->db_select("*", "tbl_city", array("state_id" => $info['state_id']), array(), '', '', '', 'all');

        $this->data['country'] = $country;
        $this->data['manager'] = $manager;
        $this->data['state'] = $state;
        $this->data['city'] = $city;
        $this->data['info'] = $info;

        $this->load->view('header', $this->data);
        $this->load->view('sidebar', $this->data);
        $this->load->view('edit_sales_people', $this->data);
    }


    public function add_promocode()
    {
        if ((is_permission($this->data['user_info']['role'], "add_promocode")) == FALSE) {
            echo "You Don't have permission to access this page";
            exit;
        }
        $message = "";
        error_reporting(E_ALL);

        if (isset($_POST['add_promocode'])) {
//            echo "<pre>";print_r($_POST);exit;
            $this->load->library('form_validation');
            $this->form_validation->set_error_delimiters('<span for="required" generated="true" class="help-inline" style="color:red;"> *', '</span>');


            $this->form_validation->set_rules('promocode', 'Promocode', 'required|is_unique[promocodes.promocode]');
            $this->form_validation->set_rules('promocode_text', 'Promocode Text', 'required');
            $this->form_validation->set_rules('dtStart', 'Start Date', 'required');
            $this->form_validation->set_rules('dtEnd', 'End Date', 'required');
            $this->form_validation->set_rules('percent_amount', 'Percentage Amount', 'required');


            if ($this->form_validation->run() == TRUE) {


                $inn = array(
                    "promocode" => $this->input->post('promocode'),
                    "promocode_text" => $this->input->post('promocode_text'),
                    "promocode_type" => $this->input->post('promocode_type'),
                    "dtStart" => $this->input->post('dtStart'),
                    "dtEnd" => $this->input->post('dtEnd'),
                    "status" => $this->input->post('status'),
                    "type" => $this->input->post('type'),
                    "percent_amount" => $this->input->post('percent_amount'),
                    "intmonthsfree" => $this->input->post('intmonthsfree')

                );

                $temp = $this->m_common->insert_entry("promocodes", $inn, 1);
                if ($temp['last_id'] > 0) {


                    $message = "The promocodes " . $inn['promocode'] . " has successfully added";
                    $this->session->set_userdata('current_message', $message);
                    redirect('site/list_promocode', 'refresh');
                }
            }
        }


        $this->data['message'] = $message;
        $this->load->view('header', $this->data);
        $this->load->view('sidebar', $this->data);
        $this->load->view('add_promocode', $this->data);
    }

    public function list_promocode()
    {
        $message = "";
        if ((is_permission($this->data['user_info']['role'], "list_promocode")) == FALSE) {
            echo "You Don't have permission to access this page";
            exit;
        }
        $this->data['info'] = $this->m_common->db_select("*", "promocodes");

        $this->data['message'] = $message;
        $this->load->view('header', $this->data);
        $this->load->view('sidebar', $this->data);
        $this->load->view('list_promocode', $this->data);
    }

    public function edit_promocode()
    {

        $message = "";
        if ((is_permission($this->data['user_info']['role'], "edit_promocode")) == FALSE) {
            echo "You Don't have permission to access this page";
            exit;
        }
        if (isset($_POST['promocode_text'])) {

            $this->load->library('form_validation');
            $this->form_validation->set_error_delimiters('<span for="required" generated="true" class="help-inline" style="color:red;"> *', '</span>');
            $this->form_validation->set_rules('promocode_text', 'Promocode Text', 'required');
            $this->form_validation->set_rules('dtStart', 'Start Date', 'required');
            $this->form_validation->set_rules('dtEnd', 'End Date', 'required');
            $this->form_validation->set_rules('percent_amount', 'Percentage Amount', 'required');

            if ($this->form_validation->run() == TRUE) {


                $inn = array(
                    "promocode" => $this->input->post('promocode'),
                    "promocode_text" => $this->input->post('promocode_text'),
                    "promocode_type" => $this->input->post('promocode_type'),
                    "dtStart" => $this->input->post('dtStart'),
                    "dtEnd" => $this->input->post('dtEnd'),
                    "status" => $this->input->post('status'),
                    "type" => $this->input->post('type'),
                    "percent_amount" => $this->input->post('percent_amount'),
                    "intmonthsfree" => $this->input->post('intmonthsfree')
                );


                $wh = array("id" => $_POST['id']);
                $temp = $this->m_common->update_entry("promocodes", $inn, $wh);
                $message = "The promocodes " . $inn['promocode'] . " has successfully Updated";
                $this->session->set_userdata('current_message', $message);
                redirect('site/list_promocode', 'refresh');
            }
        }

        $id = $_GET['id'];

        $this->data['info'] = $this->m_common->db_select("*", "promocodes", array("id" => $id));
        $this->data['id'] = $id;
        $this->data['message'] = $message;
        $this->load->view('header', $this->data);
        $this->load->view('sidebar', $this->data);
        $this->load->view('edit_promocode', $this->data);
    }

    public function delete_promo()
    {
        if (isset($_POST['id'])) {

            $id = $_POST['id'];
            $id = trim(str_replace("del_", "", $id));
            $this->m_common->delete_entry("promocodes", array('id' => $id));
            $message = "You have successfully deleted the promocodes.";
            $this->session->set_userdata('current_message', $message);
            echo '1';
            exit;
        }
    }

    public function add_task()
    {

        $inn = array(
            "task" => $_POST['task_text'],
            "task_by" => $this->data['user_info']['user_id'],
            "bussiness_id" => $_POST['business_id'],
            "end_date" => $_POST['date']
        );
        $src = (!empty($this->data['user_info']['profile_pic'])) ? base_url() . "uploads/user/" . $this->data['user_info']['profile_pic'] : base_url() . "assets/img/profile-pic.jpg";
        $this->m_common->insert_entry("tbl_task", $inn);
        $str = '1';


        echo $str;
        exit;

    }


    public function manage_category()
    {
        if ((is_permission($this->data['user_info']['role'], "manage_category")) == FALSE) {
            echo "You Don't have permission to access this page";
            exit;
        }
        //$this->is_access_permission();
        $message = "";
        if (isset($_POST['add_cat'])) {

            $this->load->library('form_validation');
            $this->form_validation->set_error_delimiters('<span for="required" generated="true" class="help-inline" style="color:red;"> *', '</span>');
            $this->form_validation->set_rules('cname', 'Category Name', 'required|is_unique[tbl_category.cname]');
            $this->form_validation->set_rules('cdis', 'Distance', 'trim|required|callback_cat_distance');

            if ($this->form_validation->run() == TRUE) {

                $inn = array(
                    "cname" => $this->input->post('cname'),
                    "dis" => $this->input->post('cdis'),
                );

                $temp = $this->m_common->insert_entry("tbl_category", $inn, 1);
                if ($temp['last_id'] > 0) {
                    $message = "category " . $this->input->post('cname') . " successfully inserted";
                    if (isset($_FILES['cfile'])) {
                        $r = $this->file_upload($_FILES['cfile'], $temp['last_id']);
                        if (!empty($r)) {
                            $up = array(
                                "cimage" => $r,
                            );
                            $this->m_common->update_entry("tbl_category", $up, array("cid" => $temp['last_id']));
                        }
                    }
                }
                $message = "Category " . $inn['cname'] . " has successfully added";
                $this->session->set_userdata('current_message', $message);
            }
        }


        $this->data['message'] = $message;
        $this->load->view('header', $this->data);
        $this->load->view('sidebar', $this->data);
        $this->load->view('manage_category', $this->data);
    }

    public function list_category()
    {
        if ((is_permission($this->data['user_info']['role'], "list_category")) == FALSE) {
            echo "You Don't have permission to access this page";
            exit;
        }
        //$this->is_access_permission();
        $message = "";

        $q = "select * from tbl_category order by cname ASC";
        $info = $this->m_common->select_custom($q);

        $this->data['info'] = $info;
        $this->data['message'] = $message;
        //echo "<pre>";print_r($this->data);exit;
        $this->load->view('header', $this->data);
        $this->load->view('sidebar', $this->data);
        $this->load->view('list_category', $this->data);
    }


    public function check_city_state_country($str)
    {

        $city = $this->input->post('city');
        $state = $this->input->post('state');
        $country = $this->input->post('country');
        
        if( $city == '' || $state == '' || $country == ''){
            $this->form_validation->set_message('check_city_state_country', 'Enter Full Address like (city state country) ');
            return FALSE;
        } else {
            return TRUE;
        }
    }

    function get_city_state_country_id(){

        $city = $this->input->post('city');
        $state = $this->input->post('state');
        $country = $this->input->post('country');

        $this->db->select('id');
        $this->db->from('tbl_country');
        $this->db->where('name',$country);
        $country_result = $this->db->get()->row();

        if( $country_result ){

            $country_id = $country_result->id;
        }else{
            
            $this->db->insert('tbl_country',array('name'=>$country));
            $country_id = $this->db->insert_id();
        }


        $this->db->select('sid');
        $this->db->from('tbl_state');
        $this->db->where('state_name',$state);
        $this->db->where('cid',$country_id);
        $state_result = $this->db->get()->row();

        if( $state_result ){

            $state_id = $state_result->sid;
        }else{
            
            $this->db->insert('tbl_state',array('state_name'=>$state, 'cid'=>$country_id));
            $state_id = $this->db->insert_id();
        }


        $this->db->select('city_id');
        $this->db->from('tbl_city');
        $this->db->where('city_name',$city);
        $this->db->where('cid',$country_id);
        $this->db->where('state_id',$state_id);
        $city_result = $this->db->get()->row();

        if( $city_result ){

            $city_id = $city_result->city_id;
        }else{
            
            $this->db->insert('tbl_city',array('city_name'=>$city, 'cid'=>$country_id,'state_id'=>$state_id));
            $city_id = $this->db->insert_id();
        }

        $info = array(
            "country_id" => $country_id,
            "state_id" => $state_id,
            "city_id" => $city_id,
        );

        return $info;

    }


    public function manage_shop()
    {
        if ((is_permission($this->data['user_info']['role'], "manage_shop")) == FALSE) {
            echo "You Don't have permission to access this page";
            exit;
        }
        //$this->is_access_permission();

        $message = "";
        if (isset($_POST['add_shop'])) {

            $this->load->library('form_validation');
            $this->form_validation->set_error_delimiters('<span for="required" generated="true" class="help-inline" style="color:red;"> *', '</span>');
            $this->form_validation->set_rules('sname', 'Business Name', 'required');
            $this->form_validation->set_rules('scat', 'Business Category', 'required');
            $this->form_validation->set_rules('sdesp', 'Business Description', 'required');
            
            // $this->form_validation->set_rules('scountry', 'Country', 'required');
            // $this->form_validation->set_rules('sstate', 'State', 'required');
            // $this->form_validation->set_rules('scity', 'City', 'required');
            $this->form_validation->set_rules('sadd', 'Business Address', 'required|callback_check_city_state_country');
            
            $this->form_validation->set_rules('city', 'City', 'trim');
            $this->form_validation->set_rules('state', 'State', 'trim');
            $this->form_validation->set_rules('country', 'Country', 'trim');

            $this->form_validation->set_rules('postal_code', 'zip code', 'trim|required');
            $this->form_validation->set_rules('email', 'Business Email', 'required|valid_email|is_unique[tbl_users.email]');
            $this->form_validation->set_rules('password', 'Business Password', 'required|min_length[8]|max_length[32]');
            $this->form_validation->set_rules('password1', 'Confirm Password', 'required|callback_confirm_password');
            $this->form_validation->set_rules('sfname', 'Business Owner First Name', 'required');
            $this->form_validation->set_rules('slname', 'Business Owner Last Name', 'required');
            
            // $this->form_validation->set_rules('scfname', 'Second Contact First Name', '');
            // $this->form_validation->set_rules('sclname', 'Second Contact Last Name', '');
            // $this->form_validation->set_rules('scphone', 'Second Contact Number', 'callback_check_phone');
            // $this->form_validation->set_rules('scemail', 'Second Contact Email address', 'valid_email');

            $this->form_validation->set_rules('sphone', 'Business Phone Number', 'callback_check_phone');
            $this->form_validation->set_rules('burl', 'Website Url', '');
            $this->form_validation->set_rules('lat', 'Latitude', 'required');
            $this->form_validation->set_rules('lng', 'Longitude', 'required');
            if ($this->form_validation->run() == TRUE) {

                $location_info = $this->get_city_state_country_id();
                
                $country_id = $location_info['country_id'];
                $state_id = $location_info['state_id'];
                $city_id = $location_info['city_id'];


                $inn_user = array(
                    "first_name" => $this->input->post('sfname'),
                    "last_name" => $this->input->post('slname'),
                    "country_id" => $country_id,
                    "state_id" => $state_id,
                    "city_id" => $city_id,
                    "zip_code" => $this->input->post('postal_code'),
                    "email" => $this->input->post('email'),
                    "password" => md5($this->input->post('password')),
                    "role" => 6,
                );
                $temp = $this->m_common->insert_entry("tbl_users", $inn_user, 1);


                if ($temp['last_id'] > 0) {
                    $inn = array(
                        "user_id" => $temp['last_id'],
                        "shop_name" => $this->input->post('sname'),
                        "shop_cats" => $this->input->post('scat'),
                        "shop_description" => $this->input->post('sdesp'),
                        "country_id" => $country_id,
                        "state_id" => $state_id,
                        "city_id" => $city_id,
                        "address" => $this->input->post('sadd'),
                        "zip_code" => $this->input->post('postal_code'),
                        "email" => $this->input->post('email'),
                        "url" => addScheme($this->input->post('burl')),
                        //"username" => $this->input->post('suname'),
                        "password" => md5($this->input->post('password')),
                        "latitude" => $this->input->post('lat'),
                        "longitude" => $this->input->post('lng'),
                        "first_name" => $this->input->post('sfname'),
                        "last_name" => $this->input->post('slname'),
                        // "contact_first_name" => $this->input->post('scfname'),
                        // "contact_last_name" => $this->input->post('sclname'),
                        // "contact_phone" => $this->input->post('scphone'),
                        "business_phone" => $this->input->post('sphone'),
                        // "contact_email" => $this->input->post('scemail'),
                        "add_by" => $this->data['user_info']['user_id']
                    );
                    $temp_shop = $this->m_common->insert_entry("tbl_shop", $inn, 1);

                    

                     //user add in tbl_users_shops
                    $info_users_shops = array(
                        "user_id" => $temp['last_id'],
                        "shop_id"=>$temp_shop['last_id'],
                        "corporate_user_id"=>$this->data['user_info']['user_id'],
                    );

                    $this->m_common->insert_entry("tbl_users_shops", $info_users_shops, 1);


                    
                    $shop_id = $temp_shop['last_id'];
                    $message = "Shop " . $this->input->post('sname') . " successfully inserted <a target='_blank' href='".base_url()."site/payment?shop_id=".$shop_id."'>Payment</a>";
                    if (isset($_FILES['sfile'])) {
                        $r = $this->file_upload($_FILES['sfile'], $temp_shop['last_id'], 1);
                        if (!empty($r)) {
                            $up = array(
                                "shop_image" => $r,
                            );
                            $this->m_common->update_entry("tbl_shop", $up, array("shop_id" => $temp_shop['last_id']));
                            $this->m_common->update_entry("tbl_users", array("profile_pic" => $r), array("user_id" => $temp['last_id']));
                        }
                    }
                    $dt = date('l jS \of F Y \a\t h:i:s A'); // in mail display time text
                    $data = array(
                        "name" => $inn_user['first_name'] . " " . $inn_user['last_name'],
                        "dt" => $dt,
                    );

                    $full_shop_info = $this->get_full_shop_info($temp_shop['last_id']);

                    $message_body = $this->load->view('email/welcome_signup', $data, true);
                    $message_body_new_bsignup = $this->load->view('email/message_body_new_bsignup', $full_shop_info, true);
                    $mail_p = array(
                        "to" => $this->input->post('semail'),
                        "message_body" => $message_body,
                        "subject" => "Welcome to the Locally Epic",
                    );
                    $m_new_bsignup = array(
                        "to" => "dealsonthegogo@gmail.com",
                        "message_body" => $message_body_new_bsignup,
                        "subject" => "Locally Epic : New Business Signup",
                    );
                    $this->sent_email($mail_p);
//                    $this->sent_email($m_new_bsignup);

                    $this->session->set_userdata('current_message', $message);
                    redirect('/site/list_shop', 'refresh');
                }
            }
        }
        $cats = $this->m_common->db_select("*", "tbl_category", array(), array(), 'cname ASC', '', '', 'all');

        $this->data['cats'] = $cats;
        $this->data['message'] = $message;

        $this->load->view('header', $this->data);
        $this->load->view('sidebar', $this->data);
        $this->load->view('manage_shop', $this->data);
    }

    function hms2sec($hms)
    {

        $arr = explode(" ", $hms);
        $b = end($arr);
        $hms = reset($arr);
        $arr_hms = explode(":", $hms);
        if (($b == "PM" || $b == "pm") && $arr_hms[0] != 12) {
            $arr_hms[0] += 12;
        } else {
            //if($arr_hms[0]==12){
            //$arr_hms[0]=0;
            //}
        }

        $seconds = 0;
        $seconds += (intval($arr_hms[0]) * 3600);
        $seconds += (intval($arr_hms[1]) * 60);


        return $seconds;
    }

    public function hms()
    {

        $hms = "10:00 PM";

        echo $this->hms2sec($hms);
        exit;

    }

    public function get_prv_deal_info()
    {
        $arr = array(
            "contact_name" => "",
            "contact_number" => "",
            "website" => "",
        );

        $shop_id = $_POST['shop_id'];

        if ($shop_id != '') {
            $deal = $this->m_common->db_select("*", "tbl_deal", array("shop_id" => $shop_id), array(), 'id desc', '', array(1, 0), 'row_array');
            if (!empty($deal)) {
                //echo "<pre>";print_r($deal);
                $arr['contact_name'] = $deal['contact_name'];
                $arr['contact_number'] = $deal['contact_number'];
                $arr['website'] = $deal['website'];
            } else {
                $shop = $this->m_common->db_select("*", "tbl_shop", array("shop_id" => $shop_id), array(), '', '', array(1, 0), 'row_array');

                //echo "<pre>";print_r($shop);
                $arr['contact_name'] = $shop['contact_first_name'] . " " . $shop['contact_last_name'];
                $arr['contact_number'] = $shop['contact_phone'];
                $arr['website'] = $shop['url'];

                if ($shop['contact_phone'] == '') {

                    $arr['contact_number'] = $shop['business_phone'];
                }


            }
        }
        echo json_encode($arr);
        exit;
    }

    public function create_deal()
    {
        if ((is_permission($this->data['user_info']['role'], "create_deal")) == FALSE) {
            echo "You Don't have permission to access this page";
            exit;
        }



        $deal = array("timezone" => "UM5");

        if (isset($_GET['deal_id'])) {
            $deal_id = $_GET['deal_id'];
            $deal = $this->m_common->db_select("*", "tbl_deal", array("id" => $deal_id), array(), '', '', '', 'row_array');

            $deal['deal_start'] = DateTime::createFromFormat('Y-m-d', $deal['deal_start'])->format('m/d/y');
        }


        if ($this->session->userdata('role') == 6) {

            if($this->data['user_info']['is_corporate_business_user'] == 1){

                $this->db->select('t2.shop_id,t2.timezone,t2.shop_image');
                $this->db->from('tbl_users_shops t1');
                $this->db->join('tbl_shop t2','t2.shop_id = t1.shop_id');
                $this->db->where('t1.user_id',$this->session->userdata('user_id'));
                $row = $this->db->get()->row();

            }else{

                $sql = "select shop_id,timezone,shop_image from tbl_shop where user_id = ?";
                $result = $this->db->query($sql, array($this->session->userdata('user_id')));

                //print_rr($this->db->last_query());
                $row = $result->row();
            }

            $_GET["id"] = $row->shop_id;
            $deal = array("timezone" => $row->timezone);
        } else {

            if (!isset($_POST["shop_id"]) && isset($deal["shop_id"])) {
                $_POST["shop_id"] = $deal["shop_id"];
                $_GET["id"] = $deal["shop_id"];
            }

            if (isset($_GET["id"])) {
                $sql = "select shop_id,timezone,shop_image from tbl_shop where shop_id = ?";
                $result = $this->db->query($sql, array($_GET["id"]));
                $row = $result->row();
            }

            if (isset($_POST["shop_id"])) {
                $sql = "select shop_id,timezone,shop_image from tbl_shop where shop_id = ?";
                $result = $this->db->query($sql, array($_POST["shop_id"]));
                $row = $result->row();
            }

        }

        if (isset($row)) {
            $this->data['shop'] = $row;
        }

        $this->load->helper('date');
        $message = "";
        $where = array();
        $join = array();

        $this->row3 = 0;
        $this->row4 = 0;


        if (isset($_POST['create_deal'])) {


            if ($this->session->userdata('role') == 9) {

                $this->load->library('form_validation');
                $this->form_validation->set_error_delimiters('<span for="required" generated="true" class="help-inline" style="color:red;"> *', '</span>');
                $this->form_validation->set_rules('offershop_id[]', 'Select Business', 'required');
                $this->form_validation->set_rules('deal_title', 'Deal Title', 'required|callback_check_swear');
                $this->form_validation->set_rules('deal_description', 'Deal Description', 'required|callback_check_swear');
                $this->form_validation->set_rules('original_price', 'Original Price', 'required');
                $this->form_validation->set_rules('offer_price', 'Deal Price', 'required');
                $this->form_validation->set_rules('deal_start', 'Deal Start Date', 'required');

                $this->form_validation->set_rules('deal_repeat[]', 'deal repeat', '');
                $deal_repeat = (array)$this->input->post('deal_repeat');

                for($i=1; $i<=7; $i++){
                
                    $is_find = in_array($i, $deal_repeat);

                    if($is_find){

                        $this->form_validation->set_rules('datepicker_start_'.$i, 'Offer Start Start', 'required');
                        $this->form_validation->set_rules('datepicker_end_'.$i, 'Offer Start End', 'required');
                    }
                }

                // if ($this->form_validation->run() == TRUE) {
                //     echo "done";
                //     echo "<pre>";
                //     print_r($_POST);
                //     exit();

                // }

                if ($this->form_validation->run() == TRUE) {

                    $offershops_ids = $this->input->post('offershop_id');
                   
                    $start_date = DateTime::createFromFormat('m/d/y', $_POST['deal_start'])->format('Y-m-d');

                    $converted_start_date = convert_to_utc($start_date, $this->input->post('deal_time'), $this->input->post('timezone'));
                    $converted_end_date = convert_to_utc($start_date, $this->input->post('deal_end_time'), $this->input->post('timezone'));


                    $deal_time = $converted_start_date["seconds"];
                    $deal_end_time = $converted_end_date["seconds"];

                    
                    if (isset($_FILES['deal_image'])) {
                        $r = $this->file_upload($_FILES['deal_image'], 'deal');
                        if (!empty($r)) {

                            $deal_image = "http://" . $_SERVER['SERVER_NAME'] . "/uploads/$r";

                            $config['image_library'] = 'gd2';
                            $config['source_image'] = '/var/www/html/uploads/' . $r;
                            $config['create_thumb'] = FALSE;
                            $config['maintain_ratio'] = FALSE;
                            $config['width'] = 75;
                            $config['height'] = 75;
                            $config['new_image'] = '/var/www/html/uploads/thumbs/' . $r;
                            $this->load->library('image_lib', $config);
                            $this->image_lib->resize();
                        }
                    }

                    $barcode_image = '';
                    if (isset($_FILES['barcode_image'])) {
                        $r = $this->file_upload($_FILES['barcode_image'], 'barcode');
                        if (!empty($r)) {

                            $barcode_image = "http://" . $_SERVER['SERVER_NAME'] . "/uploads/$r";

                            $config['image_library'] = 'gd2';
                            $config['source_image'] = '/var/www/html/uploads/' . $r;
                            $config['create_thumb'] = FALSE;
                            $config['maintain_ratio'] = FALSE;
                            $config['width'] = 75;
                            $config['height'] = 75;
                            $config['new_image'] = '/var/www/html/uploads/thumbs/' . $r;
                            $this->load->library('image_lib', $config);
                            $this->image_lib->resize();
                        }
                    }


                    foreach ($offershops_ids as $osikey => $osivalue) {

                        $offer_shop_id = $osivalue;

                        $rep_array = (array)$this->input->post('deal_repeat');
                        $repeat = implode(",", $rep_array);
                        $deal_start_date = $converted_start_date["utc_date"];


                        if (!isset($deal_image)) {
                            $deal_image = "https://" . $_SERVER['SERVER_NAME'] . "/images/no_image.png";
                        }


                        $inn = array(
                            "shop_id" => $offer_shop_id,
                            "deal_title" => $this->input->post('deal_title'),
                            "deal_description" => $this->input->post('deal_description'),
                            "original_price" => $this->input->post('original_price'),
                            "offer_price" => $this->input->post('offer_price'),
                            "deal_start" => $deal_start_date,
                            "deal_end" => $converted_end_date["utc_date"],
                            "deal_time" => $deal_time,
                            "deal_end_time" => $deal_end_time,
                            "repeat" => $repeat,
                            "deal_image" => $deal_image,
                            "barcode_image" => $barcode_image,
                            "is_main_deal" => 1,
                            "timezone" => $this->input->post('timezone')
                        );


                        if (isset($_POST['deal_image_dup'])) {
                            $inn['deal_image'] = $_POST['deal_image_dup'];
                        }


                        $temp = $this->m_common->insert_entry("tbl_deal", $inn, 1);
                        if ($temp['last_id'] > 0) {

                           

                            $this->createRepeatDeals($temp['last_id'], $deal_start_date, $rep_array,$_POST);
                        }
                    }
                    
                    $message = "Your Deal Was Successfully Created Thank You";
                    $this->session->set_userdata('current_message', $message);
                    // exit();
                    // if ($this->data['user_info']['role'] == 6) {
                    //     redirect('/site/create_deal', 'refresh');
                    // } 
                    // else if ($this->data['user_info']['role'] == 9) {
                        redirect('/site/view_shop/?id='.$_GET["id"], 'refresh');
                    // }else {
                    //     redirect('/site/manage_deal', 'refresh');
                    // }
                }


            }else{


                 

                

                $this->load->library('form_validation');
                $this->form_validation->set_error_delimiters('<span for="required" generated="true" class="help-inline" style="color:red;"> *', '</span>');
                $this->form_validation->set_rules('shop_id', 'Business Name', 'required');
                $this->form_validation->set_rules('deal_title', 'Deal Title', 'required|callback_check_swear');
                $this->form_validation->set_rules('deal_description', 'Deal Description', 'required|callback_check_swear');
                $this->form_validation->set_rules('original_price', 'Original Price', 'required');
                $this->form_validation->set_rules('offer_price', 'Deal Price', 'required');
                $this->form_validation->set_rules('deal_start', 'Deal Start Date', 'required');

                $this->form_validation->set_rules('deal_repeat[]', 'deal repeat', '');
                $this->form_validation->set_rules('deal_time', 'Deal Time', 'required|callback_dealtimeoverlap');

                $deal_repeat = (array)$this->input->post('deal_repeat');

                for($i=1; $i<=7; $i++){
                
                    $is_find = in_array($i, $deal_repeat);

                    if($is_find){

                        $this->form_validation->set_rules('datepicker_start_'.$i, 'Offer Start Start', 'required');
                        $this->form_validation->set_rules('datepicker_end_'.$i, 'Offer Start End', 'required');
                    }
                }


                if ($this->form_validation->run() == TRUE) {

                    if (!empty($_POST['deal_start']) && !empty($_POST['deal_start'])) {
                    $start_date = DateTime::createFromFormat('m/d/y', $_POST['deal_start'])->format('Y-m-d');

                    $converted_start_date = convert_to_utc($start_date, $this->input->post('deal_time'), $this->input->post('timezone'));
                    $converted_end_date = convert_to_utc($start_date, $this->input->post('deal_end_time'), $this->input->post('timezone'));

                    $shop_id = $_POST['shop_id'];


                    $sql = "SELECT
                            MAX(deal_end_time) AS max_deal_end_time,
                            MAX(deal_time) AS deal_time
                        FROM
                            tbl_deal
                        WHERE
                            `deal_end` = ?
                        AND `shop_id` =?
                        AND `is_active` = 1
                        AND `is_off` = 0";

                    $result = $this->db->query($sql, array($converted_start_date["utc_date"], $shop_id));

                    $max_deal_end_time = $result->row()->max_deal_end_time;
                    $max_deal_start_time = $result->row()->deal_time;

                    $deal_time = $converted_start_date["seconds"];
                    $deal_end_time = $converted_end_date["seconds"];

                    $q3 = "SELECT count(*) as c from tbl_deal where deal_start = ? and ? >= deal_time and ? <=deal_end_time AND `shop_id`=$shop_id AND `is_active` = 1 AND `is_off` = 0";

                    $q4 = "SELECT count(*) as c from tbl_deal where deal_start = ? and ? >= deal_time and ? <=deal_end_time AND `shop_id`=$shop_id AND `is_active` = 1 AND `is_off` = 0";

                    $q3res = $this->db->query($q3, array($converted_start_date["utc_date"], $deal_time, $deal_time));
                    $q4res = $this->db->query($q4, array($converted_start_date["utc_date"], $deal_end_time, $deal_end_time));

                    $row3 = $q3res->row();
                    $row4 = $q4res->row();

                    $this->row3 = $row3->c;
                    $this->row4 = $row4->c;

                }

                    $rep_array = (array)$this->input->post('deal_repeat');
                    $repeat = implode(",", $rep_array);
                    $deal_start_date = $converted_start_date["utc_date"];

                    $tsDealStart = strtotime("$deal_start_date " . $converted_start_date["utc_time"]);
                    $tsDealEnd = strtotime($converted_end_date["utc_date"] . " " . $converted_end_date["utc_time"]);

                    $img = "https://" . $_SERVER['SERVER_NAME'] . "/images/no_image.png";

                    if ($row->shop_image != '') {
                        $img = "https://" . $_SERVER['SERVER_NAME'] . "/uploads/user/" . $row->shop_image;
                    }


                    $inn = array(
                        "shop_id" => $this->input->post('shop_id'),
                        "deal_title" => $this->input->post('deal_title'),
                        "deal_description" => $this->input->post('deal_description'),
                        "original_price" => $this->input->post('original_price'),
                        "offer_price" => $this->input->post('offer_price'),
                        "deal_start" => $deal_start_date,
                        "deal_end" => $converted_end_date["utc_date"],
                        "deal_time" => $deal_time,
                        "deal_end_time" => $deal_end_time,
                        "repeat" => $repeat,
                        "deal_image" => $img,
                        "is_main_deal" => 1,
                        "timezone" => $this->input->post('timezone')
                    );


                    if (isset($_POST['deal_image_dup'])) {
                        $inn['deal_image'] = $_POST['deal_image_dup'];
                    }


                    $temp = $this->m_common->insert_entry("tbl_deal", $inn, 1);
                    if ($temp['last_id'] > 0) {

                        $message = "Your Deal Was Successfully Created Thank You";
                        if (isset($_FILES['deal_image'])) {
                            $r = $this->file_upload($_FILES['deal_image'], $temp['last_id']);
                            if (!empty($r)) {
                                $up = array(
                                    "deal_image" => "http://" . $_SERVER['SERVER_NAME'] . "/uploads/$r",
                                );
                                $this->m_common->update_entry("tbl_deal", $up, array("id" => $temp['last_id']));

                                $config['image_library'] = 'gd2';
                                $config['source_image'] = '/var/www/html/uploads/' . $r;
                                $config['create_thumb'] = FALSE;
                                $config['maintain_ratio'] = FALSE;
                                $config['width'] = 75;
                                $config['height'] = 75;
                                $config['new_image'] = '/var/www/html/uploads/thumbs/' . $r;

                                $this->load->library('image_lib', $config);

                                $this->image_lib->resize();
                            }
                        }

                        if (isset($_FILES['barcode_image'])) {
                            $r = $this->file_upload($_FILES['barcode_image'], 'barcode');
                            if (!empty($r)) {

                                $barcode_image = "http://" . $_SERVER['SERVER_NAME'] . "/uploads/$r";
                                $up = array(
                                    "barcode_image" => $barcode_image,
                                );
                                $this->m_common->update_entry("tbl_deal", $up, array("id" => $temp['last_id']));
                                

                                $config['image_library'] = 'gd2';
                                $config['source_image'] = '/var/www/html/uploads/' . $r;
                                $config['create_thumb'] = FALSE;
                                $config['maintain_ratio'] = FALSE;
                                $config['width'] = 75;
                                $config['height'] = 75;
                                $config['new_image'] = '/var/www/html/uploads/thumbs/' . $r;
                                $this->load->library('image_lib', $config);
                                $this->image_lib->resize();
                            }
                        }                     

                        $this->createRepeatDeals($temp['last_id'], $deal_start_date, $rep_array,$_POST);

                        $this->session->set_userdata('current_message', $message);

                        if ($this->data['user_info']['role'] == 6) {
                            redirect('/site/create_deal', 'refresh');
                        } 
                        else if ($this->data['user_info']['role'] == 9) {
                            redirect('/site/view_shop/?id='.$_GET["id"], 'refresh');
                        }else {
                            redirect('/site/manage_deal', 'refresh');
                        }
                    }
                }
            }
        }

        
        if ($this->data['user_info']['role'] == 2) {
            $where = array("add_by" => $this->data['user_info']['user_id']);
        }
        if ($this->data['user_info']['role'] == 3) {
            $where = array("add_by" => $this->data['user_info']['user_id']);
        }
        if ($this->data['user_info']['role'] == 4) {
            $where = array("add_by" => $this->data['user_info']['user_id']);
        }
        if ($this->data['user_info']['role'] == 5) {
            $where = array("add_by" => $this->data['user_info']['user_id']);
        }
        if ($this->data['user_info']['role'] == 6) {
            $where = array("shop_id" => $this->data['user_info']['user_id']);
        }
        if ($this->data['user_info']['role'] == 9) {
            $where = array("shop_id" => $_GET["id"]);
        }


        // if($this->data['user_info']['is_corporate_business_user'] == 1 && $this->data['user_info']['role'] == 6){
        if($this->data['user_info']['role'] == 9 || $this->data['user_info']['role'] == 6){

            $this->db->select('t2.shop_id,t2.shop_name,t2.shop_image');
            $this->db->from('tbl_users_shops t1');
            $this->db->join('tbl_shop t2','t2.shop_id = t1.shop_id');
            $this->db->where('t1.user_id',$this->data['user_info']['user_id']);
            $this->db->where('t2.corporate_main_shop',0);
            $shops = $this->db->get()->result_array();
        }
        else{
            $shops = $this->m_common->db_select("shop_id,shop_name,shop_image", "tbl_shop", $where, array(), 'shop_name', '', '', 'all');
        }


        if($this->data['user_info']['role'] == 9){
            $this->data['corporate_business_list'] = $this->get_corporate_business_v2($this->session->userdata('user_id'));
        }


        if (isset($_GET["id"])) {
            $deal["shop_id"] = $_GET["id"];
        }


        $this->data['deal'] = $deal;

        $this->data['shops'] = $shops;

        // echo "<pre>";
        // print_r($shops);
        // exit();

        $this->data['message'] = $message;
        $this->load->view('header', $this->data);
        $this->load->view('sidebar', $this->data);
        $this->load->view('create_deal', $this->data);

    }

    public function repeat()
    {

        $deal_id = $this->input->get('deal_id');
        $start_date = date("Y-m-d");
        $repeats = array(1, 2, 3, 4, 5, 6, 7);
        $this->createRepeatDeals($deal_id, $start_date, $repeats);


    }

    private function createRepeatDeals($deal_id, $start_date, $repeats , $post_data = array(),$offer_type = 1)
    {


        //get the deal
        $sql = "select * from tbl_deal where id = ?";
        $result = $this->db->query($sql, array($deal_id));

        $parent_deal = $result->row();

        
        foreach ($repeats as &$value) {

            if(!empty($value)){
                $RepeatdateArr = $this->getDateForSpecificDayBetweenDates($post_data["datepicker_start_$value"], $post_data["datepicker_end_$value"], $value - 1);

                // echo '<pre>';
                // print_r($dateArr);
                // exit();
                foreach ($RepeatdateArr as &$Repeatdate) {
                    $new_date = $Repeatdate;

                    $converted_start_date = convert_to_utc($new_date, $post_data["deal_time"], $post_data['timezone']);
                    $converted_end_date = convert_to_utc($new_date, $post_data["deal_end_time"], $post_data['timezone']);
                    $deal_time = $converted_start_date["seconds"];
                    $deal_end_time = $converted_end_date["seconds"];

                    $deal_start_date = $converted_start_date["utc_date"];
                    $deal_end_date = $converted_end_date["utc_date"];


                    // TODO: Need to add code to check for duplicate deals

                    $sql = "
                            SELECT
                                count(*) as thecount
                            FROM
                                tbl_deal
                            WHERE
                                shop_id = ?
                            AND `is_active` = 1
                            AND deal_start = ?
                            AND ? BETWEEN deal_time
                            AND deal_end_time;
                          ";
                    $result1 = $this->db->query($sql, array($parent_deal->shop_id, $deal_start_date, $deal_time));
                    $dealcount = $result1->row();

                    if ($dealcount->thecount == 0) {

                        $shop_id = $parent_deal->shop_id;
                        $deal_title = $parent_deal->deal_title;
                        $deal_description = $parent_deal->deal_description;
                        $schedule_text = $parent_deal->schedule_text;
                        $original_price = $parent_deal->original_price;
                        $offer_price = $parent_deal->offer_price;
                        $deal_image = $parent_deal->deal_image;
                        $deal_start = $deal_start_date;
                        $deal_end = $deal_end_date;
                        $deal_time = $deal_time;
                        $deal_end_time = $deal_end_time;

                        // $deal_time = $deal_time + $value * 86400;
                        // $deal_end_time = $deal_end_time + $value * 86400;
                        // $repeat = $value;
                        $barcode_image = $parent_deal->barcode_image;
                        $featured_deal = $parent_deal->featured_deal;
                        $contact_name = $parent_deal->contact_name;
                        $contact_number = $parent_deal->contact_number;
                        $website = $parent_deal->website;
                        $share_count = $parent_deal->share_count;
                        $status = $parent_deal->status;
                        $date = $parent_deal->date;
                        $is_active = $parent_deal->is_active;
                        $is_off = $parent_deal->is_off;
                        $timezone = $parent_deal->timezone;
                        $offer_type = $offer_type;

                        $sql = "insert into tbl_deal set
                            shop_id=?,
                            deal_title=?,
                            deal_description=?,
                            schedule_text=?,
                            original_price=?,
                            offer_price=?,
                            deal_image=?,
                            deal_start=?,
                            deal_end=?,
                            deal_time=?,
                            deal_end_time=?,
                            featured_deal=?,
                            contact_name=?,
                            contact_number=?,
                            website=?,
                            share_count=?,
                            status=?,
                            date=?,
                            is_active=?,
                            is_off=?,
                            timezone=?,
                            barcode_image=?,
                            post_data = ?,
                            offer_type = ?
                            ";
                        $result = $this->db->query($sql, array(

                            $shop_id,
                            $deal_title,
                            $deal_description,
                            $schedule_text,
                            $original_price,
                            $offer_price,
                            $deal_image,
                            $deal_start,
                            $deal_end,
                            $deal_time,
                            $deal_end_time,
                            $featured_deal,
                            $contact_name,
                            $contact_number,
                            addScheme($website),
                            $share_count,
                            $status,
                            $date,
                            $is_active,
                            $is_off,
                            $timezone,
                            $barcode_image,
                            serialize($post_data),
                            $offer_type
                        ));
                    }
                }
            }

        }

    }

    //0=sunday
    function getDateForSpecificDayBetweenDates($startDate, $endDate, $weekdayNumber)
    {
        $startDate = strtotime($startDate);
        $endDate = strtotime($endDate);

        $dateArr = array();

        do
        {
            if(date("w", $startDate) != $weekdayNumber)
            {
                $startDate += (24 * 3600); // add 1 day
            }
        } while(date("w", $startDate) != $weekdayNumber);


        while($startDate <= $endDate)
        {
            $dateArr[] = date('Y-m-d', $startDate);
            $startDate += (7 * 24 * 3600); // add 7 days
        }

        return($dateArr);
    }



    function jnk(){

        $dateArr = $this->getDateForSpecificDayBetweenDates('2017-04-01', '2017-04-30', 0);

        print "<pre>";
        print_r($dateArr);

    }

    public function view_deal()
    {

        $deal = $this->m_common->db_select('*', "tbl_deal", array('id' => $_GET['id']), array(), '', '', array(1, 0), 'row_array');
        $this->data['deal'] = $deal;
        $arr = $this->m_common->db_select('*', "push_notes", array('join_id' => $_GET['id']), array());


        $result = $this->m_common->statatics3($_GET['id']);
        $this->data['data'] = $result;
        $this->data['id'] = $_GET['id'];
        $pcount = count($result);

        if ($result == '') {
            $pcount = 0;
        }


        $sql = "select count(*) as thecount from tbl_deals_activated where deal_id=?";
        $result = $this->db->query($sql, array($_GET['id']));
        $this->data['activation_count'] = $result->row()->thecount;


        $sql = "select shop_id, shop_name, shop_image from tbl_shop where shop_id = ?";
        $result = $this->db->query($sql, array($deal["shop_id"]));
        $row = $result->row();

        $this->data['push_count'] = $pcount;
        $this->data['shop'] = $row;
        $this->load->view('header', $this->data);
        $this->load->view('sidebar', $this->data);
        $this->load->view('view_deal', $this->data);

    }

    public function deal_off()
    {
        if (!isset($_GET['deal_id'])) {
            redirect('site/manage_deal', 'refresh');
        }
        $deal = $this->m_common->db_select("*", "tbl_deal", array("id" => $_GET['deal_id']), array(), '', '', array(1, 0), 'row_array');

        if (!empty($deal)) {
            $this->m_common->update_entry("tbl_deal", array('is_off' => 1), array('id' => $deal['id']));
            $message = "The DEAL (<strong>" . $deal['deal_title'] . "</strong>) successfully been CANCELLED";
            $this->session->set_userdata('current_message', $message);
        }

        if ($this->session->userdata('role') == 6) {
            redirect('/site/view_shop/', 'refresh');
        } else {
            redirect('/site/manage_deal', 'refresh');
        }
    }

    public function deal_on()
    {
        if (!isset($_GET['deal_id'])) {
            redirect('site/manage_deal', 'refresh');
        }
        $deal = $this->m_common->db_select("*", "tbl_deal", array("id" => $_GET['deal_id']), array(), '', '', array(1, 0), 'row_array');

        if (!empty($deal)) {
            $this->m_common->update_entry("tbl_deal", array('is_off' => 0), array('id' => $deal['id']));
            $message = "The DEAL (<strong>" . $deal['deal_title'] . "</strong>) successfully been STARTED";
            $this->session->set_userdata('current_message', $message);
        }

        if ($this->session->userdata('role') == 6) {
            redirect('/site/view_shop/', 'refresh');
        } else {
            redirect('/site/manage_deal', 'refresh');
        }
    }

    public function deal_delete()
    {
        if (!isset($_GET['deal_id'])) {
            redirect('site/manage_deal', 'refresh');
        }
        $deal = $this->m_common->db_select("deal_title", "tbl_deal", array("id" => $_GET['deal_id']), array(), '', '', array(1, 0), 'row_array');
        if (!empty($deal)) {
            $this->m_common->update_entry("tbl_deal", array('is_active' => 0), array('id' => $_GET['deal_id']));
            $message = "The deal <strong>" . $deal['deal_title'] . "</strong>  has been deleted.";
            $this->session->set_userdata('current_message', $message);
        }

        if ($this->session->userdata('role') == 6) {
            redirect('/site/view_shop/', 'refresh');
        } else {
            redirect('/site/manage_deal', 'refresh');
        }
    }

    public function list_consumers()
    {

        if ((is_permission($this->data['user_info']['role'], "list_consumers")) == FALSE) {
            echo "You Don't have permission to access this page";
            exit;
        }
        $message = "";
        $page_no = 1;
        $wh = "";

        $page_row_limit = isset($_GET['perpage']) ? $_GET['perpage'] : 10;

        if (isset($_GET['search']) && !empty($_GET['search'])) {

            $search = $_GET['search'];
            $this->data['search'] = $search;
            if (str_word_count($search) > 2) {
                $wh .= " and ( t1.email like '%$search%' ) ";
            } else {

                $wh .= " and ( t1.name like '%$search%') ";
            }
        }


        if (isset($_GET['scat']) && ($_GET['scat'] > 0)) {
            $scat = $_GET['scat'];
            $wh .= " and ( t1.shop_cats = $scat ) ";
        }


        $q = "select count(t1.user_id) as cnt from tbl_customer t1 where 1=1 $wh";
        $res_tb = $this->m_common->select_custom($q);
        // echo "<pre>";print_r($res_tb);exit;
        $tot_rows = $res_tb[0]['cnt'];

        $tot_page = ceil($tot_rows / $page_row_limit);
        if (isset($_GET['page_no']) && $_GET['page_no'] > 0) {
            $page_no = $_GET['page_no'];
        }
        $offset = ($page_no * $page_row_limit) - $page_row_limit;


        $q = "select t1.* from tbl_customer t1 where 1=1 $wh group by t1.user_id limit $offset,$page_row_limit";
        $info = $this->m_common->select_custom($q);

        //echo "<pre>";print_r($info);exit;

        $prev = ($page_no - 6);
        if ($prev <= 0) {
            $prev = 1;
        }
        $next = ($page_no + 6);
        if ($next >= $tot_page) {
            $next = $tot_page;
        }


        $this->data['info'] = $info;
        $this->data['tot_page'] = $tot_page;
        $this->data['curr_page'] = $page_no;
        $this->data['prev'] = $prev;
        $this->data['next'] = $next;


        $this->load->view('header', $this->data);
        $this->load->view('sidebar', $this->data);
        $this->load->view('list_consumers', $this->data);

    }

    function edit_consumers()
    {
        if (isset($_GET['id']) && !empty($_GET['id'])) {
            $id = $_GET['id'];


            $info = $this->m_common->db_select("*", "tbl_customer", array("user_id" => $id));
            $cats = $this->m_common->db_select("*", "tbl_category", array(), array(), 'cname ASC', '', '', 'all');


            $this->data['info'] = $info;
            $this->data['cats'] = $cats;
            $this->load->view('header', $this->data);
            $this->load->view('sidebar', $this->data);
            $this->load->view('edit_consumers', $this->data);
        }
        if (isset($_POST['user_id']) && !empty($_POST['user_id'])) {

            $id = $_POST['user_id'];

            $this->load->library('form_validation');
            $this->form_validation->set_error_delimiters('<span for="required" generated="true" class="help-inline" style="color:red;"> *', '</span>');
            $this->form_validation->set_rules('name', 'Name', 'required');
            $this->form_validation->set_rules('address', 'Address', 'required');
            $this->form_validation->set_rules('phone_no', 'Phone No', 'required');
            $this->form_validation->set_rules('user_cat', 'User Category', 'required');

            if ($this->form_validation->run() == TRUE) {
                $cats = implode(",", $this->input->post('user_cat'));
                $inn = array(
                    "name" => $this->input->post('name'),
                    "address" => $this->input->post('address'),
                    "phone_no" => $this->input->post('phone_no'),
                    "user_cat" => $cats
                );
                $wh = array(
                    "user_id" => $id,
                );
                $this->m_common->update_entry("tbl_customer", $inn, $wh);
                //echo "<pre>";print_r($_FILES);
                //echo "<pre>";print_r($inn);exit;
                //$this->m_common->update_entry("tbl_users", $inn_user, array("user_id" => $this->input->post('user_id')));

                if ($this->input->post('password') != '') {
                    $info = $this->m_common->db_select("user_token", "tbl_customer", array("user_id" => $id));
                    $this->appuser->update_password($id, $info[0]["user_token"], trim($this->input->post('password')));
                }

                $message = "Coustomer " . $this->input->post('name') . " has successfully Updated.";
                $this->session->set_userdata('current_message', $message);

                redirect('site/list_consumers', 'refresh');
            }

        }
    }

    public function disable_user()
    {
        $id = $_POST['id'];
        if (isset($id)) {
            $wh = array("user_id" => $id);
            $up = array("is_disable" => 1);
            $data = $this->m_common->update_entry("tbl_customer", $up, $wh);
            echo $data;
        }

    }

    public function read_message()
    {
        $id = $_POST['id'];
        $select = $this->m_common->db_select("*", "tbl_message", array("message_id" => $id));
        $who_open = $select[0]['who_open'];
        $who_open .= "," . $this->data['user_info']['user_id'];
        $up = array("who_open" => $who_open);
        $this->m_common->update_entry("tbl_message", $up, array("message_id" => $id));
        return TRUE;
    }

    public function list_message()
    {
        $this->data['sendbox'] = array();
        $this->data['inbox'] = array();
        $q = "select t1.*,t2.* from tbl_message t1 join tbl_users t2 on t1.message_from = t2.user_id ORDER BY t1.message_id DESC";
        $msg = $this->m_common->select_custom($q);
        $count_inbox = 0;
        $count_sentbox = 0;
        for ($i = 0; $i < count($msg); $i++) {
            $inbox = explode(",", $msg[$i]['message_to']);

            if (in_array($this->data['user_info']['user_id'], $inbox)) {
                $msg_receive = $msg[$i];
                if (count($this->data['inbox']) < 10) {
                    array_push($this->data['inbox'], $msg_receive);
                }
                $count_inbox++;
            }

            if (trim($this->data['user_info']['user_id']) == trim($msg[$i]['message_from'])) {
                $msg_send = $msg[$i];
                if (count($this->data['sendbox']) < 10) {
                    array_push($this->data['sendbox'], $msg_send);
                }
                $count_sentbox++;
            }

        }
        $this->data['count_inbox'] = $count_inbox;
        $this->data['count_sentbox'] = $count_sentbox;

        $this->data['All_msg'] = $msg;
//        echo "<pre>";
//        print_r($this->data);
//        exit;
        $this->load->view('header', $this->data);
        $this->load->view('sidebar', $this->data);
        $this->load->view('list_message', $this->data);

    }

    public function message_ajax_lable()
    {
        $id = $this->data['user_info']['user_id'];
        $wh = "";
        $str = "";
        $lable = $_POST['load_type'];
        $wh .= " AND t1.lable = $lable";
        $wh .= " AND t1.message_to LIKE '%$id%'";
        $q = "select t1.*,t2.* from tbl_message t1 join tbl_users t2 on t1.message_from = t2.user_id where 1=1 $wh ORDER BY t1.message_id DESC";

        $message_info = $this->m_common->select_custom($q);
        if (!empty($message_info)) {
            foreach ($message_info as $v) {
                $str .= '<tr class="clickableRow ';
                $t = explode(",", $v['who_open']);
                if (!(in_array($id, $t))) {
                    $str .= ' unread-message';
                }
                $str .= '" id="' . $v["message_id"] . '" ><td class="checkbox-col"><input type="checkbox" class="selectedId_inb" id="' . $v["message_id"] . '" value="' . $v["message_id"] . '" name="selectedId_inb[]"></td><td class="from-col" onclick="open_msg(' . $v["message_id"] . ')">';
                if ($v['is_imp'] == 1) {
                    $str .= '<i class="fa fa-exclamation-circle"></i>';
                }
                $str .= $v['first_name'] . " " . $v["last_name"] . '</td><td class="msg-col" onclick="open_msg(' . $v["message_id"] . ')"><i id="' . $v["message_id"] . '" class="fa  ';
                if ($v["lable"] == 1) {
                    $str .= 'fa-square text-green';
                } else if ($v["lable"] == 2) {
                    $str .= 'fa-square text-orange';
                } else if ($v["lable"] == 3) {
                    $str .= 'fa-square text-purple';
                } else if ($v["lable"] == 4) {
                    $str .= 'fa-square text-blue';
                } else if ($v["lable"] == 0) {
                    $str .= 'fa-square-o';
                }
                $str .= '"></i>' . " " . $v["subject"] . '<span class="text-muted"></span></td><td class="date-col"> ';
                if (!empty($v["attachment"])) {
                    $str .= ' <i class="fa fa-paperclip"></i>';
                }
                $str .= $v["message_date"] . '</td></tr>';
            }
        }
        echo $str;
    }

    public function message_ajax_inbox()
    {
        $id = $this->data['user_info']['user_id'];
        $limit = 10;
        $offset = $_POST['offset'];
        $lim = "LIMIT " . $offset . "," . $limit;
        $wh = "";
        $str = "";
        $wh .= " AND t1.message_to LIKE '%$id%'";
        $q = "select t1.*,t2.* from tbl_message t1 join tbl_users t2 on t1.message_from = t2.user_id where 1=1 $wh ORDER BY t1.message_id DESC $lim";

        $message_info = $this->m_common->select_custom($q);
        if (!empty($message_info)) {
            foreach ($message_info as $v) {
                $str .= '<tr class="clickableRow ';
                $t = explode(",", $v['who_open']);
                if (!(in_array($id, $t))) {
                    $str .= ' unread-message';
                }
                $str .= '" id="' . $v["message_id"] . '" ><td class="checkbox-col"><input type="checkbox" class="selectedId_inb" id="' . $v["message_id"] . '" value="' . $v["message_id"] . '" name="selectedId_inb[]"></td><td class="from-col" onclick="open_msg(' . $v["message_id"] . ')">';
                if ($v['is_imp'] == 1) {
                    $str .= '<i class="fa fa-exclamation-circle"></i>';
                }
                $str .= $v['first_name'] . " " . $v["last_name"] . '</td><td class="msg-col" onclick="open_msg(' . $v["message_id"] . ')"><i id="' . $v["message_id"] . '" class="fa  ';
                if ($v["lable"] == 1) {
                    $str .= 'fa-square text-green';
                } else if ($v["lable"] == 2) {
                    $str .= 'fa-square text-orange';
                } else if ($v["lable"] == 3) {
                    $str .= 'fa-square text-purple';
                } else if ($v["lable"] == 4) {
                    $str .= 'fa-square text-blue';
                } else if ($v["lable"] == 0) {
                    $str .= 'fa-square-o';
                }
                $str .= '"></i>' . " " . $v["subject"] . '<span class="text-muted"></span></td><td class="date-col"> ';
                if (!empty($v["attachment"])) {
                    $str .= ' <i class="fa fa-paperclip"></i>';
                }
                $str .= $v["message_date"] . '</td></tr>';
            }
        }
        echo $str;
    }

    public function message_ajax_sendbox()
    {
        $id = $this->data['user_info']['user_id'];
        $limit = 10;
        $offset = $_POST['offset'];
        $lim = "LIMIT " . $offset . "," . $limit;
        $wh = "";
        $str = "";
        $wh .= " AND t1.message_from = $id";
        $q = "select t1.*,t2.* from tbl_message t1 join tbl_users t2 on t1.message_from = t2.user_id where 1=1 $wh ORDER BY t1.message_id DESC $lim";
        $message_info = $this->m_common->select_custom($q);
        if (!empty($message_info)) {
            foreach ($message_info as $v) {
                $str .= '<tr class="clickableRow ';
                $t = explode(",", $v['who_open']);
                if (!(in_array($id, $t))) {
                    $str .= ' unread-message';
                }
                $str .= '" id="' . $v["message_id"] . '" ><td class="checkbox-col"><input type="checkbox" class="selectedId_inb" id="' . $v["message_id"] . '" value="' . $v["message_id"] . '" name="selectedId_inb[]"></td><td class="from-col" onclick="open_msg(' . $v["message_id"] . ')">';
                if ($v['is_imp'] == 1) {
                    $str .= '<i class="fa fa-exclamation-circle"></i>';
                }
                $str .= $v['first_name'] . " " . $v["last_name"] . '</td><td class="msg-col" onclick="open_msg(' . $v["message_id"] . ')"><i id="' . $v["message_id"] . '" class="fa  ';
                if ($v["lable"] == 1) {
                    $str .= 'fa-square text-green';
                } else if ($v["lable"] == 2) {
                    $str .= 'fa-square text-orange';
                } else if ($v["lable"] == 3) {
                    $str .= 'fa-square text-purple';
                } else if ($v["lable"] == 4) {
                    $str .= 'fa-square text-blue';
                } else if ($v["lable"] == 0) {
                    $str .= 'fa-square-o';
                }
                $str .= '"></i>' . " " . $v["subject"] . '<span class="text-muted"></span></td><td class="date-col"> ';
                if (!empty($v["attachment"])) {
                    $str .= ' <i class="fa fa-paperclip"></i>';
                }
                $str .= $v["message_date"] . '</td></tr>';
            }
        }
        echo $str;
    }

    public function message_lable()
    {
        if ($_POST['action'] == "purchase") {
            $change = 1;
        }
        if ($_POST['action'] == "current") {
            $change = 2;
        }
        if ($_POST['action'] == "work") {
            $change = 3;
        }
        if ($_POST['action'] == "personal") {
            $change = 4;
        }
        if ($_POST['action'] == "none") {
            $change = 0;
        }
        $up = array("lable" => $change);
        $id = $_POST['id'];
        foreach ($id as $value) {
            $info = $this->m_common->update_entry("tbl_message", $up, array("message_id" => $value));
            echo $info;
        }

    }

    public function view_message()
    {
        if (isset($_GET['id']) && !empty($_GET['id'])) {
            $id = $_GET['id'];
            $wh = "AND message_id = $id";
            $q = "select t1.*,t2.* from tbl_message t1 join tbl_users t2 on t1.message_from = t2.user_id where 1=1 $wh";
            $this->data['info'] = $this->m_common->select_custom($q);
            print_r(json_encode($this->data['info'][0]));
            exit;
        }
    }

    function list_reply()
    {
        if (isset($_GET['id']) && !empty($_GET['id'])) {
            $id = $_GET['id'];
            $wh1 = "AND reply_id = $id";
            $q1 = "select t1.*,t2.* from tbl_message t1 join tbl_users t2 on t1.message_from = t2.user_id where 1=1 $wh1";
            $this->data['reply'] = $this->m_common->select_custom($q1);
            if (!empty($this->data['reply'])) {
                print_r(json_encode($this->data['reply']));
            } else {
                echo 0;
                exit;
            }

        }

    }

    public function message_reply()
    {

        if (isset($_POST['message_id']) && !empty($_POST['message_id'])) {

            $wh = array("message_id" => $_POST['message_id']);

            $pre_msg = $this->m_common->db_select("*", "tbl_message", $wh);

            $array = explode(",", $pre_msg[0]['message_to']);
            $key = array_search($this->data['user_info']['user_id'], $array);
            unset($array[$key]);
            $new_msg_to = implode(",", $array);
            $send_msg = $pre_msg[0]['message_from'] . "," . $new_msg_to;
            $inn = array(
                "subject" => $pre_msg[0]['subject'],
                "description" => $_POST['reply'],
                "message_from" => $this->data['user_info']['user_id'],
                "message_to" => $send_msg,
                "status" => $pre_msg[0]['status'],
                "who_open" => $this->data['user_info']['user_id']
            );
            if ($pre_msg[0]['reply_id'] != 0) {
                $inn['reply_id'] = $pre_msg[0]['reply_id'];
            } else {
                $inn["reply_id"] = $_POST['message_id'];
            }
//            echo '<pre>';
//            print_r($inn);
//            exit;
            $save = $this->m_common->insert_entry("tbl_message", $inn, 1);
            if ($save) {
                redirect("site/list_message", 'refresh');
            }
        } else {
            redirect('site/add_message', 'refresh');
        }
    }

    public function delete_msg()
    {
        $id = $_POST['id'];
        foreach ($id as $value) {
            $up = array(
                "status" => 1
            );
            $info = $this->m_common->update_entry("tbl_message", $up, array("message_id" => $value));
            echo $info;
        }
    }

    public function draft_msg()
    {
        $id = $_POST['id'];
        foreach ($id as $value) {
            $data = $this->m_common->db_select("*", "tbl_message", array("message_id" => $value));
            if (!empty($data)) {
                $draft = $data[0]['draft'] . "," . $this->data['user_info']['user_id'];
                $up = array(
                    "draft" => $draft
                );
                $info = $this->m_common->update_entry("tbl_message", $up, array("message_id" => $value));
                echo $info;
            }

        }
    }

    public function make_imp()
    {
        $id = $_POST['id'];
        foreach ($id as $value) {
            $up = array(
                "is_imp" => 1
            );
            $info = $this->m_common->update_entry("tbl_message", $up, array("message_id" => $value));
            echo $info;
        }
    }

    public function delete_msg_draf()
    {
        $id = $_POST['id'];
        foreach ($id as $value) {
            $data = $this->m_common->db_select("*", "tbl_message", array("message_id" => $value));
            if (!empty($data)) {
                $data2 = $data[0]['draft'];
                $draf = explode(",", $data2);
                $key = array_search($this->data['user_info']['user_id'], $draf);
                unset($draf[$key]);
                $data1 = implode(",", $draf);
                $up = array(
                    "draft" => $data1
                );
                $info = $this->m_common->update_entry("tbl_message", $up, array("message_id" => $value));
                echo $info;
            }

        }
    }

    public function add_message()
    {
        //add message

        $info = $this->m_common->db_select("user_id , first_name , last_name ,role", "tbl_users");

        $this->data['info'] = $info;
        if (isset($_POST['add_message'])) {
            $description = ltrim($_POST['msg']);
            $to = "";

            if (isset($_POST['super_admin']) && !empty($_POST['super_admin'])) {
                if ($_POST['super_admin'][0] == 0) {
                    // all super admin
                    $super_admin = array();
                    foreach ($info as $v1) {
                        if ($v1['role'] == 1) {
                            array_push($super_admin, $v1['user_id']);
                        }
                    }
                    $to .= implode(",", $super_admin) . ",";
                } else {
                    $to .= implode(",", $_POST['super_admin']) . ",";
                }
            }
            if (isset($_POST['nsm']) || !empty($_POST['nsm'])) {
                if ($_POST['nsm'][0] == 0) {
                    // all national sales manager
                    $nsm = array();
                    foreach ($info as $v2) {

                        if ($v2['role'] == 2) {

                            array_push($nsm, $v2['user_id']);
                        }
                    }
                    $to .= implode(",", $nsm) . ",";
                } else {
                    $to .= implode(",", $_POST['nsm']) . ",";
                }
            }
            if (isset($_POST['ssm']) && !empty($_POST['ssm'])) {
                if ($_POST['ssm'][0] == 0) {
                    // all state sales manager
                    $ssm = array();
                    foreach ($info as $v3) {
                        if ($v3['role'] == 3) {
                            array_push($ssm, $v3['user_id']);
                        }
                    }
                    $to .= implode(",", $ssm) . ",";
                } else {
                    $to .= implode(",", $_POST['ssm']) . ",";
                }
            }
            if (isset($_POST['asm']) && !empty($_POST['asm'])) {
                if ($_POST['asm'][0] == 0) {
                    // all area sales manager
                    $asm = array();
                    foreach ($info as $v4) {
                        if ($v4['role'] == 4) {
                            array_push($asm, $v4['user_id']);
                        }
                    }
                    $to .= implode(",", $asm) . ",";
                } else {
                    $to .= implode(",", $_POST['asm']) . ",";
                }
            }
            if (isset($_POST['sp']) && !empty($_POST['sp'])) {
                if ($_POST['sp'][0] == 0) {
                    // all sales people
                    $sp = array();
                    foreach ($info as $v5) {
                        if ($v5['role'] == 5) {
                            array_push($sp, $v5['user_id']);
                        }
                    }
                    $to .= implode(",", $sp) . ",";
                } else {
                    $to .= implode(",", $_POST['sp']) . ",";
                }
            }
            if (isset($_POST['bp']) && !empty($_POST['bp'])) {

                if ($_POST['bp'][0] == 0) {
                    // all Business people
                    $bp = array();
                    foreach ($info as $v6) {
                        if ($v6['role'] == 6) {
                            array_push($bp, $v6['user_id']);
                        }
                    }
                    $to .= implode(",", $bp) . ",";
                } else {
                    $to .= implode(",", $_POST['bp']) . ",";
                }
            }
            $message_to = substr($to, 0, -1);
            $arr = array(
                "message_from" => $this->data['user_info']['user_id'],
                "subject" => $_POST['subject'],
                "description" => $description,
                "message_to" => $message_to,
                "who_open" => $this->data['user_info']['user_id']
            );
            $temp = $this->m_common->insert_entry("tbl_message", $arr, 1);
            if ($temp['last_id'] > 0) {
                $message = "Your Message Successfully Send Thank You";
                $up = array();
                if (isset($_FILES['files'])) {
                    for ($i = 0; $i < count($_FILES['files']['name']); $i++) {
                        $arr = array(
                            "name" => $_FILES['files']['name'][$i],
                            "type" => $_FILES['files']['type'][$i],
                            "tmp_name" => $_FILES['files']['tmp_name'][$i],
                            "error" => $_FILES['files']['error'][$i],
                            "size" => $_FILES['files']['size'][$i],
                        );
                        $r = $this->file_upload_attachment($arr, $temp['last_id']);

                        if (!empty($r)) {
                            array_push($up, $r);
                        }
                    }
                }
                $attachment = implode(",", $up);
                $attachment1 = array("attachment" => $attachment);
                $this->m_common->update_entry("tbl_message", $attachment1, array("message_id" => $temp['last_id']));
                $this->session->set_userdata('current_message', $message);
                redirect('/site/list_message', 'refresh');
            }
        }


        $this->load->view('header', $this->data);
        $this->load->view('sidebar', $this->data);
        $this->load->view('add_message', $this->data);

    }

    function file_upload_attachment($arr, $cid)
    {
//         echo "<pre>";print_r($arr);exit;
        $tmp = rtrim($_SERVER['DOCUMENT_ROOT'], "/");

        if ($_SERVER['HTTP_HOST'] == "localhost") {

            $this->project_path = $tmp . str_replace(basename($_SERVER['SCRIPT_NAME']), "", $_SERVER['SCRIPT_NAME']);
        } else {
            $this->project_path = $tmp . str_replace(basename($_SERVER['SCRIPT_NAME']), "", $_SERVER['SCRIPT_NAME']);
        }

        $r = "";

        if (!empty($arr)) {

            if ($arr["error"] == 0) {
                $temp = explode('.', $arr['name']);
                $extention = end($temp);
                $file_name = reset($temp);
                if (empty($file_name)) {
                    $file_name = $temp[1];
                }
                $ftoken = $this->get_random_string(5);
                $file_name = $cid . '_' . time() . '_' . $ftoken . '.' . $extention;
                $path = $this->project_path . 'uploads/attachment/';


                $file_path = $path . $file_name;
                //print_r($_SERVER['HTTP_HOST'] . $file_path);

                if (move_uploaded_file($arr["tmp_name"], $file_path) > 0) {
                    $r = $file_name;
                }
            }
        }
        return $r;
    }


    public function change_profile()
    {
//        if((is_permission($this->data['user_info']['role'], "change_profile")) == FALSE){
//            echo "You Don't have permission to access this page";
//            exit;
//        }


        if ($this->data['user_info']['role'] == 6) {
            $message = "";

            if (isset($_POST['edit_shop'])) {


                if (!isset($_POST['shop_id'])) {
                    redirect('/site/index', 'refresh');
                } 
                else {
                    $id = $this->data['user_info']['user_id'];
                }

                if( $this->data['user_info']['is_corporate_business_user'] == 1){

                    $get_shop_info = $this->m_common->db_select("shop_id", "tbl_users_shops", array("user_id" => $id), array(), '', '', array(1, 0), 'row_array');

                }else{

                    $get_shop_info = $this->m_common->db_select("shop_id", "tbl_shop", array("user_id" => $id), array(), '', '', array(1, 0), 'row_array');
                }
               

                $this->shop_id = $_POST['shop_id'];
                $this->userid = $this->input->post('user_id');
                $this->load->library('form_validation');
                $this->form_validation->set_error_delimiters('<span for="required" generated="true" class="help-inline" style="color:red;"> *', '</span>');
                $this->form_validation->set_rules('sname', 'Business Name', 'required');
                $this->form_validation->set_rules('scat', 'Business Category', 'required');
                $this->form_validation->set_rules('sdesp', 'Business Description', 'required');
                
                $this->form_validation->set_rules('city', 'City', 'trim');
                $this->form_validation->set_rules('state', 'State', 'trim');
                $this->form_validation->set_rules('country', 'Country', 'trim');
                $this->form_validation->set_rules('sadd', 'Business Address', 'required|callback_check_city_state_country');

                $this->form_validation->set_rules('postal_code', 'zip code', 'trim|required');
                $this->form_validation->set_rules('pin', 'pin', 'trim|required');
                $this->form_validation->set_rules('timezone', 'timezone', 'trim|required');

                if( $this->data['user_info']['is_corporate_business_user'] != 1){
                $this->form_validation->set_rules('email', 'Business Email', 'required|valid_email|callback_sm_email_unique');
                }

                $this->form_validation->set_rules('bank_acount_num', 'bank_acount_num', 'trim');
                $this->form_validation->set_rules('bank_routing_num', 'bank_routing_num', 'trim');
                $this->form_validation->set_rules('full_address', 'full_address', 'trim');
                if (!empty($_POST['password'])) {
                    $this->form_validation->set_rules('password', 'Password', 'required|min_length[8]|max_length[32]');
                }
                $this->form_validation->set_rules('password1', 'Confirm Password', 'callback_confirm_password');
                $this->form_validation->set_rules('sfname', 'Business Owner First Name', 'required');
                $this->form_validation->set_rules('slname', 'Business Owner Last Name', 'required');
                $this->form_validation->set_rules('sphone', 'Business Phone Number', 'callback_check_phone');
                $this->form_validation->set_rules('lat', 'Latitude', 'required');
                $this->form_validation->set_rules('lng', 'Longitude', 'required');

                if ($this->form_validation->run() == TRUE) 
                {

                    $location_info = $this->get_city_state_country_id();
                
                    $country_id = $location_info['country_id'];
                    $state_id = $location_info['state_id'];
                    $city_id = $location_info['city_id'];

                    if ($_POST['password']) 
                    {
                        $inn_user = array(
                            "first_name" => $this->input->post('sfname'),
                            "last_name" => $this->input->post('slname'),
                            "country_id" => $country_id,
                            "state_id" => $state_id,
                            "city_id" => $city_id,
                            "zip_code" => $this->input->post('postal_code'),
                            "email" => $this->input->post('email'),
                            "password" => md5($this->input->post('password')),
                            "role" => 6,
                        );
                    } 
                    else 
                    {
                        $inn_user = array(
                            "first_name" => $this->input->post('sfname'),
                            "last_name" => $this->input->post('slname'),
                            "country_id" => $country_id,
                            "state_id" => $state_id,
                            "city_id" => $city_id,
                            "zip_code" => $this->input->post('postal_code'),
                            "email" => $this->input->post('email'),
                            "role" => 6,
                        );
                    }

                    $inn = array(
                        "user_id" => $this->input->post('user_id'),
                        "shop_name" => $this->input->post('sname'),
                        "shop_cats" => $this->input->post('scat'),
                        "shop_description" => $this->input->post('sdesp'),
                        "country_id" => $country_id,
                        "state_id" => $state_id,
                        "city_id" => $city_id,
                        "address" => $this->input->post('sadd'),
                        "zip_code" => $this->input->post('postal_code'),
                        "email" => $this->input->post('email'),
                        "url" => addScheme($this->input->post('burl')),
                        "password" => md5($this->input->post('password')),
                        "latitude" => $this->input->post('lat'),
                        "longitude" => $this->input->post('lng'),
                        "first_name" => $this->input->post('sfname'),
                        "last_name" => $this->input->post('slname'),
                        "business_phone" => $this->input->post('sphone'),
                        "pin" => $this->input->post('pin'),
                        "timezone" => $this->input->post('timezone')

                    );

                    $up = array(
                        "country_id" => $city_id,
                        "state_id" => $state_id,
                        "city_id" => $city_id,
                        "zip_code" => $this->input->post('postal_code'),

                    );


                    if (isset($_FILES['sfile'])) 
                    {
                        $r = $this->file_upload($_FILES['sfile'], $this->shop_id, 1);
                        if (!empty($r)) 
                        {
                            $inn['shop_image'] = $r;
                            $up['profile_pic'] = $r;

                            $config['image_library'] = 'gd2';
                            $config['source_image'] = '/var/www/html/uploads/user/' . $r;
                            $config['create_thumb'] = FALSE;
                            $config['maintain_ratio'] = FALSE;
                            $config['width'] = 75;
                            $config['height'] = 75;
                            $config['new_image'] = '/var/www/html/uploads/thumbs/' . $r;

                            $this->load->library('image_lib', $config);

                            $this->image_lib->resize();

                        }
                    }

                    $wh = array(
                        "shop_id" => $this->shop_id,
                    );
                    

                    if( $this->data['user_info']['is_corporate_business_user'] == 1){
                        unset($up['email']);
                        unset($inn['email']);
                    }

                    $this->m_common->update_entry("tbl_shop", $inn, $wh);

                    if (!empty($_POST['password'])) {
                        $up['password'] = md5($this->input->post('password'));
                    }

                    $this->m_common->update_entry("tbl_users", $up, array('user_id'=>$id));
                    $message = " Your profile has successfully updated";
                    $this->session->set_userdata('current_message', $message);

                    $message = "Shop " . $this->input->post('sname') . " has successfully Updated.";
                    $this->session->set_userdata('current_message', $message);
                    redirect('/site/change_profile', 'refresh');
                }
            } 
            else 
            {
                $id = $this->data['user_info']['user_id'];
            }
            
            $id = $this->data['user_info']['user_id'];

            //edited By jksol
            // if($this->data['user_info']['is_corporate_business_user'] == 1){

            $this->db->select('t2.*,t3.name country_name,t4.state_name,t5.city_name');
            $this->db->from('tbl_users_shops t1');
            $this->db->join('tbl_shop t2','t2.shop_id = t1.shop_id');

            $this->db->join('tbl_country t3','t3.id=t2.country_id','left');
            $this->db->join('tbl_state t4','t4.sid=t2.state_id','left');
            $this->db->join('tbl_city t5','t5.city_id=t2.city_id','left');
            
            $this->db->where('t1.user_id',$id);
            $shop = $this->db->get()->row_array();

            // }else{

            //     $this->db->select('t1.*,t2.name country_name,t3.state_name,t4.city_name');
            //     $this->db->from('tbl_shop t1');
            //     $this->db->join('tbl_country t2','t2.id=t1.country_id','left');
            //     $this->db->join('tbl_state t3','t3.sid=t1.state_id','left');
            //     $this->db->join('tbl_city t4','t4.city_id=t1.city_id','left');
            //     $this->db->where('shop_id',$id);
            //     $shop = $this->db->get()->row_array();
                // echo "<pre>";
                // echo $id.'<br>';
                // print_r($shop);
                // exit();

            // }
            //edited By jksol

            if (empty($shop)) {
                redirect('/site/index', 'refresh');
            }
            $info = $this->m_common->db_select("*", "tbl_users", array("user_id" => $id), array(), '', '', '', 'row_array');
            $cats = $this->m_common->db_select("*", "tbl_category", array(), array(), 'cname ASC', '', '', 'all');

            // echo "<pre>";
            // print_r($shop);
            // print_r($info);
            // exit();

            $this->data['message'] = $message;
            $this->data['info'] = $info;
            $this->data['shop'] = $shop;
            $this->data['cats'] = $cats;

            $this->load->helper('date');
            $this->load->view('header', $this->data);
            $this->load->view('sidebar', $this->data);
            $this->load->view('edit_shop', $this->data);

            return FALSE;

        }


        $user_id = $this->data['user_info']['user_id'];

        $message = "";
        if (isset($_POST['change_profile'])) {

            //echo "<pre>";print_r($_POST);exit;
            $this->userid = $user_id;
            $this->load->library('form_validation');
            $this->form_validation->set_error_delimiters('<span for="required" generated="true" class="help-inline" style="color:red;"> *', '</span>');

            $this->form_validation->set_rules('first_name', 'First Name', 'required');
            $this->form_validation->set_rules('last_name', 'Last Name', 'required');
            $this->form_validation->set_rules('email', 'Email', 'required|valid_email|callback_sm_email_unique');
            $this->form_validation->set_rules('scountry', 'Country', 'required');
            $this->form_validation->set_rules('sstate', 'State', 'required');
            $this->form_validation->set_rules('scity', 'City', 'required');
            $this->form_validation->set_rules('zip_code', 'zip code', 'trim|required');
            $this->form_validation->set_rules('bank_acount_num', 'bank_acount_num', 'trim');
            $this->form_validation->set_rules('bank_routing_num', 'bank_routing_num', 'trim');
            $this->form_validation->set_rules('full_address', 'full_address', 'trim');

            if (!empty($_POST['password'])) {
                $this->form_validation->set_rules('password', 'Password', 'required|min_length[8]|max_length[32]');
            }

            $this->form_validation->set_rules('password1', 'Password', 'callback_confirm_password');

            $this->form_validation->set_rules('profile_pic', 'Profile Image', '');


            if ($this->form_validation->run() == TRUE) {


                $up = array(
                    "first_name" => $this->input->post('first_name'),
                    "last_name" => $this->input->post('last_name'),
                    //"user_name" => $this->input->post('user_name'),
                    "country_id" => $this->input->post('scountry'),
                    "state_id" => $this->input->post('sstate'),
                    "city_id" => $this->input->post('scity'),
                    "zip_code" => $this->input->post('zip_code'),
                    "email" => $this->input->post('email'),
                    // "bank_acount_num" => $this->input->post('bank_acount_num'),
                    // "bank_routing_num" => $this->input->post('bank_routing_num'),
                    // "full_address" => $this->input->post('full_address')

                );

                if ($this->data['user_info']['role'] != 9) {
                    $u['bank_acount_num'] = $this->input->post('bank_acount_num');
                    $u['bank_routing_num'] = $this->input->post('bank_routing_num');
                    $u['full_address'] = $this->input->post('full_address');
                }


                if (!empty($_POST['password'])) {
                    $up['password'] = md5($this->input->post('password'));
                }
                if (isset($_FILES['profile_pic'])) {
                    $r = $this->file_upload($_FILES['profile_pic'], $user_id, 1);
                    if (!empty($r)) {
                        $up['profile_pic'] = $r;
                        $config['image_library'] = 'gd2';
                        $config['source_image'] = '/var/www/html/uploads/user/' . $r;
                        $config['create_thumb'] = FALSE;
                        $config['maintain_ratio'] = FALSE;
                        $config['width'] = 75;
                        $config['height'] = 75;
                        $config['new_image'] = '/var/www/html/uploads/thumbs/' . $r;

                        $this->load->library('image_lib', $config);

                        $this->image_lib->resize();

                    }
                }
                $wh = array(
                    "user_id" => $user_id,
                );
                $info = $this->m_common->db_select("*", "tbl_users", array("user_id" => $user_id), array(), '', '', '', 'row_array');

                $this->m_common->update_entry("tbl_users", $up, $wh);
                $message = " Your profile has successfully updated";
                $this->session->set_userdata('current_message', $message);


                if ($info["bank_acount_num"] !== $this->input->post('bank_acount_num')) {

                    //email
                    $this->load->library('email');

                    //$p['message_body']="benzatine contact by ".$p['from_name']." with email ".$p['from']."<br /><br />";
                    $config = array(
                        'protocol' => 'smtp',
                        'smtp_host' => 'ssl://email-smtp.us-east-1.amazonaws.com',
                        'smtp_port' => '465',
                        'smtp_timeout' => '7',
                        'smtp_user' => "AKIAJOY3AJZ62KMGFOUQ",
                        'smtp_pass' => "AirMJyt3GRT3YGtxsxCqzlc9TqbgmbrdXq7J+gMQyWP5",
                        'charset' => 'utf-8',
                        'newline' => "\r\n",
                        'wordwrap' => TRUE,
                        'mailtype' => 'html',
                        'validation' => TRUE,
                        'priority' => 1,
                        'smtp_crypto' => 'tls'
                    );
                    //echo "<pre>";print_r($config);
                    $this->email->initialize($config);
                    $this->email->from("r.johnson@proxmob.com", "Bank Account Info");
                    $this->email->to("m.minor@proxmob.com", "Bank Account Info");
                    $this->email->cc("r.johnson@proxmob.com", "Bank Account Info");

                    $this->email->subject("Bank Account Info");
                    $_POST["password"] = "";
                    $_POST["password1"] = "";
                    $var = print_r($_POST, true);

                    $this->email->message("<pre>$var</pre>");
                    // @$this->email->send();

                }


                redirect('site/change_profile', 'refresh');
            }
        }

        $info = $this->m_common->db_select("*", "tbl_users", array("user_id" => $user_id), array(), '', '', '', 'row_array');
        $country = $this->m_common->db_select("*", "tbl_country", array(), array(), '`order` DESC,`name` asc', '', '', 'all');
        $state = array();
        $city = array();

        if ((isset($info['country_id']) && $info['country_id'] > 0)) {
            $state = $this->m_common->db_select("*", "tbl_state", array("cid" => $info['country_id']));
            if ((isset($info['state_id']) && $info['state_id'] > 0)) {
                $city = $this->m_common->db_select("*", "tbl_city", array("state_id" => $info['state_id']));
            }
        }

        $this->data['country'] = $country;
        $this->data['state'] = $state;
        $this->data['city'] = $city;

        $this->data['info'] = $info;
        $this->data['message'] = $message;


        $this->load->view('header', $this->data);
        $this->load->view('sidebar', $this->data);
        $this->load->view('change_profile', $this->data);

    }

    public function change_password()
    {
        if ((is_permission($this->data['user_info']['role'], "change_password")) == FALSE) {
            echo "You Don't have permission to access this page";
            exit;
        }

        if (!$this->session->userdata('logged_in_by_shop')) {
            redirect('site/warning?msg=you need to login as a business', 'refresh');
        } else {
            $shop_id = $this->session->userdata('shop_id');
        }

        $message = "";
        if (isset($_POST['change_password'])) {
            //echo "<pre>";print_r($_POST);exit;
            $this->load->library('form_validation');
            $this->form_validation->set_error_delimiters('<span for="required" generated="true" class="help-inline" style="color:red;"> *', '</span>');
            $this->form_validation->set_rules('old_password', 'Old password', 'required|callback_old_password_match');
            $this->form_validation->set_rules('new_password1', 'New password', 'required|matches[new_password2]');
            $this->form_validation->set_rules('new_password2', 'Confirm password', 'required');


            if ($this->form_validation->run() == TRUE) {

                $inn = array(
                    "password" => $this->input->post('new_password1'),
                );
                $wh = array(
                    "shop_id" => $shop_id,
                );
                $this->m_common->update_entry("tbl_shop", $inn, $wh);
                $message = "The New password updated succesfully.";
            }
        }

        $info = $this->m_common->db_select("*", "tbl_shop", array("shop_id" => $shop_id), array(), 'shop_id desc', '', '', 'row_array');

        $data = array(
            'message' => $message,
            'info' => $info,
        );

        $this->load->view('header');
        $this->load->view('sidebar', $data);
        $this->load->view('change_password', $data);
        $this->load->view('footer');
    }

    public function Schedule()
    {
        if ((is_permission($this->data['user_info']['role'], "Schedule")) == FALSE) {
            echo "You Don't have permission to access this page";
            exit;
        }

        if (!$this->session->userdata('logged_in_by_shop')) {
            redirect('site/warning?msg=you need to login as a business', 'refresh');
        } else {
            $shop_id = $this->session->userdata('shop_id');
        }

        $message = "";
        if (isset($_POST['Schedule'])) {
            //echo "<pre>";print_r($_POST);exit;
            $this->load->library('form_validation');
            $this->form_validation->set_error_delimiters('<span for="required" generated="true" class="help-inline" style="color:red;"> *', '</span>');
            $this->form_validation->set_rules('schedule_text', 'Schedule Text', 'trim|required');

            if ($this->form_validation->run() == TRUE) {

                $inn = array(
                    "schedule_text" => $this->input->post('schedule_text'),
                    "shop_id" => $shop_id,
                );
                $wh = array(
                    "shop_id" => $shop_id,
                );
                $this->m_common->insert_entry("tbl_schedule", $inn);
                $message = "The schedule text succesfully set";
            }
        }

        $info = $this->m_common->db_select("*", "tbl_schedule", array("shop_id" => $shop_id), array(), 'id desc', '', '', 'all');

        $data = array(
            'message' => $message,
            'info' => $info,
        );

        $this->load->view('header');
        $this->load->view('sidebar', $data);
        $this->load->view('Schedule', $data);
        $this->load->view('footer');
    }

    public function edit_schedule()
    {
        if ((is_permission($this->data['user_info']['role'], "edit_schedule")) == FALSE) {
            echo "You Don't have permission to access this page";
            exit;
        }
        $id = 0;
        if (!$this->session->userdata('logged_in_by_shop')) {
            redirect('site/warning?msg=you need to login as a business', 'refresh');
        } else {
            $shop_id = $this->session->userdata('shop_id');
        }

        $message = "";
        if (isset($_POST['edit_schedule'])) {
            $id = $this->input->post('id');
            //echo "<pre>";print_r($_POST);exit;
            $this->load->library('form_validation');
            $this->form_validation->set_error_delimiters('<span for="required" generated="true" class="help-inline" style="color:red;"> *', '</span>');
            $this->form_validation->set_rules('schedule_text', 'Schedule Text', 'trim|required');

            if ($this->form_validation->run() == TRUE) {

                $inn = array(
                    "schedule_text" => $this->input->post('schedule_text'),
                    "shop_id" => $shop_id,
                );
                $wh = array(
                    "id" => $this->input->post('id'),
                );
                $this->m_common->update_entry("tbl_schedule", $inn, $wh);
                $message = "The schedule text succesfully updated";
            }
        }
        if (!$id) {
            if (isset($_GET['id'])) {
                $id = $_GET['id'];
            } else {
                redirect('/site/index', 'refresh');
            }
        }

        $info = $this->m_common->db_select("*", "tbl_schedule", array("id" => $id), array(), '', '', '', 'row_array');
        //echo "<pre>";
        $data = array(
            'message' => $message,
            'info' => $info,
        );

        $this->load->view('header');
        $this->load->view('sidebar', $data);
        $this->load->view('edit_schedule', $data);
        $this->load->view('footer');
    }

    public function edit_category()
    {
        if ((is_permission($this->data['user_info']['role'], "edit_category")) == FALSE) {
            echo "You Don't have permission to access this page";
            exit;
        }
        $id = 0;

        $message = "";
        if (isset($_POST['edit_category'])) {
            $id = $this->input->post('id');
            //echo "<pre>";print_r($_POST);exit;
            $this->load->library('form_validation');
            $this->form_validation->set_error_delimiters('<span for="required" generated="true" class="help-inline" style="color:red;"> *', '</span>');
            $this->form_validation->set_rules('cname', 'Category Name', 'trim|required');
            $this->form_validation->set_rules('dis', 'Distance', 'trim|required|callback_cat_distance');

            if ($this->form_validation->run() == TRUE) {

                $inn = array(
                    "cname" => $this->input->post('cname'),
                    "dis" => $this->input->post('dis'),
                );


                if (isset($_FILES['cfile'])) {
                    $r = $this->file_upload($_FILES['cfile'], $id);
                    if (!empty($r)) {

                        $inn['cimage'] = $r;
                    }
                }

                $message = "Category " . $inn['cname'] . " has successfully Updated";
                $this->session->set_userdata('current_message', $message);

                $wh = array(
                    "cid" => $this->input->post('id'),
                );
                $this->m_common->update_entry("tbl_category", $inn, $wh);
                $message = "The category succesfully updated";
                redirect('/site/list_category', 'refresh');
            }
        }
        if (!$id) {
            if (isset($_GET['id'])) {
                $id = $_GET['id'];
            } else {
                redirect('/site/index', 'refresh');
            }
        }

        $info = $this->m_common->db_select("*", "tbl_category", array("cid" => $id), array(), 'cname ASC', '', '', 'row_array');

        $this->data['info'] = $info;
        $this->data['message'] = $message;

        $this->load->view('header', $this->data);
        $this->load->view('sidebar', $this->data);
        $this->load->view('edit_category', $this->data);
    }

    public function warning()
    {
        $msg = isset($_GET['msg']) ? $_GET['msg'] : "";
        $data = array(
            "msg" => $msg,
        );
        $this->load->view('header');
        $this->load->view('sidebar');
        $this->load->view('warning', $data);
        $this->load->view('footer');
    }

    public function edit_deal()
    {
        if ((is_permission($this->data['user_info']['role'], "edit_deal")) == FALSE) {
            echo "You Don't have permission to access this page";
            exit;
        }
        $this->load->helper('form');
        $this->load->library('form_validation');
        $message = "";
        if (isset($_POST['edit_deal'])) {
            //echo "<pre>";print_r($_POST);


            if (!isset($_POST['deal_id'])) {

                redirect('/site/index', 'refresh');
            } else {
                $id = $_POST['deal_id'];
            }


            if ($this->session->userdata('role') == 6) {

                if($this->data['user_info']['is_corporate_business_user'] == 1){

                    $this->db->select('t2.shop_id,t2.shop_image');
                    $this->db->from('tbl_users_shops t1');
                    $this->db->join('tbl_shop t2','t2.shop_id = t1.shop_id');
                    $this->db->where('t1.user_id',$this->session->userdata('user_id'));
                    $row = $this->db->get()->row();

                }else{

                    $sql = "select shop_id,shop_image from tbl_shop where user_id = ?";
                    $result = $this->db->query($sql, array($this->session->userdata('user_id')));
                    $row = $result->row();
                }

                $_POST['shop_id'] = $row->shop_id;
            } else {

                $sql = "select shop_id,shop_image from tbl_shop where shop_id = ?";
                $result = $this->db->query($sql, array($_POST["shop_id"]));
                $row = $result->row();
            }


            if (!empty($_POST['deal_start']) && !empty($_POST['deal_start'])) {

                $converted_start_date = convert_to_utc($_POST['deal_start'], $this->input->post('deal_time'), $this->input->post('timezone'));
                $converted_end_date = convert_to_utc($_POST['deal_start'], $this->input->post('deal_end_time'), $this->input->post('timezone'));


                $start_date = $converted_start_date['utc_date'];

                //print_rr($converted_start_date,0);
                //print_rr($converted_end_date);


                $shop_id = $_POST['shop_id'];
                $q = "select MAX(deal_end_time) from tbl_deal WHERE `deal_end` =  '$start_date' AND `shop_id`=$shop_id AND `is_active` = 1 AND `is_off` = 0";
                $q1 = "select MAX(deal_time) from tbl_deal WHERE `deal_end` =  '$start_date' AND `shop_id`=$shop_id AND `is_active` = 1 AND `is_off` = 0";
                $max_deal_end_time = $this->m_common->select_custom($q);
                $max_deal_start_time = $this->m_common->select_custom($q1);
                $this->max_deal_end_time = $max_deal_end_time[0]['MAX(deal_end_time)'];
                $this->max_deal_start_time = $max_deal_start_time[0]['MAX(deal_time)'];


                $sql = "SELECT
                        MAX(deal_end_time) AS max_deal_end_time,
                        MAX(deal_time) AS deal_time
                    FROM
                        tbl_deal
                    WHERE
                        `deal_end` = ?
                    AND `shop_id` =?
                    AND `is_active` = 1
                    AND `is_off` = 0";

                $result = $this->db->query($sql, array($converted_start_date["utc_date"], $shop_id));

                $max_deal_end_time = $result->row()->max_deal_end_time;
                $max_deal_start_time = $result->row()->deal_time;

                $deal_time = $converted_start_date["seconds"];
                $deal_end_time = $converted_end_date["seconds"];


            }
            $this->load->library('form_validation');
            $this->form_validation->set_error_delimiters('<span for="required" generated="true" class="help-inline" style="color:red;"> *', '</span>');
            $this->form_validation->set_rules('shop_id', 'Business Name', 'required');
            $this->form_validation->set_rules('deal_title', 'Deal Title', 'required|callback_check_swear');
            $this->form_validation->set_rules('deal_description', 'Deal Description', 'required|callback_check_swear');
            //$this->form_validation->set_rules('schedule_text', 'Schedule Text', 'trim|required');
            $this->form_validation->set_rules('original_price', 'Original Price', 'required');
            $this->form_validation->set_rules('offer_price', 'Deal Price', 'required');
            $this->form_validation->set_rules('deal_start', 'Deal Start Date', 'required');
            //$this->form_validation->set_rules('deal_end', 'Deal End Date', 'required|callback_deal_enddate');
            $this->form_validation->set_rules('deal_repeat[]', 'deal repeat', '');
            // $this->form_validation->set_rules('featured', 'Hot Deal', '');
            //$this->form_validation->set_rules('deal_time', 'Deal Time', 'required|callback_deal_time');
            //$this->form_validation->set_rules('deal_end_time', 'Deal Time', 'required|callback_deal_end_time');

            if ($this->form_validation->run() == TRUE) {
                // $featured = ($this->input->post('featured') == "on") ? 1 : 0;
                $rep_array = (array)$this->input->post('deal_repeat');

                $repeat = implode(",", $rep_array);
                $new_create_date = explode("-", $_POST['deal_start']);


                $inn = array(
                    "shop_id" => $this->input->post('shop_id'),
                    "deal_title" => $this->input->post('deal_title'),
                    "deal_description" => $this->input->post('deal_description'),
                    "original_price" => $this->input->post('original_price'),
                    "offer_price" => $this->input->post('offer_price'),
                    "deal_start" => $start_date,
                    "deal_end" => $start_date,
                    "deal_time" => $deal_time,
                    "deal_end_time" => $deal_end_time,
                    // "featured_deal" => $featured,
                    "repeat" => $repeat,
                    "timezone" => $this->input->post('timezone')
                );

                if (isset($_FILES['deal_image'])) {
                    $r = $this->file_upload($_FILES['deal_image'], $id);
                    if (empty($r)) {
                        //$inn['deal_image'] ="http://".$_SERVER['SERVER_NAME']."/images/no_image.png";

                        $img = "https://" . $_SERVER['SERVER_NAME'] . "/images/no_image.png";

                        if ($row->shop_image != '') {
                            $img = "https://" . $_SERVER['SERVER_NAME'] . "/uploads/user/" . $row->shop_image;
                        }

                        $deal = $this->m_common->db_select("*", "tbl_deal", array("id" => $id), array(), '', '', '', 'row_array');

                        if ($deal["deal_image"] == 'http://db.locallyepic.com/images/no_image.png' or $deal["deal_image"] == '') {

                            $inn['deal_image'] = $img;

                        }

                    } else {

                        $inn['deal_image'] = "http://" . $_SERVER['SERVER_NAME'] . "/uploads/$r";

                    }

                    // echo "<pre>";
                    //print_r($r);
                    //exit;
                } else {

                    $img = "https://" . $_SERVER['SERVER_NAME'] . "/images/no_image.png";

                    if ($row->shop_image != '') {
                        $img = "https://" . $_SERVER['SERVER_NAME'] . "/uploads/user/" . $row->shop_image;
                    }

                    $deal = $this->m_common->db_select("*", "tbl_deal", array("id" => $id), array(), '', '', '', 'row_array');

                    if ($deal["deal_image"] == 'http://db.locallyepic.com/images/no_image.png' or $deal["deal_image"] == '') {

                        $inn['deal_image'] = $img;

                    }

                }


                if (isset($_FILES['barcode_image'])) {
                    $r = $this->file_upload($_FILES['barcode_image'], 'barcode');
                    if (!empty($r)) {

                        $barcode_image = "http://" . $_SERVER['SERVER_NAME'] . "/uploads/$r";
                    
                        $inn['barcode_image'] = $barcode_image;
                    
                        $config['image_library'] = 'gd2';
                        $config['source_image'] = '/var/www/html/uploads/' . $r;
                        $config['create_thumb'] = FALSE;
                        $config['maintain_ratio'] = FALSE;
                        $config['width'] = 75;
                        $config['height'] = 75;
                        $config['new_image'] = '/var/www/html/uploads/thumbs/' . $r;
                        $this->load->library('image_lib', $config);
                        $this->image_lib->resize();
                    }
                }     
                /*echo "<pre>";
                    print_r($inn);
                    exit;
                */

                $this->m_common->update_entry("tbl_deal", $inn, array("id" => $id));

                $message = "Deal " . $this->input->post('deal_title') . " successfully updated";
                $this->session->set_userdata('current_message', $message);

                if ($this->session->userdata('role') == 6) {
                    redirect('/site/view_shop/', 'refresh');
                } else {

                    redirect('/site/manage_deal', 'refresh');

                }


                //echo "<pre>";print_r($_FILES);

            }
        } else {

            if (isset($_GET['id']) && $_GET['id'] > 0) {
                $id = $_GET['id'];
            } else {
                redirect('/site/index', 'refresh');
            }
        }

        $deal = $this->m_common->db_select("*", "tbl_deal", array("id" => $id), array(), '', '', '', 'row_array');


        //-----------------------------------


        $start_date = utc_to_local($deal['deal_start'], $deal['deal_time'], $deal["timezone"]);
        $end_date = utc_to_local($deal['deal_start'], $deal['deal_end_time'], $deal["timezone"]);

        $deal['deal_start'] = $start_date['user_date_calendar'];

        $deal['deal_time'] = $start_date['user_time'];
        $deal['deal_end_time'] = $end_date['user_time'];


        $shops = $this->m_common->db_select("shop_id,shop_name", "tbl_shop", array(), array(), 'shop_name', '', '', 'all');


        $this->data['shops'] = $shops;
        $this->data['message'] = $message;
        $this->data['deal'] = $deal;
        $this->load->view('header', $this->data);
        $this->load->view('sidebar', $this->data);
        $this->load->view('edit_deal', $this->data);

    }

    public function edit_shop()
    {
        if ((is_permission($this->data['user_info']['role'], "edit_shop")) == FALSE) {
            echo "You Don't have permission to access this page";
            exit;
        }
//echo "<pre>";print_r($_POST);exit;
        $message = "";
        if (isset($_POST['edit_shop'])) {

            //echo "<pre>";print_r($_POST);exit;
            if (!isset($_POST['shop_id'])) {

                redirect('/site/index', 'refresh');
            } else {
                $id = $_POST['shop_id'];
            }

            $this->shop_id = $id;
            $this->userid = $this->input->post('user_id');
            $this->load->library('form_validation');
            $this->form_validation->set_error_delimiters('<span for="required" generated="true" class="help-inline" style="color:red;"> *', '</span>');
            $this->form_validation->set_rules('sname', 'Business Name', 'required');
            $this->form_validation->set_rules('scat', 'Business Category', 'required');
            $this->form_validation->set_rules('sdesp', 'Business Description', 'required');

            $this->form_validation->set_rules('city', 'City', 'trim');
            $this->form_validation->set_rules('state', 'State', 'trim');
            $this->form_validation->set_rules('country', 'Country', 'trim');

            $this->form_validation->set_rules('sadd', 'Business Address', 'required|callback_check_city_state_country');
            $this->form_validation->set_rules('postal_code', 'zip code', 'trim|required');
            $this->form_validation->set_rules('pin', 'Pin', 'trim|required');
            // $this->form_validation->set_rules('email', 'Business Email', 'required|valid_email|callback_sm_email_unique');
            $this->form_validation->set_rules('email', 'Business Email', 'required|valid_email');

            if (!empty($_POST['password'])) {
                $this->form_validation->set_rules('password', 'Password', 'required|min_length[8]|max_length[32]');
            }
            $this->form_validation->set_rules('password1', 'Confirm Password', 'callback_confirm_password');
            $this->form_validation->set_rules('sfname', 'Business Owner First Name', 'required');
            $this->form_validation->set_rules('slname', 'Business Owner Last Name', 'required');
            $this->form_validation->set_rules('sphone', 'Business Phone Number', 'callback_check_phone');
            $this->form_validation->set_rules('lat', 'Latitude', 'required');
            $this->form_validation->set_rules('lng', 'Longitude', 'required');

            if ($this->form_validation->run() == TRUE) {

                $location_info = $this->get_city_state_country_id();
                
                $country_id = $location_info['country_id'];
                $state_id = $location_info['state_id'];
                $city_id = $location_info['city_id'];


                if ($_POST['password']) {
                    $inn_user = array(
                        "first_name" => $this->input->post('sfname'),
                        "last_name" => $this->input->post('slname'),
                        "country_id" => $country_id,
                        "state_id" => $state_id,
                        "city_id" => $city_id,
                        "zip_code" => $this->input->post('postal_code'),
                        "email" => $this->input->post('email'),
                        "password" => md5($this->input->post('password')),
                        "role" => 6,
                    );
                } else {
                    $inn_user = array(
                        "first_name" => $this->input->post('sfname'),
                        "last_name" => $this->input->post('slname'),
                        "country_id" => $country_id,
                        "state_id" => $state_id,
                        "city_id" => $city_id,
                        "zip_code" => $this->input->post('postal_code'),
                        "email" => $this->input->post('email'),
                        "role" => 6,
                    );
                }


                $inn = array(
                    "user_id" => $this->input->post('user_id'),
                    "shop_name" => $this->input->post('sname'),
                    "shop_cats" => $this->input->post('scat'),
                    "shop_description" => $this->input->post('sdesp'),
                    "country_id" => $country_id,
                    "state_id" => $state_id,
                    "city_id" => $city_id,
                    "address" => $this->input->post('sadd'),
                    "zip_code" => $this->input->post('postal_code'),
                    "email" => $this->input->post('email'),
                    "url" => addScheme($this->input->post('burl')),
                    "password" => md5($this->input->post('password')),
                    "latitude" => $this->input->post('lat'),
                    "longitude" => $this->input->post('lng'),
                    "first_name" => $this->input->post('sfname'),
                    "last_name" => $this->input->post('slname'),
                    "business_phone" => $this->input->post('sphone'),
                    "pin" => $this->input->post('pin'),
                    "timezone" => $this->input->post('timezone'),
                    "add_by" => $this->input->post('add_by')

                );

                if (isset($_FILES['sfile'])) {
                    $r = $this->file_upload($_FILES['sfile'], $id, 1);
                    if (!empty($r)) {
                        //$inn_user['profile_pic']=$r;
                        $inn['shop_image'] = $r;
                        $config['image_library'] = 'gd2';
                        $config['source_image'] = '/var/www/html/uploads/user/' . $r;
                        $config['create_thumb'] = FALSE;
                        $config['maintain_ratio'] = FALSE;
                        $config['width'] = 75;
                        $config['height'] = 75;
                        $config['new_image'] = '/var/www/html/uploads/thumbs/' . $r;

                        $this->load->library('image_lib', $config);

                        $this->image_lib->resize();

                    }
                }
                $wh = array(
                    "shop_id" => $this->shop_id
                );
                $this->m_common->update_entry("tbl_shop", $inn, $wh);

                if (!empty($_POST['password'])) {
                    $up['password'] = md5($this->input->post('password'));
                    $this->m_common->update_entry("tbl_users", $up, array('user_id'=>$this->userid));
                }


                // $sql = "update tbl_users set email=? where user_id=?";
                // $result = $this->db->query($sql, array($this->input->post('email'), $this->input->post('user_id')));
               

                if ($this->data['user_info']['role'] == 1) {

                    $a = array(
                        "is_payment" => $this->input->post('is_payment'),
                        "blnexpired" => $this->input->post('blnexpired')
                    );

                    $this->m_common->update_entry("tbl_shop", $a, $wh);
                }

                $message = "Shop " . $this->input->post('sname') . " has successfully Updated.";
                $this->session->set_userdata('current_message', $message);

                redirect('/site/list_shop', 'refresh');
            }
        } else {

            if (isset($_GET['id']) && $_GET['id'] > 0) {
                $id = $_GET['id'];
            } else {
                redirect('/site/index', 'refresh');
            }

        }

        $this->db->select('t1.*,t2.name country_name,t3.state_name,t4.city_name');
        $this->db->from('tbl_shop t1');
        $this->db->join('tbl_country t2','t2.id=t1.country_id','left');
        $this->db->join('tbl_state t3','t3.sid=t1.state_id','left');
        $this->db->join('tbl_city t4','t4.city_id=t1.city_id','left');
        $this->db->where('shop_id',$id);
        $shop = $this->db->get()->row_array();

        // echo "<pre>";
        // print_r($shop);
        // exit();

        $cats = $this->m_common->db_select("*", "tbl_category", array(), array(), 'cname ASC', '', '', 'all');
        $sql = "select user_id, concat_ws(' ', first_name, last_name) as `name` from tbl_users where role in (1,2,3,4,5) order by `name`";
        $result = $this->db->query($sql, array());

        $r = $result->result_array();

        $this->data['message'] = $message;
        $this->data['shop'] = $shop;
        $this->data['cats'] = $cats;
        $this->data['salespersons'] = $r;
        $this->data['add_by'] = $shop['add_by'];
        $this->load->helper('date');
        $this->load->view('header', $this->data);
        $this->load->view('sidebar', $this->data);
        $this->load->view('edit_shop', $this->data);
    }

    public function manage_deal()
    {

        if ((is_permission($this->data['user_info']['role'], "manage_deal")) == FALSE) {
            echo "You Don't have permission to access this page";
            exit;
        }
        $message = "";
        $page_no = 1;
        $wh = " and t1.is_active = 1 ";

        $page_row_limit = isset($_GET['perpage']) ? $_GET['perpage'] : 25;

        $country = array();
        $state = array();
        $city = array();
        $wh_state = array();
        $wh_city = array();
        $wh_country = array();


        if ($this->data['user_info']['role'] == 2) {

            $wh .= " and t2.add_by = " . $this->data['user_info']['user_id'];
            $wh .= " or t2.country_id = " . $this->data['user_info']['country_id'];

            $wh_country = array("id" => $this->data['user_info']['country_id']);
            $country = $this->m_common->db_select("*", "tbl_country", $wh_country, array(), '`order` DESC,`name` asc', '', '', 'all');

        } else if ($this->data['user_info']['role'] == 3) {
            $wh .= " and t2.add_by = " . $this->data['user_info']['user_id'];
            $wh .= " or t2.state_id = " . $this->data['user_info']['state_id'];
            $wh_country = array("id" => $this->data['user_info']['country_id']);
            $wh_state = array("sid" => $this->data['user_info']['state_id']);
            $wh_city = array("state_id" => $this->data['user_info']['state_id']);
            $country = $this->m_common->db_select("*", "tbl_country", $wh_country, array(), '`order` DESC,`name` asc', '', '', 'all');
            $state = $this->m_common->db_select("*", "tbl_state", $wh_state, array(), 'state_name asc', '', '', 'all');
        } else if ($this->data['user_info']['role'] == 4) {
            $wh .= " and t2.add_by = " . $this->data['user_info']['user_id'];
            $wh .= " or t2.zip_code = " . $this->data['user_info']['zip_code'];
            $wh_country = array("id" => $this->data['user_info']['country_id']);
            $wh_state = array("sid" => $this->data['user_info']['state_id']);
            $wh_city = array("city_id" => $this->data['user_info']['city_id']);
            $country = $this->m_common->db_select("*", "tbl_country", $wh_country, array(), '`order` DESC,`name` asc', '', '', 'all');
            $state = $this->m_common->db_select("*", "tbl_state", $wh_state, array(), 'state_name asc', '', '', 'all');
            $city = $this->m_common->db_select("*", "tbl_city", $wh_city, array(), 'city_name asc', '', '', 'all');
        } else if ($this->data['user_info']['role'] == 5) {
            $wh .= " and t2.add_by = " . $this->data['user_info']['user_id'];
            $country = $this->m_common->db_select("*", "tbl_country", array(), array(), '`order` DESC,`name` asc', '', '', 'all');
        } else if ($this->data['user_info']['role'] == 6) {

            $wh .= " and  t2.user_id = " . $this->data['user_info']['user_id'];
            $country = $this->m_common->db_select("*", "tbl_country", array(), array(), '`order` DESC,`name` asc', '', '', 'all');
        } else {
            $country = $this->m_common->db_select("*", "tbl_country", array(), array(), '`order` DESC,`name` asc', '', '', 'all');
        }

        if (isset($_GET['search']) && !empty($_GET['search'])) {
            $search = $_GET['search'];
            $wh .= " and ( t1.deal_title like '%$search%' )";
            $this->data['search'] = $search;
        }
        if (isset($_GET['scat']) && ($_GET['scat'] > 0)) {
            $scat = $_GET['scat'];
            $wh .= " and ( t2.shop_cats = $scat ) ";
        }
        if (isset($_GET['scountry']) && ($_GET['scountry'] > 0)) {
            $scountry = $_GET['scountry'];
            $wh .= " and ( t2.country_id = $scountry ) ";
        }
        if (isset($_GET['sstate']) && ($_GET['sstate'] > 0)) {
            $sstate = $_GET['sstate'];
            $wh .= " and ( t2.state_id = $sstate ) ";
        }
        if (isset($_GET['scity']) && ($_GET['scity'] > 0)) {
            $scity = $_GET['scity'];
            $wh .= " and ( t2.city_id = $scity ) ";
        }
        if (isset($_GET['zip_code']) && !empty($_GET['zip_code'])) {
            $zip_code = $_GET['zip_code'];
            $wh .= " and ( t2.zip_code = $zip_code ) ";
        }


        $q = "select count(t1.id) as cnt from tbl_deal t1 join tbl_shop t2 on t1.shop_id = t2.shop_id where 1=1 $wh";
        $res_tb = $this->m_common->select_custom($q);
        // echo "<pre>";print_r($res_tb);exit;
        $tot_rows = $res_tb[0]['cnt'];


        $tot_page = ceil($tot_rows / $page_row_limit);
        if (isset($_GET['page_no']) && $_GET['page_no'] > 0) {
            $page_no = $_GET['page_no'];
        }
        $offset = ($page_no * $page_row_limit) - $page_row_limit;


        $q = "select t1.*,t2.shop_name,t2.address,t2.shop_image from tbl_deal t1 join tbl_shop t2 on t1.shop_id = t2.shop_id where 1=1 $wh group by t1.id  order by id desc limit $offset,$page_row_limit";
        $info = $this->m_common->select_custom($q);

        //echo "<pre>";print_r($info);exit;

        $prev = ($page_no - 6);
        if ($prev <= 0) {
            $prev = 1;
        }
        $next = ($page_no + 6);
        if ($next >= $tot_page) {
            $next = $tot_page;
        }

        $cats = $this->m_common->db_select("*", "tbl_category", array(), array(), 'cname ASC', '', '', 'all');


        //echo "<pre>";print_r($country);exit;
        // change 22/12/2014
//         if ((isset($_GET['scountry']) &&  $_GET['scountry']>0)) {
//            $state = $this->m_common->db_select("*", "tbl_state", array("cid" => $_GET['scountry']));
//            if ((isset($_GET['sstate']) && $_GET['sstate']>0)) {
//                $city = $this->m_common->db_select("*", "tbl_city", array("state_id" => $_GET['sstate']));
//            }
//        }
        $this->data['cats'] = $cats;
        $this->data['country'] = $country;
        $this->data['state'] = $state;
        $this->data['city'] = $city;

        //echo "<pre>";print_r($info);exit;
        $this->data['info'] = $info;
        $this->data['tot_page'] = $tot_page;
        $this->data['curr_page'] = $page_no;
        $this->data['prev'] = $prev;
        $this->data['next'] = $next;

        $this->load->view('header', $this->data);
        $this->load->view('sidebar', $this->data);
        $this->load->view('manage_deal', $this->data);


    }

    public function list_shop()
    {

        if ((is_permission($this->data['user_info']['role'], "list_shop")) == FALSE) {
            echo "You Don't have permission to access this page";
            exit;
        }
        $message = "";
        $page_no = 1;
        $wh = "";
        $join = "";
        $state = array();
        $city = array();
        $wh_state = array();
        $wh_city = array();
        $wh_country = array();

        if ($this->data['user_info']['role'] == 2) {

            $wh .= " and t1.add_by = " . $this->data['user_info']['user_id'];
            $wh .= " or t1.country_id = " . $this->data['user_info']['country_id'];

            $wh_country = array("id" => $this->data['user_info']['country_id']);
            $country = $this->m_common->db_select("*", "tbl_country", $wh_country, array(), '`order` DESC,`name` asc', '', '', 'all');

        } else if ($this->data['user_info']['role'] == 3) {
            $wh .= " and t1.add_by = " . $this->data['user_info']['user_id'];
            $wh .= " or t1.state_id = " . $this->data['user_info']['state_id'];
            $wh_country = array("id" => $this->data['user_info']['country_id']);
            $wh_state = array("sid" => $this->data['user_info']['state_id']);
            $wh_city = array("state_id" => $this->data['user_info']['state_id']);
            $country = $this->m_common->db_select("*", "tbl_country", $wh_country, array(), '`order` DESC,`name` asc', '', '', 'all');
            $state = $this->m_common->db_select("*", "tbl_state", $wh_state, array(), 'state_name asc', '', '', 'all');
        } else if ($this->data['user_info']['role'] == 4) {
            $wh .= " and t1.add_by = " . $this->data['user_info']['user_id'];
            $wh .= " or t1.zip_code = " . $this->data['user_info']['zip_code'];
            $wh_country = array("id" => $this->data['user_info']['country_id']);
            $wh_state = array("sid" => $this->data['user_info']['state_id']);
            $wh_city = array("city_id" => $this->data['user_info']['city_id']);
            $country = $this->m_common->db_select("*", "tbl_country", $wh_country, array(), '`order` DESC,`name` asc', '', '', 'all');
            $state = $this->m_common->db_select("*", "tbl_state", $wh_state, array(), 'state_name asc', '', '', 'all');
            $city = $this->m_common->db_select("*", "tbl_city", $wh_city, array(), 'city_name asc', '', '', 'all');
        } else if ($this->data['user_info']['role'] == 5) {
            $wh .= " and t1.add_by = " . $this->data['user_info']['user_id'];
            $country = $this->m_common->db_select("*", "tbl_country", array(), array(), '`order` DESC,`name` asc', '', '', 'all');
        } else if ($this->data['user_info']['role'] == 6) {
            $wh .= " and t1.user_id = " . $this->data['user_info']['user_id'];
            $country = $this->m_common->db_select("*", "tbl_country", array(), array(), '`order` DESC,`name` asc', '', '', 'all');
        }
        
        else if ($this->data['user_info']['role'] == 9) {
            $user_id = $this->data['user_info']['user_id'];
            $join .="LEFT JOIN tbl_users_shops t2 ON t2.shop_id=t1.shop_id";
            $wh .= " and t2.corporate_user_id='$user_id'";
        }

         else {
            $country = $this->m_common->db_select("*", "tbl_country", array(), array(), '`order` DESC,`name` asc', '', '', 'all');
        }
        if (isset($_GET['search']) && !empty($_GET['search'])) {
            $search = $_GET['search'];
            $wh .= " and ( t1.shop_name LIKE '%$search%' OR t1.email LIKE '%$search%' ) ";
            $this->data['search'] = $search;
        }
        if (isset($_GET['scat']) && ($_GET['scat'] > 0)) {
            $scat = $_GET['scat'];
            $wh .= " and ( t1.shop_cats = $scat ) ";
        }
        if (isset($_GET['zip_code']) && !empty($_GET['zip_code'])) {
            $zip_code = $_GET['zip_code'];
            $wh .= " and ( t1.zip_code = $zip_code ) ";
        }
        if (isset($_GET['scountry']) && ($_GET['scountry'] > 0)) {
            $scountry = $_GET['scountry'];
            $wh .= " and ( t1.country_id = $scountry ) ";
        }
        if (isset($_GET['sstate']) && ($_GET['sstate'] > 0)) {
            $sstate = $_GET['sstate'];
            $wh .= " and ( t1.state_id = $sstate ) ";
        }
        if (isset($_GET['scity']) && ($_GET['scity'] > 0)) {
            $scity = $_GET['scity'];
            $wh .= " and ( t1.city_id = $scity ) ";
        }

        if (isset($_GET['ustatus']) && ($_GET['ustatus'] != '')) {
            $ustatus = $_GET['ustatus'];
            if( $ustatus == 'Deactivate'){
                $wh .= " and ( t3.user_is_active = 'No' ) ";
            }
            if( $ustatus == 'Active'){
                $wh .= " and ( t3.user_is_active = 'Yes' ) ";
            }
        }


        $country = $this->m_common->db_select("*", "tbl_country", array(), array(), '`order` DESC,`name` asc', '', '', 'all');

        $page_row_limit = isset($_GET['perpage']) ? $_GET['perpage'] : 25;
        /*if (isset($_GET['search']) && !empty($_GET['search'])) {
            $search = $_GET['search'];
            $wh.=" and ( t1.shop_name like '%$search%' ) ";
        }*/
        if (isset($_GET['scat']) && ($_GET['scat'] > 0)) {
            $scat = $_GET['scat'];
            $wh .= " and ( t1.shop_cats = $scat ) ";
        }


        $q = "select count(t1.shop_id) as cnt from tbl_shop t1 $join JOIN tbl_users t3 ON t3.user_id=t1.user_id where 1=1 $wh";
        $res_tb = $this->m_common->select_custom($q);
        // echo "<pre>";print_r($res_tb);exit;
        $tot_rows = $res_tb[0]['cnt'];

        $tot_page = ceil($tot_rows / $page_row_limit);
        if (isset($_GET['page_no']) && $_GET['page_no'] > 0) {
            $page_no = $_GET['page_no'];
        }
        $offset = ($page_no * $page_row_limit) - $page_row_limit;


        $q = "select t1.*,t3.user_is_active from tbl_shop t1 $join JOIN tbl_users t3 ON t3.user_id=t1.user_id where 1=1 $wh group by t1.shop_id  order by trim(t1.shop_name) asc limit $offset,$page_row_limit";
        $info = $this->m_common->select_custom($q);

        //echo "<pre> $q";
        //print_r($info);exit;

        $prev = ($page_no - 6);
        if ($prev <= 0) {
            $prev = 1;
        }
        $next = ($page_no + 6);
        if ($next >= $tot_page) {
            $next = $tot_page;
        }

        $cats = $this->m_common->db_select("*", "tbl_category", array(), array(), 'cname ASC', '', '', 'all');


        $this->data['cats'] = $cats;
        $this->data['country'] = $country;
        $this->data['state'] = $state;
        $this->data['city'] = $city;

        $this->data['info'] = $info;
        $this->data['tot_page'] = $tot_page;
        $this->data['curr_page'] = $page_no;
        $this->data['prev'] = $prev;
        $this->data['next'] = $next;

        $this->load->view('header', $this->data);
        $this->load->view('sidebar', $this->data);
        $this->load->view('list_shop', $this->data);
    }

    public function view_shop()
    {

        if ($this->session->userdata('role') == 6) {

             if($this->data['user_info']['is_corporate_business_user'] == 1){

                $this->db->select('t2.shop_id');
                $this->db->from('tbl_users_shops t1');
                $this->db->join('tbl_shop t2','t2.shop_id = t1.shop_id');
                $this->db->where('t1.user_id',$this->session->userdata('user_id'));
                $row = $this->db->get()->row();

            }else{
    
                $sql = "select shop_id from tbl_shop where user_id = ?";
                $result = $this->db->query($sql, array($this->session->userdata('user_id')));
                $row = $result->row();
            }

            $_GET["id"] = $row->shop_id;
        }

        if ($this->data['user_info']['role'] == 9) {

            $corporate_business_list = $this->get_corporate_business($this->session->userdata('user_id'));
            
            if(count($corporate_business_list) > 1){
                $this->data['corporate_business_list'] = $corporate_business_list;
            }else{
                $_GET["id"] = $corporate_business_list[0]['shop_id'];
            }


            if(empty($_GET["id"])){
                $_GET["id"] = 0;
            }
        }

        if ($_GET['id']) {

            $wh = " and t1.shop_id = " . $_GET['id'];
            $offset = 0;
            $page_row_limit = 1000;
            $q = "select t1.*,t2.shop_name,t2.address,t2.shop_image from tbl_deal t1 join tbl_shop t2 on t1.shop_id = t2.shop_id where 1=1 and is_active=1 $wh order by t1.id DESC limit $offset,$page_row_limit";
            $deal = $this->m_common->select_custom($q);


            $shop = $this->m_common->view_shop_detail($_GET['id']);
            if (empty($shop)) {
                redirect("/site/index", "refresh");
            }
            $wh1 = " AND t1.business_id =" . $_GET['id'];

            $q1 = "select t1.*,t2.* from tbl_business_notes t1 join tbl_users t2 on t1.notes_by = t2.user_id where 1=1 $wh1 order by t1.notes_id DESC";
            $notes = $this->m_common->select_custom($q1);

            $q2 = "select t1.*,t2.* from tbl_task t1 join tbl_users t2 on t1.task_by = t2.user_id where 1=1 order by t1.task_id DESC";
            $task = $this->m_common->select_custom($q2);

            $this->data['shop'] = $shop;
            $this->data['deal'] = $deal;
            $this->data['notes'] = $notes;
            $this->data['task'] = $task;
        }

        $this->load->view('header', $this->data);
        $this->load->view('sidebar', $this->data);
        $this->load->view('view_shop', $this->data);
    }



    public function ajax_deal()
    {
        $data = "";
        $wh = " and t1.shop_id = " . $_POST['id'];
        $offset = $_POST['offset'];
        $page_row_limit = 7;
        $q = "select t1.*,t2.shop_name,t2.address,t2.shop_image from tbl_deal t1 join tbl_shop t2 on t1.shop_id = t2.shop_id where 1=1 $wh order by t1.id DESC limit $offset,$page_row_limit";
        $deal = $this->m_common->select_custom($q);

        foreach ($deal as $v) {

            $Repeat_str = "";
            $rep_arr = explode(",", $v['repeat']);
            foreach ($rep_arr as $repi) {
                $Repeat_str .= get_dayname($repi) . "<br/>";
            }
            $Repeat_str = rtrim($Repeat_str, ",");

            $dtarr = secondsToTime($v['deal_time']);
            $dtarr1 = secondsToTime($v['deal_end_time']);
            $src = get_deal_image_src($v['deal_image'], $v['shop_image']);


            $data_image = "<img src=" . $src . " width='150' height='auto' />";


            $data .= "<tr><td>" . $v['deal_title'] . "</td><td>" . $data_image . "</td><td>" . $v['original_price'] . "</td><td>" . $v['offer_price'] . "</td><td>" . date("F j, Y", strtotime($v['deal_start'])) . "</td><td>" . $dtarr . "</td><td>" . $dtarr1 . "</td><td>" . $Repeat_str . "</td></tr>";
        }
        print_r($data);
    }

    public function user_route()
    {
        if ((is_permission($this->data['user_info']['role'], "user_route")) == FALSE) {
            echo "You Don't have permission to access this page";
            exit;
        }
        $data_str = "";
        $message = "";
        if (!$this->session->userdata('is_admin')) {
            redirect('site/warning?msg=you can not access this module', 'refresh');
        }

        $q = "select t1.*,t2.name from customer_location t1 join tbl_customer t2 on t1.id = t2.user_id where 1=1 
            group by t1.user_id ";

        $info = $this->m_common->select_custom($q);
        foreach ($info as $v) {

            $name = $v['name'];
            $latitude = $v['latitude'];
            $longitude = $v['longitude'];
            $data_str .= "['$name', $latitude,$longitude],";
        }
        $data_str;
        $data_str = rtrim($data_str, ",");
        //echo "<pre>";print_r($info);exit;


        $data = array(
            "message" => $message,
            "data_str" => $data_str,
        );
        $this->load->view('header');
        $this->load->view('sidebar', $data);
        $this->load->view('user_route', $data);
        $this->load->view('footer');
    }

//    public function statistics() {
//        $message = "";
//        $data_str = "";
//        $select = " count(*) as cnt,date ";
//        if (!$this->session->userdata('is_admin')) {
//            redirect('site/warning?msg=you can not access this module', 'refresh');
//        }
//
//        $y = isset($_GET['chartyear']) ? $_GET['chartyear'] : date("Y");
//        $g = isset($_GET['chartcat']) ? $_GET['chartcat'] : "Monthly";
//        $charttype = isset($_GET['charttype']) ? $_GET['charttype'] : "Line";
//        $wh = " and YEAR(date) = $y ";
//        $gp = "";
//        if ($g == "Monthly") {
//            $gp.=" MONTH(date) ";
//            $select.=",MONTH(date) as xval";
//        } else if ($g == "Weekly") {
//            $gp.=" WEEK(date) ";
//            $select.=",WEEK(date) as xval";
//        } else {
//            $gp.=" DAY(date) ";
//            $select.=",DAY(date) as xval";
//        }
//        $q = "select $select from push_notes where 1=1 $wh group by $gp";
//        $info = $this->m_common->select_custom($q);
//
//        //echo "<pre>";print_r($info);exit;
//        if ($charttype == "line") {
//            $data_str = $this->get_line_graph($info, $g);
//        } else if ($charttype == "col") {
//            $data_str = $this->get_col_graph($info, $g);
//        } else {
//            $data_str = $this->get_line_graph($info, $g);
//        }
//
//        $data = array(
//            "data_str" => $data_str,
//            "gyear" => $y,
//            "gmonth" => $g,
//            "gtype" => $charttype,
//        );
//        echo '<pre>';
//        print_r($data);
//        exit;
//        $this->load->view('header');
//        $this->load->view('sidebar', $data);
//        $this->load->view('statistics', $data);
//        $this->load->view('footer');
//    }
    public function statistic()
    {
        if ((is_permission($this->data['user_info']['role'], "select_shop")) == FALSE) {
            echo "You Don't have permission to access this page";
            exit;
        }
        $message = "";
        $page_no = 1;
        $wh = "";
        $state = array();
        $city = array();
        $wh_state = array();
        $wh_city = array();
        $wh_country = array();

        if ($this->data['user_info']['role'] == 2) {
            $wh .= " and t1.add_by = " . $this->data['user_info']['user_id'];
            $wh .= " or t1.country_id = " . $this->data['user_info']['country_id'];
        } else if ($this->data['user_info']['role'] == 3) {
            $wh .= " and t1.add_by = " . $this->data['user_info']['user_id'];
            $wh .= " or t1.state_id = " . $this->data['user_info']['state_id'];
        } else if ($this->data['user_info']['role'] == 4) {
            $wh .= " and t1.add_by = " . $this->data['user_info']['user_id'];
            $wh .= " or t1.zip_code = " . $this->data['user_info']['zip_code'];
        } else if ($this->data['user_info']['role'] == 5) {
            $wh .= " and t1.add_by = " . $this->data['user_info']['user_id'];
        } else if ($this->data['user_info']['role'] == 6) {
            $wh .= " and t1.user_id = " . $this->data['user_info']['user_id'];
        }
        $country = $this->m_common->db_select("*", "tbl_country", array(), array(), '`order` DESC,`name` asc', '', '', 'all');
//        if(isset($_GET['add_shop'])){
        $this->data['add_shop'] = "";
        $this->data['scat'] = "";
        $this->data['scountry'] = "";
        $this->data['sstate'] = "";
        $this->data['scity'] = "";
        $this->data['zip_code'] = "";

        if (isset($_GET['scat']) && ($_GET['scat'] > 0)) {
            $scat = $_GET['scat'];
            $wh .= " and ( t1.shop_cats = $scat ) ";
            $this->data['scat'] = $scat;
        }
        if (isset($_GET['scountry']) && ($_GET['scountry'] > 0)) {
            $scountry = $_GET['scountry'];
            $wh .= " and ( t1.country_id = $scountry ) ";
            $this->data['scountry'] = $scountry;
        }
        if (isset($_GET['sstate']) && ($_GET['sstate'] > 0)) {
            $sstate = $_GET['sstate'];
            $wh .= " and ( t1.state_id = $sstate ) ";
            $this->data['sstate'] = $sstate;
        }
        if (isset($_GET['scity']) && ($_GET['scity'] > 0)) {
            $scity = $_GET['scity'];
            $wh .= " and ( t1.city_id = $scity ) ";
            $this->data['scity'] = $scity;
        }
        if (isset($_GET['zip_code']) && !empty($_GET['zip_code'])) {
            $zip_code = $_GET['zip_code'];
            $wh .= " and ( t1.zip_code = $zip_code ) ";
            $this->data['zip_code'] = $zip_code;
        }


        $page_row_limit = isset($_GET['perpage']) ? $_GET['perpage'] : 25;
        if (isset($_GET['search']) && !empty($_GET['search'])) {
            $search = $_GET['search'];
            $wh .= " and ( t1.shop_name like '%$search%' ) ";
        }
        if (isset($_GET['scat']) && ($_GET['scat'] > 0)) {
            $scat = $_GET['scat'];
            $wh .= " and ( t1.shop_cats = $scat ) ";
        }


        $q = "select count(t1.shop_id) as cnt from tbl_shop t1 where 1=1 $wh";
        $res_tb = $this->m_common->select_custom($q);
//         echo "<pre>";print_r($res_tb);exit;
        $tot_rows = $res_tb[0]['cnt'];

        $tot_page = ceil($tot_rows / $page_row_limit);
        if (isset($_GET['page_no']) && $_GET['page_no'] > 0) {
            $page_no = $_GET['page_no'];
        }
        $offset = ($page_no * $page_row_limit) - $page_row_limit;


        $q = "select t1.* from tbl_shop t1 where 1=1 $wh group by t1.shop_id  order by t1.shop_name ASC limit $offset,$page_row_limit";
        $info = $this->m_common->select_custom($q);

//        echo "<pre>";print_r($info);exit;

        $prev = ($page_no - 6);
        if ($prev <= 0) {
            $prev = 1;
        }
        $next = ($page_no + 6);
        if ($next >= $tot_page) {
            $next = $tot_page;
        }

        $this->data['info'] = $info;
        $this->data['tot_page'] = $tot_page;
        $this->data['curr_page'] = $page_no;
        $this->data['prev'] = $prev;
        $this->data['next'] = $next;

//    }


        $cats = $this->m_common->db_select("*", "tbl_category", array(), array(), 'cname ASC', '', '', 'all');

        $this->data['message'] = $message;
        $this->data['cats'] = $cats;
        $this->data['country'] = $country;
        $this->data['state'] = $state;
        $this->data['city'] = $city;

        $this->load->view('header', $this->data);
        $this->load->view('sidebar', $this->data);
        $this->load->view('shop_select', $this->data);
    }

    public function statistics()
    {
        if ((is_permission($this->data['user_info']['role'], "statistics")) == FALSE) {
            echo "You Don't have permission to access this page";
            exit;
        }
        if (isset($_POST['deal_start']) && isset($_POST['deal_end'])) {
            $page_no = 1;
            if ($_POST['deal_start'] == $_POST['deal_end']) {
                $message = "";
                $data_str = "";
                $result = $this->m_common->statatics($_POST['deal_start'], $_POST['id']);
                $tot_rows = count($result);
                $page_row_limit = isset($_GET['perpage']) ? $_GET['perpage'] : 25;


                $tot_page = ceil($tot_rows / $page_row_limit);
                if (isset($_GET['page_no']) && $_GET['page_no'] > 0) {
                    $page_no = $_GET['page_no'];
                }
                $offset = ($page_no * $page_row_limit) - $page_row_limit;
                $result = $this->m_common->statatics_pagi($_POST['deal_start'], $offset, $page_row_limit, $_POST['id']);
                $prev = ($page_no - 6);
                if ($prev <= 0) {
                    $prev = 1;
                }
                $next = ($page_no + 6);
                if ($next >= $tot_page) {
                    $next = $tot_page;
                }

                $this->data['message'] = $message;
                $this->data['data_str'] = $data_str;
                $this->data['data'] = $result;
                $this->data['start_date'] = $_POST['deal_start'];
                $this->data['end_date'] = $_POST['deal_end'];
                $this->data['tot_page'] = $tot_page;
                $this->data['curr_page'] = $page_no;
                $this->data['id'] = $_POST['id'];
                $this->data['prev'] = $prev;
                $this->data['next'] = $next;

                $this->load->view('header', $this->data);
                $this->load->view('sidebar', $this->data);
                $this->load->view('statistics1', $this->data);
            }
            $message = "";
            $data_str = "";
            $result = $this->m_common->statatics1($_POST['deal_start'], $_POST['deal_end'], $_POST['id']);

            $tot_rows = count($result);
            $page_row_limit = isset($_GET['perpage']) ? $_GET['perpage'] : 25;


            $tot_page = ceil($tot_rows / $page_row_limit);
            if (isset($_GET['page_no']) && $_GET['page_no'] > 0) {
                $page_no = $_GET['page_no'];
            }
            $offset = ($page_no * $page_row_limit) - $page_row_limit;
            $result = $this->m_common->statatics1_pagi($_POST['deal_start'], $_POST['deal_end'], $offset, $page_row_limit, $_POST['id']);
            $prev = ($page_no - 6);
            if ($prev <= 0) {
                $prev = 1;
            }
            $next = ($page_no + 6);
            if ($next >= $tot_page) {
                $next = $tot_page;
            }

            $this->data['message'] = $message;
            $this->data['data_str'] = $data_str;
            $this->data['data'] = $result;
            $this->data['start_date'] = $_POST['deal_start'];
            $this->data['end_date'] = $_POST['deal_end'];
            $this->data['tot_page'] = $tot_page;
            $this->data['curr_page'] = $page_no;
            $this->data['id'] = $_POST['id'];
            $this->data['prev'] = $prev;
            $this->data['next'] = $next;

            $this->load->view('header', $this->data);
            $this->load->view('sidebar', $this->data);
            $this->load->view('statistics1', $this->data);
        } elseif (isset ($_GET['id']) && !empty($_GET['id'])) {
            $page_no = 1;
            $message = "";
            $data_str = "";
            $result = $this->m_common->statatics2($_GET['id']);

            $tot_rows = count($result);
            $page_row_limit = isset($_GET['perpage']) ? $_GET['perpage'] : 10;


            $tot_page = ceil($tot_rows / $page_row_limit);
            if (isset($_GET['page_no']) && $_GET['page_no'] > 0) {
                $page_no = $_GET['page_no'];
            }
            $offset = ($page_no * $page_row_limit) - $page_row_limit;
            $result = $this->m_common->statatics2_pagi($offset, $page_row_limit, $_GET['id']);
            $prev = ($page_no - 6);
            if ($prev <= 0) {
                $prev = 1;
            }
            $next = ($page_no + 6);
            if ($next >= $tot_page) {
                $next = $tot_page;
            }
            $this->data['message'] = $message;
            $this->data['data_str'] = $data_str;
            $this->data['data'] = $result;
            $this->data['tot_page'] = $tot_page;
            $this->data['curr_page'] = $page_no;
            $this->data['id'] = $_GET['id'];
            $this->data['prev'] = $prev;
            $this->data['next'] = $next;

            $this->load->view('header', $this->data);
            $this->load->view('sidebar', $this->data);
            $this->load->view('statistics1', $this->data);
        } else {
            redirect('site/statistic', 'refresh');
        }
    }

    public function list_ambassador()
    {
        if ((is_permission($this->data['user_info']['role'], "list_ambassador")) == FALSE) {
            echo "You Don't have permission to access this page";
            exit;
        }

        $page_no = 1;
        $wh = " and role=8 ";


        $country = array();
        $state = array();
        $city = array();
        $wh_state = array();
        $wh_city = array();
        $wh_country = array();


        if ($this->data['user_info']['role'] == 2) {
            $wh .= " and country_id = " . $this->data['user_info']['country_id'];
            $wh_country = array("id" => $this->data['user_info']['country_id']);
            $country = $this->m_common->db_select("*", "tbl_country", $wh_country, array(), '`order` DESC,`name` asc', '', '', 'all');
        } else if ($this->data['user_info']['role'] == 3) {
            $wh .= " and country_id = " . $this->data['user_info']['country_id'] . " and state_id = " . $this->data['user_info']['state_id'];
            $wh_country = array("id" => $this->data['user_info']['country_id']);
            $wh_state = array("sid" => $this->data['user_info']['state_id']);
            $wh_city = array("state_id" => $this->data['user_info']['state_id']);
            $country = $this->m_common->db_select("*", "tbl_country", $wh_country, array(), '`order` DESC,`name` asc', '', '', 'all');
            $state = $this->m_common->db_select("*", "tbl_state", $wh_state, array(), 'state_name asc', '', '', 'all');
        } else if ($this->data['user_info']['role'] == 4) {
            $wh .= " and country_id = " . $this->data['user_info']['country_id'] . " and state_id = " . $this->data['user_info']['state_id'] . " and zip_code = " . $this->data['user_info']['zip_code'];
            $wh_country = array("id" => $this->data['user_info']['country_id']);
            $wh_state = array("sid" => $this->data['user_info']['state_id']);
            $wh_city = array("city_id" => $this->data['user_info']['city_id']);
            $country = $this->m_common->db_select("*", "tbl_country", $wh_country, array(), '`order` DESC,`name` asc', '', '', 'all');
            $state = $this->m_common->db_select("*", "tbl_state", $wh_state, array(), 'state_name asc', '', '', 'all');
            $city = $this->m_common->db_select("*", "tbl_city", $wh_city, array(), 'city_name asc', '', '', 'all');
        } else {
            $country = $this->m_common->db_select("*", "tbl_country", array(), array(), '`order` DESC,`name` asc', '', '', 'all');
        }

        if (isset($_GET['search']) && !empty($_GET['search'])) {

            $search = $_GET['search'];
            $this->data['search'] = $search;
            if (str_word_count($search) > 2) {
                $wh .= " and ( email like '%$search%' ) ";
            } else {
                $exsearch = explode(" ", $search);
                $first_name = $exsearch[0];
                $lastname = $exsearch[1];
                $wh .= " and ( first_name like '%$first_name%' or  last_name like '%$lastname%' ) ";
            }
        }

        if (isset($_GET['zip_code']) && !empty($_GET['zip_code'])) {
            $zip_code = $_GET['zip_code'];
            $wh .= " and ( zip_code = $zip_code ) ";
        }
        if (isset($_GET['scountry']) && ($_GET['scountry'] > 0)) {
            $scountry = $_GET['scountry'];
            $wh .= " and ( country_id = $scountry ) ";
        }
        if (isset($_GET['sstate']) && ($_GET['sstate'] > 0)) {
            $sstate = $_GET['sstate'];
            $wh .= " and ( state_id = $sstate ) ";
        }
        if (isset($_GET['scity']) && ($_GET['scity'] > 0)) {
            $scity = $_GET['scity'];
            $wh .= " and ( city_id = $scity ) ";
        }
        if (isset($_GET['by_area_manager']) && ($_GET['by_area_manager'] > 0)) {
            $by_area_manager = $_GET['by_area_manager'];
            $wh .= " and ( perent_id= $by_area_manager ) ";
        }

        $page_row_limit = isset($_GET['perpage']) ? $_GET['perpage'] : 25;

        $q = "select count(user_id) as cnt from tbl_users where 1=1 $wh limit 1";
        $res_tb = $this->m_common->select_custom($q);
        // echo "<pre>";print_r($res_tb);exit;
        $tot_rows = $res_tb[0]['cnt'];


        $tot_page = ceil($tot_rows / $page_row_limit);
        if (isset($_GET['page_no']) && $_GET['page_no'] > 0) {
            $page_no = $_GET['page_no'];
        }
        $offset = ($page_no * $page_row_limit) - $page_row_limit;


        $q = "select * from tbl_users where 1=1 $wh group by user_id order by user_id ASC limit $offset,$page_row_limit";
        $info = $this->m_common->select_custom($q);

        //echo "<pre>";print_r($info);exit;

        $prev = ($page_no - 6);
        if ($prev <= 0) {
            $prev = 1;
        }
        $next = ($page_no + 6);
        if ($next >= $tot_page) {
            $next = $tot_page;
        }


        $this->data['country'] = $country;
        $this->data['state'] = $state;
        $this->data['city'] = $city;

        $this->data['info'] = $info;
        $this->data['tot_page'] = $tot_page;
        $this->data['curr_page'] = $page_no;
        $this->data['prev'] = $prev;
        $this->data['next'] = $next;
        $this->data['page_row_limit'] = $page_row_limit;

        $this->load->view('header', $this->data);
        $this->load->view('sidebar', $this->data);
        $this->load->view('list_ambassador', $this->data);
    }

    public function add_ambassador()
    {
        if ((is_permission($this->data['user_info']['role'], "add_ambassador")) == FALSE) {
            echo "You Don't have permission to access this page";
            exit;
        }
        $message = "";

        if (isset($_POST['add_ambassador'])) {
//            echo "<pre>";print_r($_POST);exit;
            $this->load->library('form_validation');
            $this->form_validation->set_error_delimiters('<span for="required" generated="true" class="help-inline" style="color:red;"> *', '</span>');
            $this->form_validation->set_rules('first_name', 'First Name', 'required');
            $this->form_validation->set_rules('last_name', 'Last Name', 'required');
            $this->form_validation->set_rules('email', 'Email', 'required|valid_email|is_unique[tbl_users.email]');
            $this->form_validation->set_rules('scountry', 'Country', 'required');
            $this->form_validation->set_rules('sstate', 'State', 'required');
            $this->form_validation->set_rules('scity', 'City', 'required');
            $this->form_validation->set_rules('zip_code', 'zip code', 'trim|required');
            $this->form_validation->set_rules('password', 'Password', 'required|min_length[8]|max_length[32]');
            $this->form_validation->set_rules('password1', 'Password', 'required|callback_confirm_password');
            $this->form_validation->set_rules('profile_pic', 'Profile Image', '');


            if ($this->form_validation->run() == TRUE) {

                if (!isset($_POST['perent_id'])) {
                    $_POST['perent_id'] = 0;
                }

                $inn = array(
                    "first_name" => $this->input->post('first_name'),
                    "last_name" => $this->input->post('last_name'),
                    //"user_name" => $this->input->post('user_name'),
                    "email" => $this->input->post('email'),
                    "password" => md5($this->input->post('password')),
                    "role" => $this->input->post('role'),
                    "country_id" => $this->input->post('scountry'),
                    "state_id" => $this->input->post('sstate'),
                    "city_id" => $this->input->post('scity'),
                    "zip_code" => $this->input->post('zip_code'),
                );

                $temp = $this->m_common->insert_entry("tbl_users", $inn, 1);
                if ($temp['last_id'] > 0) {
                    //$message = "Deal " . $this->input->post('deal_title') . " successfully inserted";
                    $message = "Sales Manager  Successfully Added";
                    if (isset($_FILES['profile_pic'])) {
                        $r = $this->file_upload($_FILES['profile_pic'], $temp['last_id'], 1);
                        if (!empty($r)) {
                            $up = array(
                                "profile_pic" => $r,
                            );
                            $this->m_common->update_entry("tbl_users", $up, array("user_id" => $temp['last_id']));
                        }
                    }
                    $message = "Sales Manager " . $inn['first_name'] . " " . $inn['last_name'] . " has successfully added";
                    $this->session->set_userdata('current_message', $message);
                    redirect('site/list_ambassador', 'refresh');
                }
            }
        }
        $country = $this->m_common->db_select("*", "tbl_country", array(), array(), '`order` DESC,`name` asc', '', '', 'all');

        $state = array();
        $city = array();
        //echo "<pre>";print_r($country);exit;
        // change 22/12/2014
        if ((isset($_POST['scountry']) && $_POST['scountry'] > 0)) {
            $state = $this->m_common->db_select("*", "tbl_state", array("cid" => $_POST['scountry']));
            if ((isset($_POST['sstate']) && $_POST['sstate'] > 0)) {
                $city = $this->m_common->db_select("*", "tbl_city", array("state_id" => $_POST['sstate']));
            }
        }


        $this->data['country'] = $country;
        $this->data['state'] = $state;
        $this->data['city'] = $city;
        $data = array(
            'message' => $message,
        );
        $this->load->view('header', $this->data);
        $this->load->view('sidebar', $this->data);
        $this->load->view('add_ambassador', $this->data);
    }

    public function edit_ambassador()
    {
        if ((is_permission($this->data['user_info']['role'], "edit_ambassador")) == FALSE) {
            echo "You Don't have permission to access this page";
            exit;
        }

        $id = 0;
        $message = "";
        if (isset($_POST['edit_ambassador'])) {
            $id = $this->input->post('user_id');
            $this->userid = $id;
            //echo "<pre>";print_r($_POST);exit;
            $this->load->library('form_validation');
            $this->form_validation->set_error_delimiters('<span for="required" generated="true" class="help-inline" style="color:red;"> *', '</span>');


            $this->form_validation->set_rules('user_id', 'User Identification', 'required');
            $this->form_validation->set_rules('first_name', 'First Name', 'required');
            $this->form_validation->set_rules('last_name', 'Last Name', 'required');
            $this->form_validation->set_rules('scountry', 'Country', 'required');
            $this->form_validation->set_rules('sstate', 'State', 'required');
            $this->form_validation->set_rules('scity', 'City', 'required');
            if (isset($_POST['password']) && !empty($_POST['password'])) {
                $this->form_validation->set_rules('password', 'Password', 'required|min_length[8]|max_length[32]');
                $this->form_validation->set_rules('password1', 'Password', 'required|callback_confirm_password');
            }
            $this->form_validation->set_rules('role', 'Manager Role', 'required');
            $this->form_validation->set_rules('profile_pic', 'Profile Image', '');


            if ($this->form_validation->run() == TRUE) {

                if (!isset($_POST['perent_id'])) {
                    $_POST['perent_id'] = 0;
                }
                if (isset($_POST['password']) && !empty($_POST['password'])) {

                    $up = array(
                        "first_name" => $this->input->post('first_name'),
                        "last_name" => $this->input->post('last_name'),
                        "country_id" => $this->input->post('scountry'),
                        "state_id" => $this->input->post('sstate'),
                        "city_id" => $this->input->post('scity'),
                        "password" => md5($this->input->post('password')),
                        "role" => $this->input->post('role'),
                    );

                } else {

                    $up = array(
                        "first_name" => $this->input->post('first_name'),
                        "last_name" => $this->input->post('last_name'),
                        "country_id" => $this->input->post('scountry'),
                        "state_id" => $this->input->post('sstate'),
                        "city_id" => $this->input->post('scity'),
                        "role" => $this->input->post('role'),
                    );

                }
                if (isset($_POST['email']) && !empty($_POST['email'])) {
                    $up['email'] = $this->input->post('email');
                }
                if (isset($_POST['zip_code']) && !empty($_POST['zip_code'])) {
                    $up['zip_code'] = $this->input->post('zip_code');
                }
                //echo "<pre>";print_r($_FILES);
                if (isset($_FILES['profile_pic'])) {
                    $r = $this->file_upload($_FILES['profile_pic'], $id, 1);
                    if (!empty($r)) {

                        $up['profile_pic'] = $r;
                    }
                }
                $this->m_common->update_entry("tbl_users", $up, array("user_id" => $id));
                $message = "Ambassador " . $up['first_name'] . " has successfully updated";
                $this->session->set_userdata('current_message', $message);
                redirect('site/list_ambassador', 'refresh');
            }
        }
        if (!$id) {
            if (isset($_GET['id'])) {
                $id = $_GET['id'];
            } else {
                redirect('/site/index', 'refresh');
            }
        }

        $info = $this->m_common->db_select("*", "tbl_users", array("user_id" => $id), array(), '', '', array(1, 0), 'row_array');
        //echo "<pre>";print_r($info);exit;
        $country = $this->m_common->db_select("*", "tbl_country", array(), array(), '`order` DESC,`name` asc', '', '', 'all');


        $state = $this->m_common->db_select("*", "tbl_state", array("cid" => $info['country_id']), array(), '', '', '', 'all');

        $city = $this->m_common->db_select("*", "tbl_city", array("state_id" => $info['state_id']), array(), '', '', '', 'all');

        $this->data['country'] = $country;
        $this->data['state'] = $state;
        $this->data['city'] = $city;
        $this->data['info'] = $info;

        $this->load->view('header', $this->data);
        $this->load->view('sidebar', $this->data);
        $this->load->view('edit_ambassador', $this->data);

    }

    public function delete_ambassador()
    {

        if (isset($_POST['id'])) {

            $id = $_POST['id'];
            $id = trim(str_replace("del_", "", $id));
            $this->m_common->delete_entry("tbl_users", array('user_id' => $id));
            $message = "You have successfully deleted the Ambassador.";
            $this->session->set_userdata('current_message', $message);
            echo 1;
            exit;
        }

    }


    public function add_corporate()
    {
        if ((is_permission($this->data['user_info']['role'], "manage_shop")) == FALSE) {
            echo "You Don't have permission to access this page";
            exit;
        }
        //$this->is_access_permission();

        $message = "";
        if (isset($_POST['add_corporate'])) {

            $this->load->library('form_validation');
            $this->form_validation->set_error_delimiters('<span for="required" generated="true" class="help-inline" style="color:red;"> *', '</span>');
            $this->form_validation->set_rules('sname', 'Business Name', 'required');
            $this->form_validation->set_rules('scat', 'Business Category', 'required');
            $this->form_validation->set_rules('sdesp', 'Business Description', 'required');
            
            $this->form_validation->set_rules('city', 'City', 'trim');
            $this->form_validation->set_rules('state', 'State', 'trim');
            $this->form_validation->set_rules('country', 'Country', 'trim');

            $this->form_validation->set_rules('sadd', 'Business Address', 'required|callback_check_city_state_country');
            $this->form_validation->set_rules('postal_code', 'zip code', 'trim|required');
            $this->form_validation->set_rules('email', 'Business Email', 'required|valid_email|is_unique[tbl_users.email]');
            $this->form_validation->set_rules('password', 'Business Password', 'required|min_length[8]|max_length[32]');
            $this->form_validation->set_rules('password1', 'Confirm Password', 'required|callback_confirm_password');
            $this->form_validation->set_rules('sfname', 'Business Owner First Name', 'required');
            $this->form_validation->set_rules('slname', 'Business Owner Last Name', 'required');
            $this->form_validation->set_rules('sphone', 'Business Phone Number', 'callback_check_phone');
            $this->form_validation->set_rules('burl', 'Website Url', '');
            $this->form_validation->set_rules('lat', 'Latitude', 'required');
            $this->form_validation->set_rules('lng', 'Longitude', 'required');
            if ($this->form_validation->run() == TRUE) {

                $location_info = $this->get_city_state_country_id();
                
                $country_id = $location_info['country_id'];
                $state_id = $location_info['state_id'];
                $city_id = $location_info['city_id'];
                
                $inn_user = array(
                    "first_name" => $this->input->post('sfname'),
                    "last_name" => $this->input->post('slname'),
                    "country_id" => $country_id,
                    "state_id" => $state_id,
                    "city_id" => $city_id,
                    "zip_code" => $this->input->post('postal_code'),
                    "email" => $this->input->post('email'),
                    "password" => md5($this->input->post('password')),
                    "role" => 9,
                );
                $temp = $this->m_common->insert_entry("tbl_users", $inn_user, 1);


                if ($temp['last_id'] > 0) {
                    $inn = array(
                        "user_id" => $temp['last_id'],
                        "shop_name" => $this->input->post('sname'),
                        "shop_cats" => $this->input->post('scat'),
                        "shop_description" => $this->input->post('sdesp'),
                        "country_id" => $country_id,
                        "state_id" => $state_id,
                        "city_id" => $city_id,
                        "address" => $this->input->post('sadd'),
                        "zip_code" => $this->input->post('postal_code'),
                        "email" => $this->input->post('email'),
                        "url" => addScheme($this->input->post('burl')),
                        //"username" => $this->input->post('suname'),
                        "password" => md5($this->input->post('password')),
                        "latitude" => $this->input->post('lat'),
                        "longitude" => $this->input->post('lng'),
                        "first_name" => $this->input->post('sfname'),
                        "last_name" => $this->input->post('slname'),
                        "business_phone" => $this->input->post('sphone'),
                        "corporate_main_shop" => 1,
                        "add_by" => $this->data['user_info']['user_id']
                    );
                    $temp_shop = $this->m_common->insert_entry("tbl_shop", $inn, 1);


                    //user add in tbl_users_shops
                    $info_users_shops = array(
                        "user_id" => $temp['last_id'],
                        "shop_id"=>$temp_shop['last_id'],
                        "corporate_user_id"=>$temp['last_id'],
                    );
                    $this->m_common->insert_entry("tbl_users_shops", $info_users_shops, 1);



                    $message = "Corporate " . $this->input->post('sname') . " successfully inserted";
                    if (isset($_FILES['sfile'])) {
                        $r = $this->file_upload($_FILES['sfile'], $temp_shop['last_id'], 1);
                        if (!empty($r)) {
                            $up = array(
                                "shop_image" => $r,
                            );
                            $this->m_common->update_entry("tbl_shop", $up, array("shop_id" => $temp_shop['last_id']));
                            $this->m_common->update_entry("tbl_users", array("profile_pic" => $r), array("user_id" => $temp['last_id']));
                        }
                    }
                    $dt = date('l jS \of F Y \a\t h:i:s A'); // in mail display time text
                    $data = array(
                        "name" => $inn_user['first_name'] . " " . $inn_user['last_name'],
                        "dt" => $dt,
                    );

                    $full_shop_info = $this->get_full_shop_info($temp_shop['last_id']);

                    $message_body = $this->load->view('email/welcome_signup', $data, true);
                    $message_body_new_bsignup = $this->load->view('email/message_body_new_bsignup', $full_shop_info, true);
                    $mail_p = array(
                        "to" => $this->input->post('semail'),
                        "message_body" => $message_body,
                        "subject" => "Welcome to the Locally Epic",
                    );
                    $m_new_bsignup = array(
                        "to" => "dealsonthegogo@gmail.com",
                        "message_body" => $message_body_new_bsignup,
                        "subject" => "Locally Epic : New Business Signup",
                    );
                    $this->sent_email($mail_p);
                    // $this->sent_email($m_new_bsignup);

                    $this->session->set_userdata('current_message', $message);
                    redirect('/site/list_corporate', 'refresh');
                }
            }
        }
        $cats = $this->m_common->db_select("*", "tbl_category", array(), array(), 'cname ASC', '', '', 'all');

        $this->data['cats'] = $cats;
        $this->data['message'] = $message;

        $this->load->view('header', $this->data);
        $this->load->view('sidebar', $this->data);
        $this->load->view('add_corporate', $this->data);
    }



    public function list_corporate()
    {

        if ((is_permission($this->data['user_info']['role'], "list_corporate")) == FALSE) {
            echo "You Don't have permission to access this page";
            exit;
        }
        $message = "";
        $page_no = 1;
        $wh = " and role=9 ";
        $state = array();
        $city = array();
        $wh_state = array();
        $wh_city = array();
        $wh_country = array();

        $country = $this->m_common->db_select("*", "tbl_country", array(), array(), '`order` DESC,`name` asc', '', '', 'all');

        if (isset($_GET['search']) && !empty($_GET['search'])) {
            $search = $_GET['search'];
            $wh .= " and ( t1.first_name LIKE '%$search%' OR t1.last_name LIKE '%$search%'  OR t1.email LIKE '%$search%' ) ";
            $this->data['search'] = $search;
        }
        if (isset($_GET['zip_code']) && !empty($_GET['zip_code'])) {
            $zip_code = $_GET['zip_code'];
            $wh .= " and ( t1.zip_code = $zip_code ) ";
        }
        if (isset($_GET['scountry']) && ($_GET['scountry'] > 0)) {
            $scountry = $_GET['scountry'];
            $wh .= " and ( t1.country_id = $scountry ) ";
        }
        if (isset($_GET['sstate']) && ($_GET['sstate'] > 0)) {
            $sstate = $_GET['sstate'];
            $wh .= " and ( t1.state_id = $sstate ) ";
        }
        if (isset($_GET['scity']) && ($_GET['scity'] > 0)) {
            $scity = $_GET['scity'];
            $wh .= " and ( t1.city_id = $scity ) ";
        }


        $country = $this->m_common->db_select("*", "tbl_country", array(), array(), '`order` DESC,`name` asc', '', '', 'all');

        $page_row_limit = isset($_GET['perpage']) ? $_GET['perpage'] : 25;
        /*if (isset($_GET['search']) && !empty($_GET['search'])) {
            $search = $_GET['search'];
            $wh.=" and ( t1.shop_name like '%$search%' ) ";
        }*/
        if (isset($_GET['scat']) && ($_GET['scat'] > 0)) {
            $scat = $_GET['scat'];
            $wh .= " and ( t1.shop_cats = $scat ) ";
        }


        $q = "select count(t1.user_id) as cnt from tbl_users t1 where 1=1 $wh";
        $res_tb = $this->m_common->select_custom($q);
        // echo "<pre>";print_r($res_tb);exit;
        $tot_rows = $res_tb[0]['cnt'];

        $tot_page = ceil($tot_rows / $page_row_limit);
        if (isset($_GET['page_no']) && $_GET['page_no'] > 0) {
            $page_no = $_GET['page_no'];
        }
        $offset = ($page_no * $page_row_limit) - $page_row_limit;


        $q = "select t1.* from tbl_users t1 where 1=1 $wh group by t1.user_id limit $offset,$page_row_limit";
        $info = $this->m_common->select_custom($q);

        // echo "<pre> $q";
        // print_r($info);exit;

        $prev = ($page_no - 6);
        if ($prev <= 0) {
            $prev = 1;
        }
        $next = ($page_no + 6);
        if ($next >= $tot_page) {
            $next = $tot_page;
        }

        $cats = $this->m_common->db_select("*", "tbl_category", array(), array(), 'cname ASC', '', '', 'all');


        $this->data['cats'] = $cats;
        $this->data['country'] = $country;
        $this->data['state'] = $state;
        $this->data['city'] = $city;

        $this->data['info'] = $info;
        $this->data['tot_page'] = $tot_page;
        $this->data['curr_page'] = $page_no;
        $this->data['prev'] = $prev;
        $this->data['next'] = $next;

        $this->load->view('header', $this->data);
        $this->load->view('sidebar', $this->data);
        $this->load->view('list_corporate', $this->data);
    }




    public function edit_corporate()
    {
        if ((is_permission($this->data['user_info']['role'], "edit_corporate")) == FALSE) {
            echo "You Don't have permission to access this page";
            exit;
        }
        //echo "<pre>";print_r($_POST);exit;
        $message = "";
        if (isset($_POST['edit_corporate'])) {

            //echo "<pre>";print_r($_POST);exit;
            if (!isset($_POST['shop_id'])) {
                redirect('/site/index', 'refresh');
            } else {
                $id = $_POST['shop_id'];
            }

            $this->shop_id = $id;
            $this->userid = $this->input->post('user_id');
            $this->load->library('form_validation');
            $this->form_validation->set_error_delimiters('<span for="required" generated="true" class="help-inline" style="color:red;"> *', '</span>');
            $this->form_validation->set_rules('sname', 'Business Name', 'required');
            $this->form_validation->set_rules('scat', 'Business Category', 'required');
            $this->form_validation->set_rules('sdesp', 'Business Description', 'required');
            $this->form_validation->set_rules('scountry', 'Country', 'required');
            $this->form_validation->set_rules('sstate', 'State', 'required');
            $this->form_validation->set_rules('scity', 'City', 'required');
            $this->form_validation->set_rules('sadd', 'Business Address', 'required');
            $this->form_validation->set_rules('zip_code', 'zip code', 'trim|required');
            $this->form_validation->set_rules('pin', 'Pin', 'trim|required');
            $this->form_validation->set_rules('email', 'Business Email', 'required|valid_email|callback_sm_email_unique');
            if (!empty($_POST['password'])) {
                $this->form_validation->set_rules('password', 'Password', 'required|min_length[8]|max_length[32]');
            }
            $this->form_validation->set_rules('password1', 'Confirm Password', 'callback_confirm_password');
            $this->form_validation->set_rules('sfname', 'Business Owner First Name', 'required');
            $this->form_validation->set_rules('slname', 'Business Owner Last Name', 'required');
            $this->form_validation->set_rules('scfname', 'Second Contact First Name', '');
            $this->form_validation->set_rules('sclname', 'Second Contact Last Name', '');
            $this->form_validation->set_rules('scphone', 'Second Contact Number', 'callback_check_phone');
            $this->form_validation->set_rules('sphone', 'Business Phone Number', 'callback_check_phone');
            $this->form_validation->set_rules('scemail', 'Second Contact Email address', 'valid_email');
            $this->form_validation->set_rules('lat', 'Latitude', 'required');
            $this->form_validation->set_rules('lng', 'Longitude', 'required');

            if ($this->form_validation->run() == TRUE) {

                if ($_POST['password']) {
                    $inn_user = array(
                        "first_name" => $this->input->post('sfname'),
                        "last_name" => $this->input->post('slname'),
                        "country_id" => $this->input->post('scountry'),
                        "state_id" => $this->input->post('sstate'),
                        "city_id" => $this->input->post('scity'),
                        "zip_code" => $this->input->post('zip_code'),
                        "email" => $this->input->post('email'),
                        "password" => md5($this->input->post('password')),
                        "role" => 9,
                    );
                } else {
                    $inn_user = array(
                        "first_name" => $this->input->post('sfname'),
                        "last_name" => $this->input->post('slname'),
                        "country_id" => $this->input->post('scountry'),
                        "state_id" => $this->input->post('sstate'),
                        "city_id" => $this->input->post('scity'),
                        "zip_code" => $this->input->post('zip_code'),
                        "email" => $this->input->post('email'),
                        "role" => 9,
                    );
                }


                $inn = array(
                    // "user_id" => $this->input->post('user_id'),
                    "shop_name" => $this->input->post('sname'),
                    "shop_cats" => $this->input->post('scat'),
                    "shop_description" => $this->input->post('sdesp'),
                    "country_id" => $this->input->post('scountry'),
                    "state_id" => $this->input->post('sstate'),
                    "city_id" => $this->input->post('scity'),
                    "address" => $this->input->post('sadd'),
                    "zip_code" => $this->input->post('zip_code'),
                    "email" => $this->input->post('email'),
                    "url" => addScheme($this->input->post('burl')),
                    //"username" => $this->input->post('suname'),
                    "password" => md5($this->input->post('password')),
                    "latitude" => $this->input->post('lat'),
                    "longitude" => $this->input->post('lng'),
                    "first_name" => $this->input->post('sfname'),
                    "last_name" => $this->input->post('slname'),
                    "contact_first_name" => $this->input->post('scfname'),
                    "contact_last_name" => $this->input->post('sclname'),
                    "contact_phone" => $this->input->post('scphone'),
                    "business_phone" => $this->input->post('sphone'),
                    "contact_email" => $this->input->post('scemail'),
                    "pin" => $this->input->post('pin'),
                    "timezone" => $this->input->post('timezone'),
                    "add_by" => $this->input->post('add_by')

                );

                if (isset($_FILES['sfile'])) {
                    $r = $this->file_upload($_FILES['sfile'], $id, 1);
                    if (!empty($r)) {
                        //$inn_user['profile_pic']=$r;
                        $inn['shop_image'] = $r;
                        $config['image_library'] = 'gd2';
                        $config['source_image'] = '/var/www/html/uploads/user/' . $r;
                        $config['create_thumb'] = FALSE;
                        $config['maintain_ratio'] = FALSE;
                        $config['width'] = 75;
                        $config['height'] = 75;
                        $config['new_image'] = '/var/www/html/uploads/thumbs/' . $r;

                        $this->load->library('image_lib', $config);

                        $this->image_lib->resize();

                    }
                }
                $wh = array(
                    "shop_id" => $this->shop_id
                );
                $this->m_common->update_entry("tbl_shop", $inn, $wh);

                if (!empty($_POST['password'])) {
                    $up['password'] = md5($this->input->post('password'));
                    $this->m_common->update_entry("tbl_users", $up, array('user_id',$this->userid));
                }

                if ($this->input->ip_address() == '68.80.62.233_randy') {
                    echo "<br>";
                    echo $this->input->post('password');
                    echo "<br>";
                    echo $_POST['password'];
                    echo "<pre>";
                    print_r($inn);
                    echo "<br>";
                    exit;
                }

                // $sql = "update tbl_users set email=? where user_id=?";
                // $result = $this->db->query($sql, array($this->input->post('email'), $this->input->post('user_id')));
                // echo  $this->db->last_query();
                // exit;


                //echo "<pre>";print_r($_FILES);
                //echo "<pre>";print_r($inn);exit;
                //$this->m_common->update_entry("tbl_users", $inn_user, array("user_id" => $this->input->post('user_id')));

                if ($this->data['user_info']['role'] == 1) {

                    $a = array(
                        "is_payment" => $this->input->post('is_payment'),
                        "blnexpired" => $this->input->post('blnexpired')
                    );

                    $this->m_common->update_entry("tbl_shop", $a, $wh);
                }

                $message = "Corporate " . $this->input->post('sname') . " has successfully Updated.";
                $this->session->set_userdata('current_message', $message);

                redirect('/site/list_corporate', 'refresh');
            }
        } else {

            if (isset($_GET['id']) && $_GET['id'] > 0) {
                $id = $_GET['id'];
            } else {
                redirect('/site/index', 'refresh');
            }

        }

        $shop = $this->m_common->db_select("*", "tbl_shop", array("shop_id" => $id), array(), '', '', array(1, 0), 'row_array');

        $cats = $this->m_common->db_select("*", "tbl_category", array(), array(), 'cname ASC', '', '', 'all');

        //$cats = $this->m_common->db_select("*", "tbl_category", array('cid != '=>102), array(), 'cname ASC', '', '', 'all');

        $country = $this->m_common->db_select("*", "tbl_country", array(), array(), '`order` DESC,`name` asc', '', '', 'all');

        $state = $this->m_common->db_select("*", "tbl_state", array("cid" => $shop['country_id']), array(), '', '', '', 'all');

        $city = $this->m_common->db_select("*", "tbl_city", array("state_id" => $shop['state_id']), array(), '', '', '', 'all');

        $sql = "select user_id, concat_ws(' ', first_name, last_name) as `name` from tbl_users where role in (1,2,3,4,5) order by `name`";
        $result = $this->db->query($sql, array());

        $r = $result->result_array();

        $this->data['message'] = $message;
        $this->data['shop'] = $shop;
        $this->data['cats'] = $cats;
        $this->data['country'] = $country;
        $this->data['state'] = $state;
        $this->data['city'] = $city;
        $this->data['salespersons'] = $r;
        $this->data['add_by'] = $shop['add_by'];
        //echo "<pre>";print_r($shop);exit;
        $this->load->helper('date');
        $this->load->view('header', $this->data);
        $this->load->view('sidebar', $this->data);
        $this->load->view('edit_corporate', $this->data);
    }


    function view_corporate_shop(){
        if ((is_permission($this->data['user_info']['role'], "view_corporate_shop")) == FALSE) {
            echo "You Don't have permission to access this page";
            exit;
        }
    }

    public function logout()
    {

        $array_items = array('logged_in' => 0);
        $this->session->unset_userdata($array_items);
        $this->session->userdata = array();
        $this->session->sess_destroy();
        redirect('/site/index', 'refresh');
    }

    public function business_password($str)
    {
        if ($_POST['user_name'] == $str) {
            $this->form_validation->set_message('business_password', 'The password must be diffrent from the username');
            return FALSE;
        } else {
            return TRUE;
        }
    }

    public function sm_username_unique($str)
    {


        $shops = $this->m_common->db_select("count(user_id) as cnt", "tbl_users", array("user_name" => $str, 'user_id !=' => $this->userid), array(), '', '', array(1, 0), 'row_array');
        if ($shops['cnt'] > 0) {
            $this->form_validation->set_message('sm_username_unique', 'The username already exists');
            return FALSE;
        } else {
            return TRUE;
        }
    }

    public function sm_email_unique($str)
    {

        $shops = $this->m_common->db_select("count(user_id) as cnt", "tbl_users", array("email" => $str, 'user_id !=' => $this->userid), array(), '', '', array(1, 0), 'row_array');
        if ($shops['cnt'] > 0) {
            $this->form_validation->set_message('sm_email_unique', 'The email already exists');
            return FALSE;
        } else {
            return TRUE;
        }
    }

    public function shop_username_unique($str)
    {

        $shop_id = $this->shop_id;
        $shops = $this->m_common->db_select("count(shop_id) as cnt", "tbl_shop", array("username" => $str, 'user_id !=' => $shop_id), array(), '', '', '', 'row_array');
        if ($shops['cnt'] > 0) {
            $this->form_validation->set_message('shop_username_unique', 'The username already exists');
            return FALSE;
        } else {
            return TRUE;
        }
    }

    public function shop_name_unique($str)
    {

        $shop_id = $this->shop_id;
        $shops = $this->m_common->db_select("count(shop_id) as cnt", "tbl_shop", array("shop_name" => $str, 'user_id !=' => $shop_id), array(), '', '', '', 'row_array');
        if ($shops['cnt'] > 0) {
            $this->form_validation->set_message('shop_username_unique', 'The shop name already exists');
            return FALSE;
        } else {
            return TRUE;
        }
    }

    public function confirm_password($str)
    {


        if ($str != $_POST['password']) {
            $this->form_validation->set_message('confirm_password', 'The confirm password not match');
            return FALSE;
        } else {
            return TRUE;
        }
    }

    public function cat_distance($str)
    {

        if (!preg_match('/[0-9.]+/', $str)) {
            $this->form_validation->set_message('cat_distance', 'Please enter distance in integer or float. (e.g 1,1.5 or 2.0)');
            return FALSE;
        } else {
            return TRUE;
        }
    }

    public function deal_enddate($str)
    {

        $s = $_POST['deal_start'];
        $e = $str;
        if ($e <= $s) {
            $this->form_validation->set_message('deal_enddate', 'The deal end date must be greater than the deal start date.');
            //echo "sdfdsf";exit;
            return FALSE;
        } else {
            return TRUE;
        }
    }

    public function check_swear($str)
    {

        $deal = explode(" ", $str);
        for ($i = 0; $i < count($deal); $i++) {
            $is_swear = $this->m_common->db_select("swear_word", "tbl_swear");
            $swear = array();
            foreach ($is_swear as $key => $value) {

                foreach ($value as $k => $v) {
                    array_push($swear, $v);

                }
            }
            if (in_array($deal[$i], $swear)) {
                $this->form_validation->set_message('check_swear', 'You Can not Add this type word in deal');
                return FALSE;
            } else {
                return TRUE;
            }
        }


    }


    public function dealtimeoverlap()
    {

        if ($this->row4 > 0 || $this->row3 > 0) {

            $this->form_validation->set_message('dealtimeoverlap', "This deal overlaps with another deal.  Please select a different timeframe.");
            return FALSE;
        } else {
            return TRUE;
        }

    }

    public function deal_time()
    {
        $st1 = $this->hms2sec($this->input->post('deal_time'));
        $et1 = $this->hms2sec($this->input->post('deal_end_time'));
        if (isset($this->max_deal_end_time) && !empty($this->max_deal_end_time) && isset($this->max_deal_start_time) && !empty($this->max_deal_start_time)) {
            if (($st1 > $this->max_deal_start_time && $st1 > $this->max_deal_end_time) || ($et1 < $this->max_deal_start_time && $et1 < $this->max_deal_end_time) || ($st1 = $this->max_deal_start_time || $et1 = $this->max_deal_end_time)) {
                $time = $_POST['deal_end_time'];
                $time2 = $_POST['deal_time'];
                $this->form_validation->set_message('deal_time', "The deal is already exists for given time you need to set deal time grater than $time or less than $time2");

                return FALSE;

            } else {
                return TRUE;
            }
        }
    }

    public function deal_end_time($str)
    {

        $deal_time = $this->hms2sec($this->input->post('deal_time'));
        $deal_end_time = $this->hms2sec($str);

        if ($deal_time > $deal_end_time) {

            $this->form_validation->set_message('deal_end_time', 'The deal end time must be greater than the deal start time.');

            return FALSE;
        } else {

            return TRUE;
        }


    }

    public function old_password_match($str)
    {
        $shop_id = $this->session->userdata('shop_id');
        $shops = $this->m_common->db_select("count(shop_id) as cnt", "tbl_shop", array("password" => $str, 'shop_id' => $shop_id), array(), '', '', '', 'row_array');
        if ($shops['cnt'] == 0) {
            $this->form_validation->set_message('old_password_match', 'The Old password not match');
            return FALSE;
        } else {
            return TRUE;
        }
    }

    public function shop_email($str)
    {

        $shop_id = $this->shop_id;
        $shops = $this->m_common->db_select("count(shop_id) as cnt", "tbl_shop", array("email" => $str, 'user_id !=' => $shop_id), array(), '', '', '', 'row_array');
        if ($shops['cnt'] > 0) {
            $this->form_validation->set_message('shop_email', 'The Email address already exists');
            return FALSE;
        } else {
            return TRUE;
        }
    }

    public function add_notes()
    {

        $inn = array(
            "notes_text" => $_POST['notes_text'],
            "notes_by" => $this->data['user_info']['user_id'],
            "business_id" => $_POST['business_id'],
        );
        $src = (!empty($this->data['user_info']['profile_pic'])) ? base_url() . "uploads/user/" . $this->data['user_info']['profile_pic'] : base_url() . "assets/img/profile-pic.jpg";
        $this->m_common->insert_entry("tbl_business_notes", $inn);
        $str = '';
        $str .= '<tr>';
        $str .= '<td>' . $this->data['user_info']['first_name'] . ' ' . $this->data['user_info']['last_name'] . ' <img src="' . $src . '" width="70" height="auto"></td>';
        $str .= '<td>' . $_POST['notes_text'] . '</td>';
        $str .= '<td>' . date("F j, Y") . '</td>';
        $str .= '</tr>';

        echo $str;
        exit;
    }

    private function get_country_name($id)
    {
        if ((is_permission($this->data['user_info']['role'], "get_country_name")) == FALSE) {
            echo "You Don't have permission to access this page";
            exit;
        }
        $r = "";
        $a = $this->m_common->db_select("name", "tbl_country", array("id" => $id), array(), '`order` DESC,`name` asc', '', '', 'row_array');
        if (!empty($a)) {
            $r = $a['name'];
        }
        return $r;
    }

    private function get_state_name($id)
    {

        $r = "";
        $a = $this->m_common->db_select("state_name as name", "tbl_state", array("sid" => $id), array(), '', '', '', 'row_array');
        if (!empty($a)) {
            $r = $a['name'];
        }
        return $r;
    }

    private function get_city_name($id)
    {

        $r = "";
        $a = $this->m_common->db_select("city_name as name", "tbl_city", array("city_id" => $id), array(), '', '', '', 'row_array');
        if (!empty($a)) {
            $r = $a['name'];
        }
        return $r;
    }

    public function delete_deal()
    {

        if (isset($_POST['id'])) {

            $id = $_POST['id'];
            $id = trim(str_replace("del_", "", $id));

            $deal = $this->m_common->db_select("deal_title", "tbl_deal", array("id" => $id), array(), '', '', array(1, 0), 'row_array');
            if (!empty($deal)) {
                $this->m_common->update_entry("tbl_deal", array('is_active' => 0), array('id' => $id));
                $message = "You have successfully deleted the deal.";
                $this->session->set_userdata('current_message', $message);
                echo '1';
                exit;
            }

        }
    }

    public function delete_cat()
    {

        if (isset($_POST['id'])) {

            $id = $_POST['id'];
            $id = trim(str_replace("del_", "", $id));
            $this->m_common->delete_entry("tbl_category", array('cid' => $id));
            $message = "You have successfully deleted the category.";
            $this->session->set_userdata('current_message', $message);
            echo '1';
            exit;
        }
    }

    public function delete_shop()
    {


        if (isset($_POST['id'])) {

            $id = $_POST['id'];
            $id = trim(str_replace("del_", "", $id));
            $this->m_common->delete_entry("tbl_shop", array('shop_id' => $id));
            $message = "You have successfully deleted the business.";
            $this->session->set_userdata('current_message', $message);
            echo '1';
            exit;
        }
    }



    public function update_status()
    {

        if (isset($_POST['id'])) {

            $id = $_POST['id'];
            $id = trim(str_replace("up_", "", $id));

            $result = $this->m_common->db_select("user_id , user_is_active", "tbl_users", array("user_id" => $id));
            if($result){
                if($result[0]['user_is_active'] == 'Yes'){
                    $this->m_common->update_entry("tbl_users", array('user_is_active'=>'No'), array("user_id" => $id));
                    $message = "You have successfully Deactivate the business.";
                    $status_code =  '1';

                }else{
                    $this->m_common->update_entry("tbl_users", array('user_is_active'=>'Yes'), array("user_id" => $id));
                    $message = "You have successfully Activate the business.";
                    $status_code =  '1';
                }

            }else{

                $message = "Please Try Later";
                $status_code =  '2';
            }
            
            $this->session->set_userdata('current_message', $message);
            echo $status_code;
            exit;
        }
    }



    public function delete_sales_manager()
    {


        if (isset($_POST['id'])) {

            $id = $_POST['id'];
            $id = trim(str_replace("del_", "", $id));
            $this->m_common->delete_entry("tbl_users", array('user_id' => $id));
            $message = "You have successfully deleted the Sales Manager.";
            $this->session->set_userdata('current_message', $message);
            echo 1;
            exit;
        }
    }

    public function delete_schedule()
    {


        if (isset($_POST['id'])) {

            $id = $_POST['id'];
            $id = trim(str_replace("del_", "", $id));
            $this->m_common->delete_entry("tbl_schedule", array('id' => $id));

            echo '1';
            exit;
        }
    }

    public function loadData()
    {

        $loadType = $_POST['loadType'];
        $loadId = $_POST['loadId'];

        $result = $this->m_common->getData($loadType, $loadId);
        $HTML = "";

        if ($result->num_rows() > 0) {
            foreach ($result->result() as $list) {
                if (isset($_POST['setvalue']) && !empty($_POST['setvalue'])) {
                    $HTML .= "<option value='" . $list->id . "' ";
                    if ($_POST['setvalue'] == $list->id) {
                        $HTML .= "selected";
                    }
                    $HTML .= ">" . $list->name . "</option>";
                } else {
                    $HTML .= "<option value='" . $list->id . "'>" . $list->name . "</option>";
                }
            }
        }
        echo $HTML;
    }

    public function load_perent_state_vise()
    {

        $state_id = $_POST['state_id'];
        $HTML = '';
        $q = "SELECT user_id , first_name , last_name ,role FROM tbl_users WHERE (role = 3 AND state_id = $state_id) OR (role = 4 AND state_id = $state_id) or role=2";
        $result = $this->m_common->select_custom($q);
        if (!empty($result)) {

            foreach ($result as $list) {

                $HTML .= "<option value='" . $list['user_id'] . "'>" . $list['first_name'] . " " . $list['last_name'] . "</option>";

            }
            $HTML .= '';
        }
        echo $HTML;
    }

    public function loadparent()
    {
        $role = $_POST['role'];
        $HTML = "";
        if ($role == 2) {
            $a = 0;
            echo "";
        }
        if ($role == 3) {
            $a = 2;
            $result = $this->m_common->db_select("user_id , first_name , last_name", "tbl_users", array("role" => $a));

            if (!empty($result)) {
                $HTML .= '<label class="col-sm-2 control-label">Select National Manager *:</label><div class="col-sm-10"> <select class="form-control" name="perent_id">';
                foreach ($result as $list) {

                    $HTML .= "<option value='" . $list['user_id'] . "'>" . $list['first_name'] . " " . $list['last_name'] . "</option>";

                }
                $HTML .= '</select></div>';
            }
        }
        if ($role == 4) {
            $a = 3;
            $result = $this->m_common->db_select("user_id , first_name , last_name", "tbl_users", array("role" => $a));

            if (!empty($result)) {
                $HTML .= '<label class="col-sm-2 control-label">Select State Manager *:</label><div class="col-sm-10"> <select class="form-control" name="perent_id">';
                foreach ($result as $list) {

                    $HTML .= "<option value='" . $list['user_id'] . "'>" . $list['first_name'] . " " . $list['last_name'] . "</option>";

                }
                $HTML .= '</select></div>';
            }
        }
        echo $HTML;
    }

    private function file_upload($arr, $cid, $is_user = 0)
    {
        //echo "<pre>";print_r($arr);
        $tmp = rtrim($_SERVER['DOCUMENT_ROOT'], "/");
        if ($_SERVER['HTTP_HOST'] == "localhost") {

            $this->project_path = $tmp . str_replace(basename($_SERVER['SCRIPT_NAME']), "", $_SERVER['SCRIPT_NAME']);
        } else {
            $this->project_path = $tmp . str_replace(basename($_SERVER['SCRIPT_NAME']), "", $_SERVER['SCRIPT_NAME']);
        }

        $r = "";

        if (!empty($arr)) {
            if ($arr["error"] == 0) {
                $temp = explode('.', $arr['name']);
                $extention = end($temp);
                $file_name = reset($temp);
                if (empty($file_name)) {
                    $file_name = $temp[1];
                }
                $ftoken = $this->get_random_string(5);
                $file_name = $cid . '_' . time() . '_' . $ftoken . '.' . $extention;
                if ($is_user) {
                    $path = $this->project_path . 'uploads/user/';
                } else {
                    $path = $this->project_path . 'uploads/';
                }

                $file_path = $path . $file_name;

                if (move_uploaded_file($arr["tmp_name"], $file_path) > 0) {
                    $r = $file_name;
                }
            }
        }

        return $r;
    }

    private function check_login()
    {
        if (!$this->session->userdata('logged_in')) {
            redirect('authentication/login', 'refresh');
        }

        //Check to make sure the user has a valid payment
        //$user_info = $this->m_common->db_select("*", "tbl_users", array("user_id" => $this->user_id), '', '', '', array(1, 0), 'row_array');

        if ($this->session->userdata('role') == 6) {
            $chk_is_payment = $this->m_common->db_select("is_payment,expiration_date", "tbl_shop", array("shop_id" => $this->session->userdata('shop_id')), '', '', '', array(1, 0), 'row_array');

            if ($chk_is_payment["is_payment"] == 0) {
                redirect('/authentication/payment?expired=true', 'refresh');
            }

            $expiration_date = $chk_is_payment['expiration_date'];

            if( !empty($expiration_date) )
            {
                $current_date = date('Y-m-d');

                if( strtotime($current_date) > strtotime($expiration_date)){
                    
                    $this->m_common->update_entry("tbl_shop", array('is_payment'=> 0,'blnexpired'=>1), array("shop_id" => $this->session->userdata('shop_id') ));
                    redirect('/authentication/payment?expired=true', 'refresh');
                }

            }
            
        }

    }   


    function check_phone($phone)
    {
        $phone = trim($phone);
        if ($phone == '') {
            return TRUE;
        } else {
            if (preg_match('/^\(?[0-9]{3}\)?[-. ]?[0-9]{3}[-. ]?[0-9]{4}$/', $phone)) {
                return preg_replace('/^\(?([0-9]{3})\)?[-. ]?([0-9]{3})[-. ]?([0-9]{4})$/', '($1) $2-$3', $phone);
            } else {
                $this->form_validation->set_message('check_phone', '%s ' . $phone . ' is not valid. Only numbers or Numbers that follows the + sign is an valid format');

                return FALSE;
            }
        }
    }

    public function check_phone1($phone)
    {
        $p = "/^[\+]*[0-9]{4,15}+$/";
        $phone = trim($phone);
        if ($phone == '') {
            return TRUE;
        }
        if (preg_match($p, $phone)) {
            return true;
        } else {
            $this->form_validation->set_message('check_phone', '%s ' . $phone . ' is not valid. Only numbers or Numbers that follows the + sign is an valid format');
            return false;
        }
    }

    public function test_email()
    {
        //echo phpinfo();
        $arr = array(
            "to" => "jckhunt4@gmail.com",
            "subject" => "",
            "message_body" => "dsf ft",

        );
        $this->sent_email($arr, 1);
    }

    private function sent_email($p, $is_debug = 0)
    {


        $this->load->library('email');

        //$p['message_body']="benzatine contact by ".$p['from_name']." with email ".$p['from']."<br /><br />";
        $config = array(
            'protocol' => 'smtp',
            'smtp_host' => 'smtp.email-smtp.us-east-1.amazonaws.com',
            'mailpath' => '/usr/sbin/sendmail',
            'smtp_port' => 465,
            'smtp_timeout' => '7',
            'smtp_user' => "AKIAIZL5JQNDI36X75MA",
            'smtp_pass' => "Ag5bvFfJJyt3uO81rTcCZNpVvuzis0CQcbrHt6d4b32q",
            'charset' => 'utf-8',
            'newline' => "\r\n",
            'wordwrap' => TRUE,
            'mailtype' => 'html',
            'validation' => TRUE,
            'priority' => 1,
        );

        //echo "<pre>";print_r($config);
        $this->email->initialize($config);
        $this->email->from("info@locallyepic.com", "Locally Epic");
        $this->email->to($p['to']);
        $this->email->subject($p['subject']);
        $this->email->message($p['message_body']);
        @$this->email->send();
        if ($is_debug == 1) {
            echo $this->email->print_debugger();
            exit;
        }

    }

    private function get_random_string($length = 10)
    {
        $alphabet = "abcdefghijklmnopqrstuwxyzABCDEFGHIJKLMNOPQRSTUWXYZ0123456789";
        $token = "";
        $alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
        for ($i = 0; $i < $length; $i++) {
            $n = rand(0, $alphaLength);
            $token .= $alphabet[$n];
        }
        return $token;
    }

    private function is_access_permission()
    {
        if ($this->session->userdata('is_admin') == 0) {
            redirect('site/warning?msg=you need to login as a admin', 'refresh');
        }
    }

    private function get_line_graph($arr, $type)
    {
        $cat_key = "xval";
        if ($type == "Daily") {
            $cat_key = "xval";
        }
        $data = "";
        $cats = "";
        foreach ($arr as $v) {
            $data .= $v['cnt'] . ",";
            if ($type == "Daily") {
                $c = date("j M", strtotime($v['date']));
            } else {
                $c = $v['xval'];
            }
            $cats .= "'" . $c . "',";
        }
        $r = "";
        $r = "{
        title: {
            text: 'Push Message',
            x: -20 //center
        },
        subtitle: {
            text: '',
            x: -20
        },
        xAxis: {
            categories: [" . $cats . "]
        },
        yAxis: {
            title: {
                text: 'Count'
            },
            plotLines: [{
                value: 0,
                width: 1,
                color: '#808080'
            }]
        },
        tooltip: {
            valueSuffix: ''
        },
        legend: {
            layout: 'vertical',
            align: 'right',
            verticalAlign: 'middle',
            borderWidth: 0
        },
        series: [{
            name: 'Push Notes',
            data: [" . $data . "]
        }]
    }";

        return $r;
    }

    private function get_col_graph($arr, $type)
    {
        $cat_key = "xval";
        if ($type == "Daily") {
            $cat_key = "xval";
        }
        $data = "";
        $cats = "";
        foreach ($arr as $v) {
            $data .= $v['cnt'] . ",";
            if ($type == "Daily") {
                $c = date("j M", strtotime($v['date']));
            } else {
                $c = $v['xval'];
            }
            $cats .= "'" . $c . "',";
        }
        $r = "";
        $r = "{
        chart: {
            type: 'column'
        },
        title: {
            text: 'Push Message'
        },
        subtitle: {
            text: ''
        },
        xAxis: {
            categories: [" . $cats . "]
        },
        yAxis: {
            min: 0,
            title: {
                text: 'Count'
            }
        },
        tooltip: {
            headerFormat: '<span >{point.key}</span><table>',
            pointFormat: '<tr><td >{series.name}: </td>' +
                '<td><b>{point.y:.1f} </b></td></tr>',
            footerFormat: '</table>',
            shared: true,
            useHTML: true
        },
        plotOptions: {
            column: {
                pointPadding: 0.2,
                borderWidth: 0
            }
        },
        series: [{
            name: 'Push Notes',
             data: [" . $data . "]

        },]
    }";

        return $r;
    }

    private function get_full_shop_info($id)
    {
        $result = array();

        $q = "select * from tbl_shop  where shop_id = $id limit 1";
        $info = $this->m_common->select_custom($q);
        foreach ($info as $k => $v) {
            $result = $v;
            $result['shop_cats'] = $this->get_shop_cats_names($v['shop_cats']);
            $result['shop_image'] = $this->get_shop_image_path($v['shop_image']);
            $result['country_name'] = $this->get_country_name($v['country_id']);
            $result['state_name'] = $this->get_state_name($v['state_id']);
            $result['city_name'] = $this->get_city_name($v['city_id']);
        }
        return $result;
    }

    private function get_shop_cats_names($str)
    {
        if ($str) {
            $q = "select group_concat(`cname`) as str from tbl_category where cid in ($str) limit 1";
            $info = $this->m_common->select_custom($q);
            if (isset($info[0]['str']) && !empty($info[0]['str'])) {
                return $info[0]['str'];
            } else {
                return "";
            }
        } else {
            return "";
        }
    }

    private function get_tot_bsignup($str)
    {

        $wh = "";
        $td = date("Y-m-d");
        $tm = date("m");
        $ty = date("Y");
        if ($str == "today") {
            $wh .= " and DATE(`date`) =  '$td'";
        } else if ($str == "MTD") {
            $wh .= " and MONTH(`date`) =  '$tm' and YEAR(`date`) =  '$ty'";
        } else if ($str == "YTD") {
            $wh .= " and YEAR(`date`) =  '$ty' ";
        }

        foreach ($this->wh_serch as $wk => $wv) {
            $wh .= " and `$wk` =  $wv ";
        }

        $q = "select count(`shop_id`) as cnt from tbl_shop where 1=1 $wh limit 1";

        $info = $this->m_common->select_custom($q);
        if (isset($info[0]['cnt'])) {
            return $info[0]['cnt'];
        } else {
            return 0;
        }

    }


    private function get_tot_messagesent($str)
    {
        $wh = "";
        $td = date("Y-m-d");
        $tm = date("m");
        $ty = date("Y");
        if ($str == "today") {
            $wh .= " and t1.`date` =  '$td'";
        } else if ($str == "MTD") {
            $wh .= " and MONTH(t1.`date`) =  '$tm' and YEAR(t1.`date`) =  '$ty'";
        } else if ($str == "YTD") {
            $wh .= " and YEAR(t1.`date`) =  '$ty' ";
        }

        foreach ($this->wh_serch as $wk => $wv) {
            $wh .= " and t3.`$wk` =  $wv ";
        }

        foreach ($this->wh_in_search as $key => $value) {
            $wh .= " and t3.`$key` IN  ($value) ";
        }

        $q = "select count(t1.`id`) as cnt 
                    from push_notes t1 
                    join tbl_deal t2 on t1.deal_id = t2.id 
                    join tbl_shop t3 on t2.shop_id = t3.shop_id 
                    where 1=1 $wh limit 1";

        $info = $this->m_common->select_custom($q);
        if (isset($info[0]['cnt'])) {
            return $info[0]['cnt'];
        } else {
            return 0;
        }
    }


    private function get_tot_pnotes($str)
    {
        $wh = "";
        $td = date("Y-m-d");
        $tm = date("m");
        $ty = date("Y");
        if ($str == "today") {
            $wh .= " and t1.`date` =  '$td'";
        } else if ($str == "MTD") {
            $wh .= " and MONTH(t1.`date`) =  '$tm' and YEAR(t1.`date`) =  '$ty'";
        } else if ($str == "YTD") {
            $wh .= " and YEAR(t1.`date`) =  '$ty' ";
        }

        foreach ($this->wh_serch as $wk => $wv) {
            $wh .= " and t3.`$wk` =  $wv ";
        }

        foreach ($this->wh_in_search as $key => $value) {
            $wh .= " and t3.`$key` IN  ($value) ";
        }

        $q = "select count(t1.`id`) as cnt 
                    from push_notes t1 
                    join tbl_deal t2 on t1.deal_id = t2.id 
                    join tbl_shop t3 on t2.shop_id = t3.shop_id 
                    where 1=1 $wh limit 1";

        $info = $this->m_common->select_custom($q);
        if (isset($info[0]['cnt'])) {
            return $info[0]['cnt'];
        } else {
            return 0;
        }
    }

    private function get_tot_deal_created($str)
    {
        $wh = "";
        $td = date("Y-m-d");
        $tm = date("m");
        $ty = date("Y");
        if ($str == "today") {
            $wh .= " and DATE(t1.`deal_start`) =  '$td'";
        } else if ($str == "MTD") {
            $wh .= " and MONTH(t1.`deal_start`) =  '$tm' and YEAR(t1.`date`) =  '$ty'";
        } else if ($str == "YTD") {
            $wh .= " and YEAR(t1.`deal_start`) =  '$ty' ";
        }

        foreach ($this->wh_serch as $wk => $wv) {
            $wh .= " and t2.`$wk` =  $wv ";
        }

        foreach ($this->wh_in_search as $key => $value) {
            $wh .= " and t2.`$key` IN  ($value) ";
        }

        $q = "select count(t1.`id`) as cnt from tbl_deal t1 join tbl_shop t2 on t1.shop_id = t2.shop_id where 1=1 $wh";
//            print_r($q);
        $info = $this->m_common->select_custom($q);

        // echo $this->db->last_query();
        // exit();
//            echo "<pre>";print_r($info);
//            echo '<pre>';
//            print_r($q);
//            echo '<br/>';
//            print_r($info);
//            exit;
        if (isset($info[0]['cnt'])) {
            return $info[0]['cnt'];
        } else {
            return 0;
        }
    }

    private function get_tot_deal_activated($str)
    {
        $wh = "";
        $td = date("Y-m-d");
        $tm = date("m");
        $ty = date("Y");
        $dayn = date("N");


        if ($str == "today") {
            $wh .= " and DATE(t2.`record_time`) <= '$td' and DATE(t2.`record_time`) >= '$td' ";
        } else if ($str == "MTD") {
            $wh .= " and MONTH(t2.`record_time`) =  '$tm' ";
        } else if ($str == "YTD") {
            $wh .= " and YEAR(t2.`record_time`) =  '$ty' ";
        }
        foreach ($this->wh_serch as $wk => $wv) {
            $wh .= " and t3.`$wk` =  $wv ";
        }

        foreach ($this->wh_in_search as $key => $value) {
            $wh .= " and t3.`$key` IN  ($value) ";
        }


        $q = "select count(t1.`id`) as cnt from tbl_deal t1 join tbl_deals_activated t2 on t1.id = t2.deal_id join tbl_shop t3 on t1.shop_id = t3.shop_id where 1=1 AND t1.is_active = 1 $wh ";

        $info = $this->m_common->select_custom($q);

        // echo $this->db->last_query();
        // exit();
        $this->sqllog(__FUNCTION__, 0, $this->db->last_query(), $str);

        if (isset($info[0]['cnt'])) {
            return $info[0]['cnt'];
        } else {
            return 0;
        }


        #####################################################################


//            if($str=="today"){
//                $wh.=" and DATE(t1.`deal_start`) <= '$td' and DATE(t1.`deal_end`) >= '$td' ";
//            }
//            else if($str=="MTD"){
//                $wh.=" and MONTH(t1.`deal_start`) =  '$tm' ";
//            }
//            else if($str=="YTD"){
//                $wh.=" and YEAR(t1.`deal_start`) =  '$ty' ";
//            }
//
//            foreach($this->wh_serch as $wk=>$wv){
//                $wh.=" and t2.`$wk` =  $wv ";
//            }
//
//
//            $q = "select count(t1.`id`) as cnt from tbl_deal t1 join tbl_shop t2 on t1.shop_id = t2.shop_id where 1=1 AND t1.is_active = 1 $wh ";
//
//            $info = $this->m_common->select_custom($q);
//
//            if(isset($info[0]['cnt'])){
//                return $info[0]['cnt'];
//            }else{
//                return 0;
//            }
    }

    private function get_tot_newapp($str)
    {
        $ret = array();
        $wh = "";
        $td = date("Y-m-d");
        $tm = date("m");
        $ty = date("Y");
        if ($str == "today") {
            $wh .= " and DATE(`date`) =  '$td' ";
        } else if ($str == "MTD") {
            $wh .= " and MONTH(`date`) =  '$tm' and YEAR(`date`) =  '$ty' ";
        } else if ($str == "YTD") {
            $wh .= " and YEAR(`date`) =  '$ty'";
        }
        $q = "select count(`user_id`) as cnt from tbl_customer where 1=1 and device_type = 1 $wh limit 1";
        $info = $this->m_common->select_custom($q, "row_array");
        $ret['ios'] = $info[0]['cnt'];
        $q = "select count(`user_id`) as cnt from tbl_customer where 1=1 and device_type = 2 $wh limit 1";
        $info = $this->m_common->select_custom($q);
        $ret['android'] = $info[0]['cnt'];
        return $ret;
    }


    private function get_shop_image_path($str)
    {
        return base_url() . "uploads/" . $str;
    }

    private function get_tot_deal_used()
    {

        $q = "select count(`deal_activated_id`) as cnt from tbl_deals_activated";
        $info = $this->m_common->select_custom($q);
        if (isset($info[0]['cnt'])) {
            return $info[0]['cnt'];
        } else {
            return 0;
        }
    }

    private function get_tot_deal_shared()
    {

        $q = "select sum(`share_count`) as cnt from tbl_deal";
        $info = $this->m_common->select_custom($q);
        if (isset($info[0]['cnt'])) {
            return $info[0]['cnt'];
        } else {
            return 0;
        }
    }

    private function sqllog($verb, $user_id, $sql, $results, $extended_info = array())
    {

        $results = json_encode($results);
        $extended_info = json_encode($extended_info);

        $q = "insert into sqllog set apicall=?, user_id=?, `sql`=?, sql_results=?,extended_info=?";
        $result = $this->db->query($q, array($verb, $user_id, $sql, $results, $extended_info));
    }




    function get_corporate_business($user_id){
        $q = "SELECT t1.corporate_user_id,t2.shop_id,t2.user_id,t2.shop_name,t2.address FROM tbl_users_shops t1 JOIN tbl_shop t2 ON t2.shop_id = t1.shop_id  where t1.corporate_user_id='$user_id'  GROUP BY t1.shop_id";
        $info = $this->m_common->select_custom($q);
        return $info;
    }


     function get_corporate_business_v2($user_id){
        $q = "SELECT t1.corporate_user_id,t2.shop_id,t2.user_id,t2.shop_name,t2.address FROM tbl_users_shops t1 JOIN tbl_shop t2 ON t2.shop_id = t1.shop_id  where t1.corporate_user_id='$user_id' and t2.corporate_main_shop = 0 GROUP BY t1.shop_id";
        $info = $this->m_common->select_custom($q);
        return $info;
    }


    function get_corporate_business_id($user_id){
        $q = "SELECT IFNULL(GROUP_CONCAT(t1.shop_id),0) as busi_id FROM tbl_users_shops t1 JOIN tbl_shop t2 ON t2.shop_id = t1.shop_id  where t1.corporate_user_id='$user_id' ";
        $info = $this->m_common->select_custom($q);
        return $info[0]['busi_id'];
    }


    function get_user_shop_id($user_id){
        $this->db->select('t2.shop_id');
        $this->db->from('tbl_users_shops t1');
        $this->db->join('tbl_shop t2','t2.shop_id = t1.shop_id');
        $this->db->where('t1.user_id',$user_id);
        return $row = $this->db->get()->row();
    }


    public function add_business()
    {
        if ((is_permission($this->data['user_info']['role'], "add_business")) == FALSE) {
            echo "You Don't have permission to access this page";
            exit;
        }
        //$this->is_access_permission();

        $message = "";
        if (isset($_POST['add_shop'])) {

            $this->load->library('form_validation');
            $this->form_validation->set_error_delimiters('<span for="required" generated="true" class="help-inline" style="color:red;"> *', '</span>');
            $this->form_validation->set_rules('sname', 'Business Name', 'required');
            $this->form_validation->set_rules('scat', 'Business Category', 'required');
            $this->form_validation->set_rules('sdesp', 'Business Description', 'required');

            $this->form_validation->set_rules('city', 'City', 'trim');
            $this->form_validation->set_rules('state', 'State', 'trim');
            $this->form_validation->set_rules('country', 'Country', 'trim');
            
            $this->form_validation->set_rules('sadd', 'Business Address', 'required|callback_check_city_state_country');

            $this->form_validation->set_rules('postal_code', 'zip code', 'trim|required');
            // $this->form_validation->set_rules('email', 'Business Email', 'required|valid_email|is_unique[tbl_users.email]');
            $this->form_validation->set_rules('email', 'Business Email', 'required|valid_email');
            $this->form_validation->set_rules('sfname', 'Business Owner First Name', 'required');
            $this->form_validation->set_rules('slname', 'Business Owner Last Name', 'required');
            $this->form_validation->set_rules('sphone', 'Business Phone Number', 'callback_check_phone');
            $this->form_validation->set_rules('burl', 'Website Url', '');
            $this->form_validation->set_rules('lat', 'Latitude', 'required');
            $this->form_validation->set_rules('lng', 'Longitude', 'required');
            if ($this->form_validation->run() == TRUE) {

                $location_info = $this->get_city_state_country_id();
                
                $country_id = $location_info['country_id'];
                $state_id = $location_info['state_id'];
                $city_id = $location_info['city_id'];
                
                // $inn_user = array(
                //     "first_name" => $this->input->post('sfname'),
                //     "last_name" => $this->input->post('slname'),
                //     "country_id" => $country_id,
                //     "state_id" => $state_id,
                //     "city_id" => $city_id,
                //     "zip_code" => $this->input->post('postal_code'),
                //     "email" => $this->input->post('email'),
                //     "password" => md5($this->input->post('password')),
                //     "role" => 6,
                // );
                // $temp = $this->m_common->insert_entry("tbl_users", $inn_user, 1);


                // if ($temp['last_id'] > 0) {
                    $inn = array(
                        // "user_id" => $temp['last_id'],
                        "user_id" => $this->data['user_info']['user_id'],
                        "shop_name" => $this->input->post('sname'),
                        "shop_cats" => $this->input->post('scat'),
                        "shop_description" => $this->input->post('sdesp'),
                        "country_id" => $country_id,
                        "state_id" => $state_id,
                        "city_id" => $city_id,
                        "address" => $this->input->post('sadd'),
                        "zip_code" => $this->input->post('postal_code'),
                        "email" => $this->input->post('email'),
                        "url" => addScheme($this->input->post('burl')),
                        "password" => md5($this->input->post('password')),
                        "latitude" => $this->input->post('lat'),
                        "longitude" => $this->input->post('lng'),
                        "first_name" => $this->input->post('sfname'),
                        "last_name" => $this->input->post('slname'),
                        "business_phone" => $this->input->post('sphone'),
                        "add_by" => $this->data['user_info']['user_id']
                    );
                    $temp_shop = $this->m_common->insert_entry("tbl_shop", $inn, 1);


                     //user add in tbl_users_shops
                    $info_users_shops = array(
                        "user_id" => $this->data['user_info']['user_id'],
                        "shop_id"=>$temp_shop['last_id'],
                        "corporate_user_id"=>$this->data['user_info']['user_id'],
                    );

                    $this->m_common->insert_entry("tbl_users_shops", $info_users_shops, 1);


                    $message = "Shop " . $this->input->post('sname') . " successfully inserted";
                    if (isset($_FILES['sfile'])) {
                        $r = $this->file_upload($_FILES['sfile'], $temp_shop['last_id'], 1);
                        if (!empty($r)) {
                            $up = array(
                                "shop_image" => $r,
                            );
                            $this->m_common->update_entry("tbl_shop", $up, array("shop_id" => $temp_shop['last_id']));
                        }
                    }



                    $dt = date('l jS \of F Y \a\t h:i:s A'); // in mail display time text
                    $data = array(
                        "name" => $inn_user['first_name'] . " " . $inn_user['last_name'],
                        "dt" => $dt,
                    );

                    $full_shop_info = $this->get_full_shop_info($temp_shop['last_id']);

                    $message_body = $this->load->view('email/welcome_signup', $data, true);
                    $message_body_new_bsignup = $this->load->view('email/message_body_new_bsignup', $full_shop_info, true);
                    $mail_p = array(
                        "to" => $this->input->post('semail'),
                        "message_body" => $message_body,
                        "subject" => "Welcome to the Locally Epic",
                    );
                    $m_new_bsignup = array(
                        "to" => "dealsonthegogo@gmail.com",
                        "message_body" => $message_body_new_bsignup,
                        "subject" => "Locally Epic :Corporate New Business Create",
                    );
                    $this->sent_email($mail_p);
                   
                    $this->session->set_userdata('current_message', $message);
                    redirect('/site/list_business', 'refresh');
            }
        }
        $cats = $this->m_common->db_select("*", "tbl_category", array(), array(), 'cname ASC', '', '', 'all');
        
        $this->data['cats'] = $cats;
        $this->data['message'] = $message;

        $this->load->view('header', $this->data);
        $this->load->view('sidebar', $this->data);
        $this->load->view('add_business', $this->data);
    }




    public function list_business()
    {
        if ((is_permission($this->data['user_info']['role'], "list_business")) == FALSE) {
            echo "You Don't have permission to access this page";
            exit;
        }
        $message = "";
        $page_no = 1;
        $wh = "";
        $join = "";
        $state = array();
        $city = array();
        $wh_state = array();
        $wh_city = array();
        $wh_country = array();

        
        if ($this->data['user_info']['role'] == 9) {
            $user_id = $this->data['user_info']['user_id'];
            $join .="LEFT JOIN tbl_users_shops t2 ON t2.shop_id=t1.shop_id";
            $wh .= " and t2.corporate_user_id='$user_id'";
        }
        elseif ( isset($_GET['id']) ) {
            $join .="LEFT JOIN tbl_users_shops t2 ON t2.shop_id=t1.shop_id";
            $wh .= " and t2.corporate_user_id='".$_GET['id']."'";
        }else{
            echo 'Invalid';
            exit();
        }

         
        if (isset($_GET['search']) && !empty($_GET['search'])) {
            $search = $_GET['search'];
            $wh .= " and ( t1.shop_name LIKE '%$search%' OR t1.email LIKE '%$search%' ) ";
            $this->data['search'] = $search;
        }
        if (isset($_GET['scat']) && ($_GET['scat'] > 0)) {
            $scat = $_GET['scat'];
            $wh .= " and ( t1.shop_cats = $scat ) ";
        }
        if (isset($_GET['zip_code']) && !empty($_GET['zip_code'])) {
            $zip_code = $_GET['zip_code'];
            $wh .= " and ( t1.zip_code = $zip_code ) ";
        }
        if (isset($_GET['scountry']) && ($_GET['scountry'] > 0)) {
            $scountry = $_GET['scountry'];
            $wh .= " and ( t1.country_id = $scountry ) ";
        }
        if (isset($_GET['sstate']) && ($_GET['sstate'] > 0)) {
            $sstate = $_GET['sstate'];
            $wh .= " and ( t1.state_id = $sstate ) ";
        }
        if (isset($_GET['scity']) && ($_GET['scity'] > 0)) {
            $scity = $_GET['scity'];
            $wh .= " and ( t1.city_id = $scity ) ";
        }

        if (isset($_GET['ustatus']) && ($_GET['ustatus'] != '')) {
            $ustatus = $_GET['ustatus'];
            if( $ustatus == 'Deactivate'){
                $wh .= " and ( t3.user_is_active = 'No' ) ";
            }
            if( $ustatus == 'Active'){
                $wh .= " and ( t3.user_is_active = 'Yes' ) ";
            }
        }


        $country = $this->m_common->db_select("*", "tbl_country", array(), array(), '`order` DESC,`name` asc', '', '', 'all');

        $page_row_limit = isset($_GET['perpage']) ? $_GET['perpage'] : 25;
        
        if (isset($_GET['scat']) && ($_GET['scat'] > 0)) {
            $scat = $_GET['scat'];
            $wh .= " and ( t1.shop_cats = $scat ) ";
        }


        $q = "select count(t1.shop_id) as cnt from tbl_shop t1 $join JOIN tbl_users t3 ON t3.user_id=t1.user_id where 1=1 $wh";
        $res_tb = $this->m_common->select_custom($q);
        // echo "<pre>";print_r($res_tb);exit;
        $tot_rows = $res_tb[0]['cnt'];

        $tot_page = ceil($tot_rows / $page_row_limit);
        if (isset($_GET['page_no']) && $_GET['page_no'] > 0) {
            $page_no = $_GET['page_no'];
        }
        $offset = ($page_no * $page_row_limit) - $page_row_limit;


        $q = "select t1.*,t3.user_is_active from tbl_shop t1 $join JOIN tbl_users t3 ON t3.user_id=t1.user_id where 1=1 $wh group by t1.shop_id  order by trim(t1.shop_name) asc limit $offset,$page_row_limit";
        $info = $this->m_common->select_custom($q);


        $prev = ($page_no - 6);
        if ($prev <= 0) {
            $prev = 1;
        }
        $next = ($page_no + 6);
        if ($next >= $tot_page) {
            $next = $tot_page;
        }

        $cats = $this->m_common->db_select("*", "tbl_category", array(), array(), 'cname ASC', '', '', 'all');


        $this->data['cats'] = $cats;
        $this->data['country'] = $country;
        $this->data['state'] = $state;
        $this->data['city'] = $city;

        $this->data['info'] = $info;
        $this->data['tot_page'] = $tot_page;
        $this->data['curr_page'] = $page_no;
        $this->data['prev'] = $prev;
        $this->data['next'] = $next;

        $this->load->view('header', $this->data);
        $this->load->view('sidebar', $this->data);
        $this->load->view('list_business', $this->data);
    }



    public function edit_business()
    {
        if ((is_permission($this->data['user_info']['role'], "edit_business")) == FALSE) {
            echo "You Don't have permission to access this page";
            exit;
        }
        $message = "";
        if (isset($_POST['edit_business'])) {

            //echo "<pre>";print_r($_POST);exit;
            if (!isset($_POST['shop_id'])) {

                redirect('/site/index', 'refresh');
            } else {
                $id = $_POST['shop_id'];
            }

            $this->shop_id = $id;
            $this->userid = $this->input->post('user_id');
            $this->load->library('form_validation');
            $this->form_validation->set_error_delimiters('<span for="required" generated="true" class="help-inline" style="color:red;"> *', '</span>');
            $this->form_validation->set_rules('sname', 'Business Name', 'required');
            $this->form_validation->set_rules('scat', 'Business Category', 'required');
            $this->form_validation->set_rules('sdesp', 'Business Description', 'required');
            $this->form_validation->set_rules('city', 'City', 'trim');
            $this->form_validation->set_rules('state', 'State', 'trim');
            $this->form_validation->set_rules('country', 'Country', 'trim');
            $this->form_validation->set_rules('sadd', 'Business Address', 'required|callback_check_city_state_country');
            $this->form_validation->set_rules('postal_code', 'zip code', 'trim|required');
            $this->form_validation->set_rules('pin', 'Pin', 'trim|required');
            $this->form_validation->set_rules('email', 'Business Email', 'required|valid_email');
            // $this->form_validation->set_rules('email', 'Business Email', 'required|valid_email|callback_sm_email_unique');
            if (!empty($_POST['password'])) {
                // $this->form_validation->set_rules('password', 'Password', 'required|min_length[8]|max_length[32]');
            }
            // $this->form_validation->set_rules('password1', 'Confirm Password', 'callback_confirm_password');
            $this->form_validation->set_rules('sfname', 'Business Owner First Name', 'required');
            $this->form_validation->set_rules('slname', 'Business Owner Last Name', 'required');
            $this->form_validation->set_rules('sphone', 'Business Phone Number', 'callback_check_phone');
            $this->form_validation->set_rules('lat', 'Latitude', 'required');
            $this->form_validation->set_rules('lng', 'Longitude', 'required');

            if ($this->form_validation->run() == TRUE) {

                $location_info = $this->get_city_state_country_id();
                
                $country_id = $location_info['country_id'];
                $state_id = $location_info['state_id'];
                $city_id = $location_info['city_id'];

                $inn = array(
                    // "user_id" => $this->input->post('user_id'),
                    "shop_name" => $this->input->post('sname'),
                    "shop_cats" => $this->input->post('scat'),
                    "shop_description" => $this->input->post('sdesp'),
                    "country_id" => $country_id,
                    "state_id" => $state_id,
                    "city_id" => $city_id,
                    "address" => $this->input->post('sadd'),
                    "zip_code" => $this->input->post('postal_code'),
                    "email" => $this->input->post('email'),
                    "url" => addScheme($this->input->post('burl')),
                    //"username" => $this->input->post('suname'),
                    "password" => md5($this->input->post('password')),
                    "latitude" => $this->input->post('lat'),
                    "longitude" => $this->input->post('lng'),
                    "first_name" => $this->input->post('sfname'),
                    "last_name" => $this->input->post('slname'),
                    "business_phone" => $this->input->post('sphone'),
                    "pin" => $this->input->post('pin'),
                    "timezone" => $this->input->post('timezone'),
                    // "add_by" => $this->input->post('add_by')

                );

                if (isset($_FILES['sfile'])) {
                    $r = $this->file_upload($_FILES['sfile'], $id, 1);
                    if (!empty($r)) {
                        //$inn_user['profile_pic']=$r;
                        $inn['shop_image'] = $r;
                        $config['image_library'] = 'gd2';
                        $config['source_image'] = '/var/www/html/uploads/user/' . $r;
                        $config['create_thumb'] = FALSE;
                        $config['maintain_ratio'] = FALSE;
                        $config['width'] = 75;
                        $config['height'] = 75;
                        $config['new_image'] = '/var/www/html/uploads/thumbs/' . $r;

                        $this->load->library('image_lib', $config);

                        $this->image_lib->resize();

                    }
                }
                $wh = array(
                    "shop_id" => $this->shop_id
                );
                $this->m_common->update_entry("tbl_shop", $inn, $wh);


                $message = "Shop " . $this->input->post('sname') . " has successfully Updated.";
                $this->session->set_userdata('current_message', $message);

                if ($this->data['user_info']['role'] == 1) {
                    redirect('/site/list_business?id='.$this->userid, 'refresh');
                }

                redirect('/site/list_business', 'refresh');
            }
        } else {

            if (isset($_GET['id']) && $_GET['id'] > 0) {
                $id = $_GET['id'];
            } else {
                redirect('/site/index', 'refresh');
            }

        }

        $this->db->select('t1.*,t2.name country_name,t3.state_name,t4.city_name');
        $this->db->from('tbl_shop t1');
        $this->db->join('tbl_country t2','t2.id=t1.country_id','left');
        $this->db->join('tbl_state t3','t3.sid=t1.state_id','left');
        $this->db->join('tbl_city t4','t4.city_id=t1.city_id','left');
        $this->db->where('shop_id',$id);
        $shop = $this->db->get()->row_array();

        if($shop){

            $cats = $this->m_common->db_select("*", "tbl_category", array(), array(), 'cname ASC', '', '', 'all');

            $sql = "select user_id, concat_ws(' ', first_name, last_name) as `name` from tbl_users where role in (1,2,3,4,5) order by `name`";
            $result = $this->db->query($sql, array());

            $r = $result->result_array();
           
            $this->data['message'] = $message;
            $this->data['shop'] = $shop;
            $this->data['cats'] = $cats;
            $this->data['salespersons'] = $r;
            $this->data['add_by'] = $shop['add_by'];
            //echo "<pre>";print_r($shop);exit;
            $this->load->helper('date');
            $this->load->view('header', $this->data);
            $this->load->view('sidebar', $this->data);
            $this->load->view('edit_business', $this->data);
        }else{

            redirect('/');
        }


    }




     public function add_user()
    {
        if ((is_permission($this->data['user_info']['role'], "add_user")) == FALSE) {
            echo "You Don't have permission to access this page";
            exit;
        }
        //$this->is_access_permission();
        $corporate_user_id = $this->data['user_info']['user_id'];


        $q = "SELECT t2.shop_id,t2.shop_name FROM tbl_users_shops t1 JOIN tbl_shop t2 ON t2.shop_id = t1.shop_id WHERE t1.corporate_user_id = '$corporate_user_id' GROUP BY t1.shop_id";
        $res_q = $this->m_common->select_custom($q);
        $this->data['corporate_busi_list'] = $res_q;

        $message = "";
        if (isset($_POST['add_shop'])) {

            $this->load->library('form_validation');
            $this->form_validation->set_error_delimiters('<span for="required" generated="true" class="help-inline" style="color:red;"> *', '</span>');
            $this->form_validation->set_rules('scountry', 'Country', 'required');
            $this->form_validation->set_rules('sstate', 'State', 'required');
            $this->form_validation->set_rules('scity', 'City', 'required');
            $this->form_validation->set_rules('zip_code', 'zip code', 'trim|required');
            $this->form_validation->set_rules('email', 'User Email', 'required|valid_email|is_unique[tbl_users.email]');
            $this->form_validation->set_rules('password', 'User Password', 'required|min_length[8]|max_length[32]');
            $this->form_validation->set_rules('password1', 'Confirm Password', 'required|callback_confirm_password');
            $this->form_validation->set_rules('sfname', 'User First Name', 'required');
            $this->form_validation->set_rules('slname', 'User Last Name', 'required');
            $this->form_validation->set_rules('cbid', 'Select Business ', 'required');
            if ($this->form_validation->run() == TRUE) {
                
                $inn_user = array(
                    "first_name" => $this->input->post('sfname'),
                    "last_name" => $this->input->post('slname'),
                    "country_id" => $this->input->post('scountry'),
                    "state_id" => $this->input->post('sstate'),
                    "city_id" => $this->input->post('scity'),
                    "zip_code" => $this->input->post('zip_code'),
                    "email" => $this->input->post('email'),
                    "password" => md5($this->input->post('password')),
                    "role" => 6,
                    "perent_id" => $corporate_user_id,
                    "is_corporate_business_user" => 1,
                );
                $temp = $this->m_common->insert_entry("tbl_users", $inn_user, 1);


                if ($temp['last_id'] > 0) {

                     //user add in tbl_users_shops
                    $info_users_shops = array(
                        "user_id" => $temp['last_id'],
                        "shop_id"=>$this->input->post('cbid'),
                        "corporate_user_id"=>$corporate_user_id,
                    );
                    $this->m_common->insert_entry("tbl_users_shops", $info_users_shops, 1);


                    

                    $message = "User " . $this->input->post('sname') . " successfully inserted";
                    if (isset($_FILES['sfile'])) {
                        $r = $this->file_upload($_FILES['sfile'], $temp['last_id'], 1);
                        if (!empty($r)) {
                            $this->m_common->update_entry("tbl_users", array("profile_pic" => $r), array("user_id" => $temp['last_id']));
                        }
                    }



                    // $dt = date('l jS \of F Y \a\t h:i:s A'); // in mail display time text
                    // $data = array(
                    //     "name" => $inn_user['first_name'] . " " . $inn_user['last_name'],
                    //     "dt" => $dt,
                    // );

                    // $full_shop_info = $this->get_full_shop_info($temp_shop['last_id']);

                    // $message_body = $this->load->view('email/welcome_signup', $data, true);
                    // $message_body_new_bsignup = $this->load->view('email/message_body_new_bsignup', $full_shop_info, true);
                    // $mail_p = array(
                    //     "to" => $this->input->post('semail'),
                    //     "message_body" => $message_body,
                    //     "subject" => "Welcome to the Locally Epic",
                    // );
                    // $m_new_bsignup = array(
                    //     "to" => "dealsonthegogo@gmail.com",
                    //     "message_body" => $message_body_new_bsignup,
                    //     "subject" => "Locally Epic :Corporate New Business Create",
                    // );
                    // $this->sent_email($mail_p);
                    //$this->sent_email($m_new_bsignup);

                    $this->session->set_userdata('current_message', $message);
                    redirect('/site/list_user', 'refresh');
                }
            }
        }
        $cats = $this->m_common->db_select("*", "tbl_category", array(), array(), 'cname ASC', '', '', 'all');
        $country = $this->m_common->db_select("*", "tbl_country", array(), array(), '`order` DESC,`name` asc', '', '', 'all');
        $state = array();
        $city = array();

        if (!empty($_POST['scountry'])) {
            $state = $this->m_common->db_select("*", "tbl_state", array("cid" => $_POST['scountry']));
            if (!empty($_POST['sstate'])) {
                $city = $this->m_common->db_select("*", "tbl_city", array("state_id" => $_POST['sstate']));
            }
        }

        $this->data['cats'] = $cats;
        $this->data['country'] = $country;
        $this->data['state'] = $state;
        $this->data['city'] = $city;
        $this->data['message'] = $message;

        $this->load->view('header', $this->data);
        $this->load->view('sidebar', $this->data);
        $this->load->view('add_user', $this->data);
    }



    public function list_user()
    {
        if ((is_permission($this->data['user_info']['role'], "list_user")) == FALSE) {
            echo "You Don't have permission to access this page";
            exit;
        }
        $message = "";
        $page_no = 1;
        $wh = "";
        $join = "";
        $state = array();
        $city = array();
        $wh_state = array();
        $wh_city = array();
        $wh_country = array();
        $corporate_user_id = $this->data['user_info']['user_id'];

        if (isset($_GET['ustatus']) && ($_GET['ustatus'] != '')) {
            $ustatus = $_GET['ustatus'];
            if( $ustatus == 'Deactivate'){
                $wh .= " and ( t1.user_is_active = 'No' ) ";
            }
            if( $ustatus == 'Active'){
                $wh .= " and ( t1.user_is_active = 'Yes' ) ";
            }
        }


        $page_row_limit = isset($_GET['perpage']) ? $_GET['perpage'] : 25;
        

        $q = "SELECT count(t1.user_id) as cnt from tbl_users t1 where 1=1 and t1.perent_id='$corporate_user_id' $wh";
        $res_tb = $this->m_common->select_custom($q);
        // echo "<pre>";print_r($res_tb);exit;
        $tot_rows = $res_tb[0]['cnt'];

        $tot_page = ceil($tot_rows / $page_row_limit);
        if (isset($_GET['page_no']) && $_GET['page_no'] > 0) {
            $page_no = $_GET['page_no'];
        }
        $offset = ($page_no * $page_row_limit) - $page_row_limit;


        $q = "SELECT t1.*,t3.shop_name from tbl_users t1 JOIN tbl_users_shops t2 ON t1.user_id = t2.user_id JOIN tbl_shop t3 ON t3.shop_id = t2.shop_id where 1=1 and t1.perent_id='$corporate_user_id' and t2.corporate_user_id = '$corporate_user_id' $wh limit $offset,$page_row_limit";
        $info = $this->m_common->select_custom($q);

        // echo "<pre>";
        // print_r($info);
        // exit();

        $prev = ($page_no - 6);
        if ($prev <= 0) {
            $prev = 1;
        }
        $next = ($page_no + 6);
        if ($next >= $tot_page) {
            $next = $tot_page;
        }

        $cats = $this->m_common->db_select("*", "tbl_category", array(), array(), 'cname ASC', '', '', 'all');


        // $this->data['cats'] = $cats;
        // $this->data['country'] = $country;
        // $this->data['state'] = $state;
        // $this->data['city'] = $city;

        $this->data['info'] = $info;
        $this->data['tot_page'] = $tot_page;
        $this->data['curr_page'] = $page_no;
        $this->data['prev'] = $prev;
        $this->data['next'] = $next;

        $this->load->view('header', $this->data);
        $this->load->view('sidebar', $this->data);
        $this->load->view('list_user', $this->data);
    }


    public function edit_user()
    {
       if((is_permission($this->data['user_info']['role'], "edit_user")) == FALSE){
           echo "You Don't have permission to access this page";
           exit;
       }


        $user_id = $this->input->get('id',true);

        $message = "";
        if (isset($_POST['edit_user'])) {

            //echo "<pre>";print_r($_POST);exit;
            $this->userid = $user_id;
            $this->load->library('form_validation');
            $this->form_validation->set_error_delimiters('<span for="required" generated="true" class="help-inline" style="color:red;"> *', '</span>');

            $this->form_validation->set_rules('first_name', 'First Name', 'required');
            $this->form_validation->set_rules('last_name', 'Last Name', 'required');
            // $this->form_validation->set_rules('email', 'Email', 'required|valid_email|callback_sm_email_unique');
            $this->form_validation->set_rules('scountry', 'Country', 'required');
            $this->form_validation->set_rules('sstate', 'State', 'required');
            $this->form_validation->set_rules('scity', 'City', 'required');
            $this->form_validation->set_rules('zip_code', 'zip code', 'trim|required');
            $this->form_validation->set_rules('bank_acount_num', 'bank_acount_num', 'trim');
            $this->form_validation->set_rules('bank_routing_num', 'bank_routing_num', 'trim');
            $this->form_validation->set_rules('full_address', 'full_address', 'trim');

            if (!empty($_POST['password'])) {
                $this->form_validation->set_rules('password', 'Password', 'required|min_length[8]|max_length[32]');
            }

            $this->form_validation->set_rules('password1', 'Password', 'callback_confirm_password');

            $this->form_validation->set_rules('profile_pic', 'Profile Image', '');


            if ($this->form_validation->run() == TRUE) {


                $up = array(
                    "first_name" => $this->input->post('first_name'),
                    "last_name" => $this->input->post('last_name'),
                    //"user_name" => $this->input->post('user_name'),
                    "country_id" => $this->input->post('scountry'),
                    "state_id" => $this->input->post('sstate'),
                    "city_id" => $this->input->post('scity'),
                    "zip_code" => $this->input->post('zip_code'),
                    // "email" => $this->input->post('email'),
                    "bank_acount_num" => $this->input->post('bank_acount_num'),
                    "bank_routing_num" => $this->input->post('bank_routing_num'),
                    "full_address" => $this->input->post('full_address')

                );

                // echo "<pre>";
                // print_r($up);
                // exit();
                
                if (!empty($_POST['password'])) {
                    $up['password'] = md5($this->input->post('password'));
                }
                if (isset($_FILES['profile_pic'])) {
                    $r = $this->file_upload($_FILES['profile_pic'], $user_id, 1);
                    if (!empty($r)) {
                        $up['profile_pic'] = $r;
                        $config['image_library'] = 'gd2';
                        $config['source_image'] = '/var/www/html/uploads/user/' . $r;
                        $config['create_thumb'] = FALSE;
                        $config['maintain_ratio'] = FALSE;
                        $config['width'] = 75;
                        $config['height'] = 75;
                        $config['new_image'] = '/var/www/html/uploads/thumbs/' . $r;

                        $this->load->library('image_lib', $config);

                        $this->image_lib->resize();

                    }
                }
                $wh = array(
                    "user_id" => $user_id,
                );
                $info = $this->m_common->db_select("*", "tbl_users", array("user_id" => $user_id), array(), '', '', '', 'row_array');

                $this->m_common->update_entry("tbl_users", $up, $wh);
                $message = " Your profile has successfully updated";
                $this->session->set_userdata('current_message', $message);


                // if ($info["bank_acount_num"] !== $this->input->post('bank_acount_num')) {

                //     //email
                //     $this->load->library('email');

                //     //$p['message_body']="benzatine contact by ".$p['from_name']." with email ".$p['from']."<br /><br />";
                //     $config = array(
                //         'protocol' => 'smtp',
                //         'smtp_host' => 'ssl://email-smtp.us-east-1.amazonaws.com',
                //         'smtp_port' => '465',
                //         'smtp_timeout' => '7',
                //         'smtp_user' => "AKIAJOY3AJZ62KMGFOUQ",
                //         'smtp_pass' => "AirMJyt3GRT3YGtxsxCqzlc9TqbgmbrdXq7J+gMQyWP5",
                //         'charset' => 'utf-8',
                //         'newline' => "\r\n",
                //         'wordwrap' => TRUE,
                //         'mailtype' => 'html',
                //         'validation' => TRUE,
                //         'priority' => 1,
                //         'smtp_crypto' => 'tls'
                //     );
                //     //echo "<pre>";print_r($config);
                //     $this->email->initialize($config);
                //     $this->email->from("r.johnson@proxmob.com", "Bank Account Info");
                //     $this->email->to("m.minor@proxmob.com", "Bank Account Info");
                //     $this->email->cc("r.johnson@proxmob.com", "Bank Account Info");

                //     $this->email->subject("Bank Account Info");
                //     $_POST["password"] = "";
                //     $_POST["password1"] = "";
                //     $var = print_r($_POST, true);

                //     $this->email->message("<pre>$var</pre>");
                //     @$this->email->send();

                // }


                redirect("site/edit_user?id=$user_id", 'refresh');
            }
        }

        $info = $this->m_common->db_select("*", "tbl_users", array("user_id" => $user_id), array(), '', '', '', 'row_array');
        $country = $this->m_common->db_select("*", "tbl_country", array(), array(), '`order` DESC,`name` asc', '', '', 'all');
        $state = array();
        $city = array();

        if ((isset($info['country_id']) && $info['country_id'] > 0)) {
            $state = $this->m_common->db_select("*", "tbl_state", array("cid" => $info['country_id']));
            if ((isset($info['state_id']) && $info['state_id'] > 0)) {
                $city = $this->m_common->db_select("*", "tbl_city", array("state_id" => $info['state_id']));
            }
        }

        $this->data['country'] = $country;
        $this->data['state'] = $state;
        $this->data['city'] = $city;

        $this->data['info'] = $info;
        $this->data['message'] = $message;


        $this->load->view('header', $this->data);
        $this->load->view('sidebar', $this->data);
        $this->load->view('edit_user', $this->data);

    }


    public function edit_corporate_user()
    {

        if ((is_permission($this->data['user_info']['role'], "edit_corporate_user")) == FALSE) {
            echo "You Don't have permission to access this page";
            exit;
        }

        
        if(!isset($_GET['id'])){
            echo "invalid";
            exit();
        }
        $user_id = $_GET['id'];
        $message = "";

        if (isset($_POST['change_profile'])) {

            //echo "<pre>";print_r($_POST);exit;
            $this->userid = $user_id;
            $this->load->library('form_validation');
            $this->form_validation->set_error_delimiters('<span for="required" generated="true" class="help-inline" style="color:red;"> *', '</span>');

            $this->form_validation->set_rules('first_name', 'First Name', 'required');
            $this->form_validation->set_rules('last_name', 'Last Name', 'required');
            $this->form_validation->set_rules('email', 'Email', 'required|valid_email|callback_sm_email_unique');
            $this->form_validation->set_rules('scountry', 'Country', 'required');
            $this->form_validation->set_rules('sstate', 'State', 'required');
            $this->form_validation->set_rules('scity', 'City', 'required');
            $this->form_validation->set_rules('zip_code', 'zip code', 'trim|required');
            $this->form_validation->set_rules('bank_acount_num', 'bank_acount_num', 'trim');
            $this->form_validation->set_rules('bank_routing_num', 'bank_routing_num', 'trim');
            $this->form_validation->set_rules('full_address', 'full_address', 'trim');

            if (!empty($_POST['password'])) {
                $this->form_validation->set_rules('password', 'Password', 'required|min_length[8]|max_length[32]');
            }

            $this->form_validation->set_rules('password1', 'Password', 'callback_confirm_password');

            $this->form_validation->set_rules('profile_pic', 'Profile Image', '');


            if ($this->form_validation->run() == TRUE) {


                $up = array(
                    "first_name" => $this->input->post('first_name'),
                    "last_name" => $this->input->post('last_name'),
                    //"user_name" => $this->input->post('user_name'),
                    "country_id" => $this->input->post('scountry'),
                    "state_id" => $this->input->post('sstate'),
                    "city_id" => $this->input->post('scity'),
                    "zip_code" => $this->input->post('zip_code'),
                    "email" => $this->input->post('email'),
                    "bank_acount_num" => $this->input->post('bank_acount_num'),
                    "bank_routing_num" => $this->input->post('bank_routing_num'),
                    "full_address" => $this->input->post('full_address')

                );
                if (!empty($_POST['password'])) {
                    $up['password'] = md5($this->input->post('password'));
                }
                if (isset($_FILES['profile_pic'])) {
                    $r = $this->file_upload($_FILES['profile_pic'], $user_id, 1);
                    if (!empty($r)) {
                        $up['profile_pic'] = $r;
                        $config['image_library'] = 'gd2';
                        $config['source_image'] = '/var/www/html/uploads/user/' . $r;
                        $config['create_thumb'] = FALSE;
                        $config['maintain_ratio'] = FALSE;
                        $config['width'] = 75;
                        $config['height'] = 75;
                        $config['new_image'] = '/var/www/html/uploads/thumbs/' . $r;

                        $this->load->library('image_lib', $config);

                        $this->image_lib->resize();

                    }
                }
                $wh = array(
                    "user_id" => $user_id,
                );
                $info = $this->m_common->db_select("*", "tbl_users", array("user_id" => $user_id), array(), '', '', '', 'row_array');

                $this->m_common->update_entry("tbl_users", $up, $wh);
                $message = " Your profile has successfully updated";
                $this->session->set_userdata('current_message', $message);


                if ($info["bank_acount_num"] !== $this->input->post('bank_acount_num')) {

                    //email
                    $this->load->library('email');

                    //$p['message_body']="benzatine contact by ".$p['from_name']." with email ".$p['from']."<br /><br />";
                    $config = array(
                        'protocol' => 'smtp',
                        'smtp_host' => 'ssl://email-smtp.us-east-1.amazonaws.com',
                        'smtp_port' => '465',
                        'smtp_timeout' => '7',
                        'smtp_user' => "AKIAJOY3AJZ62KMGFOUQ",
                        'smtp_pass' => "AirMJyt3GRT3YGtxsxCqzlc9TqbgmbrdXq7J+gMQyWP5",
                        'charset' => 'utf-8',
                        'newline' => "\r\n",
                        'wordwrap' => TRUE,
                        'mailtype' => 'html',
                        'validation' => TRUE,
                        'priority' => 1,
                        'smtp_crypto' => 'tls'
                    );
                    //echo "<pre>";print_r($config);
                    $this->email->initialize($config);
                    $this->email->from("r.johnson@proxmob.com", "Bank Account Info");
                    $this->email->to("m.minor@proxmob.com", "Bank Account Info");
                    $this->email->cc("r.johnson@proxmob.com", "Bank Account Info");

                    $this->email->subject("Bank Account Info");
                    $_POST["password"] = "";
                    $_POST["password1"] = "";
                    $var = print_r($_POST, true);

                    $this->email->message("<pre>$var</pre>");
                    // @$this->email->send();

                }


                redirect('site/list_corporate', 'refresh');
            }
        }

        $info = $this->m_common->db_select("*", "tbl_users", array("user_id" => $user_id), array(), '', '', '', 'row_array');
        $country = $this->m_common->db_select("*", "tbl_country", array(), array(), '`order` DESC,`name` asc', '', '', 'all');
        $state = array();
        $city = array();

        if ((isset($info['country_id']) && $info['country_id'] > 0)) {
            $state = $this->m_common->db_select("*", "tbl_state", array("cid" => $info['country_id']));
            if ((isset($info['state_id']) && $info['state_id'] > 0)) {
                $city = $this->m_common->db_select("*", "tbl_city", array("state_id" => $info['state_id']));
            }
        }

        $this->data['country'] = $country;
        $this->data['state'] = $state;
        $this->data['city'] = $city;

        $this->data['info'] = $info;
        $this->data['message'] = $message;


        $this->load->view('header', $this->data);
        $this->load->view('sidebar', $this->data);
        $this->load->view('change_corporate_profile', $this->data);

    }





    public function message_consumer()
    {
        if ((is_permission($this->data['user_info']['role'], "message_consumer")) == FALSE) {
            echo "You Don't have permission to access this page";
            exit;
        }



        $deal = array("timezone" => "UM5");

        if (isset($_GET['deal_id'])) {
            $deal_id = $_GET['deal_id'];
            $deal = $this->m_common->db_select("*", "tbl_deal", array("id" => $deal_id), array(), '', '', '', 'row_array');

            $deal['deal_start'] = DateTime::createFromFormat('Y-m-d', $deal['deal_start'])->format('m/d/y');
        }


        if ($this->session->userdata('role') == 6) {

            if($this->data['user_info']['is_corporate_business_user'] == 1){

                $this->db->select('t2.shop_id,t2.timezone,t2.shop_image');
                $this->db->from('tbl_users_shops t1');
                $this->db->join('tbl_shop t2','t2.shop_id = t1.shop_id');
                $this->db->where('t1.user_id',$this->session->userdata('user_id'));
                $row = $this->db->get()->row();

            }else{

                $sql = "select shop_id,timezone,shop_image from tbl_shop where user_id = ?";
                $result = $this->db->query($sql, array($this->session->userdata('user_id')));

                //print_rr($this->db->last_query());
                $row = $result->row();
            }

            $_GET["id"] = $row->shop_id;
            $deal = array("timezone" => $row->timezone);
        } else {

            if (!isset($_POST["shop_id"]) && isset($deal["shop_id"])) {
                $_POST["shop_id"] = $deal["shop_id"];
                $_GET["id"] = $deal["shop_id"];
            }

            if (isset($_GET["id"])) {
                $sql = "select shop_id,timezone,shop_image from tbl_shop where shop_id = ?";
                $result = $this->db->query($sql, array($_GET["id"]));
                $row = $result->row();
            }

            if (isset($_POST["shop_id"])) {
                $sql = "select shop_id,timezone,shop_image from tbl_shop where shop_id = ?";
                $result = $this->db->query($sql, array($_POST["shop_id"]));
                $row = $result->row();
            }

        }

        if (isset($row)) {
            $this->data['shop'] = $row;
        }

        $this->load->helper('date');
        $message = "";
        $where = array();
        $join = array();

        $this->row3 = 0;
        $this->row4 = 0;


        if (isset($_POST['create_deal'])) {


            if ($this->session->userdata('role') == 9) {

                $this->load->library('form_validation');
                $this->form_validation->set_error_delimiters('<span for="required" generated="true" class="help-inline" style="color:red;"> *', '</span>');
                $this->form_validation->set_rules('offershop_id[]', 'Select Business', 'required');
                $this->form_validation->set_rules('deal_title', 'Deal Title', 'required|callback_check_swear');
                $this->form_validation->set_rules('deal_description', 'Deal Description', 'required|callback_check_swear');
                $this->form_validation->set_rules('deal_start', 'Deal Start Date', 'required');

                $this->form_validation->set_rules('deal_repeat[]', 'deal repeat', '');
                $deal_repeat = (array)$this->input->post('deal_repeat');

                for($i=1; $i<=7; $i++){
                
                    $is_find = in_array($i, $deal_repeat);

                    if($is_find){

                        $this->form_validation->set_rules('datepicker_start_'.$i, 'Offer Start Start', 'required');
                        $this->form_validation->set_rules('datepicker_end_'.$i, 'Offer Start End', 'required');
                    }
                }

                // if ($this->form_validation->run() == TRUE) {
                //     echo "done";
                //     echo "<pre>";
                //     print_r($_POST);
                //     exit();

                // }

                if ($this->form_validation->run() == TRUE) {

                    $offershops_ids = $this->input->post('offershop_id');
                   
                    $start_date = DateTime::createFromFormat('m/d/y', $_POST['deal_start'])->format('Y-m-d');

                    $converted_start_date = convert_to_utc($start_date, $this->input->post('deal_time'), $this->input->post('timezone'));
                    $converted_end_date = convert_to_utc($start_date, $this->input->post('deal_end_time'), $this->input->post('timezone'));


                    $deal_time = $converted_start_date["seconds"];
                    $deal_end_time = $converted_end_date["seconds"];

                    
                    if (isset($_FILES['deal_image'])) {
                        $r = $this->file_upload($_FILES['deal_image'], 'deal');
                        if (!empty($r)) {

                            $deal_image = "http://" . $_SERVER['SERVER_NAME'] . "/uploads/$r";

                            $config['image_library'] = 'gd2';
                            $config['source_image'] = '/var/www/html/uploads/' . $r;
                            $config['create_thumb'] = FALSE;
                            $config['maintain_ratio'] = FALSE;
                            $config['width'] = 75;
                            $config['height'] = 75;
                            $config['new_image'] = '/var/www/html/uploads/thumbs/' . $r;
                            $this->load->library('image_lib', $config);
                            $this->image_lib->resize();
                        }
                    }

                    $barcode_image = '';
                    // if (isset($_FILES['barcode_image'])) {
                    //     $r = $this->file_upload($_FILES['barcode_image'], 'barcode');
                    //     if (!empty($r)) {

                    //         $barcode_image = "http://" . $_SERVER['SERVER_NAME'] . "/uploads/$r";

                    //         $config['image_library'] = 'gd2';
                    //         $config['source_image'] = '/var/www/html/uploads/' . $r;
                    //         $config['create_thumb'] = FALSE;
                    //         $config['maintain_ratio'] = FALSE;
                    //         $config['width'] = 75;
                    //         $config['height'] = 75;
                    //         $config['new_image'] = '/var/www/html/uploads/thumbs/' . $r;
                    //         $this->load->library('image_lib', $config);
                    //         $this->image_lib->resize();
                    //     }
                    // }


                    foreach ($offershops_ids as $osikey => $osivalue) {

                        $offer_shop_id = $osivalue;

                        $rep_array = (array)$this->input->post('deal_repeat');
                        $repeat = implode(",", $rep_array);
                        $deal_start_date = $converted_start_date["utc_date"];


                        if (!isset($deal_image)) {
                            $deal_image = "https://" . $_SERVER['SERVER_NAME'] . "/images/no_image.png";
                        }


                        $inn = array(
                            "shop_id" => $offer_shop_id,
                            "deal_title" => $this->input->post('deal_title'),
                            "deal_description" => $this->input->post('deal_description'),
                            "original_price" => $this->input->post('original_price'),
                            "offer_price" => $this->input->post('offer_price'),
                            "deal_start" => $deal_start_date,
                            "deal_end" => $converted_end_date["utc_date"],
                            "deal_time" => $deal_time,
                            "deal_end_time" => $deal_end_time,
                            "repeat" => $repeat,
                            "deal_image" => $deal_image,
                            "barcode_image" => $barcode_image,
                            "is_main_deal" => 1,
                            "timezone" => $this->input->post('timezone'),
                            "offer_type" => 2,
                        );


                        if (isset($_POST['deal_image_dup'])) {
                            $inn['deal_image'] = $_POST['deal_image_dup'];
                        }


                        $temp = $this->m_common->insert_entry("tbl_deal", $inn, 1);
                        if ($temp['last_id'] > 0) {

                           

                            $this->createRepeatDeals($temp['last_id'], $deal_start_date, $rep_array,$_POST,2);
                        }
                    }
                    
                    $message = "Your Message Was Successfully Created Thank You";
                    $this->session->set_userdata('current_message', $message);
                    // exit();
                    // if ($this->data['user_info']['role'] == 6) {
                    //     redirect('/site/create_deal', 'refresh');
                    // } 
                    // else if ($this->data['user_info']['role'] == 9) {
                        redirect('/site/message_consumer');
                    // }else {
                    //     redirect('/site/manage_deal', 'refresh');
                    // }
                }


            }else{


                 

                

                $this->load->library('form_validation');
                $this->form_validation->set_error_delimiters('<span for="required" generated="true" class="help-inline" style="color:red;"> *', '</span>');
                $this->form_validation->set_rules('shop_id', 'Business Name', 'required');
                $this->form_validation->set_rules('deal_title', 'Deal Title', 'required|callback_check_swear');
                $this->form_validation->set_rules('deal_description', 'Deal Description', 'required|callback_check_swear');
                $this->form_validation->set_rules('deal_start', 'Deal Start Date', 'required');

                $this->form_validation->set_rules('deal_repeat[]', 'deal repeat', '');
                $this->form_validation->set_rules('deal_time', 'Deal Time', 'required|callback_dealtimeoverlap');

                $deal_repeat = (array)$this->input->post('deal_repeat');

                for($i=1; $i<=7; $i++){
                
                    $is_find = in_array($i, $deal_repeat);

                    if($is_find){

                        $this->form_validation->set_rules('datepicker_start_'.$i, 'Offer Start Start', 'required');
                        $this->form_validation->set_rules('datepicker_end_'.$i, 'Offer Start End', 'required');
                    }
                }


                if ($this->form_validation->run() == TRUE) {

                    if (!empty($_POST['deal_start']) && !empty($_POST['deal_start'])) {
                    $start_date = DateTime::createFromFormat('m/d/y', $_POST['deal_start'])->format('Y-m-d');

                    $converted_start_date = convert_to_utc($start_date, $this->input->post('deal_time'), $this->input->post('timezone'));
                    $converted_end_date = convert_to_utc($start_date, $this->input->post('deal_end_time'), $this->input->post('timezone'));

                    $shop_id = $_POST['shop_id'];


                    $sql = "SELECT
                            MAX(deal_end_time) AS max_deal_end_time,
                            MAX(deal_time) AS deal_time
                        FROM
                            tbl_deal
                        WHERE
                            `deal_end` = ?
                        AND `shop_id` =?
                        AND `is_active` = 1
                        AND `is_off` = 0";

                    $result = $this->db->query($sql, array($converted_start_date["utc_date"], $shop_id));

                    $max_deal_end_time = $result->row()->max_deal_end_time;
                    $max_deal_start_time = $result->row()->deal_time;

                    $deal_time = $converted_start_date["seconds"];
                    $deal_end_time = $converted_end_date["seconds"];

                    $q3 = "SELECT count(*) as c from tbl_deal where deal_start = ? and ? >= deal_time and ? <=deal_end_time AND `shop_id`=$shop_id AND `is_active` = 1 AND `is_off` = 0";

                    $q4 = "SELECT count(*) as c from tbl_deal where deal_start = ? and ? >= deal_time and ? <=deal_end_time AND `shop_id`=$shop_id AND `is_active` = 1 AND `is_off` = 0";

                    $q3res = $this->db->query($q3, array($converted_start_date["utc_date"], $deal_time, $deal_time));
                    $q4res = $this->db->query($q4, array($converted_start_date["utc_date"], $deal_end_time, $deal_end_time));

                    $row3 = $q3res->row();
                    $row4 = $q4res->row();

                    $this->row3 = $row3->c;
                    $this->row4 = $row4->c;

                }

                    $rep_array = (array)$this->input->post('deal_repeat');
                    $repeat = implode(",", $rep_array);
                    $deal_start_date = $converted_start_date["utc_date"];

                    $tsDealStart = strtotime("$deal_start_date " . $converted_start_date["utc_time"]);
                    $tsDealEnd = strtotime($converted_end_date["utc_date"] . " " . $converted_end_date["utc_time"]);

                    $img = "https://" . $_SERVER['SERVER_NAME'] . "/images/no_image.png";

                    if ($row->shop_image != '') {
                        $img = "https://" . $_SERVER['SERVER_NAME'] . "/uploads/user/" . $row->shop_image;
                    }


                    $inn = array(
                        "shop_id" => $this->input->post('shop_id'),
                        "deal_title" => $this->input->post('deal_title'),
                        "deal_description" => $this->input->post('deal_description'),
                        "original_price" => $this->input->post('original_price'),
                        "offer_price" => $this->input->post('offer_price'),
                        "deal_start" => $deal_start_date,
                        "deal_end" => $converted_end_date["utc_date"],
                        "deal_time" => $deal_time,
                        "deal_end_time" => $deal_end_time,
                        "repeat" => $repeat,
                        "deal_image" => $img,
                        "is_main_deal" => 1,
                        "timezone" => $this->input->post('timezone'),
                        "offer_type" => 2,
                    );


                    if (isset($_POST['deal_image_dup'])) {
                        $inn['deal_image'] = $_POST['deal_image_dup'];
                    }


                    $temp = $this->m_common->insert_entry("tbl_deal", $inn, 1);
                    if ($temp['last_id'] > 0) {

                        $message = "Your Message Was Successfully Created Thank You";
                        if (isset($_FILES['deal_image'])) {
                            $r = $this->file_upload($_FILES['deal_image'], $temp['last_id']);
                            if (!empty($r)) {
                                $up = array(
                                    "deal_image" => "http://" . $_SERVER['SERVER_NAME'] . "/uploads/$r",
                                );
                                $this->m_common->update_entry("tbl_deal", $up, array("id" => $temp['last_id']));

                                $config['image_library'] = 'gd2';
                                $config['source_image'] = '/var/www/html/uploads/' . $r;
                                $config['create_thumb'] = FALSE;
                                $config['maintain_ratio'] = FALSE;
                                $config['width'] = 75;
                                $config['height'] = 75;
                                $config['new_image'] = '/var/www/html/uploads/thumbs/' . $r;

                                $this->load->library('image_lib', $config);

                                $this->image_lib->resize();
                            }
                        }

                        // if (isset($_FILES['barcode_image'])) {
                        //     $r = $this->file_upload($_FILES['barcode_image'], 'barcode');
                        //     if (!empty($r)) {

                        //         $barcode_image = "http://" . $_SERVER['SERVER_NAME'] . "/uploads/$r";
                        //         $up = array(
                        //             "barcode_image" => $barcode_image,
                        //         );
                        //         $this->m_common->update_entry("tbl_deal", $up, array("id" => $temp['last_id']));
                                

                        //         $config['image_library'] = 'gd2';
                        //         $config['source_image'] = '/var/www/html/uploads/' . $r;
                        //         $config['create_thumb'] = FALSE;
                        //         $config['maintain_ratio'] = FALSE;
                        //         $config['width'] = 75;
                        //         $config['height'] = 75;
                        //         $config['new_image'] = '/var/www/html/uploads/thumbs/' . $r;
                        //         $this->load->library('image_lib', $config);
                        //         $this->image_lib->resize();
                        //     }
                        // }                     

                        $this->createRepeatDeals($temp['last_id'], $deal_start_date, $rep_array,$_POST,2);

                        $this->session->set_userdata('current_message', $message);

                        if ($this->data['user_info']['role'] == 6) {
                            redirect('/site/message_consumer', 'refresh');
                        } 
                        else if ($this->data['user_info']['role'] == 9) {
                            redirect('/site/message_consumer/');
                        }else {
                            redirect('/site/message_consumer');
                        }
                    }
                }
            }
        }

        
        if ($this->data['user_info']['role'] == 2) {
            $where = array("add_by" => $this->data['user_info']['user_id']);
        }
        if ($this->data['user_info']['role'] == 3) {
            $where = array("add_by" => $this->data['user_info']['user_id']);
        }
        if ($this->data['user_info']['role'] == 4) {
            $where = array("add_by" => $this->data['user_info']['user_id']);
        }
        if ($this->data['user_info']['role'] == 5) {
            $where = array("add_by" => $this->data['user_info']['user_id']);
        }
        if ($this->data['user_info']['role'] == 6) {
            $where = array("shop_id" => $this->data['user_info']['user_id']);
        }
       

        // if($this->data['user_info']['is_corporate_business_user'] == 1 && $this->data['user_info']['role'] == 6){
        if($this->data['user_info']['role'] == 9 || $this->data['user_info']['role'] == 6){

            $this->db->select('t2.shop_id,t2.shop_name,t2.shop_image');
            $this->db->from('tbl_users_shops t1');
            $this->db->join('tbl_shop t2','t2.shop_id = t1.shop_id');
            $this->db->where('t1.user_id',$this->data['user_info']['user_id']);
            $shops = $this->db->get()->result_array();
        }
        else{
            $shops = $this->m_common->db_select("shop_id,shop_name,shop_image", "tbl_shop", $where, array(), 'shop_name', '', '', 'all');
        }


        if($this->data['user_info']['role'] == 9){
            $this->data['corporate_business_list'] = $this->get_corporate_business_v2($this->session->userdata('user_id'));
        }


        if (isset($_GET["id"])) {
            $deal["shop_id"] = $_GET["id"];
        }


        $this->data['deal'] = $deal;

        $this->data['shops'] = $shops;

        $this->data['message'] = $message;
        $this->load->view('header', $this->data);
        $this->load->view('sidebar', $this->data);
        $this->load->view('message_consumer', $this->data);

    }






     public function payment() {

        // $this->session->sess_destroy();
        // exit();
        $this->load->model('business');

        $payment_shop_id = isset( $_GET['shop_id'] ) ?  $_GET['shop_id'] : 0;

        if( $payment_shop_id == 0){
            echo 'invalid request';
            exit();
        }


        $business_info = $this->business->getBusiness($payment_shop_id); 
        if( empty($business_info)){
            echo 'invalid request';
            exit(); 
        }

       
        if (null !==$this->session->flashdata('message') && $this->session->flashdata('message') !=''){
            echo $this->session->flashdata('message'); 
            exit();
        } 


        $blnPaidActivation = $business_info->blnPaidActivationFee;
        $blnexpired = $business_info->blnexpired;
        $is_payment = $business_info->is_payment;

        if( $blnexpired == 0 && $is_payment == 1){
            echo 'Already paid';
            exit(); 
        }
        


        $day = intval(date('j'));
        $dateraw = strtotime(date('m', strtotime('+1 month')).'/01/'.date('Y').' 00:00:00');
        $date = date("m/d/Y", $dateraw);
            
        $next_due_date = date('Y-m-d', strtotime("+30 days"));


        $membership_activation_fee=199.00;
        $amount = 199.00;

        $monthly_network_fee = $promocode_activation = $promocode_monthly = $promocode_activation_id = $promocode_monthly_id = 0;
        $error_msg = $success_msg = '';

        if (isset($_POST["hiddenpromocode1"]) && trim($_POST["hiddenpromocode1"])!='') {

            $promocode_activation = trim($_POST["hiddenpromocode1"]);
        }

      

        $temp_error = "";

        if ($_SERVER['REQUEST_METHOD']=='POST') 
        {

            $this->load->library(array('form_validation'));

            $this->form_validation->set_rules('payment_method', 'Method of Payment', 'required');

            $payment_method = $this->input->post('payment_method');

            $this->form_validation->set_rules('cardname', 'Card Name', 'trim');
            $this->form_validation->set_rules('cardNumber', 'Card Number', 'required');
            $this->form_validation->set_rules('expiry_month', 'Expiration Month', 'required');
            $this->form_validation->set_rules('expiry_year', 'Expiration Year', 'required');
            $this->form_validation->set_rules('cardCode', 'Security Code', 'required');
            $this->form_validation->set_rules('t_and_c', 'Payment Terms and Conditions', 'required');
            $this->form_validation->set_rules('packageradio', 'Package', 'required');
            $this->form_validation->set_rules('billingZip', 'billingZip', 'trim');
            $hasErrors = FALSE;

            //Form Validation
            $this->form_validation->set_rules('hiddenpromocode1', 'hiddenpromocode1', 'trim');

            

            $this->form_validation->set_error_delimiters('<div class="error">', '</div>');

            if ($this->form_validation->run() === FALSE) {
                 $hasErrors = TRUE;
            }

            //activation fee
            //echo "135 ($hasErrors )($promocode_activation)<br>";
            if ($promocode_activation!='0') {

                //echo "Entering Activation Area...<br>";

                //process activation promocode
                $data = $this->promocode->checkpromocode($promocode_activation, 'activation fee');
                $qry_promo_activation = $data;

                if (is_array($data["result"]) and count($data["result"]) > 0 ) {

                    //do nothing for now.
                    $amount = $data["total_due_today"];

                    $membership_activation_fee=$data["membership_activation_fee"];

                    $promocode_activation_id = $data["result"][0]->id;

                } else {

                    $hasErrors= TRUE;

                }

            }



            //THE INITIAL CREDIT CARD CHARGE
            if ($membership_activation_fee ==0 && $monthly_network_fee ==0){

                $d = array('expiration_date'=>$next_due_date,'is_payment'=>1, 'subscriptionId'=>'free','promocode_activation_id'=>$promocode_activation_id, 'promocode_monthly_id'=>$promocode_monthly_id, 'blnPaidActivationFee'=>1);
                $this->db->where('shop_id', $payment_shop_id);
                $this->db->update('tbl_shop', $d); 
            }


            
            //check already customer
            require(APPPATH.'libraries/stripe/init.php');
            \Stripe\Stripe::setApiKey('sk_test_DaNW3aIALBKXT0Jb946QxrAm');

            $customer_id = $business_info->customer_id;
           
            if( $customer_id == '' )
            {
                try {
                    $result = \Stripe\Customer::create(array(
                      "description" => $business_info->shop_name." (". $business_info->email.")",
                       "metadata" => array(
                                        "email" =>$business_info->email,
                                        "shop_id" =>$business_info->shop_id,
                                        "shop_name" =>$business_info->shop_name,
                                    )
                    ));
                    
                    $customer_id = $result['id'];
                    $d = array('customer_id'=>$customer_id);
                    $this->db->where('shop_id', $payment_shop_id);
                    $this->db->update('tbl_shop', $d); 

                } catch(\Stripe\Error\Customer $e) {
                   $hasErrors= TRUE;
                   $error_msg = "Please Try Later";
                }
            }





            if ($hasErrors == FALSE && $membership_activation_fee > 0) {


                try {
                    $customer = \Stripe\Customer::retrieve($customer_id);
                    $customer->sources->create(
                        array(
                                "card" => array(
                                "name" =>$this->input->post('card_name'),
                                "number" => $this->input->post('cardNumber'),
                                "exp_month" => $this->input->post('expiry_month'),
                                "exp_year" => $this->input->post('expiry_year'),
                                "cvc" => $this->input->post('cardCode')
                                )));


                    try 
                    {
                        $charge = \Stripe\Charge::create(
                            array(
                                    'amount' => (number_format($membership_activation_fee, 2, '.', '')*100), 
                                    'currency' => 'usd',
                                    'customer' => $customer_id
                            ));
                        if($charge['status']=='succeeded')
                        {
                            $charge_id=$charge['id'];
                            $balance_transaction_id=$charge['balance_transaction'];
                          

                            $info=array(
                                'TransactionShopId'=>$payment_shop_id,
                                'TransactionChargeId'=>$charge_id,
                                'TransactionBalanceTransaction'=>$balance_transaction_id,
                                );
                            $this->db->insert('transaction_history', $info);
                            $plan_type = $this->input->post('packageradio');
                            
                            $d = array('plan_type'=>$plan_type,'is_payment'=>1,'subscriptionId'=>'free','activationfeeid'=>$charge_id,'monthlyfee'=>$monthly_network_fee, 'blnPaidActivationFee'=>1,'promocode_activation_id'=>$promocode_activation_id, 'promocode_monthly_id'=>$promocode_monthly_id,'expiration_date'=>$next_due_date,);
                            $this->db->where('shop_id', $payment_shop_id);
                            $this->db->update('tbl_shop', $d);
                            
                            $this->session->set_flashdata('message', 'successfully Payment');
                            redirect('/site/payment?shop_id='.$payment_shop_id);
                        }   
                        else
                        {
                            $hasErrors= TRUE;
                            $error_msg = "Invalid Card Detail";
                        }

                    } catch(\Stripe\Error\Card $e){

                        $hasErrors= TRUE;
                        $error_msg = "Invalid Card Detail";
                    }


                } catch(\Stripe\Error\Card $e) {
                        $hasErrors= TRUE;
                        $error_msg = "Invalid Card Detail";
                }

            }

        }



        if ($this->input->get_post('new')) {
            $new = 1;
            $paymentBlurb = "You are almost done.  Just add a payment option before and you are all set.";
        } else {
            $new = 0;
            $paymentBlurb = "You need to have payment information on file before you can access your dashboard.";
        }



        $data = array(
                'day'=>$day,
                'date'=>$date,
                'error_msg' =>$error_msg,
                'success_msg' =>$success_msg,
                'new'=>$new,
                'paymentBlurb'=>$paymentBlurb,
                'error'=>$temp_error,
                'monthly_network_fee'=>$monthly_network_fee,
                'membership_activation_fee'=>$membership_activation_fee,
                'amount'=>$amount,
                'shop_id' => $payment_shop_id,
            );

         $this->load->view('payment', $data);
    }

 




    function get_webhook(){
        
        require(APPPATH.'libraries/stripe/init.php');
        \Stripe\Stripe::setApiKey('sk_test_DaNW3aIALBKXT0Jb946QxrAm');

        // Set your secret key: remember to change this to your live secret key in production
        // See your keys here: https://dashboard.stripe.com/account/apikeys
        \Stripe\Stripe::setApiKey("sk_test_DaNW3aIALBKXT0Jb946QxrAm");

        // Retrieve the request's body and parse it as JSON
        $input = @file_get_contents("php://input");
        $event_json = json_decode($input);

        // Do something with $event_json

        http_response_code(200); // PHP 5.4 or greater

    }

    

} ?>