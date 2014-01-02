<?php namespace Spinegar\Sugar7Wrapper;

use Guzzle\Common\Event;
use Guzzle\Http\Client;
use Guzzle\Http\Query;

/**
 * SugarCRM 7 REST API Class
 *
 * @package   Sugar7Wrapper
 * @category  Libraries
 * @author  Sean Pinegar
 * @license MIT License
 * @link    https://github.com/spinegar/sugar7wrapper
 */

class Rest {

  /**
  * Variable: $username
  * Description:  A SugarCRM User. 
  */
  private $username;

  /**
  * Variable: $password
  * Description:  The password for the $username SugarCRM account
  */
  private $password;

  /**
  * Variable: $token
  * Description:  OAuth 2.0 token
  */
  private $token;

  /**
  * Variable: $client
  * Description:  Guzzle Client
  */
  private $client;

  /**
  * Function: __construct()
  * Parameters:   none    
  * Description:  Construct Class
  * Returns:  VOID
  */
  function __construct()
  {
    $this->client = new Client();
  }

  /**
  * Function: __destruct()
  * Parameters:   none    
  * Description:  OAuth2 Logout
  * Returns:  TRUE on success, otherwise FALSE
  */
  function __destruct()
  {
    if(!self::check())
      return true;
    
    $request = $this->client->post('oauth2/logout');
    $request->setHeader('OAuth-Token', $this->token);    
    $result = $request->send()->json();

    return $result;
  }

  
  /**
  * Function: connect()
  * Parameters:   none    
  * Description:  Authenticate and set the oAuth 2.0 token
  * Returns:  TRUE on login success, otherwise FALSE
  */
  public function connect()
  {
    $request = $this->client->post('oauth2/token', null, array(
        'grant_type' => 'password',
        'client_id' => 'sugar',
        'username' => $this->username,
        'password' => $this->password,
    ));

    $result = $request->send()->json();
   
    if(!$result['access_token'])
      return false;

    $this->token = $result['access_token'];

    $this->client->getEventDispatcher()->addListener('request.before_send', function(Event $event) {
      $event['request']->setHeader('OAuth-Token', $this->token);
    });
    
    return true;
  }

  /**
  * Function: check()
  * Parameters:   none    
  * Description:  Check if authenticated
  * Returns:  TRUE if authenticated, otherwise FALSE
  */
  public function check()
  {
    if(!$this->token)
      return false;

    return true;
  }

  /**
  * Function: setUrl()
  * Parameters:   $value = URL for the REST API    
  * Description:  Set $url
  * Returns:  returns $url
  */
  public function setUrl($value)
  {
    $this->client->setBaseUrl($value) ;

    return $this;
  }

  /**
  * Function: setUsername()
  * Parameters:   $value = Username for the REST API User    
  * Description:  Set $username
  * Returns:  returns $username
  */
  public function setUsername($value)
  {
    $this->username = $value;

    return $this;
  }

  /**
  * Function: setPassword()
  * Parameters:   none    
  * Description:  Set $password
  * Returns:  returns $passwrd
  */
  public function setPassword($value)
  {
    $this->password = $value;

    return $this;
  }

  /**
  * Function: get()
  * Parameters: 
  *   $endpoint = endpoint per API specs
  *   $parameters = Parameters per API specs
  * Description:  Calls the API via HTTP GET
  * Returns:  Returns an Array if successful, otherwise FALSE
  */
  public function get($endpoint, $parameters = array())
  {
    if(!self::check())
      self::connect();

    $request = $this->client->get($endpoint);

    $query = $request->getQuery();

    foreach($parameters as $key=>$value)
    {
      $query->add($key, $value);
    }

    $result = $request->send()->json();

    if(!$result)
      return false;

    return $result;
  }

  /**
  * Function: post()
  * Parameters: 
  *   $endpoint = endpoint per API specs
  *   $parameters = Parameters per API specs
  * Description:  Calls the API via HTTP POST
  * Returns:  Returns an Array if successful, otherwise FALSE
  */
  public function post($endpoint, $parameters = array())
  {
    if(!self::check())
      self::connect();

    $request = $this->client->post($endpoint, null, json_encode($parameters));
    $result = $request->send()->json();

    if(!$result)
      return false;

    return $result;
  }
  
  /**
  * Function: put()
  * Parameters: 
  *   $endpoint = endpoint per API specs
  *   $parameters = Parameters per API specs
  * Description:  Calls the API via HTTP PUT
  * Returns:  Returns an Array if successful, otherwise FALSE
  */
  public function put($endpoint, $parameters = array())
  {
    if(!self::check())
      self::connect();

    $request = $this->client->put($endpoint, null, json_encode($parameters));
    $result = $request->send()->json();

    if(!$result)
      return false;

    return $result;
  }

    /**
  * Function: delete()
  * Parameters: 
  *   $endpoint = endpoint per API specs
  * Description:  Calls the API via HTTP DELETE
  * Returns:  Returns an Array if successful, otherwise FALSE
  */
  public function delete($endpoint)
  {
    if(!self::check())
      self::connect();

    $request = $this->client->delete($endpoint);
    $result = $request->send()->json();

    if(!$result)
      return false;

    return $result;
  }
}