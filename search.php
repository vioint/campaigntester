<?php

require 'DAL.php';

// get input either from POST or GET (GET only used for easy testing)
$input_data = ($_GET) ? $_GET['json'] : file_get_contents('php://input');
error_log("Input data loaded to search : \n $input_data");
if ($input_data) {
	$user_data = json_decode($input_data, true);
	//error_log("User data loaded to search : \n $user_data");
	// on heroku we need double decoding (probably some environment misconfiguration which we cannot override)
	$user_attrs = json_decode($user_data, true);
	error_log("User attrs : " +  json_encode($user_attrs));
    $dal = new DAL();
    $results = $dal->get_campaigns($user_attrs['profile']);
    echo json_encode($results, JSON_PRETTY_PRINT);
}