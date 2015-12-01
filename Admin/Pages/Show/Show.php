<!-- MODULE DESIGN & CODE GOES HERE -->
<a href="<?php echo $linkBase; ?>&amp;type=category">Kategorie</a> |
<a href="<?php echo $linkBase; ?>&amp;type=product">Produkty</a> | <a href="<?php echo $linkBase; ?>&amp;type=supplier">Dodavatelé</a>
<hr>
<?php

if(isset($_GET["type"]) && $_GET["type"] == "category")
{
    if(isset($_POST["deleteCategory"]) && is_numeric($_POST["deleteCategory"]))
    {
        $deleteCategoryResult = $MH->deleteCategory($_POST["deleteCategory"]);
        if($deleteCategoryResult)
        {
            $PBH->showMessage("Kategorie smazána!");
        }
        else
        {
            $PBH->showMessage($MH->getPostbackInfo(), "error");
        }
    }
    $MH->loadCategoryList();


    echo "<hr>";

    if(isset($_POST["deleteSubcategory"]) && is_numeric($_POST["deleteSubcategory"]))
    {
        $deleteSubcategoryResult = $MH->deleteSubcategory($_POST["deleteSubcategory"]);
        if($deleteSubcategoryResult)
        {
            $PBH->showMessage("Podkategorie smazána!");
        }
        else
        {
            $PBH->showMessage($MH->getPostbackInfo(), "error");
        }
    }
    $MH->loadSubcategoryList();
}


if(isset($_GET["type"]) && $_GET["type"] == "supplier")
{

    if(isset($_POST["deleteSupplier"]) && is_numeric($_POST["deleteSupplier"]))
    {
        $deleteSupplierResult = $MH->deleteSupplier($_POST["deleteSupplier"]);
        if($deleteSupplierResult)
        {
            $PBH->showMessage("Dodavatel smazán!");
        }
        else
        {
            $PBH->showMessage($MH->getPostbackInfo(), "error");
        }
    }

    $MH->loadSupplierList();
}

if(isset($_GET["type"]) && $_GET["type"] == "product")
{
    if(isset($_POST["deleteProduct"]) && is_numeric($_POST["deleteProduct"]))
    {
        $deleteProductResult = $MH->deleteProduct($_POST["deleteProduct"]);
        if($deleteProductResult)
        {
            $PBH->showMessage("Produkt smazán!");
        }
        else
        {
            $PBH->showMessage($MH->getPostbackInfo(), "error");
        }
    }

     $MH->loadProductList();
}

?>