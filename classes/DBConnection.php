
<?php 

require_once 'jwt.php';

class DBConnection{

    private $host;
    private $user;
    private $password;
    private $database;
    private $cn;

    function __construct(){

        // get data conecction of config.json file
        $data = $this->dataConnection();

        $this->host = $data['host'];
        $this->user = $data['user'];
        $this->password = $data['password'];
        $this->database = $data['database'];

        try {

            // mysql info
            $dsn = "mysql:host=$this->host;dbname=$this->database";

            // get connection to mysql with pdo
            $this->cn = new PDO($dsn, $this->user, $this->password);

            // set connection attributtes
            $this->cn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        } catch (PDOException $e){

            // If PDOException, show error message
            echo $e->getMessage();

        }

    }

    // Return config.json data in array
    private function dataConnection(){

        $jsonData = file_get_contents(dirname(__FILE__) . '/config.json');

        return json_decode($jsonData, true);

    }

    // Convert characters to UTF8 encoding
    private function convertUTF8($array){

        array_walk_recursive($array, function(&$item, $key){

            if(!mb_detect_encoding($item, 'utf-8', true))
                $item = utf8_encode($item);

        });

        return $array;

    }

    // Return data of the database in array
    public function getData($sql){

        try{

            // get data of database
            $data = $this->cn->query($sql)->fetchAll(PDO::FETCH_ASSOC);

            return $this->convertUTF8($data);

        }catch(PDOException $e){
            
            echo $e->getMessage();

        }

    }

    // Return number of rows affected in the sql statment
    public function setData($sql){

        try{

            $stmt = $this->cn->query($sql);

            return $stmt->rowCount();

        }catch(PDOException $e){
            
            echo $e->getMessage();

        }

    }

    // Return last insert id in the sql statment
    public function setDataId($sql){

        try{

            

            $stmt = $this->cn->query($sql);

            $rows = $stmt->rowCount();

            if($rows >= 1)
                return $this->cn->lastInsertId();
            
            return false;

        }catch(PDOException $e){
            
            echo $e->getMessage();

        }

    }

    // Encrypt password
    protected function encrypt($password){

        return md5($password);

    }

    // Generate Token and return [UserId. Token, Estate, Date]
    public function genUserToken($userId){

        $serverKey = '5f2b5cdbe5194f10b3241568fe4e2b24';

        $payloadArray = ['userId' => $userId];

        // Current time
        $nbf = date("Y-m-d H:i");

        // Current time + 1 day
        //$exp = $nbf + (60*60*24);

        if (isset($nbf)) {$payloadArray['nbf'] = $nbf;}
        //if (isset($exp)) {$payloadArray['exp'] = $exp;}

        // Encode and get jwt
        $token = JWT::encode($payloadArray, $serverKey);
        
        return [$userId,$token,'Active',$nbf];

    }

}
