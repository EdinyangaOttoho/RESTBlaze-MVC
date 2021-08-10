<?php
    $method = strtolower($_SERVER["REQUEST_METHOD"]);
    $req_method = strtolower($_GET["restblaze_method"]);
    $function = $_GET["restblaze_func"];
    $opts = $_GET["restblaze_opts"];
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

    $controller_handler = explode("@", $function);
    $class = ucwords(strtolower($controller_handler[0]));
    $func = $controller_handler[1];
    $file = $class.'.php';

    include("./dotenv.php");

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
            include("../views/".$name.".php");
        }
    }

    if ($method == $req_method) {

        unset($_GET["restblaze_method"]);
        unset($_GET["restblaze_opts"]);
        unset($_GET["restblaze_func"]);
        
        include("../controllers/".$file);
        $call = '$controller = new '.$class.'();';
        if ($method == "post") {
            $call .= '$controller->request = (object) $_POST;';
        }
        else {
            $call .= '$controller->request = (object) $_GET;';
        }
        $call .= '$controller->files = $_FILES;';
        $call .= '$controller->env = $env;';
        $call .= '$controller->DB = $db;';
        $call .= '$controller->'.$func.'('.$params.');';
        eval($call);
    }
    else {
        $expected = strtoupper($req_method);
        echo "Invalid request method. Expected: $expected!";
    }
?>