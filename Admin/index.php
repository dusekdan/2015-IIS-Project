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

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" type="text/css" href="../Resources/CSS/BackendStyles.css">
    <title></title>
</head>

<body>
<div class="page">

<header>

    <img src="../Resources/Images/logo.png" alt="LOGO" class="logo" height="120px">
    <div class="headline">Přihlášení do systému</div>
    <hr class="headerLine">

</header>
<form class="signInForm" method="post" action="LogOn.php">
    <table>
        <tr>
            <td>Přihlašovací jméno:</td>
            <td class="thin"></td>
            <td><input type="text" name="logon_username" class="text"></td>
        </tr>
        <tr>
            <td>Heslo:</td>
            <td class="thin"></td>
            <td><input type="password" name="logon_password" class="text"></td>
        </tr>

        <tr>
            <td></td>
            <td class="thin"><input type="submit" value="PŘIHLÁSIT" class="button"></td>
            <td></td>
        </tr>
    </table>
</form>
</div>
</body>
