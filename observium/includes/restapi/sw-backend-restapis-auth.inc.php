<?php

/**
 * Whistler
 *
 *   This file is part of Whistler.
 *
 * @package    whistler
 * @subpackage Restfull 
 * @copyright  (C) 2017-2017 Whistler, (C) 2017-2017 Etherton Technologies Inc.
 *
 */

include_once("../sql-config.inc.php");

class RemoteAddress
{
    /**
     * Whether to use proxy addresses or not.
     *
     * As default this setting is disabled - IP address is mostly needed to increase
     * security. HTTP_* are not reliable since can easily be spoofed. It can be enabled
     * just for more flexibility, but if user uses proxy to connect to trusted services
     * it's his/her own risk, only reliable field for IP address is $_SERVER['REMOTE_ADDR'].
     *
     * @var bool
     */
    protected $useProxy = false;

    /**
     * List of trusted proxy IP addresses
     *
     * @var array
     */
    protected $trustedProxies = array();

    /**
     * HTTP header to introspect for proxies
     *
     * @var string
     */
    protected $proxyHeader = 'HTTP_X_FORWARDED_FOR';

    // [...]

    /**
     * Returns client IP address.
     *
     * @return string IP address.
     */
    public function getIpAddress()
    {
        $ip = $this->getIpAddressFromProxy();
        if ($ip) {
            return $ip;
        }

        // direct IP address
        if (isset($_SERVER['REMOTE_ADDR'])) {
            return $_SERVER['REMOTE_ADDR'];
        }

        return '';
    }

    /**
     * Attempt to get the IP address for a proxied client
     *
     * @see http://tools.ietf.org/html/draft-ietf-appsawg-http-forwarded-10#section-5.2
     * @return false|string
     */
    protected function getIpAddressFromProxy()
    {
        if (!$this->useProxy
            || (isset($_SERVER['REMOTE_ADDR']) && !in_array($_SERVER['REMOTE_ADDR'], $this->trustedProxies))
        ) {
            return false;
        }

        $header = $this->proxyHeader;
        if (!isset($_SERVER[$header]) || empty($_SERVER[$header])) {
            return false;
        }

        // Extract IPs
        $ips = explode(',', $_SERVER[$header]);
        // trim, so we can compare against trusted proxies properly
        $ips = array_map('trim', $ips);
        // remove trusted proxy IPs
        $ips = array_diff($ips, $this->trustedProxies);

        // Any left?
        if (empty($ips)) {
            return false;
        }

        // Since we've removed any known, trusted proxy servers, the right-most
        // address represents the first IP we do not know about -- i.e., we do
        // not know if it is a proxy server, or a client. As such, we treat it
        // as the originating IP.
        // @see http://en.wikipedia.org/wiki/X-Forwarded-For
        $ip = array_pop($ips);
        return $ip;
    }

}



class WhisOAuth  extends RemoteAddress { 
 	protected $config;
 
	protected $REFRESH_TOKEN_EXPIRE_TIME = 24 * 3600; //Seconds
  
	protected $ACCESS_TOKEN_EXPIRE_TIME = 60; //Seconds

  public function __construct($config) {
    //print "__construct <br/>";
    $this->config=$config;
    //print_r($this->config);

    if (isset($config['refresh_token_expire_time']) && 
      is_numeric($config['refresh_token_expire_time']) &&
	$config['refresh_token_expire_time'] > 30 )
        $this->REFRESH_TOKEN_EXPIRE_TIME = $config['refresh_token_expire_time'];

    if (isset($config['access_token_expire_time']) && 
      is_numeric($config['access_token_expire_time']) && 
	$config['access_token_expire_time'] > 1  )
        $this->ACCESS_TOKEN_EXPIRE_TIME = $config['access_token_expire_time'];
       
	//print ("REFRESH_TOKEN_EXPIRE_TIME=" . $this->REFRESH_TOKEN_EXPIRE_TIME);
	//print ("ACCESS_TOKEN_EXPIRE_TIME=" . $this->ACCESS_TOKEN_EXPIRE_TIME);
 
  }  
  
  /**
   * Returns client IP string.
   *
   * @return string client IP.
   */	
	protected function GetClientIp() {
		//$remoteaddress = new RemoteAddress();
		return parent::getIpAddress();
	}


  /**
   * Parse a token string to array
   *
   * @param string token 
   * @return array array of tokens
   */  
	protected function GetAuthTokens($token) {
		$client_auth_words = $this->Decode( $token );
		return  explode( ":", $client_auth_words );
	} 

  
  /**
   * Generate a refresh token string
   *
   * @param string client_id 
   * @return string refresh token string
   */  
	public function getRefreshToken( $client_id ) {
		$client_ip = $this->GetClientIp();
		$client_auth_words = $client_id . ":" . $client_ip . ":" .  time();
		return $this->Encode($client_auth_words);
	}

  
  /**
   * Generate a access token string
   *
   * @param string client_id 
   * @param string refresh_token 
    * @return string access token string
   */    
	public function GetAccessToken( $client_id, $refresh_token ) {
		if ( ! $this->isValidRefreshToken($client_id, $refresh_token)) 
			return "";
		
		$client_access_token_words = $client_id . ":access:" .  time();
		
		return $this->Encode($client_access_token_words);
	} 

  
  /**
   * Renew a fresh token string
   *
   * @param string client_id 
   * @param string old or expired refresh token string 
   * @return string refreshed token string
   */  
	public function RenewRefreshToken( $client_id, $refresh_token ) {
		$client_ip = $this->GetClientIp();
		$tokens = $this->GetAuthTokens($refresh_token);
		$id = $tokens[0];
		if ( $id == $client_id ) {
			return $this->GetRefreshToken($client_id);
		}
		return "";
	}

  
  /**
   * Check if a fresh token is a valid string that can be renewed
   *
   * @param string client_id 
   * @param string old or expired refresh token string 
   * @return boolean valid or not valid
   */   
	public function AllowAutoRenew($client_id, $refresh_token ) {
		$tokens = $this->GetAuthTokens($refresh_token);
		$client_ip = $this->GetClientIp();
		if ( count( $tokens ) == 3 ) {
			$id = $tokens[0];
			$ip = $tokens[1];
			$timestamp = $tokens[2];
			$leftexpiretime = $this->GetLeftExpireTime($refresh_token);
			if ( !$this->IsRefreshTokenExpired($refresh_token) && $client_id == $id ) {
				return true;
			}

			if ( $client_ip == $ip && $client_id == $id ) {
				return true;
			}
		}
		return false;
	}
	 
   
  /**
  * Check if a Access Token is expired
  *
  * @param string access token 
  * @return boolean expired or not expired 
  */   
	public function IsAccessTokenExpired( $access_token ) {
		$leftexpiretime = $this->GetLeftExpireTime($access_token);
		if ( $leftexpiretime > $this->ACCESS_TOKEN_EXPIRE_TIME  ) 
			return true;
			
		return false;
	} 
	
  
  /**
  * Check if a Refresh Token is expired
  *
  * @param string refresh token 
  * @return boolean expired or not expired 
  */  
	public function IsRefreshTokenExpired( $refresh_token ) {
		$leftexpiretime = $this->GetLeftExpireTime($refresh_token);
		if ( $leftexpiretime > $this->REFRESH_TOKEN_EXPIRE_TIME ) 
			return true;
			
		return false;
	}  

  
  /**
  * Check if a Refresh Token is valid
  *
  * @param string refresh token 
  * @return boolean valid or not valid 
  */   
	public function isValidRefreshToken( $client_id, $refresh_token ) {
		if ($refresh_token==null || $refresh_token=="") 
			return false;
		
		return $this->AllowAutoRenew( $client_id, $refresh_token);
	}

  
  /**
  * Check if a Access Token is valid
  *
  * @param string access token 
  * @return boolean valid or not valid 
  */ 
 
	public function isUserAccessToken( $client_id, $access_token ) {
		if ( !isset($access_token) || $access_token == "" )
			return false;

		$tokens = $this->GetAuthTokens($access_token);

		if ( count($tokens) < 3 ) 
			return false;

		if ($tokens[1] != "access" ) 
			return false;

		if ($tokens[0] != $client_id)
			return false;

		return true;
	}

	
  /**
  * Check if a Access Token is valid
  *
  * @param string access token
  * @return int  0 valid access token
  *              1 "no access token"
  *              2 "invalid access token"
  *              3 "expired access token"
  */
 
        public function isValidAccessToken( $access_token ) {

                if ( !isset($access_token) || $access_token == "" )
                        return 1;

                $tokens = $this->GetAuthTokens($access_token);

                if ( count($tokens) < 3 )
                        return 2;

                if ($tokens[1] != "access" )
                        return 2;

                if ($this->IsAccessTokenExpired($access_token))
			return 3;

		return 0;
        }  

  /**
   * Generate a token's valid time that left in seconds
   *
   * @param string token 
   * @return long the length in seconds that left
   */  
	public function GetLeftExpireTime($token ) {
		$tokens = $this->GetAuthTokens($token);
		if ( count( $tokens ) == 3 ) {
			$timestamp = $tokens[2];
			return time() - $timestamp;
		}
		return -1;
	}

  /**
   * Returns encoded string.
   *
   * @return string encoded string.
   */	
	protected function Encode($str) {
		$token = base64_encode($str);
		$token = strtolower($token) ^ strtoupper($token) ^ $token;
		return strrev($token);
	}

  
  /**
   * Returns decoded string.
   *
   * @return string decoded string.
   */	
	protected function Decode($token) {
		$token = strrev($token);
		$token = strtolower($token) ^ strtoupper($token) ^ $token;
		$str = base64_decode($token);
		return $str;
	}
  
}


class WhistlerAuthentication extends WhisOAuth {

  
  public function __construct($config) {
	parent::__construct($config);
  }

  /**
   *  Attempt to confirm the auth entrypoint
   *
   * @param string url
   * @return string "auth"/"token"/"" it's a auth entrypoint url/token entrypoint url or others
   */
  public function api_auth_entry($url)
  {
	$urls = explode( '/', $url);
	if ( count($urls)>1 && $urls[0] == "api" ) {
		if ( $urls[1]=="auth" || $urls[1]=="token" )
			return $urls[1];
	}
	return "";
  }


  /**
   *  Attempt to login to Observium system for getting a refresh token
   *
   * @param string username
   * @param string password   
   * @return boolean true/false login successfully or not
   */  
  protected function mysql_authenticate($username, $password)
  {
    $encrypted_old = md5($password);
    $row = dbFetchRow("SELECT `username`, `password` FROM `users` WHERE `username`= ?", array($username));
    if ($row['username'] && $row['username'] == $username)
    {
      if ( $row['password'] == crypt($password, $row['password']))
      {
        return 1;
      }
    }

    return 0;
  }

  /**
   * Generate a refresh token for the user with mysql authentication
   *
   * @param string username
   * @param string password   
   * @return string refresh token
   */   
  public function GetRefreshToken($username, $password) {
    if ( mysql_authenticate($username, $password)==1 ) {
      return parent::GetRefreshToken($username);
    }else {
    }
    return "";

  }

  /**
   * Generate a access token 
   *
   * @param string username
   * @param string refresh_token   
   * @return string access token
   */  
  public function GetAccessToken( $username, $refresh_token ) {
    return parent::GetAccessToken( $username, $refresh_token );
  }

}

?>


