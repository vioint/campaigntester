<?php

require 'DAL.php';

// get input either from POST or GET (GET only used for easy testing)
$input_data = ($_GET) ? $_GET['json'] : file_get_contents('php://input');
if ($input_data) {
    $input_data = json_decode($input_data);
    // convert stdClass => array
    $user_data = json_decode(json_encode($input_data), true);
    $dal = new DAL();
    $results = $dal->get_campaigns($user_data['profile']);
    echo json_encode($results, JSON_PRETTY_PRINT);
}