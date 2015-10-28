<?php
/// Auto-loading function that ensures that I will never have to manually add classes from AppBase
function __autoload($className)
{
    include_once("../AppBase/" . $className . ".class.php");
}
$db = new MySQLDriver();
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
