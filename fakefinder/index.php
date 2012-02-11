<html xmlns="http://www.w3.org/1999/xhtml"
xmlns:og="http://ogp.me/ns#"
xmlns:fb="http://www.facebook.com/2008/fbml">

<head>
	<meta property="fb:app_id" content="335114976528405" />
	<meta property="fb:admins" content="1180011346,1164390835,773659044,1142910470,1164390867"/>
	<meta property="og:title" content="fakefinder"/>
	<meta property="og:type" content="profile"/>
	<meta property="og:url" content="http://localhost/fakefinder"/>
	<meta property="og:image" content="http://localhost/fakefinder/logo.jpg"/>
	<meta property="og:description" content="Description of page content" />
</head>
<body>
<?php
require_once("facebook.php");

  $config = array();
  $config['appId'] = '335114976528405';
  $config['secret'] = '8e49167412e949e6349f4c62e1e7c3eb';
  $config['fileUpload'] = false; // optional option

  $facebook = new Facebook($config);
  
  $user = null; //facebook user uid
  //Facebook Authentication part
    $user       = $facebook->getUser();
    $loginUrl   = $facebook->getLoginUrl(
            array(
                'scope' => 'email,publish_stream,user_birthday,user_location,user_work_history,user_about_me,user_hometown,user_likes,friends_likes,user_photos,friends_photos,user_work_history,'
            )
    );
	if ($user) {
      try {
        // Proceed knowing you have a logged in user who's authenticated.
        $user_profile = $facebook->api('/me');
      } catch (FacebookApiException $e) {
        //you should use error_log($e); instead of printing the info on browser
        d($e);  // d is a debug function defined at the end of this file
        $user = null;
      }
    }
 
    if (!$user) {
        echo "<script type='text/javascript'>top.location.href = '$loginUrl';</script>";
        exit;
    }
	
	//get user basic description
    $userInfo = $facebook->api(/$user);
?>



<p>hi</p>

</body>
</html>
