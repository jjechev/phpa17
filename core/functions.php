<?php

function getURLParts()
{
    return Router::$URNParts;
}

function db(){
    global $_db;
    if (!isset($_db)){
        $obj = new DbConnect();
        $_db = $obj->connect();
    }
    return $_db;
}