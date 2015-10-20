<?php
header("Content-type: text/html; charset=utf-8");

include("AppData/MySQLDriver.class.php");
$db = new MySQLDriver();



$data = $db->query("select * from testtable");

if($data != -1)
{
    while($r = mysql_fetch_assoc($data))
    {
        echo $r["name"] . "<br>";
    }
}


?>