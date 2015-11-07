<?php
/******************************************************************
 *  IIS PROJECT 2015: Internetový obchod - Papírnictví            *
 *  Created by: Daniel Dušek <xdusek21> & Anna Popková (xpopko00) *
 *  FIT VUT, 3BIT (Academic Year 2015/2016)                       *
 ******************************************************************/


function __autoload($className)
{
    include_once("../AppBase/" . $className . ".class.php");
}

$db = new MySQLDriver();
$Auth = new Auth($db);

// If SESSIONs are existent, log out, otherwise redirect on index with information about unathorized access
if(isset($_SESSION["emp_hash"]) && isset($_SESSION["emp_id"]))
{
    // Session & database record destruction itself
    $Auth->logOffEmployee();

    // Redirect back to log-in form/index, depends
    header("location: index.php?loggedout");
}
else
{
    header("location: ../index.php?unauthorizedAccess");
}
?>