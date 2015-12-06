<!-- MODULE DESIGN & CODE GOES HERE -->
<div class="navigation"><a href="<?php echo $linkBase; ?>&amp;type=category">Přidat (pod)kategorie</a> |
<a href="<?php echo $linkBase; ?>&amp;type=product">Přidat produkt</a> | <a href="<?php echo $linkBase; ?>&amp;type=supplier">Přidat dodavatele</a>
</div>

<?php

if( isset($_GET["type"]) && $_GET["type"] == "category")
{

    $renderCategoryForm = true;

    // Handle category post backs
    if(isset($_POST["categoryName"]) && @$_SESSION["formGenerationStamp"] == $_POST["formGenerationStamp"])
    {
        $submitCategoryResult = $MH->submitNewCategory();

        if($submitCategoryResult)
        {
            // No need to render form again | show message about success + maybe submit more/view rest
            $renderCategoryForm = false;
            $PBH->showMessage("Kategorie úspěšně přidána. Chcete <a href='$linkBase&amp;type=category'>přidat další</a> nebo <a href='Admin.php?action=Show&amp;type=category'>zobrazit existující</a>?", "info");
            unset($_SESSION["formGenerationStamp"]);
        }
        else
        {
            // Error post back and form is rendered
            $PBH->showMessage($MH->getPostBackInfo(), "error");
            $renderCategoryForm = true;
        }
    }

    // Handle subcategory post backs
    if(isset($_POST["subcategoryName"]) && @$_SESSION["formGenerationStamp"] == $_POST["formGenerationStamp"])
    {
        $submitSubcategoryResult = $MH->submitNewSubcategory();

        if($submitSubcategoryResult)
        {
            $renderCategoryForm = false;
            $PBH->showMessage("Podkategorie úspěšně přidána. Chcete <a href='$linkBase&amp;type=category'>přidat další</a> nebo <a href='Admin.php?action=Show&amp;type=category'>zobrazit existující</a>?", "info");
            unset($_SESSION["formGenerationStamp"]);
        }
        else
        {
            $PBH->showMessage($MH->getPostBackInfo(), "error");
        }
    }


    if($renderCategoryForm)
    {
        $MH->loadCategoryManagement();
    }
}

// Supplier rewrite
if(isset($_GET["type"]) && $_GET["type"] == "supplier")
{
    $renderForm = true;

    if(isset($_POST["supplierName"]) && @$_SESSION["formGenerationStamp"] == $_POST["formGenerationStamp"])
    {
        $submitSupplierResult = $MH->submitNewSupplier();

        if($submitSupplierResult)
        {
            $renderForm = false;
            $PBH->showMessage("Doadavatel byl přidán. Chcete <a href='$linkBase&amp;type=supplier'>přidat další</a> nebo <a href='Admin.php?action=Show&amp;type=supplier'>zobrazit existující</a>?");
            unset($_SESSION["formGenerationStamp"]);
        }
        else
        {
            $PBH->showMessage($MH->getPostBackInfo(), "error");
        }
    }

    if($renderForm)
    {
        $MH->loadAddSupplierForm();
    }
}

// Product coming clean
if(isset($_GET["type"]) && $_GET["type"] == "product")
{
    $renderForm = true;

    if(isset($_POST["productName"]) && @$_SESSION["formGenerationStamp"] == $_POST["formGenerationStamp"])
    {
        $submitProductResult = $MH->submitNewProduct();

        if($submitProductResult)
        {
            $renderForm = false;
            $PBH->showMessage("Produkt byl přidán. Chcete  <a href='$linkBase&amp;type=product'>přidat další</a> nebo <a href='Admin.php?action=Show&amp;type=product'>zobrazit existující</a>?");
            unset($_SESSION["formGenerationStamp"]);
        }
        else
        {
            $PBH->showMessage($MH->getPostBackInfo(), "error");
        }
    }

    if($renderForm)
    {
        $MH->loadAddProductForm();
    }
}

// TODO: Remove this.
