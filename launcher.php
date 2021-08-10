<?php
    //clean .htaccess
    file_put_contents("./.htaccess", "");

    //Load initial configs for .htaccess and handlers
    include("./server/manager.php");
    include("./server/dotenv.php");
    /*
    -----------------------------------
    <Welcome to the Restblaze launcher>
    -----------------------------------
    */
    echo 'App Setup Done!';
?>