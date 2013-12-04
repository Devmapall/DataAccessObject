<?php
require_once("DataAccessObject.php");
require_once("exampleClass.php");

$con = mysql_connect("localhost", "test", "test");
mysql_select_db("DAO");
$dao = new DataAccessObject($con);
$obj = new ExampleClass();
$obj->setName("Marcel");
$obj->setNr(1);
$obj->setCreate_date(date("Y-m-d"));
$dao->setObject($obj);
//$dao->create();
$attributes = array(
    "Name" => "Lausberg",
    "AND Nr" => 1
);
$obj2 = $dao->readByAttributes($attributes);
var_dump($obj2);
$obj2 = $dao->readById(2);
var_dump($obj2);
?>
