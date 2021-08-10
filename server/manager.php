<?php
    class Router {
        public $urls = [];
        public function __construct() {
            file_put_contents(".htaccess", "RewriteEngine On"); //initialize mod_rewrite
        }
        public function prefigs($route, $function, $method) {

            $htaccess = file_get_contents(".htaccess"); //Read .htaccess

            preg_match_all("/\{[a-zA-Z0-9_]+\}/", $route, $match_array); //match named parameters e.g. {userid}

            $matches = $match_array[0]; //matches array

            $m = [];
            $cnt = 0;

            foreach ($matches as $i) {
                $cnt++;
                $str = preg_replace("/(\{|\})/", "", $i); //strip off curly braces
                $route = str_replace($i, "(.*)", $route);
                array_push($m, "$$cnt");
            }

            $opts = implode(",", $m); //join params via comma

            /* Htaccess rewrite block
                Redirect params to request handler
            */
            if ($route == "" || $route == "/") {
                //entry (main) route?
                file_put_contents(".htaccess", $htaccess." \nRewriteRule ^$ /server/main.php?restblaze_func=$function&restblaze_method=$method&restblaze_opts=$opts [QSA,L]");
            }
            else {
                //Other routes
                file_put_contents(".htaccess", $htaccess."\nRewriteRule $route /server/main.php?restblaze_func=$function&restblaze_method=$method&restblaze_opts=$opts [QSA,L]");
            }
        }
        static function get($route, $function) {
            $router = new Router();
            $router->prefigs($route, $function, "get"); //GET
        }
        static function post($route, $function) {
            $router = new Router();
            $router->prefigs($route, $function, "post"); //POST
        }
    }
    $router = new Router();
    include("./routes.php");
?>