<?php

require 'DAL.php';

function create_campaigns($tgt_attr_max_len, $tgt_list_max_len, $amount_of_campaigns) {
    $alphabet = range('A', 'Z');
    // trim values to allowed ranges
    $tgt_attr_max_len = min($tgt_attr_max_len, 100);
    $tgt_list_max_len = min($tgt_list_max_len, 26);
    $amount_of_campaigns = min($amount_of_campaigns, 10000);
    $dal = new DAL();
    $new_campaigns = array();
    $new_campaign_idx = $dal->get_campaign_count();
    // generate campaigns
    for ($i = 0; $i < $amount_of_campaigns; $i++) {
        $new_campaign_idx++;
        $target_list_arr = array();
        $target_list_len = rand(1, $tgt_list_max_len);
        $offer_price = rand(0.01, 1000.0);
        for ($j = 0; $j < $target_list_len; $j++) {
            $tgt_attr_list_len = rand(1, $tgt_attr_max_len);
            $attr_list = array_map(function($k, $v) { return "$k$v"; }, array_fill(0, $tgt_attr_list_len+1, $alphabet[$j]), range(0, $tgt_attr_list_len));
            $target_list_arr[] = array(
                'target' => 'attr_' . $alphabet[$j],
                'attr_list' => $attr_list
            );;
        }

        $new_campaigns[] = array(
            'campaign_name' => "campaign$new_campaign_idx",
            'price' => $offer_price,
            'target_list' => $target_list_arr
        );
    }
    return $new_campaigns;
}

function save_campaigns($new_campaigns) {
    $dal = new DAL();
    $dal->create_campaigns($new_campaigns);
}

function clear_campaigns() {
    $dal = new DAL();
    return json_encode($dal->clear_campaigns());
}

if (!$_GET) {
    echo 'missing parameters';
} else {
    if (isset($_GET['clear']) && boolval($_GET['clear']) == TRUE) {
        return clear_campaigns();
    }

    $x = $_GET['x'];        // target list attributes max length
    $y = $_GET['y'];        // target list max length
    $z = $_GET['z'];        // number of generated campaigns

    $is_dry_run = isset($_GET['dryrun']) && (boolval($_GET['dryrun']) == TRUE);
    $nc = create_campaigns($x, $y, $z);
    if ($is_dry_run) {
        header( 'Content-Type: text/html' );
        $camp_strings = "";
        foreach ($nc as $c) {
            $camp_strings .= json_encode($c, JSON_PRETTY_PRINT) . "<br/>\n";
        }
        echo "\n incoming params: ";
        $result = var_dump($_GET) . "\n<br/> this was a dry run, nothing was saved \n<br/>" . $camp_strings;
    } else {
        header( 'Content-Type: text/json' );
        $result = json_encode($nc, JSON_PRETTY_PRINT);
        //save_campaigns($nc);
    }
    echo $result;
}


?>