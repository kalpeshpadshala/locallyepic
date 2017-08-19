<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');



switch ($_SERVER["SERVER_NAME"]) {

  case "db.locallyepic.com":
  	$config['arb_api_url'] = 'https://api.authorize.net/xml/v1/request.api'; // PRODUCTION URL
	$config['api_url'] = 'https://api.authorize.net/xml/v1/request.api'; // PRODUCTION URL
	$config['api_login_id'] = '4fwQsG72cT';
	$config['api_transaction_key'] = '4E4292h7G9yX7s6w';

  break;

  case "dev.dealsonthegogo.com":
	$config['arb_api_url'] = 'https://apitest.authorize.net/xml/v1/request.api';
	$config['api_url'] = 'https://apitest.authorize.net/xml/v1/request.api';
	$config['api_login_id'] = '9ExV722zJ';
	$config['api_transaction_key'] = '8ZAkJjm3e25C68t7';
  break;

  case "test.dealsonthegogo.com":
    $config['arb_api_url'] = 'https://apitest.authorize.net/xml/v1/request.api';
	  $config['api_url'] = 'https://apitest.authorize.net/xml/v1/request.api';
    $config['api_login_id'] = '9ExV722zJ';
	  $config['api_transaction_key'] = '8ZAkJjm3e25C68t7';

  break;

  case "randy.dealsonthegogo.com":
  	$config['arb_api_url'] = 'https://apitest.authorize.net/xml/v1/request.api';
	  $config['api_url'] = 'https://apitest.authorize.net/xml/v1/request.api';
  	$config['api_login_id'] = '9ExV722zJ';
	  $config['api_transaction_key'] = '8ZAkJjm3e25C68t7';

  break;

  case "192.168.1.20":
    $config['arb_api_url'] = 'https://apitest.authorize.net/xml/v1/request.api';
    $config['api_url'] = 'https://apitest.authorize.net/xml/v1/request.api';
    $config['api_login_id'] = '9ExV722zJ';
    $config['api_transaction_key'] = '8ZAkJjm3e25C68t7';

  break;
  case "localhost":
    $config['arb_api_url'] = 'https://apitest.authorize.net/xml/v1/request.api';
    $config['api_url'] = 'https://apitest.authorize.net/xml/v1/request.api';
    $config['api_login_id'] = '9ExV722zJ';
    $config['api_transaction_key'] = '8ZAkJjm3e25C68t7';

  break;
}





/* EOF */


