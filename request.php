<?php
include_once 'config.php';

$action = filter_var(trim($_REQUEST['action']), FILTER_SANITIZE_STRING);
$bucketName = "website-static";
$cloudPath = "uploads/";
if ($action == 'upload') {
    // $v = $_GET['v'];
    // $response['code'] = "200";

    // $cloudPath = "uploads/v$v.txt";

    // $fileContent = file_get_contents("v$v.txt");

    // $isSucceed = uploadFile($bucketName, $fileContent, $cloudPath);
    // if ($isSucceed == true) {
    //     $response['msg'] = 'SUCCESS: to upload ' . $cloudPath . PHP_EOL;
    //     // TEST: get object detail (filesize, contentType, updated [date], etc.)
    //     $response['data'] = getFileInfo($bucketName, $cloudPath);
    // } else {
    //     $response['code'] = "201";
    //     $response['msg'] = 'FAILED: to upload ' . $cloudPath . PHP_EOL;
    // }
    // header("Content-Type:application/json");
    // echo json_encode($response);
    // exit();
    $response['code'] = "200";
    if ($_FILES['file']['error'] != 4) {
        //set which bucket to work in
        // get local file for upload testing
        $fileContent = file_get_contents($_FILES["file"]["tmp_name"]);
        // NOTE: if 'folder' or 'tree' is not exist then it will be automatically created !
        $cloudPath = 'uploads/' . $_FILES["file"]["name"];
 
        $isSucceed = uploadFile($bucketName, $fileContent, $cloudPath);
 
        if ($isSucceed == true) {
            $response['msg'] = 'SUCCESS: to upload ' . $cloudPath . PHP_EOL;
            // TEST: get object detail (filesize, contentType, updated [date], etc.)
            $response['data'] = getFileInfo($bucketName, $cloudPath);
        } else {
            $response['code'] = "201";
            $response['msg'] = 'FAILED: to upload ' . $cloudPath . PHP_EOL;
        }
    }
    header("Content-Type:application/json");
    echo json_encode($response);
    exit();


    // if ($_FILES['file']['error'] != 4) {
    //     //set which bucket to work in
    //     $bucketName = "cloud-test-bucket-1";
    //     // get local file for upload testing
    //     $fileContent = file_get_contents($_FILES["file"]["tmp_name"]);
    //     // NOTE: if 'folder' or 'tree' is not exist then it will be automatically created !
    //     $cloudPath = 'uploads/' . $_FILES["file"]["name"];

    //     $isSucceed = uploadFile($bucketName, $fileContent, $cloudPath);

    //     if ($isSucceed == true) {
    //         $response['msg'] = 'SUCCESS: to upload ' . $cloudPath . PHP_EOL;
    //         // TEST: get object detail (filesize, contentType, updated [date], etc.)
    //         $response['data'] = getFileInfo($bucketName, $cloudPath);
    //     } else {
    //         $response['code'] = "201";
    //         $response['msg'] = 'FAILED: to upload ' . $cloudPath . PHP_EOL;
    //     }
    // }
    // header("Content-Type:application/json");
    // echo json_encode($response);
    // exit();
} elseif($action == "list") {
    try {
        $files = listFiles($bucketName, $cloudPath);

        $response['success'] = true;
        $response['data'] = $files;
    } catch (\Throwable $th) {
        $response['data'] = [];
        $response['success'] = false;
    }

    header("Content-Type:application/json");
    echo json_encode($response);

}
?>