<?php
require_once( dirname(__FILE__) . '/src/Facebook/autoload.php');

class FBGraph
{
  public $loggedin=false;
  public $loginUrl;
	public $name;
  public $link;
  public $gender;
	public $email;
	public $fbid;
  public $fb;
  private $targetUrl = 'http://dev.appsupport.com.ar/dev/php/fbprofile/index.php';

  public function login(){
      $this->loggedin=false;
      $this->fb = new Facebook\Facebook([
        'app_id' => '1392121210886012',
        'app_secret' => 'ac29500eb5526e771e03387a1255d520',
        'default_graph_version' => 'v2.10',
        ]);
        $helper = $this->fb->getRedirectLoginHelper();
  	    $permissions = ['email']; // optional

  	    try {
  	    	if (isset($_SESSION['facebook_access_token'])) {
  	    		$accessToken = $_SESSION['facebook_access_token'];
  	    	} else {
  	    			$accessToken = $helper->getAccessToken();
  	    	}
  	    } catch(Facebook\Exceptions\FacebookResponseException $e) {
  	    	// When Graph returns an error
  	    	//echo 'Graph returned an error: ' . $e->getMessage();
          return false;
	    		exit;
  	    } catch(Facebook\Exceptions\FacebookSDKException $e) {
  	    	// When validation fails or other local issues
  	    	//echo 'Facebook SDK returned an error: ' . $e->getMessage();
          return false;
  	    	exit;
  	     }
  	    if (isset($accessToken)) {
  	    	if (isset($_SESSION['facebook_access_token'])) {
  	    		$this->fb->setDefaultAccessToken($_SESSION['facebook_access_token']);
  	    	} else {
  	    		// getting short-lived access token
  	    		$_SESSION['facebook_access_token'] = (string) $accessToken;
  	    			// OAuth 2.0 client handler
  	    		$oAuth2Client = $this->fb->getOAuth2Client();
  	    		// Exchanges a short-lived access token for a long-lived one
  	    		$longLivedAccessToken = $oAuth2Client->getLongLivedAccessToken($_SESSION['facebook_access_token']);
  	    		$_SESSION['facebook_access_token'] = (string) $longLivedAccessToken;
  	    		// setting default access token to be used in script
  	    		$this->fb->setDefaultAccessToken($_SESSION['facebook_access_token']);
  	    	}



  	    	// redirect the user back to the same page if it has "code" GET variable
  	    	if (isset($_GET['code'])) {
  	    		header('Location: ./');
  	    	}
  	    	// getting basic info about user
  	    	try {
  	    		$profile_request = $this->fb->get('/me?fields=name,first_name,last_name,email');
  	    		$profile = $profile_request->getGraphNode()->asArray();
  	    	} catch(Facebook\Exceptions\FacebookResponseException $e) {
  	    		// When Graph returns an error
  	    		//echo 'Graph returned an error: ' . $e->getMessage();
  	    		session_destroy();
  	    		// redirecting user back to app login page
  	    		header("Location: ./");
            return false;
  	    		exit;
  	    	} catch(Facebook\Exceptions\FacebookSDKException $e) {
  	    		// When validation fails or other local issues
  	    		echo 'Facebook SDK returned an error: ' . $e->getMessage();
            return false;
  	    		exit;
  	    	}
          $this->loggedin=true;
          $this->name=$profile['name'];
          if(isset($profile['name']))
            $this->email=$profile['email'];
          if(isset($profile['link']))
            $this->link=$profile['link'];
          if(isset($profile['gender']))
            $this->link=$profile['gender'];
          //$this->count_friends = count($user_friends['data']);

          return true;
  	    } else {
  	    	// replace your website URL same as added in the developers.facebook.com/apps e.g. if you used http instead of https and you used non-www version or www version of your website then you must add the same here
  	      $this->loginUrl = $helper->getLoginUrl($this->targetUrl, $permissions);
  	    	//echo '<a href="' . $loginUrl . '">Log in with your Facebook account</a>';
          return false;
  	    }
  }

}
?>
