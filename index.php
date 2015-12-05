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
<body>
<h1>Eshop - Papírnictví</h1>
<hr>
<?php
if(isset($_SESSION["cust_id"], $_SESSION["cust_hash"]))
{
    $isSessionValid = $Auth->verifyCustomerSession($_SESSION["cust_id"], $_SESSION["cust_hash"]);
    if($isSessionValid)
    {
        $Viewer->viewProfileInfo();
    }
    else
    {
        unset($_SESSION["cust_id"]);
        unset($_SESSION["cust_hash"]);
        $PBH->showMessage("Vaše přihlášení vypršelo!", "warning");
        ?>
        <a href="index.php?auth=register">Registrovat se</a> | <a href="index.php?auth=logon">Přihlásit se</a>
        <?php
    }
}
else
{
    ?><a href="index.php?auth=register">Registrovat se</a> | <a href="index.php?auth=logon">Přihlásit se</a><?php
}
?>

<hr>

<div class="frontend-menu">
    <strong>Kategorie zboží</strong> <br>
    <?php
    $Viewer->loadCategoryListing();
    ?>


</div>

<div class="frontend-content">
<?php if(!count($_GET)) { ?>
    <h2>Nejnovější produkty...</h2>
    <?php
    $Viewer->loadNewestProducts();
    ?>
<?php } ?>

<?php
if(isset($_GET["auth"]))
{
    if ($_GET["auth"] == "register")
    {
        $renderForm = true;

        if(isset($_POST["registerFirstName"]) && @$_SESSION["formGenerationStamp"] == $_POST["formGenerationStamp"])
        {
            $registerUserResult = $Viewer->submitRegistration();
            if($registerUserResult)
            {
                $PBH->showMessage("Registrace proběhla úspěšně, nyní se můžete <a href='index.php?auth=logon'>přihlásit</a>.");
                unset($_SESSION["formGenerationStamp"]);
                $renderForm = false;
            }
            else
            {
                $PBH->showMessage($Viewer->getPostbackInfo(), "error");
            }

        }

        if($renderForm)
        {
            $Viewer->loadRegisterForm();
        }
    }

    if($_GET["auth"] == "logon")
    {

        if(isset($_POST["logonMail"], $_POST["logonPassword"]))
        {
            $logonResult = $Viewer->submitLogon();
            if($logonResult)
            {
                $PBH->redirectPage("index.php");
            }
            else
            {
                $PBH->showMessage("Zadané přihlašovací údaje nejsou platné!", "error");
            }

        }

        $Viewer->loadLogonForm();

    }

    if($_GET["auth"] == "logoff")
    {
        $Auth->logOffCustomer();
        $PBH->redirectPage("index.php");
    }
}
?>

</div>

<div class="clearingbox"></div>

</body>

</html>









<hr>
<hr>
<hr>
<hr>
<form method="post" action="">
    <table>
        <tr>
            <td>Heslo k vygenerování:</td>
            <td><input type="text" name="passwordToHash"></td>
        </tr>
        <tr>
            <td>Hash:</td>
            <td><input type="text" name="hashedPassword" value="<?php if(isset($_POST["passwordToHash"])){echo $Auth->hashPassword($_POST["passwordToHash"]);} ?>"></td>
        </tr>
        <tr>
            <td></td>
            <td><input type="submit" value="Vygenerovat hash"></td>
        </tr>
    </table>
</form>
