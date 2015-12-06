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
$IF = new InputFilter();
$PBH = new PostBackHelper();

$Viewer = new EshopViewer($db, $Auth, $IF);
?>
<html>
<head>
    <meta charset="utf-8">
    <link rel="stylesheet" href="Resources/CSS/FrontendStyles.css" type="text/css">
    <title>Eshop - Papírnictví</title>

</head>
<body onload="print();">
<h1><a href="index.php">Eshop</a> - Papírnictví</h1>
<hr>
<?php
// Logon/Userinfo part
$isSessionValid = false;
if(isset($_SESSION["cust_id"], $_SESSION["cust_hash"]))
{
    $isSessionValid = $Auth->verifyCustomerSession($_SESSION["cust_id"], $_SESSION["cust_hash"]);
    if($isSessionValid)
    {
        //$Viewer->viewProfileInfo();
        $isSessionValid = true;
        $Viewer->printOrder($_GET["orderid"]);
    }
    else
    {
        unset($_SESSION["cust_id"]);
        unset($_SESSION["cust_hash"]);
    }
}


?>
</body>
</html>