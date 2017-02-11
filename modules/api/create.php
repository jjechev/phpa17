<?php

// URL: /api/create/<APIId>/<table>?field1=234&field2=abcd
// URL: /api/create/A12/155?field1=234&field2=abcd

$params = $_REQUEST;

list($apid, $table) = getURLParts();

$apid = intval($apid);
$table = intval($table);

$placeholder = [];
foreach ($params as $key => $param) {
    $placeholder[] = ':' . $key;
}

$columns = implode(", ", array_keys($params));
$placeholders = implode(", ", $placeholder);
//$placeholders =  substr(str_repeat('?,', count($params)),0,-1);
//$values = "'".implode("', '", $escaped_values)."'";
$sql = "INSERT INTO `table_" . $apid . "_" . $table . "` ($columns) VALUES ($placeholders)";

$db = PDOMySQL::getInstance();

$db->prepare($sql, $params)->execute();

print "{true}";

/**

//Crating an statement
$stmt = db()->prepare($sql);

if (false === $stmt){
    die(htmlspecialchars($stmt->error));
}

//Binding the parameters

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
 * 
 * /*
 */
