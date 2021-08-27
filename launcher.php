<?php

    //Load initial configs for .htaccess and handlers
    include("./server/manager.php");
    include("./server/dotenv.php");

    $router = new Router();
    include("./routes.php");

    //Try to make database table migrations
    
    try {
        $dotenv = new Env();

        $db = mysqli_connect($dotenv->MYSQLI_HOST, $dotenv->MYSQLI_USER, $dotenv->MYSQLI_PASSWORD, $dotenv->MYSQLI_DATABASE);

        $query = "";

        foreach (glob("./migrations/*.sql") as $sql) {
            $query .= file_get_contents($sql);
        }
        if (trim($query) != "") {
            mysqli_query($db, $query);
        }
    }
    catch (Exception $ex) {
        //query inexistent
    }
    //Populate routes

    /*
    -----------------------------------
    <Welcome to the Restblaze launcher>
    -----------------------------------
    */
    echo 'App Setup Done!';
?>