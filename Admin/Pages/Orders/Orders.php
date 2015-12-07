<!-- MODULE DESIGN & CODE GOES HERE -->
<div class="navigation">
    <a href="<?php echo $linkBase; ?>&amp;type=resupply" class="<?php if( isset($_GET["type"]) && $_GET["type"] == "resupply")
        echo "selected"?>">Doplnit zásoby produktu</a> |
    <a href="<?php echo $linkBase; ?>&amp;type=process" class="<?php if( isset($_GET["type"]) && $_GET["type"] == "process")
        echo "selected"?>">Nevyřízené objednávky</a> |
    <a href="<?php echo $linkBase; ?>&amp;type=orderhistory" class="<?php if( isset($_GET["type"]) && $_GET["type"] == "orderhistory")
        echo "selected"?>">Historie objednávek</a>
</div>
<?php
if(isset($_GET["type"]) && $_GET["type"] == "process")
{

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

}

if(isset($_GET["type"]) && $_GET["type"] == "orderhistory")
{

    $MH->loadOrderHistory();
}

if(isset($_GET["type"]) && $_GET["type"] == "resupply")
{

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

if(isset($_GET["type"]) && $_GET["type"] == "print")
{
    if(isset($_GET["orderid"]) && is_numeric($_GET["orderid"]))
    {
        echo "<a href='javascript:print()'>Vytisknout</a><br><br>";
        $MH->printOrder($_GET["orderid"]);
    }
    else
    {
        $PBH->showMessage("Neoprávněný přístup, pardon!", "error");
    }
}
?>