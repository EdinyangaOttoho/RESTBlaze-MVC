<?php
    class Router {
        public $urls = [];
        public function __construct() {
            file_put_contents(".htaccess", "RewriteEngine On");
        }
        public static function get($route, $function) {
            $htaccess = file_get_contents(".htaccess");
            $route = preg_replace("/^\/+/", "", $route);
            preg_match_all("/\{[a-zA-Z0-9]+\}/", $route, $match_array);
            $matches = $match_array[0];
            $m = [];
            $cnt = 0;
            foreach ($matches as $i) {
                $cnt++;
                $str = str_replace("{", "", str_replace("}", "", $i));
                $route = str_replace($i, "(.*)", $route);
                array_push($m, "$$cnt");
            }
            $opts = implode(",", $m);
            if ($route == "" || $route == "/") {
                file_put_contents(".htaccess", $htaccess." \nRewriteRule ^$ /server/main.php?restblaze_func=$function&restblaze_method=get&restblaze_opts=$opts [QSA,L]");
            }
            else {
                file_put_contents(".htaccess", $htaccess."\nRewriteRule $route /server/main.php?restblaze_func=$function&restblaze_method=get&restblaze_opts=$opts [QSA,L]");
            }
        }
        public static function post($route, $function) {
            $htaccess = file_get_contents(".htaccess");
            $route = preg_replace("/^\/+/", "", $route);
            preg_match_all("/\{[a-zA-Z0-9]+\}/", $route, $match_array);
            $matches = $match_array[0];
            $m = [];
            $cnt = 0;
            foreach ($matches as $i) {
                $cnt++;
                $str = str_replace("{", "", str_replace("}", "", $i));
                $route = str_replace($i, "(.*)", $route);
                array_push($m, "$$cnt");
            }
            $opts = implode(",", $m);
            if ($route == "" || $route == "/") {
                file_put_contents(".htaccess", $htaccess." \nRewriteRule ^$ /server/main.php?restblaze_func=$function&restblaze_method=post&restblaze_opts=$opts [QSA,L]");
            }
            else {
                file_put_contents(".htaccess", $htaccess."\nRewriteRule $route /server/main.php?restblaze_func=$function&restblaze_method=post&restblaze_opts=$opts [QSA,L]");
            }
        }
    }
    $router = new Router();
    include("./routes.php");
?>