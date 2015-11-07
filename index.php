<?php
/******************************************************************
 *  IIS PROJECT 2015: Internetový obchod - Papírnictví            *
 *  Created by: Daniel Dušek <xdusek21> & Anna Popková (xpopko00) *
 *  FIT VUT, 3BIT (Academic Year 2015/2016)                       *
 ******************************************************************/


/// Auto-loading function that ensures that I will never have to manually add classes from AppBase
function __autoload($className)
{
    include_once("AppBase/" . $className . ".class.php");
}

$db = new MySQLDriver();
$Auth = new Auth($db);




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

<hr>
<form method="post" action="">
    <table>
        <tr>
            <td>Heslo k vygenerování:</td>
            <td><input type="text" name="passwordToHash"></td>
        </tr>
        <tr>
            <td>Hash:</td>
            <td><input type="text" name="hashedPassword" value="<?php if(isset($_POST["passwordToHash"])){echo $Auth->hashPassword($_POST["passwordToHash"]);} ?>"></td>
        </tr>
        <tr>
            <td></td>
            <td><input type="submit" value="Vygenerovat hash"></td>
        </tr>
    </table>
</form>
