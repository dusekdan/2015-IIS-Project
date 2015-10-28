<?php
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
    if(!$IF->checkUsernameFormat($name) || !$IF->checkPasswordFormat($password))
    {
        echo $IF->getLastError();
        exit;
    }

    // At this moment, we should be certain we can safely test user against DB
    if($Auth->verifyUserCredentials($name, $password))
    {
        header("location: admin.php");
        exit;
    }
    else
    {
        echo "Given combination of username & password does not exist!";
    }


}
else
{
    echo "No data acquired!";
}
?>