<?php

//Constants to connect with the database
define('DB_USERNAME', 'root');
define('DB_PASSWORD', '1234qazxcv');
define('DB_HOST', 'localhost');
define('DB_NAME', 'a17');


/** PDO MySQL */
PDOMySQL::setOption(
        array(
            "host" => 'localhost',
            "user" => 'root',
            "pass" => '1234qazxcv',
            "database" => 'a17',
            "charset" => "utf8"
        )
);