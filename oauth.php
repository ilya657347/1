<?php 
require __DIR__ . '/vendor/autoload.php';

use Illuminate\Http\Request;

	session_start();
	$client = new Google_Client();
	$client->setScopes(Google_Service_Calendar::CALENDAR);
	$client->setAuthConfig('client_secret.json');
	$client->setRedirectUri("http://localhost:800/wordpress/wp-admin/admin.php?page=tg_bot%2Fhome.php");
	$guzzleClient = new \GuzzleHttp\Client(array('curl' => array(CURLOPT_SSL_VERIFYPEER => false)));
	$client->setHttpClient($guzzleClient);
	if(!isset($_GET['code'])){
		$auth_url = $client->createAuthUrl();
		$filtered_url = filter_var($auth_url, FILTER_SANITIZE_URL);
		return header("Location: ".$filtered_url);
	}else{
		$client->authenticate($_GET['code']);
		$token = $client->getAccessToken();
		file_put_contents("google-calendar-token.json", json_encode($token));
		$_SESSION['access_token'] = $token;
	}
 ?>