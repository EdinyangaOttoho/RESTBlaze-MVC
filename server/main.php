<?php
    try {
        $method = strtolower($_SERVER["REQUEST_METHOD"]); //Request method
        $req_method = strtolower($_GET["restblaze_method"]); //Required method

        $function = $_GET["restblaze_func"]; //Controller class & method
        $opts = $_GET["restblaze_opts"]; //Opts (where applicable)
        
        $opt_array = [];
        $options = [];
        $params = "";

        if ($opts != "") {
            if (strpos($opts, ",") !== false) {
                $opt_array = explode(",", $opts);
            }
            else {
                $opt_array[0] = $opts;
            }
        }

        foreach ($opt_array as $i) {
            array_push($options, "'$i'");
        }

        $params = implode(", ", $options);

        $controller_handler = explode("@", $function); //Split controller class & method

        $class = ucwords(strtolower($controller_handler[0])); //Get class
        $func = $controller_handler[1]; //Get method

        $file = $class.'.php'; //File name

        include("./dotenv.php");

        class InvalidControllerMethod extends Exception {
            public function getErrorMessage() {
                //error message
                $errorMsg = 'Error on line '.$this->getLine().' in '.$this->getFile()
                .': '.$this->getMessage();
                return $errorMsg;
            }
        }
        class InvalidControllerClass extends Exception {
            public function getErrorMessage() {
                //error message
                $errorMsg = 'Error on line '.$this->getLine().' in '.$this->getFile()
                .': '.$this->getMessage();
                return $errorMsg;
            }
        }

        class Database {
            public function __construct() {
                $env = new Env();
                $this->db = mysqli_connect($env->MYSQLI_HOST, $env->MYSQLI_USER, $env->MYSQLI_PASSWORD, $env->MYSQLI_DATABASE);
                return $this->db;
            }
            public function query($str) {
                $this->query = mysqli_query($this->db, $str);
                return $this->query;
            }
            public function count() {
                return mysqli_num_rows($this->query);
            }
            public function results() {
                $results = [];
                while ($r = mysqli_fetch_array($this->query)) {
                    $row = (object) $r;
                    array_push($results, $row);
                }
                return $results;
            }
        }

        $db = new Database();
        $env = new Env();

        class RESTBlaze {
            public static function view($name, $data=[]) {
                $name = str_replace(".", "/", $name);
                include("../views/".$name.".php"); //Fit/format view directory
            }
        }

        if ($method == $req_method) {

            //Remove unnecessary params
            unset($_GET["restblaze_method"]);
            unset($_GET["restblaze_opts"]);
            unset($_GET["restblaze_func"]);
            //Remove unnecessary params
            
            $path_to_controller = "../controllers/".$file;
            
            if (!file_exists($path_to_controller)) {
                throw new InvalidControllerClass("No such class $class!");
            }

            include($path_to_controller);

            if (!class_exists($class)) {
                throw new InvalidControllerClass("No such class $class!");
            }
            $controller = new $class(); //create class instance of controller
            
            if ($method == "post") {
                $controller->request = (object) $_POST;
            }
            else {
                $controller->request = (object) $_GET;
            }

            $controller->files = $_FILES;
            $controller->env = $env;
            $controller->DB = $db;

            if (!method_exists($controller, $func)) {
                throw new InvalidControllerMethod("No such method, $func() declared in $class!");
            }

            $controller->$func($params);

        }
        else {
            $expected = strtoupper($req_method);
            echo "Invalid request method. Expected: $expected!";
        }
    }
    catch (Exception $ex) {
        echo $ex->getMessage();
    }
?>