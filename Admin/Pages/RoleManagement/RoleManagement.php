<!-- MODULE DESIGN & CODE GOES HERE -->
<a href="<?php echo $linkBase; ?>&type=list">Všichni zaměstnanci</a> |
<a href="<?php echo $linkBase;?>&type=add">Přidat zaměstnance</a> |
<a href="<?php echo $linkBase;?>&amp;type=customers">Databáze zákazníků</a>
<hr>
<?php

if(isset($_GET["type"]))
{
    $g = $_GET;

    switch($g["type"])
    {
        case "list":
            echo "<h2>Seznam zaměstnanců</h2>";


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



            $MH->loadEmployeeList();
            break;

        case "add":
            echo "<h2>Přidat zaměstnance</h2>";

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
            echo "<h2>Seznam zákazníků</h2>";
            break;
    }


}



?>