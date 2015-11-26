<!-- MODULE DESIGN & CODE GOES HERE  -->
<a href="<?php echo $linkBase; ?>&amp;type=category">Správa kategorií</a> |
<a href="<?php echo $linkBase; ?>&amp;type=product">Přidat produkt</a> | <a href="<?php echo $linkBase; ?>&amp;type=supplier">Přidat dodavatele</a>
<?php

if( (isset($_GET["type"]) && $_GET["type"] == "product") || !isset($_GET["type"]))
{
    $MH->loadAddProductForm();
}

if(isset($_GET["type"]) && $_GET["type"] == "supplier")
{
    if(isset($_POST["supplierName"]) && $_SESSION["formGenerationStamp"] == $_POST["formGenerationStamp"])
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

if(isset($_GET["type"]) && $_GET["type"] == "category")
{

    if(isset($_POST["categoryName"]))
    {
        $submitCategoryResult = $MH->submitNewCategory();

        if(is_string($submitCategoryResult))
        {
            // Error while processing
            echo $submitCategoryResult;
        }
        else
        {
            echo "Kategorie přidána";
        }
    }

    if(isset($_POST["subcategoryName"]))
    {
        if(isset($_POST["subcategoryName"]))
        {
            $submitSubcategoryResult = $MH->submitNewSubCategory();

            if(is_string($submitSubcategoryResult))
            {
                // Error while processing
                echo $submitSubcategoryResult;
            }
            else
            {
                echo "Podkategorie přidána";
            }
        }
    }

    $MH->loadCategoryManagement();
    $MH->loadCategoryList();
    echo "<hr>";
    $MH->loadSubcategoryList();
    echo "<hr>";
}


// TODO: Remove this.
// Display information about module, debugging only
echo $MH->_getModuleDescription();

?>
