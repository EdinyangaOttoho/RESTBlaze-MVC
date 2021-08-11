<?php

    //Load initial configs for .htaccess and handlers
    include("./server/manager.php");
    include("./server/dotenv.php");

    $router = new Router();
    include("./routes.php");
    //Populate routes

    /*
    -----------------------------------
    <Welcome to the Restblaze launcher>
    -----------------------------------
    */
    echo 'App Setup Done!';
?>