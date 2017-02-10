<?php

//URL: /api/read/<APIId>/<table>
//URL: /api/read/A12/155

$params = $_REQUEST;

list($apid, $table) = getURLParts();

$apid = mysql_real_escape_string($apid);
$table = mysql_real_escape_string($table);


$sql = "SELECT * FROM `table_" . $apid . "_" . $table."`";

$result = db()->query($sql);

if (!$result) {
  printf("Query failed: %s\n", db()->error);
  exit;
}  

$row = array();
while($row = $result->fetch_assoc()) {
  $rows[]=$row;
}

$result->close();

echo json_encode($rows);
