<?php

require_once 'DBConnection.php';
require_once 'responses_class.php';

class patients extends DBConnection{

    private $_response;

    private $table = 'pacientes';

    private $token;

    private $name;
    private $dni;
    private $mail;
    
    private $patientId;
    private $address;
    private $postalCode;
    private $gender;
    private $phone;
    private $birthDate;

    // Get patient list in pages
    public function patientsList($page = 1){

        $init = 0;

        $amount = 100;

        if($page > 1){

            $init = ($amount * ($page - 1) + 1);

            $amount = $amount * $page;

        }

        return parent::getData("SELECT PacienteId, Nombre, DNI, Telefono, Correo FROM pacientes limit $init,$amount");

    }

    // Get patient data from 
    public function getPatient($patientId){

        // Check valid token
        $validToken = $this->checkToken($data);

        // Token parameter not exists
        if(isset($validToken['result']['error_id']))
            return $validToken;

        return parent::getData("SELECT * FROM pacientes WHERE PacienteId = '$patientId'");

    }

    private function getJsonData($jsonData, $validFields){

        $this->_response = new Responses;

        $data = json_decode($jsonData, true);

        // Check valid token
        $validToken = $this->checkToken($data);

        // Token parameter not exists
        if(isset($validToken['result']['error_id']))
            return $validToken;

        // Check parameters
        switch ($validFields) {

            case 'POST':
                
                if(!isset($data['name']) || !isset($data['dni']) || !isset($data['mail']))
                    return $this->_response->error_400();
                else

                $this->name = $data['name'];
                $this->dni = $data['dni'];
                $this->mail = $data['mail'];
                $this->address = $data['address'] ?? "";
                $this->postalCode = $data['postalCode'] ?? "";
                $this->gender = $data['gender'] ?? "";
                $this->phone = $data['phone'] ?? "";
                $this->birthDate = $data['birthDate'] ?? "1989-02-03";

                break;
            
            case 'PUT':

                if(!isset($data['patientId']))
                    return $this->_response->error_400();

                $this->patientId = $data['patientId'];
                $this->name = $data['name'] ?? "";
                $this->dni = $data['dni'] ?? "";
                $this->mail = $data['mail'] ?? "";
                $this->address = $data['address'] ?? "";
                $this->postalCode = $data['postalCode'] ?? "";
                $this->gender = $data['gender'] ?? "";
                $this->phone = $data['phone'] ?? "";
                $this->birthDate = $data['birthDate'] ?? "1989-02-03";

                break;

            case 'DELETE':
                
                if(!isset($data['patientId']))
                    return $this->_response->error_400();

                $this->patientId = $data['patientId'];

                break;

            default:
                die();
                break;

        }

    }

    public function setPatient($jsonData){

        $this->_response = new Responses;

        $validData = $this->getJsonData($jsonData, 'POST');

        // Check errors getting json data
        if(isset($validData['result']['error_id']))
            return $validData;

        // Get database data
        $lastPatientId = parent::setDataId(
            "INSERT INTO $this->table (DNI, Nombre, Direccion, CodigoPostal, Telefono, Genero, FechaNacimiento, Correo)
            VALUES ('$this->dni', '$this->name', '$this->address', '$this->postalCode', '$this->phone', '$this->gender', '$this->birthDate', '$this->mail')");

        // Check database data
        if($lastPatientId){

            $responseData = $this->_response->responseData;

            // Return result in response
            $responseData['result'] = [
                'patientId' => $lastPatientId
            ];

            return $responseData;
        }

        return $this->_response->error_500();
    }

    public function updatePatient($jsonData){

        $this->_response = new Responses;

        $validData = $this->getJsonData($jsonData, 'PUT');

        // Check errors getting json data
        if(isset($validData['result']['error_id']))
            return $validData;

        // Get database data
        $resPatient = parent::setData(
            "UPDATE $this->table
            SET
                dni = '$this->dni',
                Nombre = '$this->name',
                Direccion = '$this->address',
                CodigoPostal = '$this->postalCode',
                Telefono = '$this->phone',
                Genero = '$this->gender',
                FechaNacimiento = '$this->birthDate',
                Correo = '$this->mail'
            WHERE PacienteId = '$this->patientId'");

        // Check database data
        if($resPatient){

            $responseData = $this->_response->responseData;

            // Return result in response
            $responseData['result'] = [
                'patientId' => $this->patientId
            ];

            return $responseData;
        }

        return $this->_response->error_500("Internal server error, The record may not exist");

    }

    public function deletePatient($jsonData){

        $this->_response = new Responses;

        $validData = $this->getJsonData($jsonData, 'DELETE');

        // Check errors getting json data
        if(isset($validData['result']['error_id']))
            return $validData; 

        // Get database data
        $resPatient = parent::setData(
            "DELETE FROM $this->table WHERE PacienteId = '$this->patientId'");

        // Check database data
        if($resPatient){

            $responseData = $this->_response->responseData;

            // Return result in response
            $responseData['result'] = [
                'patientId' => $this->patientId
            ];

            return $responseData;
        }

        return $this->_response->error_500("Internal server error, The record may not exist");

    }

    private function searchToken(){

        // Check token exists
        $resToken = parent::getData("SELECT TokenId, UsuarioId, Estado FROM usuarios_token WHERE Token = '$this->token' AND Estado = 'Active'");

        if($resToken)
            return $resToken;
        
        return false;

    }


    private function updateToken($tokenId){

        $date = date("Y-m-d H:i");

        // Set new token in database
        $resUpdateToken = parent::setData("UPDATE usuarios_token SET Fecha = '$date' WHERE TokenId = '$tokenId'");

        // Check errors
        if($resUpdateToken >= 1)

            return $resUpdateToken;
        
        return false;

    }

    private function checkToken($data){

        // Token doesn't exist
        if(!isset($data['token']))
            return $this->_response->error_401("Not authorized");

        $this->token = $data['token'];
        $arrayToken = $this->searchToken();

        // Invalid or expired token
        if(!$arrayToken)
            return $this->_response->error_401("Invalid or expired token");

    }

}