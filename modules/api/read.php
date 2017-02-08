<?php

$params = $_REQUEST;

list($apid, $table) = getURLParts();

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
