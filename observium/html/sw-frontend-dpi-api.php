<?php

/**
 * Observium
 *
 *   This file is part of Observium.
 *
 * @package    observium
 * @subpackage RestAPI webinterface
 * @author     York Chen <york.chen@istusary.com>
 * @copyright  (C) 2017-2017 Whistler, (C) 2017-2017 Etherton Technologies
 *
 *
 *   GET By ID
 *        http://device-IP:80/sw-frontend-dpi-api.php?id=<id>
 *
 *   GET All
 *        http://device-IP:80/sw-frontend-dpi-api.php
 *
 *
 *   CREATE(POST)
 *       
 *        POST -H ‘Content-Type: application/json’ –header ‘Accept: application/json’ -d ‘{<Model Object as json-Data>}’ http://device-IP:80/sw-frontend-dpi-api.php
 *
 *   DELETE By ID
 *        DELETE -i -H ‘Accept:application/json’ -d ‘{<Model Object as json data>}’ http://device-IP:80/sw-frontend-dpi-api.php
 *
 *   UPDATE(PATCH) By ID
 *        PATCH -H ‘Content-Type: application/json’ -d ‘{<Model Object as json data>}’  http://device-IP:80/sw-frontend-dpi-api.php
 *
 *
 */

header('Access-Control-Allow-Origin: *'); 

include_once("../includes/sql-config.inc.php");
include($config['html_dir'] . "/includes/functions.inc.php");
include($config['html_dir'] . "/includes/authenticate.inc.php");


$method=$_SERVER['REQUEST_METHOD'];
//print($_SERVER['REQUEST_METHOD']. "<br/>\n");
$contentType = isset($_SERVER["CONTENT_TYPE"]) ? trim($_SERVER["CONTENT_TYPE"]) : '';
//print($contentType. "\n");

if (!$_SESSION['authenticated']){
  print("{\"result\":\"0\",\"err\":\"Not Authorized to call this API.\"}");
  exit;
}


$vars = get_vars();
//print_r($vars);
//print_r($contentType);


//print_r($data);

$table=$vars["table"];
$action=$vars["action"];

//
// 1. Check API KEY
//
$api_key=$vars["api_key"];
$token=$vars["token"];
$user_id=$vars["user_id"];
//echo "<br/>api_key=" . $api_key . ";token=" . $token . ";user_id=" . $user_id . "<br/>";

if ( $api_key == null) {
  //echo("unauthenticated");
  //exit;
}

$api_auth= dbFetchRow("SELECT * FROM api_auth where user='" . $user_id . "'");
if ( $api_key != $api_auth["api_key"] ) {
  //echo "unauthenticated api key";
  //exit;
}


//
// 2. Add New Rule
//
if ($method =="POST" &&  $action == "newrule") {
  if(strcasecmp($contentType, 'application/json') == 0 ) {
     $data = json_decode(file_get_contents('php://input'), true);

     mylog("newrule");
     mylog($data["rule"]); 
     $category= $data["category"];
     $rule=trim($data["rule"]);
     mylog("rule=" . $rule);
     $head=trim(getHead($rule));
     mylog("head=" . $head);
     $options=trim(getOptions($rule));
     mylog("options=" .$options);
     $file=trim($data["file"]);
     mylog("file=" . $file);
     mylog("category=" . $category); 
    
 
     $result=addRule($head,$options, $file);
     if ( $result == "OK" ) {
        print("{\"result\":\"1\",\"err\":\"\"}" . "\n") ;
     }else {
        print("{\"result\":\"0\",\"err\":\"" . $result . "\"}" . "\n");
     }

     exit;
  }
/*   
  print("head=" . $head . "\n");
  print("options=" . $options. "\n");
  print("category=" .  $category . "\n");
  print("rule=" . $rule. "\n");
  print("file=" . $file. "\n");
*/  
  print("{\"result\":\"0\",\"err\":\"No Data\"}");
  exit;
}


//
// 3. Updaate Rule
//
if ($method =="POST" &&  $action == "updaterule") {
  if(strcasecmp($contentType, 'application/json') == 0 ) {
     $data = json_decode(file_get_contents('php://input'), true);
     $category= $data["category"];
     $rule=$data["rule"];
     $head=getHead($rule);
     $options=getOptions($rule);
     $file=$data["file"];
     if ( $category != "" &&  $category != null) {
         $file=$category;
     }

     $result=updateRule($head,$options,$file);
     if ( $result == "OK"  ) {
        print("{\"result\":\"1\",\"err\":\"\"}" . "\n") ;
     }else {
        print("{\"result\":\"0\",\"err\":\"" . $result . "\"}" . "\n");
     }
     exit;
  }
  print("{\"result\":\"0\",\"err\":\"No Data\"}");
  exit;
}



//
// 4. Query API
//
$id=$vars["id"];
$sid=$vars["sid"];
$where=" 1=1 ";
if ($id!=null && $id !="" ) {
  $where = $where . " and id=" . $id ;
}
if ($sid!=null && $sid !="" ) {
  $where = $where . " and sid=" . $sid ;
}

// 4.1. Category/Rule File Query
if ( $action == "category" || $action == "file" ) {
   $dpi_rules = dbFetchRows("select file  as 'id',file as 'title' from dpi_rules group by file");
   print(json_encode($dpi_rules));
   exit;
} 

// 4.2. Query next sid
if ( $action == "nextsid" || $action == "nextsid" ) {
   $dpi_rules = dbFetchRows("select lpad(max(sid)+1,8,0) as 'sid' from dpi_rules ");
   print(json_encode($dpi_rules));
   exit;
}

// 4.3. Query all Protocol
if ( $action == "protocol" ) {
   $dpi_rules = dbFetchRows("select protocol  as 'id', protocol as 'title' from dpi_rules group by protocol");
   print(json_encode($dpi_rules));
   exit;
}


// 4.4. Query all Actions
if ( $action == "action" ) {
   $dpi_rules = dbFetchRows("  select action  as 'id',  action  as 'title' from dpi_rules group by action");
   print(json_encode($dpi_rules));
   exit;
}


// 4.5. Query all Rules
if ( $method == "GET" ) {
   $dpi_rules = dbFetchRows("SELECT * FROM `dpi_rules` WHERE " . $where . " and disabled =0 order by id");
   print(json_encode($dpi_rules));
   exit;
}


//
// 5. Delete API
//
echo "method=$method;action=$action;contentType=" . $contentType . "\n";
if ( $method == "POST"  &&  $action == "delete" 
   && strcasecmp($contentType, 'application/json') == 0 
   ) {
     //echo "aaa";
    
     $data = json_decode(file_get_contents('php://input'), true);
     //echo $data;
     print_r($data);

    $id=$data["id"];
    $sid=$data["sid"];
    $token=$data["token"];
    $api_key=$data["api_key"];

    //echo "id=$id, token=$token, api_key=$api_key\n";

    if ( $token != null &&  $token != "" 
        && $api_key !=null && $api_key !="" 
        && $id > 0 ) {
         $result = deleteRule($id);
         echo "result=$result\n";

         if ( $result == "OK"  ) {
            print("{\"result\":\"1\",\"err\":\"\"}" . "\n") ;
            exit;
         }
    }
        
    print("{\"result\":\"0\",\"err\":\"" . $result . "\"}" . "\n");
    exit;
}

$time = time();


function formatOptions( $options_ ) {
  $ret = "";
  $ary = optionsTokens( $options_ );
  $idx = 0;

  foreach ($ary as $option) {
    $idx++;
    $option = trim( $option );

    if ($ret == "" ) {
      $ret = $option;
    }else{
      $ret = $ret . "; " . $option;
    }
  }

  return $ret;
}


function formatstr( $head ) {
  $ret = trim( $head );
  $cnt = 1;
  while ( $cnt > 0) {
    $ret = str_replace("  "," ",$ret, $cnt);
  }
  return $ret;
}


function getHead( $rule ) {
  $pos = strpos( $rule, "(");
  if ( $pos === false ){
     return "";
  }
  
  $ret=trim(substr($rule, 0,$pos));
  return $ret;
}


function getOptions( $rule ) {
  $pos = strpos( $rule, "(");
  if ( $pos === false ){
     return "";
  }

  $end=strrpos($rule, ")");
  if ( $end === false ){
     return "";
  }

  return trim(substr($rule, $pos + 1,$end - $pos - 1));
}


function optionsTokens($options) {
    $array = preg_split('/;/',$options);
    return  $array;
}


function getValue($options, $key) {
    $tokens = optionsTokens($options);

    foreach ( $tokens as $option ) {
        $pair = preg_split('/:/', $option) ;

        if ( sizeof( $pair) == 2) {
            if ( strtoupper(trim($pair[0])) == strtoupper(trim($key)) ) {
                return  trim($pair[1]);
            }
        }

        if ( sizeof( $pair) ==1) {
            if ( strtoupper($pair[0] ) == strtoupper ( $key) ) {
                return trim($pair[0]);
            }
        }
    }

    return "";
}


function addRule( $head_, $options_, $file_ ) {
  mylog("addRule1");
  
   mylog($head_);
   $head = formatstr(trim($head_));
   $options=formatOptions($options_);
   $file=trim($file_);
    mylog("head=" . $head);
    mylog("options=" . $options);
    mylog("file=" . $file);
    mylog("addrule4");
    //$head = formatstr(trim($head));
    $heads = preg_split('/\s/',$head);
    if ( sizeof($heads) != 7 ) {
       return "Rule is not in a correct format.";
    }    

    if ( $file == null || $file == "" ) {
       return "Rule Category doesn't exist";
    }

    
    $plainrule=$head . " (" . formatOptions($options) . ")"; 
    mylog("plainrule-" . $plainrule);

    $sid = dbFetchCell("select max(sid) +1  as 'sid'  from dpi_rules ");
    mylog("sid=" . $sid);
    $rev = 1;
    //print ("sid=$sid\n". "\n");
    //$sid=5555;

    $result = dbInsert(array('file' => $file, 
                         'action' => $heads[0],
                         'protocol' => $heads[1],
                         'source_ip' => $heads[2],
                         'source_port' => $heads[3],
                         'direction' => $heads[4],
                         'destination_ip' => $heads[5],
                         'destination_port' => $heads[6],
                         'options' => $plainrule,
                         'sid' => $sid, 
                         'rev' => 1 
                         ), 'dpi_rules');


    mylog("dbInsert result:");
    mylog($result);
    $ary = optionsTokens($options);
    $idx = 0;

    $rows_deleted = dbDelete('dpi_rule_options', '`sid` = ?', array($sid));

    foreach ($ary as $option) {
       $idx++;
       $pair = preg_split('/:/', $option) ;
       if ( sizeof( $pair) == 2) { 
         $key = trim($pair[0]);
         $value = trim($pair[1]);
          
         $wk_result = dbInsert(array('idx' => $idx, 
                        'sid' => $sid,
			'keyword' => $key,
                        'value' => $value),'dpi_rule_options');
       } else {
        
         $wk_result = dbInsert(array('idx' => $idx,
                        'sid' => $sid,
                        'keyword' => '',
                         'value' => $option ), 'dpi_rule_options');
        
       }       
    }

    return "OK";//$result;
}

function deleteRule( $id ) {
    $dpi_rules = dbFetchRows("SELECT * FROM `dpi_rules` WHERE id=" . $id );
    if (sizeof( $dpi_rules) ==0) {
      return "NO DATA";
    }
    print_r($dpi_rules);

    $dpi_rules[0]['disabled']=1;
    $rows_updated = dbUpdate($dpi_rules[0], 'dpi_rules', '`id` = ?',array($id));
    print "rows_updated=$rows_updated\n";
    if ( $rows_updated > 0 ) {
      return "OK";
    }
   
    return "ERROR";
}

function updateRule( $head, $options,$file ) {
    $head = formatstr(trim($head));
    $heads = preg_split('/\s/',$head);
    if ( sizeof($heads) != 7 ) {
       return "ERROR";
    }


    if ( $file == null || $file == "" ) {
       return "Rule Category doesn't exist";
    }

    $rows_updated = dbUpdate(array('file' => $file,
                         'action' => $heads[0],
                         'protocol' => $heads[1],
                         'source_ip' => $heads[2],
                         'source_port' => $heads[3],
                         'direction' => $heads[4],
                         'destination_ip' => $heads[5],
                         'destination_port' => $heads[6],
                         'options' => $options,
                         //'sid' => getValue($options, "sid"),
                         'rev' => getValue($options, "rev")
                         ), 'dpi_rules','`sid` = ?',array(getValue($options, "sid")));


    $rows_deleted = dbDelete('dpi_rule_options', '`sid` = ?', array($sid));

    $ary = optionsTokens($options);
    $idx = 0;
    foreach ($ary as $option) {
       $idx++;
       $pair = preg_split('/:/', $option) ;
       if ( sizeof( $pair) == 2) {
         $key = trim($pair[0]);
         $value = trim($pair[1]);

         $wk_result = dbInsert(array('idx' => $idx,
                        'sid' => $sid,
                        'keyword' => $key,
                        'value' => $value),'dpi_rule_options');
       } else {

         $wk_result = dbInsert(array('idx' => $idx,
                        'sid' => $sid,
                        'keyword' => '',
                         'value' => $option ), 'dpi_rule_options');

       }
    }


    if ($rows_updated == $file ) return "OK";
    return "OK";//$rows_updated;
}

function mylog($msg) {
   //$myfile = fopen("/tmp/whistler.txt", "a+");
   //fwrite($myfile, "\n". (string)$msg);
   //fclose($myfile);
}

// EOF




