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
                'scope' => 'user_about_me, friends_about_me, user_birthday, friends_birthday, user_education_history, friends_education_history, user_groups, friends_groups, user_hometown, friends_hometown, user_likes, friends_likes, user_photos, friends_photos, user_status, friends_status, user_work_history, friends_work_history, read_friendlists, user_location, friends_location, user_photo_video_tags, friends_photo_video_tags'
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
    $userInfo = $facebook->api($user);
	
	if ($user) {
  try {
    // Proceed knowing you have a logged in user who's authenticated.
  $friends = $facebook->api('/me/friends');
  } catch (FacebookApiException $e) {
    error_log($e);
    $user = null;
  }
}
set_time_limit(0);
 
 
$photos = $facebook->api($_POST['friendid'] . '/photos' . '/?limit=1500&offset=0'); 
 
$pcount = count($photos['data']);
echo 'Number Of Photos: ' . $pcount;

echo "<br>";

/*$albums = $facebook->api($_POST['friendid'] . '/albums' . '/?limit=350&offset=0');
$acount = count($albums['data']);
echo $acount;

echo $albums['Profile Pictures'][ 'count'];
echo"<br>";
*/


$mean = 301.4435;
$stdev = 329.8105961;
$e = 2.718;
$pi = 3.14;

$probability = 2*(1 / (sqrt(2 * $pi) * $stdev)) * pow($e,-(pow($pcount-$mean,2)/ (2*pow($stdev,2))));

echo 'Probability: ' . $probability;


/*if ($pcount > $mean){
echo "100%";}
else{ 
echo $pcount / $mean;
}
*/

/*foreach($friends['data'] as $val){
$photos = $facebook->api($val['id'] . '/photos' . '/?limit=1500&offset=0');
$pcount = count($photos['data']);
echo "$pcount"; 
echo "<br>";
}
*/



?>

</body>
</html>
