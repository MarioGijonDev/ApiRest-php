<?php

require_once 'classes/responses_class.php';
require_once 'classes/patients_class.php';

$_response = new Responses;
$_patients = new Patients;

switch ($_SERVER['REQUEST_METHOD']) {

    case 'GET':

        // Set header with content type json
        header("Content-Type: application/json");

        if(isset($_GET['page'])){

            // Show patient list in json format
            echo json_encode($_patients->patientsList($_GET['page']));

        }else{

            if(isset($_GET['id'])){

                // Show patient data in json format
                echo json_encode($_patients->getPatient($_GET['id']));

            }

        }

        // Set response code
        http_response_code(200);

        break;
    
    case 'POST':

        // Get data from headers
        $headers = getallheaders();

        // Check data is from header or body
        if(isset($headers['token']) && isset($headers['name']) && isset($headers['dni']) && isset($headers['mail'])){

            $dataRecived = [
                'token' => $headers['token'],
                'name' => $headers['name'],
                'dni' => $headers['dni'],
                'mail' => $headers['mail']
            ];

            // Array to json
            $dataRecived = json_encode($dataRecived);

        }else{

            // Get body data
            $dataRecived = file_get_contents('php://input');

        }

        $dataArray = $_patients->setPatient($dataRecived);

        // json type
        header('Content-Type: applicaction/json');

        // Get response code
        $responseCode = $dataArray['result']['error_id'] ?? 200;

        // Show response of login
        echo json_encode($dataArray);

        // Send response code
        http_response_code($responseCode);



        break;

    case 'PUT':

        // Get data from headers
        $headers = getallheaders();

        // Check data is from header or body
        if(isset($headers['token']) && isset($headers['patientId'])){

            $dataRecived = [
                'token' => $headers['token'],
                'patientId' => $headers['patientId']
            ];

            // Array to json
            $dataRecived = json_encode($dataRecived);

        }else{

            // Get body data
            $dataRecived = file_get_contents('php://input');

        }

        $dataArray = $_patients->updatePatient($dataRecived);

        // json type
        header('Content-Type: applicaction/json');

        // Get response code
        $responseCode = $dataArray['result']['error_id'] ?? 200;

        // Show response of login
        echo json_encode($dataArray);

        // Send response code
        http_response_code($responseCode);

        break;

    case 'DELETE':

        // Get data from headers
        $headers = getallheaders();

        // Check data is from header or body
        if(isset($headers['token']) && isset($headers['patientId'])){

            $dataRecived = [
                'token' => $headers['token'],
                'patientId' => $headers['patientId']
            ];

            // Array to json
            $dataRecived = json_encode($dataRecived);

        }else{

            // Get body data
            $dataRecived = file_get_contents('php://input');

        }

        $dataArray = $_patients->deletePatient($dataRecived);

        // json type
        header('Content-Type: applicaction/json');

        // Get response code
        $responseCode = $dataArray['result']['error_id'] ?? 200;

        // Show response of login
        echo json_encode($dataArray);

        // Send response code
        http_response_code($responseCode);

        break;

    default:
        echo json_encode($_response->error_405());
        break;
}

