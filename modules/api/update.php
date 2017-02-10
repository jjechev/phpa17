<?php
//URL:/api/update//<APIId>/<table>/<rowId>?field1=234&field2=kkkkk


$params = $_REQUEST;

list($apid,$resource,$id) = getURLParts();

$apid = mysql_real_escape_string($apid);
$table = mysql_real_escape_string($table);

$table = "table_" . $apid . "_" . $resource;
    
$query = "UPDATE `$table` SET ";

foreach ($params as $key => $value) {
        $parts[] = "`" . $key . "` = '".mysql_real_escape_string($value)."'";
    }
    
$sql = $query . implode(",", $parts) . " WHERE id = ".(int)$id;
    
//Crating an statement
//$stmt = $this->con->prepare("INSERT INTO students(".$columns.") values(?, ?, ?, ?)");
$stmt = db()->prepare($sql);

if (false === $stmt){
    die(htmlspecialchars($stmt->error));
}

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
//    var_dump( db()->errors);
}