<?php

require __DIR__ . '/vendor/autoload.php';
/*
 * Plugin Name: Telegram бот
 * Description: 
 * Version: 1.0.0.0
 */
/* function my_func(){
 	if(!function_exists("is_woocommerce_activated")){
 		function is_woocommerce_activated(){
 			if(class_exists('woocommerce')){
 				return true;
 			}else{
 				return false;
 			}
 		}
 	}*/
 	/*if(!defined('ABSPATH')){
 		exit;
 	}
 	print_r(apply_filters("active_plugins"));
 	if(in_array('woocommerce/woocommerce.php', apply_filters("active_plugins", get_option("active_plugins")))){
 		  add_action( 'admin_menu', function(){
	 		 add_menu_page(
			 'Телеграм бот', // Название страниц (Title)
			 'Телеграм бот', // Текст ссылки в меню
			 'manage_options', // Требование к возможности видеть ссылку
			 'tg_bot/home.php' // 'home' - файл отобразится по нажатию на ссылку
			 );
 		  }
 	}*/
 	/*if(is_woocommerce_activated()){
		
	}*/
	file_put_contents("D://data.txt", "new product");
	add_action( 'admin_menu', "my_func");
 function my_func(){
	 add_menu_page(
	 'Телеграм бот', // Название страниц (Title)
	 'Телеграм бот', // Текст ссылки в меню
	 'manage_options', // Требование к возможности видеть ссылку
	 'tg_bot/home.php' // 'slug' - файл отобразится по нажатию на ссылку
	 );
}
add_action("woocommerce_new_product", "new_product_alert");

function new_product_alert($id, $product){
	
}
/*apply_filters('product_type_selector',"add_ticket_type");
function add_ticket_type($arr){
	$arr["Ticket"] = __("Ticket","woocommerce");
	return $arr;
}*/
add_action("woocommerce_update_product", "update_calendar");
function update_calendar($id){
	session_start();
	$product = wc_get_product($id, []);
	if($product->get_attribute("Google calendar id")){

	}else{
		$date_start = $product->get_attribute("Начало");
		$date_end = $product->get_attribute("Конец");
		if($date_end && $date_start){
			$date_start = new DateTime($product->get_attribute("Начало"));
			$date_end = new DateTime($product->get_attribute("Конец"));
			//file_put_contents("test.txt", $id. " ".$date_start->format("Y-m-d h-i-s")." ".$date_end);
			$calendarId = 'l4bqt5bdp0c13jscetqjbp37fo@group.calendar.google.com';
			$client = new Google_Client();
			$client->setAuthConfig("D:/OSPanel/domains/localhost/wordpress/wp-content/plugins/tg_bot/client_secret.json");
			$client->setScopes(Google_Service_Calendar::CALENDAR);
			$guzzleClient = new \GuzzleHttp\Client(array('curl' => array(CURLOPT_SSL_VERIFYPEER => false)));
			$client->setHttpClient($guzzleClient);
			if(isset($_SESSION['access_token']) && $_SESSION['access_token']){
			   	$client->setAccessToken( $_SESSION['access_token']);
			   	
			   	if ($client->isAccessTokenExpired()) {              
			        $token =$client->fetchAccessTokenWithRefreshToken($client->getRefreshToken());
			        $client->setAccessToken($token);
					//file_put_contents(__DIR__."/google-calendar-token.json", json_encode($token));
			        $_SESSION['access_token'] = $token;              
			    }		
			}
			$service = new Google_Service_Calendar($client);
			$event = new Google_Service_Calendar_Event(array(
			  'summary' => $product->get_title(),
			  //'location' => '800 Howard St., San Francisco, CA 94103',
			  'description' => 'A chance to hear more about Google\'s developer products.',
			  'start' => array(
			    'dateTime' => $date_start->format("Y-m-d\Th:i:s+03:00"),
			    'timeZone' => 'America/Los_Angeles',
			  ),
			  'end' => array(
			    'dateTime' => $date_end->format("Y-m-d\Th:i:s+03:00"),
			    'timeZone' => 'America/Los_Angeles',
			  ),
			  'recurrence' => array(
			    'RRULE:FREQ=DAILY;COUNT=2'
			  ),
			  'attendees' => array(
			    array('email' => 'lpage@example.com'),
			    array('email' => 'sbrin@example.com'),
			  ),
			  'reminders' => array(
			    'useDefault' => FALSE,
			    'overrides' => array(
			      array('method' => 'email', 'minutes' => 24 * 60),
			      array('method' => 'popup', 'minutes' => 10),
			    ),
			  ),
			));
			$event = $service->events->insert($calendarId, $event);
			file_put_contents("test.txt", json_encode($event));
		}
	}
}
add_action("woocommerce_delete_product", "delete_calendar_event");

function delete_calendar_event($id, $product){
	if($product->get_attribute("Google calendar id")){
		//file_put_contents("test.txt", $id. " ".$date_start->format("Y-m-d h-i-s")." ".$date_end);
		$calendarId = 'l4bqt5bdp0c13jscetqjbp37fo@group.calendar.google.com';
		$client = new Google_Client();
		$client->setAuthConfig("D:/OSPanel/domains/localhost/wordpress/wp-content/plugins/tg_bot/client_secret.json");
		$client->setScopes(Google_Service_Calendar::CALENDAR);
		$guzzleClient = new \GuzzleHttp\Client(array('curl' => array(CURLOPT_SSL_VERIFYPEER => false)));
		$client->setHttpClient($guzzleClient);
		if(isset($_SESSION['access_token']) && $_SESSION['access_token']){
		   	$client->setAccessToken( $_SESSION['access_token']);  	
			if ($client->isAccessTokenExpired()) {              
			    $token =$client->fetchAccessTokenWithRefreshToken($client->getRefreshToken());
			    $client->setAccessToken($token);
				//file_put_contents(__DIR__."/google-calendar-token.json", json_encode($token));
			    $_SESSION['access_token'] = $token;              
			}		
		}
		$service = new Google_Service_Calendar($client);
		$event = $service->events->delete($calendarId, $product->get_attribute("Google calendar id"));
		}
	}
}
function get_google_calendar(){
	$calendarId = 'l4bqt5bdp0c13jscetqjbp37fo@group.calendar.google.com';
	$apiKey = "AIzaSyDPTDLFqIaEPruJEZmdbz5eUwDnnai7okw";
	$client = new Google_Client();
	$client->setScopes(Google_Service_Calendar::CALENDAR);
	//$client->setDeveloperKey($apiKey);
	$guzzleClient = new \GuzzleHttp\Client(array('curl' => array(CURLOPT_SSL_VERIFYPEER => false)));
	$client->setHttpClient($guzzleClient);
	$service = new Google_Service_Calendar($client);
	if(file_exists(__DIR__."/google-calendar-token.json")){
		$_SESSION['access_token']= json_decode(file_get_contents(__DIR__."/google-calendar-token.json"),true);
	}
	if(isset($_SESSION['access_token']) && $_SESSION['access_token']){
	   	$client->setAccessToken( $_SESSION['access_token']);
	   	if ($client->isAccessTokenExpired()) {              
	        $client->fetchAccessTokenWithRefreshToken($client->getRefreshToken());
	        $token = $client->getAccessToken();
	        $client->setAccessToken($token);
			file_put_contents(__DIR__."/google-calendar-token.json", json_encode($token));
	        $_SESSION['access_token'] = $token;              
	    } 
	    return $service;
		
	}
		$optParams = array(
		  'maxResults' => 10,
		  'orderBy' => 'startTime',
		  'singleEvents' => true
		);
		$results = $service->events->listEvents($calendarId, $optParams);
		$events =  $results->getItems();
		$ret_str ="";
		if (empty($events)) {
		    $ret_str.="На данное время нет мероприятий\n";
		} else {
		    $ret_str.="Запланированы мероприятия:\n";
		    foreach ($events as $event) {
		        $start = $event->start->dateTime;
		        if (empty($start)) {
		            $start = $event->start->date;
		        }
		       $ret_str.=$event->getSummary()." ". $start. "\n";
		    }
		}
		return $ret_str;
}
?>