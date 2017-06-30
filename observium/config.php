<?php

## Check http://www.observium.org/docs/config_options/ for documentation of possible settings

// Database config ---  This MUST be configured
$config['db_extension'] = 'mysqli';
$config['db_host']      = 'localhost';
$config['db_user']      = 'obsuser';
$config['db_pass']      = 'password';
$config['db_name']      = 'observium';

// Base directory
#$config['install_dir'] = "/opt/observium";

// Default community list to use when adding/discovering
$config['snmp']['community'] = array("public");

// Authentication Model
$config['auth_mechanism'] = "mysql";    // default, other options: ldap, http-auth, please see documentation for config help

// Enable alerter
// $config['poller-wrapper']['alerter'] = TRUE;

//$config['web_show_disabled'] = FALSE;    // Show or not disabled devices on major pages.

// Set up a default alerter (email to a single address)
//$config['email']['default']        = "user@your-domain";
//$config['email']['from']           = "Observium <observium@your-domain>";
//$config['email']['default_only']   = TRUE;

$config['location_map']['Markham'] = "75 Tiverton Court, Markham, ON L3R 4M8";
$config['location_map']['Vancouver'] = "1125 Howe St. Vancouver，BC, Canada V6Z 2K8";
$config['location_map']['Waterloo'] = "630 Weber St N, Waterloo, ON N2V 2N2";
$config['location_map']['Ottawa'] = "1000 Innovation Drive, Kanata, ON, K2K 3E7";

$config['geocoding']['enable'] = TRUE;                      // Enable Geocoding
$config['geocoding']['api'] = 'google';
$config['geocoding']['default']['lat'] =  "43.8547780";         // Default latitude
$config['geocoding']['default']['lon'] =  "-79.9425528";        // Default longitude
$config['location_menu_geocoded']      = TRUE;                     // Build location menu with geocoded data.

//RestfulAPIs' token life time
$config['refresh_token_expire_time'] = 36000;   //Refresh Token life time is 10 hours
$config['access_token_expire_time'] = 30;   //Access Token life time is 30 seconds

// End config.php
