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
apply_filters('product_type_selector',"add_ticket_type");
function add_ticket_type($arr){
	$arr["Ticket"] = __("Ticket","woocommerce");
	return $arr;
}
//add_action("woocommerce_update_product",);
//add_action("woocommerce_delete_product",);
function new_product_alert($id, $product){
	$event = new Google_Service_Calendar_Event(array(
		  'summary' => 'Google I/O 2015',
		  'location' => '800 Howard St., San Francisco, CA 94103',
		  'description' => 'A chance to hear more about Google\'s developer products.',
		  'start' => array(
		    'dateTime' => '2015-05-28T09:00:00-07:00',
		    'timeZone' => 'America/Los_Angeles',
		  ),
		  'end' => array(
		    'dateTime' => '2015-05-28T17:00:00-07:00',
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
		$event = $service->events->insert('l4bqt5bdp0c13jscetqjbp37fo@group.calendar.google.com', $event);
		printf('Event created: %s\n', $event->htmlLink);
}/*
function delete_product($id, $product){

}
function update_product($id, $product){

}*/
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