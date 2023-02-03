
<?php

class Responses{

    public $responseData = [
        'status' => 'ok',
        'result' => []
    ];

    public function error_405(){

        $this->responseData['status'] = "error";

        $this->responseData['result'] = [
            "error_id" => "405",
            "error_msg" => "method not allowed"
        ];

        return $this->responseData;

    }

    public function error_200($msg = "Incorrect data"){

        $this->responseData['status'] = "error";

        $this->responseData['result'] = [
            "error_id" => "200",
            "error_msg" => $msg
        ];

        return $this->responseData;
        
    }
    
    public function error_400(){
        
        $this->responseData['status'] = "error";
        
        $this->responseData['result'] = [
            "error_id" => "400",
            "error_msg" => "Incomplete or incorrectly formatted data sent"
        ];
        
        return $this->responseData;

    }

    public function error_500($msg = "Internal server error"){
        
        $this->responseData['status'] = "error";
        
        $this->responseData['result'] = [
            "error_id" => "500",
            "error_msg" => $msg
        ];
        
        return $this->responseData;

    }

    public function error_401($msg = "No autorithed"){
        
        $this->responseData['status'] = "error";
        
        $this->responseData['result'] = [
            "error_id" => "401",
            "error_msg" => $msg
        ];
        
        return $this->responseData;

    }

}
