<?php
//URL:/api/update//<APIId>/<table>/<rowId>?field1=234&field2=kkkkk


$params = $_REQUEST;

list($apid,$resource,$id) = getURLParts();

$apid = intval($apid);
$resource = intval($resource);
$id = intval($id);

$table = "table_" . $apid . "_list_" . $resource;
    
$query = "UPDATE `$table` SET ";

foreach ($params as $key => $value) {
        $parts[] = "`" . $key . "` = :$key";
    }
    
$sql = $query . implode(",", $parts) . " WHERE id = ".$id;
    

$db = PDOMySQL::getInstance();
$db->prepare($sql, $params)->execute();

print "{true}";