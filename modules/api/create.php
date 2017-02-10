<?php

// URL: /api/create/<APIId>/<table>?field1=234&field2=abcd
// URL: /api/create/A12/155?field1=234&field2=abcd

$params = $_REQUEST;

list($apid,$table) = getURLParts();

$apid = mysql_real_escape_string($apid);
$table = mysql_real_escape_string($table);

//$apid = "A12";
//$table = "155";


$columns = implode(", ", array_keys($params));
$escaped_values = array_map('mysql_real_escape_string', array_values($params));
$placeholders =  substr(str_repeat('?,', count($params)),-1);
$values = "'".implode("', '", $escaped_values)."'";
$sql = "INSERT INTO `table_" . $apid . "_" . $table . "` ($columns) VALUES ($values)";

//Crating an statement
//$stmt = $this->con->prepare("INSERT INTO students(".$columns.") values(?, ?, ?, ?)");
$stmt = db()->prepare($sql);

if (false === $stmt){
    die(htmlspecialchars($stmt->error));
}

//Binding the parameters
//$stmt->bind_param("ssss", $name, $username, $password, $apikey);

//Executing the statment
$result = $stmt->execute();

//Closing the statment
$stmt->close();

//If statment executed successfully
if ($result) {
    //Returning true means query created successfully
    echo "true";
} else {
    //Returning false means query failed to create 
    echo "false";
    var_dump( db()->errors);
}