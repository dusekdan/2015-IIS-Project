<!-- MODULE DESIGN & CODE GOES HERE -->
<a href="<?php echo $linkBase; ?>&amp;type=resupply">Doplnit zásoby produktu</a> |
<a href="<?php echo $linkBase; ?>&amp;type=process">Nevyřízené objednávky</a> | <a href="<?php echo $linkBase; ?>&amp;type=orderhistory">Historie objednávek</a>
<hr>
<?php
if(isset($_GET["type"]) && $_GET["type"] == "process")
{
    echo "<h2>Objednávky čekající na vyřízení</h2>";

    if(isset($_POST["processOrder"], $_POST["orderId"]) && is_numeric($_POST["orderId"]))
    {
        if($MH->processOrder($_POST["orderId"]))
        {
            $PBH->showMessage("Objednávka vyřízena!");
        }
        else
        {
            $PBH->showMessage("Vyskytla se neočekávaná chyba, zkuste to prosím znovu!", "error");
        }
    }

    $MH->loadUnprocessedOrders();




    echo "<h2>Objednávky čekající na zboží od dodavatele</h2>";
}

if(isset($_GET["type"]) && $_GET["type"] == "orderhistory")
{
    echo "<h2>Historie objednávek</h2>";

    $MH->loadOrderHistory();
}

if(isset($_GET["type"]) && $_GET["type"] == "resupply")
{
    echo "<h2>Doplnění zboží</h2>";

    $renderForm = true;

    if(isset($_POST["resupplyProduct"]))
    {
        $resupplyResult = $MH->resupplyProduct();
        if($resupplyResult)
        {
            $PBH->showMessage("Produkt byl doplněn na skladě. Chcete doplnit <a href=''>další produkty?</a>");
            $renderForm = false;
        }
        else
        {
            $PBH->showMessage($MH->getPostbackInfo(), "error");
        }
    }

    if($renderForm)
    {
        $MH->loadResupplyForm();
    }
}
?>