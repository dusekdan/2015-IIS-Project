<?php
/******************************************************************
 *  IIS PROJECT 2015: Internetový obchod - Papírnictví            *
 *  Created by: Daniel Dušek <xdusek21> & Anna Popková (xpopko00) *
 *  FIT VUT, 3BIT (Academic Year 2015/2016)                       *
 ******************************************************************/


/**
 * Autoloading function to load classes and interfaces as they are called
 * @param $className String name of the class to be loaded
 */
function __autoload($className)
{
    // Class auto-loading with check for file existence
    if(file_exists("../AppBase/" . $className . ".class.php"))
    {
        include_once("../AppBase/" . $className . ".class.php");
    }
    // Interface auto-loading with check for file existence
    else if(file_exists("../AppBase/". $className . ".php"))
    {
        include_once("../AppBase/" . $className . ".php");
    }
    // Neither class file nor interface file exist
    else
    {
        echo "Class/Interface does not exists and could not be loaded. Sorry.";
        exit;
    }
}

/**
 * Instantiation of classes for basic operations
 */

/// Database helper
$DB = new MySQLDriver();

/// Input Filter (user/insecure input)
$IF = new InputFilter();

/// Authentication helper
$Auth = new Auth($DB);

/// Admin module loader helper
$AML = new AdminModuleLoader();

/// PostBackHelper
$PBH = new PostBackHelper();

// Case when someone access Admin.php without SESSIONs existing
if(!isset($_SESSION["emp_id"]) || !isset($_SESSION["emp_hash"]))
{
    echo "Unauthorized access!";
    exit;
}

// Case when SESSION are no longer valid
if(!$Auth->verifyEmployeeSession($_SESSION["emp_id"], $_SESSION["emp_hash"]))
{
    echo "Unauthorized access!";
    exit;
}

// Current user data SESSION storage
$globalUID      = $_SESSION["emp_id"];
$globalHASH     = $_SESSION["emp_hash"];

$userData = $DB->fetch("select emp_fullname, emp_username, emp_email, emp_role FROM employee WHERE emp_id='$globalUID'");

// Current user data storage
$globalROLE     = $userData["emp_role"];
$globalFULLNAME = $userData["emp_fullname"];
$globalEMAIL    = $userData["emp_email"];
$globalUSERNAME = $userData["emp_username"];

// Layout base is loaded here
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title></title>
</head>
<body>

<div>
    <!-- Here goes information about user that is currently logged on -->
    <?php echo "Přihlášený uživatel: " . $userData["emp_fullname"] . "($userData[emp_email])<br> <a class='a_sign_out' href='LogOff.php'>ODHLÁSIT</a>"; ?>
</div>

<h1> Admin </h1>

<a href="admin2.php?action=categories">Kategorie</a> |
<a href="admin2.php?action=products">Produkty</a> |
<a href="admin2.php?action=suppliers">Dodavatelé</a> |
<a href="admin2.php?action=orders">Objednávky</a> |
<a href="admin2.php?action=roles">Správa uživatelů</a> |
<a href="admin2.php?action=orderstats">Přehled objednávek (admin)</a>

<?php

include("Pages/Add/AddHelper.class.php");
$add = new AddHelper($DB, $Auth, $IF);

if(isset($_GET["action"]))
{
    $action = $_GET["action"];
    switch ($action)
    {
        case "categories":
            ?>

            <hr>
            <a href="admin2.php?action=categories&do=add">Přidávat</a> |
            <a href="admin2.php?action=categories&do=list">Spravovat</a>

            <?php
            break;
        case "products":
            ?>
            <hr>

            <?php
            break;
        case "suppliers":

            break;
        case "orders":

            break;
        case "roles":

            break;
        case "orderstats":

            break;
    }
}

if(isset($_GET["action"]) && $_GET["action"] == "categories")
{

    if(isset($_GET["do"]) && $_GET["do"] == "add")
    {
        echo "<hr>";



        $add->loadCategoryManagement();
    }


}
?>



</body>
</html>
