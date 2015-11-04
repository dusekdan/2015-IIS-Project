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

// Current user data SESSION storage
$globalUID      = $_SESSION["emp_id"];
$globalHASH     = $_SESSION["emp_hash"];

$userData = $db->fetch("select emp_fullname, emp_username, emp_email, emp_role FROM employee WHERE emp_id='$globalUID'");

// Current user data storage
$globalROLE     = $userData["emp_role"];
$globalFULLNAME = $userData["emp_fullname"];
$globalEMAIL    = $userData["emp_email"];
$globalUSERNAME = $userData["emp_username"];


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" type="text/css" href="../Resources/CSS/BackendStyles.css">
    <title></title>
</head>
<body>

        <header>
            <img src="../Resources/Images/logo.png" alt="LOGO" class="logo" height="120px">
            <div class="userdata">

               <?php echo "Přihlášený uživatel: " . $userData["emp_fullname"] . "($userData[emp_email])<br> <a class='a_sign_out' href='LogOff.php'>ODHLÁSIT</a>"; ?>
            </div>
            <hr class="headerLine">
        </header>

<?php
if(!isset($_GET["action"]))
{
?>
     <div class="page">
         <div class="boxes">

        <!-- Part where currently logged employee information is displayed -->

        <!-- 1st row */ -->

        <!-- PRIVILEGE LEVEL 1 BOXES -->

            <div class="employee_box">
                <div class="box_content">
                    <div class="box_table">
                        <div class="box_table-cell">
                            <a href="Admin.php?action=Add">
                                <img class="rs" src="../Resources/Images/add.png"
                                     onmouseover="this.src='../Resources/Images/add_selected.png'"
                                     onmouseout="this.src='../Resources/Images/add.png'"/>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="employee_box">
                <div class="box_content">
                    <div class="box_table">
                        <div class="box_table-cell">
                            <a href="Admin.php?action=View">
                                <img class="rs" src="../Resources/Images/see.png"
                                     onmouseover="this.src='../Resources/Images/see_selected.png'"
                                     onmouseout="this.src='../Resources/Images/see.png'"/>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="employee_box">
                <div class="box_content">
                    <div class="box_table">
                        <div class="box_table-cell">
                            <a href="Admin.php?action=Orders">
                                <img class="rs" src="../Resources/Images/deal.png"
                                     onmouseover="this.src='../Resources/Images/deal_selected.png'"
                                     onmouseout="this.src='../Resources/Images/deal.png'"/>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- 2nd row */  -->


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


        </div>
     </div>
    <?php
}
else
{
    $action = $_GET["action"];

    switch($action)
    {

        case "Add":

            include("../addProduct.html");

            break;

        default: echo "Unauthorized access"; break;
    }
}
?>

</body>
</html>