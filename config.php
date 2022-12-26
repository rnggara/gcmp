<?php

// load GCS library
require_once 'vendor/autoload.php';

use Google\Cloud\Storage\StorageClient;

// Please use your own private key (JSON file content) which was downloaded in step 3 and copy it here
// your private key JSON structure should be similar like dummy value below.
// WARNING: this is only for QUICK TESTING to verify whether private key is valid (working) or not.  
// NOTE: to create private key JSON file: https://console.cloud.google.com/apis/credentials  
$privateKeyFileContent = '{
    "type": "service_account",
    "project_id": "hikcentral-371808",
    "private_key_id": "9623c3f958501a9f35b9a36bf49d8ebef85a0967",
    "private_key": "-----BEGIN PRIVATE KEY-----\nMIIEvQIBADANBgkqhkiG9w0BAQEFAASCBKcwggSjAgEAAoIBAQDJO7HHcL9peM/Z\n+r37SybBsLVjxYhOB18JOz9Owa2AtFlqM2ZDAgpqnKwjKB1FR5ePq5rjigC5T7wC\nyDUhic7ua8X/FziiC4orPZzOVzBofLvwDSDpFEE/hyQrNawkNZZ5hFoEajSKBwb7\nR1dctn659dtRgA4hs7Z6qkJepNo+oDhsGWePci4APkos7OF7W8l0JQNP3cCT4n0f\nTIKJ9BQPKqmEY4wF45PbnorDZe6XeqDiCK7pmNsnviJe+7Ub9h5RIE3R6H3HzJPE\nPso+wPeV8bvM6MIa63YCSckPMVLhNrLm7D5BIRLUCLgGpkIrbK9apr0ECX+nTxAN\nRRTnI53tAgMBAAECggEAIkMyMVF+ncAur6AUMdGpXygtsjejThnOMh2u2UN7vaK3\naLAotLcHvABCBNhjJ3UZlqIyxJQuLF8plVWuSjATFiAufZDgGSMa2uLqLG3G9btr\nKmOp21WSCHWDkIHade3T2YvR4deNa4TnyOfsNYJzEEmOlpFVlqJpKviLRV8PM8M5\nmumNGyjQhLn4nT/xb3ndUnMKaZAJyPt2SBHo0r2bpgA0+weXklVsUohGSwmNWqQE\nz48leCocJOrtXxDXIgpAnqjGNUSqrMInGqP8l0opgP22a1bxb+P136cIt75q0YRO\n4QnYOPgH0c6VyDlom/h7iMUuFkmzMnSPpggm/F5skQKBgQDm2dUFrzpXrH8IGTzr\n9LGcw5ETIfroUurB33QoExrBpcP06E4xwZSUaFyebGAfvwWjM0n795pU5co45KVI\n+JZhXMqxB01S2ByvxqsdIVjzuV3jlLR3z6Qljy4s1t6NkuyFhEZfHBahmL+lhTTx\nj//iw4nB0Wthd53rdPHP3UsvJQKBgQDfJ9ua57vGALstlaFgBn3U/2YXa19JVc4S\n7A5WtbNVDx2cu6N/9mWsLsXhBbUxF0OY/No6m0hVb6PztdJc4meI5pVBzUghQaPw\n3aaZIyui1fQoZrMemsOV+SZFFFABOIPGpHa+E+59/bvcfiJ0fc34dhsjPnXvYXhz\nVJH4XYR9KQKBgAMVB3YS99lx0SRfieOwmap5Jfe2bW4qpT2/aQKb9rB9MReU/m5F\nomE033+x/LqPx1h9d9BoZuQZSVDnJJnLz94u6fnhGhQwFHn4UhKKfnCKmglO0/YB\njLR/q+MnX0NOod/Ke1ILwvWXX3+rPqC3BniVmcI/tIpRmYcqQYw/7SANAoGAWBI5\n+JvmntGhDe+U/fnx5YNavnlw5NeJeixRyGTzvuk1Tas8bv9Gxzq6fAGtrg85bYK1\nehXY6WcjNMcYm/H63KvsUkj7Y6ytUB2aZ6vax+xa7SsDHFwGPwVS1kabALBWSaqU\n9pWVNYJTh0T8wi74gEvUkAdRskUsrY++AGCgLmkCgYEArexgdyczc2eZr3+yaFae\nLGZUVbrRtVjkjc5cqf0z4eY4nyP3p6mfT99hmsVn+l87FPDsY57NMM7ae3K1x/bD\nHbjHdYrs76u3cIxQkPzsfxAAbq4rKB6HS2M9MR3dm9FBtssSbOHn70sfYTY/bdYP\nKCbL4IjKEAb0kYGlgHvAFJU=\n-----END PRIVATE KEY-----\n",
    "client_email": "test-upload@hikcentral-371808.iam.gserviceaccount.com",
    "client_id": "111801828351741190029",
    "auth_uri": "https://accounts.google.com/o/oauth2/auth",
    "token_uri": "https://oauth2.googleapis.com/token",
    "auth_provider_x509_cert_url": "https://www.googleapis.com/oauth2/v1/certs",
    "client_x509_cert_url": "https://www.googleapis.com/robot/v1/metadata/x509/test-upload%40hikcentral-371808.iam.gserviceaccount.com"
    }';

/*
 * NOTE: if the server is a shared hosting by third party company then private key should not be stored as a file,
 * may be better to encrypt the private key value then store the 'encrypted private key' value as string in database,
 * so every time before use the private key we can get a user-input (from UI) to get password to decrypt it.
 */

function uploadFile($bucketName, $fileContent, $cloudPath) {
    $privateKeyFileContent = $GLOBALS['privateKeyFileContent'];
    // connect to Google Cloud Storage using private key as authentication
    try {
        $storage = new StorageClient([
            'keyFile' => json_decode($privateKeyFileContent, true)
        ]);
    } catch (Exception $e) {
        // maybe invalid private key ?
        print $e;
        return false;
    }

    // set which bucket to work in
    $bucket = $storage->bucket($bucketName);

    // upload/replace file 
    $storageObject = $bucket->upload(
            $fileContent,
            ['name' => $cloudPath]
            // if $cloudPath is existed then will be overwrite without confirmation
            // NOTE: 
            // a. do not put prefix '/', '/' is a separate folder name  !!
            // b. private key MUST have 'storage.objects.delete' permission if want to replace file !
    );

    // is it succeed ?
    return $storageObject != null;
}

function getFileInfo($bucketName, $cloudPath) {
    $privateKeyFileContent = $GLOBALS['privateKeyFileContent'];
    // connect to Google Cloud Storage using private key as authentication
    try {
        $storage = new StorageClient([
            'keyFile' => json_decode($privateKeyFileContent, true)
        ]);
    } catch (Exception $e) {
        // maybe invalid private key ?
        print $e;
        return false;
    }

    // set which bucket to work in
    $bucket = $storage->bucket($bucketName);
    $object = $bucket->object($cloudPath);
    return $object->info();
}
//this (listFiles) method not used in this example but you may use according to your need 
function listFiles($bucketName, $directory = null) {
    $privateKeyFileContent = $GLOBALS['privateKeyFileContent'];
    // connect to Google Cloud Storage using private key as authentication
    try {
        $storage = new StorageClient([
            'keyFile' => json_decode($privateKeyFileContent, true)
        ]);
    } catch (Exception $e) {
        // maybe invalid private key ?
        print $e;
        return false;
    }

    // set which bucket to work in
    $bucket = $storage->bucket($bucketName);

    if ($directory == null) {
        // list all files
        $objects = $bucket->objects();
    } else {
        // list all files within a directory (sub-directory)
        $options = array('prefix' => $directory);
        $objects = $bucket->objects($options);
    }

    $response = [];

    foreach ($objects as $object) {
        $response[] = $object->info();
        // print json_encode($object->info(), true) . PHP_EOL;
        // NOTE: if $object->name() ends with '/' then it is a 'folder'
    }

    return $response;
}