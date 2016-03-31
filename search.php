<?php

require 'DAL.php';

// get input either from POST or GET (GET only used for easy testing)
$input_data = ($_GET) ? $_GET['json'] : file_get_contents('php://input');
error_log("Input data loaded to search : \n $input_data");
if ($input_data) {
    $input_data = json_decode($input_data);
    // convert stdClass => array
    $user_data = json_decode(json_encode($input_data), true);
	error_log("User data loaded to search : \n $user_data");
    $dal = new DAL();
    $results = $dal->get_campaigns($user_data->profile);
    echo json_encode($results, JSON_PRETTY_PRINT);
}