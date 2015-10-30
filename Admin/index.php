<?php
/// Auto-loading function that ensures that I will never have to manually add classes from AppBase
function __autoload($className)
{
    include_once("../AppBase/" . $className . ".class.php");
}
$db = new MySQLDriver();
$Auth = new Auth($db);


// User session exists - we test if its valid and eventually log user back in
if(isset($_SESSION["emp_id"]) && isset($_SESSION["emp_hash"]))
{
    if($Auth->verifyEmployeeSession($_SESSION["emp_id"], $_SESSION["emp_hash"]))
    {
        header("location: Admin.php?redirected");
        exit;
    }
}
?>
<form method="post" action="LogOn.php">
    <table>
        <tr>
            <td>Přihlašovací jméno:</td>
            <td><input type="text" name="logon_username"></td>
        </tr>
        <tr>
            <td>Heslo:</td>
            <td><input type="password" name="logon_password"></td>
        </tr>
        <tr>
            <td></td>
            <td><input type="submit" value="Přihlásit se do systému"></td>
        </tr>
    </table>
</form>
