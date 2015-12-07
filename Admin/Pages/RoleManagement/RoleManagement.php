<?php
if(!$Auth->checkUserAccess(2))
{
    $PBH->showMessage("Omlouváme se, ale do této sekce nemáte přístup!", "warning");
    die();
}
?>
<!-- MODULE DESIGN & CODE GOES HERE -->
<div class="navigation">
    <a href="<?php echo $linkBase; ?>&type=list" class="<?php if( isset($_GET["type"]) && $_GET["type"] == "list")
        echo "selected"?>">Všichni zaměstnanci</a> |
    <a href="<?php echo $linkBase;?>&type=add" class="<?php if( isset($_GET["type"]) && $_GET["type"] == "add")
        echo "selected"?>">Přidat zaměstnance</a> |
    <a href="<?php echo $linkBase;?>&amp;type=customers" class="<?php if( isset($_GET["type"]) && $_GET["type"] == "customers")
        echo "selected"?>">Databáze zákazníků</a>
</div>
<?php

if(isset($_GET["type"]))
{
    $g = $_GET;

    switch($g["type"])
    {
        case "list":


            if(isset($_POST["deleteEmployee"]) && is_numeric($_POST["deleteEmployee"]))
            {
                $deleteEmployeeResult = $MH->deleteEmployee($_POST["deleteEmployee"]);
                if($deleteEmployeeResult)
                {
                    $PBH->showMessage("Uživatel smazán!");
                }
                else
                {
                    $PBH->showMessage($MH->getPostbackInfo(), "error");
                }
            }

            if(isset($_POST["activateToggleEmployee"]) && is_numeric($_POST["activateToggleEmployee"]))
            {
                $toggleEmployeeStatus = $MH->toggleEmployeeStatus($_POST["activateToggleEmployee"]);
                if($toggleEmployeeStatus)
                {
                    $PBH->showMessage("Uživatel změněn!");
                }
                else
                {
                    $PBH->showMessage($MH->getPostbackInfo(), "error");
                }
            }



            $MH->loadEmployeeList();
            break;

        case "add":

            $renderForm = true;

            if(isset($_POST["employeeName"]) && @$_SESSION["formGenerationStamp"] == $_POST["formGenerationStamp"])
            {

                $submitEmployeeResult = $MH->submitEmployee();
                if($submitEmployeeResult)
                {
                    $renderForm = false;
                    $PBH->showMessage("Uživatel úspěšně přidán! Chcete <a href='Admin.php?action=RoleManagement&type=add'>přidat dalšího zaměstnance</a> nebo přejít na <a href='Admin.php?action=RoleManagement&type=list'>seznam všechn zaměstnanců?</a>");
                    unset($_SESSION["formGenerationStamp"]);
                }
                else
                {
                    $PBH->showMessage($MH->getPostbackInfo(), "error");
                }
            }

            if($renderForm)
            {
                $MH->loadAddEmployeeForm();
            }


            break;

        case "customers":
            $MH->loadCustomerList();
            break;

    }


}

if(isset($_GET["edittype"]) && $_GET["edittype"] == "employee" && is_numeric($_GET["edit"]))
{

    $renderForm = true;

    if(isset($_POST["employeeName"]) && @$_SESSION["formGenerationStamp"] == $_POST["formGenerationStamp"])
    {
        $editEmployeeResult = $MH->editEmployee();
        if($editEmployeeResult)
        {
            $renderForm = false;
            $PBH->showMessage("Zaměstnanec upraven! Chcete se vrátit <a href='Admin.php?action=RoleManagement&amp;type=list'>zpět na seznam zaměstnanců?</a>");
            unset($_SESSION["formGenerationStamp"]);
        }
        else
        {
            $PBH->showMessage($MH->getPostbackInfo(), "error");
        }
    }

    if($renderForm)
    {
        $MH->loadEmployeeEditForm($_GET["edit"]);
    }

}

?>