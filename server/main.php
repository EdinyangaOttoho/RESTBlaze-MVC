<?php

    $named_routes = [];
    $unnamed_routes = [];

    include("./manager.php");

    $router = new Router();
    include("../routes.php");

    $method = strtolower($_SERVER["REQUEST_METHOD"]); //Request method
    $req_method = strtolower($_GET["restblaze_method"]); //Required method

    $function = $_GET["restblaze_func"]; //Controller class & method
    $opts = $_GET["restblaze_opts"]; //Opts (where applicable)
    
    $opt_array = [];
    $options = [];
    $params = "";

    if ($opts != "") {
        if (strpos($opts, "<!---[RB]---!>") !== false) {
            $opt_array = explode("<!---[RB]---!>", $opts); //Split by special character
        }
        else {
            $opt_array[0] = $opts;
        }
    }

    $controller_handler = explode("@", $function); //Split controller class & method

    $class = ucwords(strtolower($controller_handler[0])); //Get class
    $func = $controller_handler[1]; //Get method

    $file = $class.'.php'; //File name

    include("./dotenv.php");

    include("./exceptions.php");

    class Database {
        public $query;
        public $db;
        public function __construct() {
            $env = new Env();
            $this->db = mysqli_connect($env->MYSQLI_HOST, $env->MYSQLI_USER, $env->MYSQLI_PASSWORD, $env->MYSQLI_DATABASE);
            return $this->db;
        }
        public function query($str, $params=[]) {
            function buildquery($x, $y, $db) {
                $x = str_replace("'", "", $x);
                preg_match_all("/\?/", $x, $matches);
                $count = count($matches[0]);
                if (count($y) != $count) {
                    throw new DBUnmatchedParams("The parameters for query do not match the number of placeholders!");
                }
                else {
                    $cnt = 0;
                    while (strpos($x, "?") !== false) {
                        $index = strpos($x, "?");
                        
                        $start = substr($x, 0, $index)."'";
                        $mid = mysqli_real_escape_string($db, $y[$cnt]);
                        $end = "'".substr($x, $index+1, strlen($x)-$index);

                        $complete = $start.$mid.$end;
                        $x = $complete;
                        $cnt++;
                    }
                }
                return $x;
            }
            $str = buildquery($str, $params, $this->db);
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
        public function view($name, $data=[]) {
            $name = str_replace(".", "/", $name);
            $directory = "../views/".$name.".php"; //Fit/format view directory
            if (!file_exists($directory)) {
                throw new ViewNotFound("File or directory not found for view!");
            }
            foreach ($data as $_restblaze_key_=>$_restblaze_value_) {
                if (is_numeric($_restblaze_key_)) {
                    throw new InvalidRouteData("Invalid parameter used for route data!");
                }
                $$_restblaze_key_ = $_restblaze_value_;
            }
            include($directory); //Include view and pass data
        }
        public function redirect($route) {
            global $named_routes;
            global $unnamed_routes;
            $redirect = "";
            if (preg_match("/^\:{2}/", $route)) {
                $index = preg_replace("/^\:{2}/", "", $route);
                if (isset($named_routes[$index])) {
                    $route = $named_routes[$index];
                    $redirect = ($route[0] == "/")?$route:"/".$route;
                }
                else {
                    throw new InvalidRedirectPointer("Invalid root alias!");
                }
            }
            else {
                if ($route !== "/") {
                    $route = preg_replace("/^\//", "", $route);
                }
                if (in_array($route, $unnamed_routes)) {
                    $redirect = ($route[0] == "/")?$route:"/".$route;
                }
                else {
                    throw new InvalidRedirectPointer("Invalid route URL, $route");
                }
            }
            header("Location:$redirect");
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

        call_user_func_array(array($controller, $func), $opt_array);

    }
    else {
        $expected = strtoupper($req_method);
        echo "Invalid request method. Expected: $expected!";
    }
?>