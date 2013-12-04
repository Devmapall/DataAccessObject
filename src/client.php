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
$obj2 = $dao->read(2);
$obj2->setName("Lausberg");
$dao->setObject($obj2);
$dao->update();
?>
