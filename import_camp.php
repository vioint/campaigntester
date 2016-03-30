<?php
require 'DAL.php';

ini_set('post_max_size', '64M');
ini_set('upload_max_filesize', '64M');

header('Content-Type: text/html; charset=utf-8');
echo '<form enctype="multipart/form-data" method="POST" >';
echo '<h3>Campaign importer</h3>';

if (isset($_FILES['uploaded_json'])) {
    try {

        // Check error value.
        $file_upload_error_type = $_FILES['uploaded_json']['error'];
        switch ($file_upload_error_type) {
            case UPLOAD_ERR_OK:
                break;
            case UPLOAD_ERR_NO_FILE:
                throw new RuntimeException('No file was sent.');
            case UPLOAD_ERR_INI_SIZE:
            case UPLOAD_ERR_FORM_SIZE:
                throw new RuntimeException('This uploaded file exceeded file size limit. You have to update "upload_max_filesize" and "upload_max_filesize" in your php.ini file');
            default:
                throw new RuntimeException("Unknown error : $file_upload_error_type");
        }

        $content = file_get_contents($_FILES['uploaded_json']['tmp_name']);

        $json = json_decode($content, true);
        if (is_array($json)) {
            $dal = new DAL();
            $rec_count = $dal->create_campaigns($json)->getInsertedCount();
            $total_rec_count = $dal->get_campaign_count();
            echo "<label>$rec_count campaigns were imported successfully, total campaign count now is $total_rec_count.</label><br/>";

        } else {
            echo 'JSON file was in a wrong format';
        }
    } catch (RuntimeException $e) {
        echo $e->getMessage();
    }
} else {
    echo "<label>please select a JSON file to import</label><br/>";
}

echo    '<br/>';
echo    '<input type="file" accept="application/json" name="uploaded_json" id="uploaded_json" />';
echo    '<input type="submit" value"import" />';
echo '</form>';

?>