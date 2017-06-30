<?php

/**
 * Whistler
 *
 *   This file is part of Whistler
 *
 * @package    whistler 
 * @subpackage SNMP RestAPI interface
 * @author     York Chen <york.chen@istusary.com>
 * @copyright  (C) 2017-2017 Whistler, (C) 2017-2017 Etherton Technologies
 *
 *-------------------------------------------------------------------------------------------------
 * Endpoint              Description	      Options
 *-------------------------------------------------------------------------------------------------
 * /alerts/              Fetch Alerts         device_id, status (failed/failed_suppressed/delayed) 
 * /alerts/<alert_id>	                        ,entity_type, entity_id, alert_test_id 
 *                                                
 * /alert_checks/        Fetch Alert Checks	
 *   
 * /devices/             Fetch Devices        group sysname location, os, version, features, type, 
 * /devices/<device_id>                         status, ignore, disabled, graph 
 * /devices/<hostname>		
 *   
 * /ports/               Fetch Ports          location, device_id, group, disable, deleted, ignore,
 * /ports/<port_id>                              ifSpeed, ifType, hostname, ifAlias, ifDescr, 
 *                                               port_descr_type, errors (yes), alerted (yes), state 
 *                                               (down, up, admindown, shutdown), cbqos, 
 *                                               mac_accounting
 *   
 * /sensors/	         Fetch Sensors        metric, group, group_id, device_id, entity_id, 
 *                                               entity_type, sensor_descr sensor_type id event 
 *                                               (ok, alert, warn, ignore)
 *   
 * /status/	         Fetch Status	      group_id device_id id class event (ok, alert, warn,
 *                                               ignore)
 *
 *-------------------------------------------------------------------------------------------------
 *
 */

header('Access-Control-Allow-Origin: *'); 
include_once("../includes/sql-config.inc.php");
include_once($config['html_dir'] . "/includes/functions.inc.php");
include_once($config['html_dir'] . "/includes/authenticate.inc.php");
include_once("../includes/restapi/sw-backend-restapis-auth.inc.php");


$method=$_SERVER['REQUEST_METHOD'];
$contentType = isset($_SERVER["CONTENT_TYPE"]) ? trim($_SERVER["CONTENT_TYPE"]) : '';

$PHP_AUTH_USER = $_SERVER["PHP_AUTH_USER"];
$PHP_AUTH_PW = $_SERVER["PHP_AUTH_PW"];
$PATH_INFO = $_SERVER["PATH_INFO"];
$parameters = $_GET;


$url =  $_SERVER["PATH_INFO"];//$_SERVER['REQUEST_URI'];
$query_strings = $_SERVER['QUERY_STRING'];
$url = trim( $url, "/" );
$urls = explode( '/', $url);

$authentication = new WhistlerAuthentication($config);

$username = get_parameter("username");
$password = get_parameter("password");
$refresh_token = get_parameter("refresh_token");
$access_token = get_parameter("access_token");



//
// 1. Check Endpoint format
//
if (count($urls) < 3) {

  $authtype = $authentication->api_auth_entry($url);

  if ($authtype == "auth" ) {
    //echo "username=" . $username . "; password=" . $password . "<br/>\n";
    
    if ( $username == "" || $password=="" ) {
      print '{"status":"error","error":"access_denied","error_message":"You do not have access to take this action on behalf of this user"}';
      exit();
    }

    $refresh_token = $authentication->GetRefreshToken($username,$password);
    if ($refresh_token == "" ) {
      print '{"status":"error","error":"access_denied","error_message":"You do not have access to take this action on behalf of this user"}';
    }
    else {
      print '{"refresh_token":"' . $refresh_token . '","token_type":"Bearer","scope":"/api/v0/","status":"ok"}';
    }
    
    exit();
  }

  if ($authtype == "token" ) {
    $access_token = $authentication->GetAccessToken($username,$refresh_token);
    
    if ( $access_token == "" ) {
      print '{"status":"error","error":"access_denied","error_message":"Refresh Token is invalid."}';
      exit();
    }

    print '{"access_token":"' . $access_token . '","token_type":"Bearer","scope":"/api/v0/","status":"ok"}';
    
    exit();
  }

  $result = createJsonResult( "endpoint_error" );
  print( $result . "\n");
  exit();
}


//
// 2. Auth Check
//
$access_token = get_parameter("access_token"); 
//print "access_token=" . $access_token ;
$access_check_code = $authentication->isValidAccessToken($access_token);
if ( $access_check_code != 0 ) {
	if ($access_check_code == 1)
		print '{"status":"error","error":"no_access_token","error_message":"No access token"}';
        if ($access_check_code == 2)
                print '{"status":"error","error":"invalid_access_token","error_message":"Invalid access token"}';
        if ($access_check_code == 3)
                print '{"status":"error","error":"expired_access_token","error_message":"Expired access token"}';

	exit();
}


//
// 3. Find Entity data 
//

$version = $urls[1];
$entity = $urls[2];
$entity_id = "";
$hostname = "";

$entities = array( 'alerts', 'alert_checks', 'devices', 'ports', 'sensors', 'status' );

if ( ! in_array( $entity, $entities ) ) {
   $result = createJsonResult( "endpoint_error" );
   print( $result . "\n" );
   exit();
}


//
// 3.1. Create Query
//
$where = " 1=1 ";
if ( count( $urls ) > 3 ) {
  if ( is_numeric( $urls[3] )) {
    $entity_id = $urls[3];
  }
  else {
    $entity_name = $urls[3];
  }
}


//
// 3.2 Query for devices
//
if ( $entity == "devices" ) {
  if ( $entity_id != "" ) {
     $where .=" and device_id=" . $entity_id;
  }
  if ( $entity_name != "" ) {
     $where .=" and hostname like '%" . $entity_name . "%'";
  } 
  $devices_columns = array('group', 'sysname', 'location', 'os', 'version', 'features', 'type', 'status', 'ignore', 'disabled', 'graph');
  
  foreach ($parameters as $key => $value)  {
     if ( in_array($key, $devices_columns ) ) {
       $where .=" and " . $key . " like '%" . $value . "%'";   
     }
  }
}

debuglog( "select * from " . $entity . $where );


//
// 4. GET method result output
//
if ( $method == "GET" ) {
  $data =  dbFetchRows("select * from " . $entity . " where " .  $where);
  $result=array();
  $result["status"]="ok";
  $result["data"]=$data;
  print(json_encode($result));
  exit();
}


$result = createJsonResult( "" );
print( $result . "\n" );
exit();


function formatstr( $head ) {
  $ret = trim( $head );
  $cnt = 1;
  while ( $cnt > 0) {
    $ret = str_replace("  "," ",$ret, $cnt);
  }
  return $ret;
}
 
  /**
   * Generate a JSON result string
   *
   * @param string result key
    * @return string JSON result string
   */ 
function createJsonResult ( $key ) {
  if ( strtolower($key) == "endpoint_error") {
    return "{\"status\":\"error\",\"message\":\"Endpoint not found.\"}";
  }

  if ( strtolower($key) == "authentication_error") {
    return "{\"status\":\"error\",\"message\":\"Not Authorized.\"}";
  }
  
  return "{\"status\":\"error\",\"message\":\"Under Construction.\"}";
  
}

  /**
   * Get variable value from headers/POST body/GET parameters
   *
   * @param string variable name
    * @return string variable value
   */    
function get_parameter($name) {
  $headers = apache_request_headers();
  if (isset($headers[trim($name)]) && $headers[trim($name)] != "" )
        return $headers[trim($name)];

  if (isset($_POST[trim($name)]) && $_POST[trim($name)] != "" )
        return $_POST[trim($name)];

  if (isset($_GET[trim($name)]) && $_GET[trim($name)] != "" )
        return $_GET[trim($name)];

  return "";
}


  /**
   * Debug method
   *
   * @param string debug message 
    * @return void 
   */ 
function debuglog($msg) {
   $output = print_r($msg, true);
   //$myfile = fopen("/tmp/sw-frontend-rest-api.log",  "a+");
   //fwrite($myfile, "\n". (string)$msg);
   //fclose($myfile);
}

// EOF



