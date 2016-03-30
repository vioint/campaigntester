<?php

use MongoDB\Operation\FindOneAndReplace;

require 'vendor/autoload.php';

class DAL
{
    public $db_client = null;
    private $is_debug = false;
    private $current_db_name = 'test';
    private $db = null;
    private $users_collection_name = 'users';
    private $campaigns_collection_name = 'campaigns';
    private $settings_collection_name = 'settings';

    function __construct()
    {
        $db_client = new MongoDB\Client("mongodb://localhost:27017");
        $this->db = $db_client->selectDatabase($this->current_db_name);
        if ($this->is_debug)
            var_dump($db_client);

    }

    function get_campaign_count() {
        $campaigns = $this->db->selectCollection($this->campaigns_collection_name);
        return $campaigns->count();
    }

    function get_user_count() {
        $users = $this->db->selectCollection($this->users_collection_name);
        return $users->count();
    }

    function get_updated_user_creation_settings() {
        $settings = $this->db->selectCollection($this->settings_collection_name);
        return $settings->findOneAndUpdate(
            array('name' => 'user_creation_settings'),
            array('$inc' => array('create_counter' => 1)),
            array('upsert' => true, 'returnDocument' => FindOneAndReplace::RETURN_DOCUMENT_AFTER)
        );
    }

    function create_user($username, $profile) {
        $users = $this->db->selectCollection($this->users_collection_name);
        $new_user = array(
            'user' => $username,
            'profile' => $profile
        );
        return $users->insertOne($new_user);
    }

    function create_campaigns($new_camps) {
        $campaigns = $this->db->selectCollection($this->campaigns_collection_name);
        return $campaigns->insertMany($new_camps);
    }

    function get_campaigns($profile_attrs) {
        $campaigns = $this->db->selectCollection($this->campaigns_collection_name);
        // extract only the values from the associative array
        $all_attrs = array_map(function($val) { return array_values($val)[0]; }, $profile_attrs);
        //echo json_encode($all_attrs, JSON_PRETTY_PRINT);
        $query = [
            "target_list" =>
                ['$elemMatch' =>
                    ["attr_list" =>
                        ['$in' => $all_attrs]
                    ]
                ]
        ];
        $query_opts = ['campaign_name'=>true,'price' => true, 'sort' => ['price' => -1]];
        $result = $campaigns->findOne($query, $query_opts);
        $total_camps = $campaigns->count($query, $query_opts);
        $winner_name = ($total_camps == 0) ? "none" : $result['campaign_name'];
        return [
            "winner" => $winner_name,
            "counter" => $total_camps
        ];
    }

    function clear_campaigns() {
        $campaigns = $this->db->selectCollection($this->campaigns_collection_name);
        return $campaigns->drop();
    }

}