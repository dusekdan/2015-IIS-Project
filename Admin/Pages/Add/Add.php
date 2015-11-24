<!-- MODULE DESIGN & CODE GOES HERE -->

<a href="<?php echo $linkBase; ?>&amp;type=product">Přidat produkt</a> | <a href="<?php echo $linkBase; ?>&amp;type=supplier">Přidat dodavatele</a>
<?php

if( (isset($_GET["type"]) && $_GET["type"] == "product") || !isset($_GET["type"]))
{
    $MH->loadAddProductForm();
}

if(isset($_GET["type"]) && $_GET["type"] == "supplier")
{
    if(isset($_POST["supplierName"]))
    {
        $submitSupplierResult = $MH->submitNewSupplier();

        if(is_string($submitSupplierResult))
        {
            // Error occured while processing
            echo $submitSupplierResult;
        }
        else
        {
            echo "Dodavatel přidán...";
        }
    }
    $MH->loadAddSupplierForm();
    $MH->loadSupplierList();
}


// TODO: Remove this.
// Display information about module, debugging only
echo $MH->_getModuleDescription();

?>
