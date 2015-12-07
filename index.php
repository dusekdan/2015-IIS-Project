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
<div id="container">
    <div class="header">
        <div class="header_content">
<h1><a href="index.php">IIS ESHOP</a> - PAPÍRNICTVÍ</h1>
            <hr>
<?php
// Logon/Userinfo part
$isSessionValid = false;
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
    ?><a href="index.php?auth=register">Registrovat se</a> | <a href="index.php?auth=logon">Přihlásit se</a> <?php
}
?>
    </div>
        </div>




<div id="left-menu">
    <div class="left-menu-heading">
        Kategorie&nbsp;zboží
    </div>

    <?php
    $Viewer->loadCategoryListing();
    ?>
</div>

<div class="content">
<?php if(!count($_GET)) { ?>
    <h2>NEJNOVĚJŠÍ PRODUKTY</h2>
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

        if(isset($_GET["from"]) && $_GET["from"] == "addToBasket")
        {
            $PBH->showMessage("Pro přidávání položek do košíku je třeba se přihlásit!");
        }

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





if(isset($_GET["shopaction"]))
{

    switch($_GET["shopaction"])
    {


        // Shopping process cases
        case "addtobasket":
                    $renderForm = true;

                    // Check whether the user is logged on - if not, redirect to registration with information about the fact that he needs to register first (&from=addToBasket)
                    if(!isset($_SESSION["cust_id"], $_SESSION["cust_hash"]) || !$isSessionValid)
                    {
                        $PBH->redirectPage("index.php?auth=logon&from=addToBasket");
                    }

                    if(isset($_POST["addToBasketQuantity"], $_GET["productid"]) && is_numeric($_GET["productid"]))
                    {
                        // Correction of added value(s)
                        if($_POST["addToBasketQuantity"] < 0)
                            $quantity = 1;
                        else
                            $quantity = $IF->prepareInputForSQL($_POST["addToBasketQuantity"]);

                        $pid = $IF->prepareInputForSQL($_GET["productid"]);
                        $Viewer->addToBasket($pid, $quantity);
                        $PBH->showMessage("Úspěšně přidáno do košíku! Chcete si prohlédnout <a href='index.php?shopaction=viewcart'>obsah košíku</a>, nebo pokračovat v <a href='index.php'>nákupu</a>?");
                        $renderForm = false;
                    }

                    if($renderForm)
                    {
                        $Viewer->loadAddToBasketForm($_GET["productid"]);
                    }


            break;

        case "viewcart":
            $Auth->killUnauthorizedVisit();
            if(isset($_POST["deleteItem"]) && is_numeric($_POST["deleteItem"]))
            {
                if($Viewer->removeFromBasket($_POST["deleteItem"]))
                {
                    $PBH->showMessage("Položka úspěšně odebrána!");
                }
            }

            if(isset($_POST["makeOrder"]))
            {
                $Auth->killUnauthorizedVisit();
                $Viewer->orderBasketContent();
                $Viewer->emptyBasket();
                $PBH->showMessage("Obsah košíku byl objednán. Objednávku si můžete <a href='index.php?shopaction=orderhistory'>prohlédnout zde</a>.");
            }

            $Viewer->loadBasketContents();
            break;


        case "orderhistory":
            $Auth->killUnauthorizedVisit();
            $Viewer->showOrderHistory();
            break;

        case "changecontact":
            $Auth->killUnauthorizedVisit();

            if(isset($_POST["registerFirstName"]))
            {
                $editCustomerResult = $Viewer->editCustomer();
                if($editCustomerResult)
                {
                    $PBH->showMessage("Údaje úspěšně změněny!");
                }
                else
                {
                    $PBH->showMessage($Viewer->getPostbackInfo(), "error");
                }
            }

            $Viewer->loadEditContactForm();
            break;

        // Listing cases
        case "viewproduct":
            if(isset($_GET["productid"]) && is_numeric($_GET["productid"]))
                $Viewer->showProductDetail($_GET["productid"]);
            else
                echo "Neoprávněný přístup! Pokud jste adresu zadávali ručně, zkontrolujte, že nedošlo k překlepu.";
            break;
        case "viewcategory":
            if(isset($_GET["categoryid"]) && is_numeric($_GET["categoryid"]))
                $Viewer->showProductsInCategory($_GET["categoryid"]);
            else
                echo "Neoprávněný přístup! Pokud jste adresu zadávali ručně, zkontrolujte, že nedošlo k překlepu.";

            break;
        case "viewsubcategory":
            if(isset($_GET["subcategoryid"]) && is_numeric($_GET["subcategoryid"]))
                $Viewer->showProductsInSubcategory($_GET["subcategoryid"]);
            else
                echo "Neoprávněný přístup! Pokud jste adresu zadávali ručně, zkontrolujte, že nedošlo k překlepu.";
            break;
    }
}





?>

</div>

<div class="clearingbox"></div>
</div>
</body>

</html>