<?php
function baseURL()
{
    $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
    return $protocol . $_SERVER['HTTP_HOST'] . str_replace(basename($_SERVER['SCRIPT_FILENAME']), "", $_SERVER['SCRIPT_NAME']);
}

function post_json_request($request_url, $request_content)
{
    $options = array(
        'http' => array(
            'method'  => 'POST',
            'content' => json_encode( $request_content ),
            'header'=>
                "Content-Type: application/json\r\n" .
                "Accept: application/json\r\n"
        )
    );

    $context  = stream_context_create( $options );
    $result = file_get_contents( $request_url, false, $context );
    return $result;
}

function perform_auto_search()
{

    $search_url = baseURL() . 'search.php';
    $user_gen_url = baseURL() . 'user.php';

    $new_user_data = json_decode(file_get_contents($user_gen_url), true);
    $search_results = json_decode(post_json_request($search_url, $new_user_data));

    return $search_results;
}

echo perform_auto_search();


