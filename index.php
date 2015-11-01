<?php
/// Auto-loading function that ensures that I will never have to manually add classes from AppBase
function __autoload($className)
{
    include_once("AppBase/" . $className . ".class.php");
}

$db = new MySQLDriver();





echo "<h1>Systémové statistiky</h1>";

$data = $db->query("select * from employee, employee_role WHERE emp_role = erole_id");
if($data != -1)
{
    echo "<h2>Aktuálně existující uživatelé:</h2>" . PHP_EOL;
    while($r = mysql_fetch_assoc($data))
    {
        echo $r["erole_name"] . " " . $r["emp_fullname"] . " (" . $r["emp_username"]  . ") <br>" . PHP_EOL;
    }
}


?>