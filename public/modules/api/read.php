<?php

//URL: /api/read/<APIId>/<table>
//URL: /api/read/A12/155

$params = $_REQUEST;

list($apid, $table) = getURLParts();

$apid = intval($apid);
$table = intval($table);


$sql = "SELECT * FROM `table_" . $apid . "_" . $table."`";


$db = PDOMySQL::getInstance();

$data = $db->prepare($sql)->execute()->fetchAllAssoc();

echo json_encode($data);

