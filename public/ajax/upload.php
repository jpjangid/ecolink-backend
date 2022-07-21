<?php
/*
    Created by Tomaac (https://github.com/tomaac)
    2019.
    
    Updated by Axel Hardy (https://axelhardy.com/en)
    2020.
*/

// -- Include the upload.class.php
include("../includes/upload.class.php");

// -- Change this to your uploads folder
// This folder needs to have write access
define('UPLOAD_PATH', '../uploads/');

// -- Your website URL
//define('URL', 'https://axelhardy.com/dev/summernote-ajax-upload/');
$URL = '//' . $_SERVER['HTTP_HOST'];
define('URL', $URL);

$resp_mes = '';
$resp = '';

// If a file is present...
if (isset($_FILES["file"]["type"])) {

    $photo = $_FILES["file"];

    if (!empty($photo) && $photo["size"] > 0) {

        // Generate a unique name for the directory
        $unique_photo_name = sha1(time() . mt_rand(0, 99999));

        $handle = new Upload($photo);
        $photo_mime_type = $handle->file_src_mime;

        if ($handle->uploaded) {
            $complete_dir_name = UPLOAD_PATH;

            // Parameters before uploading the photo
            $handle->image_resize = false;
            $handle->png_compression = 8;
            $handle->webp_quality = 80;
            $handle->jpeg_quality = 80;
            $handle->file_new_name_body = $unique_photo_name;

            $handle->Process($complete_dir_name);

            if ($handle->processed) {

                $handle->clean();

                $photo_url = $complete_dir_name . $handle->file_dst_name;
                $photo_url_path = str_replace("../", "", $photo_url);

                $resp = URL . "/" . $photo_url_path; // gives back image name for editor
                $resp_mes = 'ok';
            } else {
                $resp_mes = 'Error: Could not upload the file...';
            }
        } else {
            $resp_mes = 'Error: Could not upload the file...';
        }
    }
} else {
    $resp_mes = 'Error: Please pick a file!';
}


// Send response back to javascript
echo json_encode(array(
    'response' => $resp,
    'message' => $resp_mes
));
