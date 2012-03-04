<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
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


	function feedmetric($facebook){

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
		
		
		return $score;	
		
	}
	
	
	function mutualfriendsmetric($friends, $facebook){
		

			$mfriends = $facebook->api('me/mutualfriends/' . $_POST['friendid'] );
			
			$mfcount = count($mfriends['data']);
			
			$mfmean = 96.9345;
			
			$mfscore = min(2*(1-(($mfmean - $mfcount)/$mfmean)),1);
			
			
			return $mfscore;
			
		
	}
	
	function picturemetric($facebook){
		
		$photos = $facebook->api($_POST['friendid'] . '/photos' . '/?limit=1500&offset=0'); 
 
		$pcount = count($photos['data']);
		
		$mean = 301.4435;
		$stdev = 329.8105961;
		$e = 2.718;
		$pi = 3.14;
		
		$probability = min(1,1-(($mean-$pcount)/$mean));
		
		return $probability;

		
	}
	
	function educationmetric($facebook, $user, $friend){
		
		if(!array_key_exists('education',$user) or !array_key_exists('education',$friend))
		{
			return 0;	
		}
		else
		{
			foreach($user['education'] as $mschool){
				foreach($friend['education'] as $fschool){
					if ($mschool['school']==$fschool['school']){
						return 1;	
					}
				}
			}
			return 0;
		}
	}
	
	function familymetric($facebook){
		$userfam = $facebook->api('me/family');
	
		if(count($userfam) == 0)
		{
			return 0;	
		}
		else
		{
			foreach($userfam as $mrel){
				if($mrel['id'] = $_POST['friendid']){
					return 1.5;	
				}
			}
			return 0;
		}
	}
	
				
?>

<?php
	
	set_time_limit(0);
	$m1 = mutualfriendsmetric($friends, $facebook);
	$m2 = feedmetric($facebook);
	$m3 = picturemetric($facebook);
	
	$user = $facebook->api('me');
	$friend = $facebook->api($_POST['friendid']);
	
	$denom = 3;
	
	$m4 = educationmetric($facebook, $user, $friend);
	
	$m5 = familymetric($facebook);
	
		
	
	echo $m1 . "<br/>" . $m2 . "<br/>" . $m3 . "<br/>" . $m4 . "<br/>" . $m5 . "<br/>" . ($m1+$m2+$m3+$m4+$m5)/($denom+$m4+$m5);

?>
</body>
</html>