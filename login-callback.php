<?php
session_start();
require_once __DIR__ . '/vendor/autoload.php';

$fb = new Facebook\Facebook([
  'app_id' => '593921763996004',
  'app_secret' => '264e38ac166cb92db8b73ae00803cd37',
  'default_graph_version' => 'v2.5',
]);

$helper = $fb->getRedirectLoginHelper();
try {
  $accessToken = $helper->getAccessToken();
} catch(Facebook\Exceptions\FacebookResponseException $e) {
  // When Graph returns an error
  echo 'Graph returned an error: ' . $e->getMessage();
  exit;
} catch(Facebook\Exceptions\FacebookSDKException $e) {
  // When validation fails or other local issues
  echo 'Facebook SDK returned an error: ' . $e->getMessage();
  exit;
}

if (isset($accessToken)) {
  echo 'Logged in!'."<br>";
  $_SESSION['facebook_access_token'] = (string) $accessToken;
  $fb->setDefaultAccessToken($_SESSION['facebook_access_token']);
  header('Location: index.php');
  // Now you can redirect to another page and use the
  // access token from $_SESSION['facebook_access_token']
}