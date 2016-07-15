<?php
session_start();
require_once __DIR__ . '/vendor/autoload.php';

$fb = new Facebook\Facebook([
  'app_id' => '593921763996004',
  'app_secret' => '264e38ac166cb92db8b73ae00803cd37',
  'default_graph_version' => 'v2.5',
]);

if(!isset($_SESSION['facebook_access_token'])){
$helper = $fb->getRedirectLoginHelper();
$permissions = ['email', 'user_likes', 'user_friends']; // optional
$loginUrl = $helper->getLoginUrl('http://localhost/FbApi/login-callback.php', $permissions);

echo '<a href="' . $loginUrl . '">Log in with Facebook!</a>';
}else{
	$fb->setDefaultAccessToken($_SESSION['facebook_access_token']);
	$response = $fb->get('me/');
	$user = $response->getGraphUser();
	
	echo "Welcome Back ".$user->getName()."<br>";
	// get friend list
	$response = $fb->get('me/taggable_friends');
	$graphEdge = $response->getGraphEdge();
	echo "<table border='1' width='1024'>";
	foreach ($graphEdge as $graphNode) {
		$friend = $graphNode->asArray();
		echo "<tr><td><img src='".$friend['picture']['url']."'></td><td>".$friend['name']."</td></tr>";
	  
	}
	
	echo "</table>";
	if($_GET['postMessage']){
		$linkData = [
						'link' => 'http://www.example.com',
						'message' => 'User provided message',
					];
		try {
		  // Returns a `Facebook\FacebookResponse` object
		  $response = $fb->post('/me/feed', $linkData);
		} catch(Facebook\Exceptions\FacebookResponseException $e) {
		  echo 'Graph returned an error: ' . $e->getMessage();
		  exit;
		} catch(Facebook\Exceptions\FacebookSDKException $e) {
		  echo 'Facebook SDK returned an error: ' . $e->getMessage();
		  exit;
		}

		$graphNode = $response->getGraphNode();

		echo 'Posted with id: ' . $graphNode['id'];
	}
}