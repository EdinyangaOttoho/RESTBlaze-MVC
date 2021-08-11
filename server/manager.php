<?php
    
    $named_routes = [];
    $unnamed_routes = [];

    class Router {
        public function prefigs($route, $function, $method, $name=0) {

            $htaccess = file_get_contents("/.htaccess"); //Read .htaccess

            $route = preg_replace("/^\//", "", $route);

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

            $opts = implode("<!---[RB]---!>", $m); //join params via special separator

            /* Htaccess rewrite block
                Redirect params to request handler
            */
            if ($route == "" || $route == "/") {
                $route = "/";
                //entry (main) route?
                file_put_contents("/.htaccess", $htaccess." \nRewriteRule ^$ /server/main.php?restblaze_func=$function&restblaze_method=$method&restblaze_opts=$opts [QSA,L]");
            }
            else {
                //Other routes
                file_put_contents("/.htaccess", $htaccess."\nRewriteRule $route /server/main.php?restblaze_func=$function&restblaze_method=$method&restblaze_opts=$opts [QSA,L]");
            }
            if ($name !== 0) {
                global $named_routes;
                $named_routes[$name] = $route;
            }
            else {
                global $unnamed_routes;
                array_push($unnamed_routes, $route);
            }
        }
        static function get($route, $function, $name=0) {
            $router = new Router();
            $router->prefigs($route, $function, "get", $name); //GET
        }
        static function post($route, $function, $name=0) {
            $router = new Router();
            $router->prefigs($route, $function, "post", $name); //POST
        }
    }
?>