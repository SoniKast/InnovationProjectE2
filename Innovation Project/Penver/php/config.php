<?php

    define('DB_SERVER', 'localhost');
    define('DB_USERNAME', 'root');
    define('DB_PASSWORD', '');
    define('DB_DATABASE', 'pim');
    $db = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_DATABASE); 
    
    if(!$con)
    {
        die("Connection Error");
    }
?>