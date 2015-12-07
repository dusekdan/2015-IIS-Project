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
    header("location: index.php?unauthorizedaccess");
    exit;
}

// Case when SESSION are no longer valid
if(!$Auth->verifyEmployeeSession($_SESSION["emp_id"], $_SESSION["emp_hash"]))
{
    $Auth->logOffEmployee();
    header("location: index.php?timeout");
    exit;
}
else
{
    $Auth->reactivateUser($_SESSION["emp_id"], $_SESSION["emp_hash"]);
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
    <script src="//ajax.googleapis.com/ajax/libs/jquery/2.0.0/jquery.min.js"></script>
    <link rel="stylesheet" type="text/css" href="../Resources/CSS/BackendStyles.css">
    <title></title>
</head>
<body>

        <div class="page">
        <header>
            <a href="./Admin.php"><img src="../Resources/Images/logo.png" alt="LOGO" class="logo" height="120px"></a>
            <div class="userdata">
               <!-- Here goes information about user that is currently logged on -->
               <?php echo "Přihlášený uživatel: <span class=\"username\">" . $userData["emp_fullname"] . "</span> ($userData[emp_email])<br> <a class='a_sign_out' href='LogOff.php'>ODHLÁSIT</a>"; ?>
            </div>
            <hr class="headerLine">
        </header>

<?php
// When no get parameter for action is present, show dashboard layout (HTML)
if(!isset($_GET["action"]))
{

?>
         <div class="boxes">

        <!-- Part where currently logged employee information is displayed -->

        <!-- 1st row */ -->

        <!-- PRIVILEGE LEVEL 1 BOXES -->

         <?php
         if($globalROLE == 1)
         {
         ?>
            <div class="employee_box">
                <div class="box_content">
                    <div class="box_table">
                        <div class="box_table-cell">
                            <a href="Admin.php?action=Add&type=product">
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
                            <a href="Admin.php?action=Show&type=product">
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
                            <a href="Admin.php?action=Orders&type=process">
                                <img class="rs" src="../Resources/Images/deal.png"
                                     onmouseover="this.src='../Resources/Images/deal_selected.png'"
                                     onmouseout="this.src='../Resources/Images/deal.png'"/>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

             <?php }

            else
            {

              ?>
            <!-- 2nd row */  -->


            <div class="director_box">
                <div class="box_content">
                    <div class="box_table">
                        <div class="box_table-cell">
                            <a href="Admin.php?action=Stats">
                                <img class="rs" src="../Resources/Images/seest.png"
                                     onmouseover="this.src='../Resources/Images/seest_selected.png'"
                                     onmouseout="this.src='../Resources/Images/seest.png'"/>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="director_box">
                <div class="box_content">
                    <div class="box_table">
                        <div class="box_table-cell">
                            <a href="Admin.php?action=OrderState">
                                <img class="rs" src="../Resources/Images/seeobj.png"
                                     onmouseover="this.src='../Resources/Images/seeobj_selected.png'"
                                     onmouseout="this.src='../Resources/Images/seeobj.png'"/>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="director_box">
                <div class="box_content">
                    <div class="box_table">
                        <div class="box_table-cell">
                            <a href="Admin.php?action=RoleManagement&type=list">
                                <img class="rs" src="../Resources/Images/edit.png"
                                     onmouseover="this.src='../Resources/Images/edit_selected.png'"
                                     onmouseout="this.src='../Resources/Images/edit.png'"/>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

          <?php
            } ?>

        </div>
     </div>
    <?php
}
// When get parameter for action is present, load admin module accordingly
else
{
    $action = $_GET["action"];
    switch($action)
    {
        case $action:

            // Creation of paths to files that should module contain
            $moduleFile = "Pages/" . $action . "/" . $action . ".php";
            $moduleHelperClassFile = "Pages/" . $action . "/" . $action . "Helper.class.php";

            // Check whether module file is present
            if(!file_exists($moduleFile))
            {
                echo "Module file does not exists.";
                exit;
            }

            // Check whether module helper class is present
            if(!file_exists($moduleHelperClassFile))
            {
                echo "Module helper class file does not exists.";
                exit;
            }

            // TODO: Resolve whether this is duplicate code (checkModuleExists)
            if($AML->checkModuleExists($action, "../Admin/Pages/"))
            {

                // There is no security risk since existence of the file was confirmed already
                include($moduleHelperClassFile);


                // Every module HAS TO implement IAdminModule interface. This is the check.
                $className = $action . "Helper";
                if(!$AML->checkModuleImplementsInterface($className))
                {
                    echo "Module has to implement IAdminModule interface and its methods.";
                    exit;
                }

                // Creating class dynamically using ReflectionClass in php
                $reflection = new ReflectionClass($className);
                $classArgs = array($DB, $Auth, $IF);

                // Creates module helper object, while ensuring entry parameters (Database helper, Auth helper, Input Filter)
                // This variable is used further on, in the Module.php file to access properly created ModuleHelper object
                $MH = $reflection->newInstanceArgs($classArgs);
                $linkBase = basename($_SERVER['PHP_SELF']) . "?action=$action";

                // Same as the include above, no security risks
                // Including the module file itself (should contain your custom code, calls & handles for actions contained)
                include($moduleFile);
            }
            // Links to TO-DO in condition starting block
            else
            {
                echo "Module loading file and/or helper class file do not exist!";
            }
            break;
    }
}
?>
</body>
</html>