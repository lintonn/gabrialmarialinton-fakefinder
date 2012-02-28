<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
</head>

<body>
<?php

function erf($x) 
{ 
        $pi = 3.1415927; 
        $a = (8*($pi - 3))/(3*$pi*(4 - $pi)); 
        $x2 = $x * $x; 

        $ax2 = $a * $x2; 
        $num = (4/$pi) + $ax2; 
        $denom = 1 + $ax2; 

        $inner = (-$x2)*$num/$denom; 
        $erf2 = 1 - exp($inner); 

        return sqrt($erf2); 
} 

function cdf($n) 
{ 
        if($n < 0) 
        { 
                return (1 - erf($n / sqrt(2)))/2; 
        } 
        else 
        { 
                return (1 + erf($n / sqrt(2)))/2; 
        } 
} 


?>

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
                'scope' => 'user_about_me, friends_about_me, user_birthday, friends_birthday, user_education_history, friends_education_history, user_groups, friends_groups, user_hometown, friends_hometown, user_likes, friends_likes, user_photos, friends_photos, user_status, friends_status, user_work_history, friends_work_history, read_friendlists, user_location, friends_location, user_photo_video_tags, friends_photo_video_tags, read_stream'
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

	
		$friendfeed = $facebook->api($_POST['friendid'] . '/feed?limit=500');
			
		$last = count($friendfeed['data']) - 1;
		
		$oldest = array_slice($friendfeed['data'],$last);
		$newest = array_slice($friendfeed['data'],0,1);
	
		$newpost = $newest[0]['created_time'];
		$oldpost = $oldest[0]['created_time'];
		
		$newtime = new DateTime($newpost);
		$oldtime = new DateTime($oldpost);
		
		$interval = $oldtime->diff($newtime);
		$postday = $interval->format('%R%a days');
		
		$metric = $last/$postday;
		
		$avg = 0.799061;
		$std = 0.706539;
		$f = 0.474736;
		$e = 2.71828;
		
		$score = $f*2*pow($e,-(pow($metric - $avg,2)/(2*pow($std,2))));
		
		echo 'Mean: ' . $avg . "<br>";
		echo 'Std: ' . $std . "<br>";
		echo "<br>";
		echo $postday . "<br>";
		echo $last . "<br>";
		echo 'Score: ' . $metric . "<br>";
		echo 'Percentile: ' . $score
		
		
		
				
		
	
	

?>
</body>
</html>