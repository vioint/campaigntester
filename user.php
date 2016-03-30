<?php
require 'DAL.php';

function generate_user()
{
    $dal = new DAL();

    $us = $dal->get_updated_user_creation_settings();
    $username = 'u' . $us->create_counter;
    $alphabet = range('A', 'Z');
    $profile_attr_list_len = max($us->create_counter % 26, 1);
    $profile = array();
    for ($i = 0; $i < $profile_attr_list_len; $i++) {
        $profile[] = array(
            'attr_' . $alphabet[$i] => $alphabet[$i] . rand(0, 200)
        );
    }
    $new_user = array(
        'user' => $username,
        'profile' => $profile
    );
    $dal->create_user($new_user['user'],$new_user['profile']);
    return $new_user;
}

echo json_encode(generate_user());

?>