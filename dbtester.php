<?php
require 'vendor/autoload.php';

$db_client = new MongoDB\Client("mongodb://localhost:27017");
var_dump($manager);

$m_db_name = 'test';
$db = $db_client->$m_db_name;

$users = $db->users;

$users_count = $users->count();

$user = array(
    'name' => 'u' . $users_count,
    'last_name' => 'Fan',
    'tags' => array('developer','user')
);

$users->insert($user);

$client = new MongoDB\Driver\Command(array("*"));
print_r($client);

//$m = new MongoClient(); // connect
//$db = $m->selectDB("example");

?>