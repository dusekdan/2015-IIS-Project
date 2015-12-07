<?php
/******************************************************************
 *  IIS PROJECT 2015: Internetový obchod - Papírnictví            *
 *  Created by: Daniel Dušek <xdusek21> & Anna Popková (xpopko00) *
 *  FIT VUT, 3BIT (Academic Year 2015/2016)                       *
 ******************************************************************/


if(isset($_POST["logon_username"]) && isset($_POST["logon_password"]))
{
    // Raw data from post stored here
    $name = $_POST["logon_username"];
    $password = $_POST["logon_password"];

    function __autoload($className)
    {
        include_once("../AppBase/" . $className . ".class.php");
    }

    $db = new MySQLDriver();

    // InputFilter
    $IF = new InputFilter();
    $Auth = new Auth($db);


    // Removal of dangerous characters - and THEN checking the username length
    $name = $IF->stripUnsafeCredentialsCharacters($name);

    // Checks whether accepted data are in a correct format (no additional input filtration)
    // Simplified in the end
    /*if(!$IF->checkUsernameFormat($name) || !$IF->checkPasswordFormat($password))
    {
        echo $IF->getLastError();
        header("location: index.php?loginunsuccessfull")
        exit;
    }*/

    // Trial to log user on
    if($Auth->logOnEmployee($name, $password))
    {
        header("location: Admin.php");
        exit;
    }
    else
    {
        header("location: index.php?loginunsuccessful");
        exit;
    }

}
else
{
    echo "No data acquired!";
}
?>