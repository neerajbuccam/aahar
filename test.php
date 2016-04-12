<?php

include_once './DbConnect.php';
include_once './img_upload.php';

global $upload;

$text = $_POST['firstname'];

$fp = fopen("test.txt", 'w');
fwrite($fp, $text);
fclose($fp);

$upload->uploadImage($_POST['image'], "Rest_Images", "test image", 11);

?>