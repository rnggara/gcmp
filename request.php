<?php
include_once 'config.php';

$action = filter_var(trim($_REQUEST['action']), FILTER_SANITIZE_STRING);
$bucketName = "website-static";
$cloudPath = "uploads/";
if ($action == 'upload') {
    $response['code'] = "200";

    $directory = 'videos';
    $scanned_directory = array_diff(scandir($directory), array('..', '.'));

    try {
        foreach($scanned_directory as $item){
            $cloudPath = "uploads/$item";
    
            $fileContent = file_get_contents("videos/$item");
    
            $isSucceed = uploadFile($bucketName, $fileContent, $cloudPath);
            if ($isSucceed == true) {
                $response['msg'] = 'SUCCESS: to upload ' . $cloudPath . PHP_EOL;
                // TEST: get object detail (filesize, contentType, updated [date], etc.)
                $response['data'][] = getFileInfo($bucketName, $cloudPath);
            } else {
                $response['code'] = "201";
                $response['msg'] = 'FAILED: to upload ' . $cloudPath . PHP_EOL;
            }
        }
    } catch (\Throwable $th) {
        $response['code'] = "201";
        $response['msg'] = $th->getMessage();
    }

    header("Content-Type:application/json");
    echo json_encode($response);
    exit();
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