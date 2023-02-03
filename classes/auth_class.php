
<?php

require_once 'DBConnection.php';
require_once 'responses_class.php';

class Auth extends DBConnection{

    // login into api
    public function login($json){

        $_response = new Responses;

        // Decode json in array
        $jsonData = json_decode($json, true);

        var_dump($jsonData);

        // Check user an password exists
        if(isset($jsonData['user']) && isset($jsonData['password'])){

            // Extract json data
            $user = $jsonData['user'];
            $password = $jsonData['password'];

            // Get user data
            $userData = $this->getUserData($user);

            // If exists user data
            if($userData){

                // Check password is the same as the database
                if(parent::encrypt($password) === $userData[0]['Password']){

                    // Check user is active
                    if($userData[0]['Estado'] === 'Activo'){

                        // Get token
                        $verifyToken = $this->setUserToken($userData[0]['UsuarioId']);

                        // Check token
                        if($verifyToken){

                            // Get response data array
                            $result = $_response->responseData;

                            // Add token to result
                            $result['result'] = [
                                'token' => $verifyToken
                            ];

                            // Return result
                            return $result;

                        }else{

                            // Internal error
                            return $_responses->error_500("Internal error, we couldn't save");

                        }
                        
                    }else{

                        // Error inactive user
                        return $_response->error_200("Inactive user");

                    }
                    
                }else{

                    // Error invalid pass
                    return $_response->error_200("Invalid password");
                }

            }else{

                // User not exist
                return $_response->error_200("User $user not exist");

            }

        }else{

            // Show error data sent
            return $_response->error_400();

        }

    }

    private function getUserData($user){
        
        $data = parent::getData("SELECT UsuarioId, Password, Estado FROM usuarios WHERE Usuario = '$user'");

        if(isset($data[0]['UsuarioId']))
            return $data;
        
        return false;

    }

    public function setUserToken($userId){

        // Get token info
        [$userId, $token, $estate, $date] = parent::genUserToken($userId);
        
        // Set token to user
        $verify = parent::setData(
            "INSERT INTO usuarios_token (UsuarioId, Token, Estado, Fecha)
            VALUES('$userId', '$token', '$estate', '$date')");

        // Return token if is insert, false if isn't insert
        return $verify ? $token : false;
        
    }

}


?>