<?php

require 'DAL.php';

// get input either from POST or GET (GET only used for easy testing)
$input_data = ($_GET) ? $_GET['json'] : file_get_contents('php://input');
error_log("Input data loaded to search : \n $input_data");
if ($input_data) {
    //$input_data = json_decode($input_data, true);
    // convert stdClass => array
    //$user_data = json_decode(json_encode($input_data), true);
	$user_data = json_decode($input_data);
	error_log("User data loaded to search : \n $user_data");
	$user_attrs = $user_data['profile'];
	error_log("User attrs : " +  json_encode($user_attrs));
    $dal = new DAL();
    $results = $dal->get_campaigns($user_attrs);
    echo json_encode($results, JSON_PRETTY_PRINT);
}