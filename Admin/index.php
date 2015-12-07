<?php
/******************************************************************
 *  IIS PROJECT 2015: Internetový obchod - Papírnictví            *
 *  Created by: Daniel Dušek <xdusek21> & Anna Popková (xpopko00) *
 *  FIT VUT, 3BIT (Academic Year 2015/2016)                       *
 ******************************************************************/


/// Auto-loading function that ensures that I will never have to manually add classes from AppBase
function __autoload($className)
{
    include_once("../AppBase/" . $className . ".class.php");
}
$db = new MySQLDriver();
$Auth = new Auth($db);
$PBH = new PostBackHelper();


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

    <a href="../index.php"><img src="../Resources/Images/logo.png" alt="LOGO" class="logo" height="120px"></a>
    <div class="headline">Přihlášení do systému</div>
    <hr class="headerLine">

</header>

    <?php
    if(isset($_GET["timeout"]))
    {
        $PBH->showMessage("Vaše přihlášení vypršelo, přihlašte se prosím znovu!", "warning");
    }

    if(isset($_GET["unauthorizedaccess"]))
    {
        $PBH->showMessage("Neoprávněný přístup do administrace. Račte se přihlásit.", "warning");
    }

    if(isset($_GET["loggedout"]))
    {
        $PBH->showMessage("Odhlášení proběhlo úspěšně!");
    }

    if(isset($_GET["loginunsuccessful"]))
    {
        $PBH->showMessage("Přihlášení nebylo úspěšné. Buď byla zadána neexistující kombinace uživatelského jména a hesla, nebo Váš účet byl deaktivován.", "error");
    }
    ?>

<form class="form" method="post" action="LogOn.php">
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
