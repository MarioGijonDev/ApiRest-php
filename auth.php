
<?php

require_once 'classes/auth_class.php';
require_once 'classes/responses_class.php';

$_auth = new Auth;
$_response = new Responses;

// Check request methdo is post
if($_SERVER['REQUEST_METHOD'] === "POST"){

    // Get data of post
    $postBody = file_get_contents('php://input');

    // Login in the api
    $dataArray = $_auth->login($postBody);

    // json type
    header('Content-Type: applicaction/json');

    // Get response code
    $responseCode = $dataArray['result']['error_id'] ?? 200;

    // Show response of login
    echo json_encode($dataArray);

    // Send response code
    http_response_code($responseCode);

}else{

    // json type
    header('Content-Type: application/json');

    // show error data
    echo json_encode($_response->error_405());

}

