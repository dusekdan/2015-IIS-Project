<?php
function __autoload($className)
{
    include_once("../AppBase/" . $className . ".class.php");
}

$db = new MySQLDriver();
$Auth = new Auth($db);

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

$userData = $db->fetch("select emp_fullname, emp_username, emp_email, emp_role FROM employee WHERE emp_id='$globalUID'");

// Current user data storage
$globalROLE     = $userData["emp_role"];
$globalFULLNAME = $userData["emp_fullname"];
$globalEMAIL    = $userData["emp_email"];
$globalUSERNAME = $userData["emp_username"];

// Current user data SESSION storage
$globalUID      = $_SESSION["emp_id"];
$globalHASH     = $_SESSION["emp_hash"];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" type="text/css" href="../Resources/CSS/BackendStyles.css">
    <title></title>
</head>
<body>

<!-- Part where currently logged employee information is displayed -->
<div class="header main_header">
    <?php echo "Přihlášený uživatel: " . $userData["emp_fullname"] . "($userData[emp_email]), <a class='a_sign_out' href='LogOff.php'>odhlásit</a>"; ?>
</div>

<div class ="boxes">

    <!-- 1st row */ -->
    <div class="employee_box">
        <div class="box_content">
            <div class="box_table">
                <div class="box_table-cell">
                    <a href="addProduct.html">
                        <img class="rs" src="https://farm9.staticflickr.com/8461/8048823381_0fbc2d8efb.jpg"/>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="employee_box">
        <div class="box_content">
            <div class="box_table">
                <div class="box_table-cell">
                    ... CONTENT HERE ...
                </div>
            </div>
        </div>
    </div>

    <div class="employee_box">
        <div class="box_content">
            <div class="box_table">
                <div class="box_table-cell">
                    ... CONTENT HERE ...
                </div>
            </div>
        </div>
    </div>

    <!-- 2nd row */  -->
    <!--<div class="director_box">
        <div class="box_content">
            <div class="box_table">
                <div class="box_table-cell">
                    <a href="...">
                        <img class="rs" src="https://farm9.staticflickr.com/8461/8048823381_0fbc2d8efb.jpg"/>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="director_box">
        <div class="box_content">
            <div class="box_table">
                <div class="box_table-cell">
                    ... CONTENT HERE ...
                </div>
            </div>
        </div>
    </div>

    <div class="director_box">
        <div class="box_content">
            <div class="box_table">
                <div class="box_table-cell">
                    ... CONTENT HERE ...
                </div>
            </div>
        </div>
    </div>-->

</div>