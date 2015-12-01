<!-- MODULE DESIGN & CODE GOES HERE -->
<a href="<?php echo $linkBase; ?>&amp;type=category">Kategorie</a> |
<a href="<?php echo $linkBase; ?>&amp;type=product">Produkty</a> | <a href="<?php echo $linkBase; ?>&amp;type=supplier">Dodavatelé</a>
<hr>
<?php

if(isset($_GET["type"]) && $_GET["type"] == "category")
{
    $MH->loadCategoryList();
    echo "<hr>";
    $MH->loadSubcategoryList();
}


if(isset($_GET["type"]) && $_GET["type"] == "supplier")
{
    $MH->loadSupplierList();
}

if(isset($_GET["type"]) && $_GET["type"] == "product")
{
     $MH->loadProductList();
}

?>