<?php

final class ShowHelper implements IAdminModule
{
    /**
     * Helper properties for variables passed to constructor enforced by IAdminModule interface
     */
    /// Database helper
    private $DBH;
    /// Authentication helper (typically for determining whether user has privileges to perform operation)
    private $AUTH;
    /// InputFilter - helps work with user input (or other not secure strings)
    private $FILTER;


    const PRODUCT_DEFAULT_URL = "HTTP_DEFAULT_URL_FILLIN";


    private $postBackInfo = "";
        public function getPostBackInfo()
        {
            return $this->postBackInfo;
        }


    /**
     * Constructor (enforced by IAdminModule interface)
     * @param $DBDriver MySQLDriver object
     * @param $Auth Auth object
     * @param $IF InputFilter object
     */
    public function __construct($DBDriver, $Auth, $IF)
    {
        // Assigning references to internal properties
        $this->DBH      = $DBDriver;
        $this->AUTH     = $Auth;
        $this->FILTER   = $IF;
    }


    /**
     * Gets module version (enforced by IAdminModule interface)
     * @return float Module version
     */
    public function _getModuleVersion()
    {
        return 0.1;
    }


    /**
     * Gets name of the author of the module (enforced by interface)
     * @return string
     */
    public function _getModuleAuthor()
    {
        return "Daniel Dušek &lt;dusekdan@gmail.com&gt;";
    }


    /**
     * Returns HTML table containing full description of the module (enforced by interface)
     * @return string
     */
    public function _getModuleDescription()
    {
        // Tabs and line breaks are used for formatting resulting in readable HTML code
        $description = "<table>" . PHP_EOL;
        $description .= "\t<tr>" . PHP_EOL;
        $description .= "\t\t<td>Module name:</td>" . PHP_EOL . "\t\t<td>Product/Supplier add</td>" . PHP_EOL;
        $description .= "\t</tr>" . PHP_EOL;
        $description .= "\t<tr>" . PHP_EOL;
        $description .= "\t\t<td>Author:</td>" . PHP_EOL . "\t\t<td>" .   $this->_getModuleAuthor() . "</td>" . PHP_EOL;
        $description .= "\t</tr>" . PHP_EOL;
        $description .= "\t<tr>" . PHP_EOL;
        $description .= "\t\t<td>Version:</td>" . PHP_EOL . "\t\t<td>" .   $this->_getModuleVersion() . "</td>" . PHP_EOL;
        $description .= "\t</tr>" . PHP_EOL;
        $description .= "\t<tr>" . PHP_EOL;
        $description .= "\t\t<td>Description:</td>" . PHP_EOL . "\t\t<td>This module allows user (employee) to add products to the system as well as suppliers.</td>" . PHP_EOL;
        $description .= "\t</tr>" . PHP_EOL;
        $description .= "</table>" . PHP_EOL;

        return $description;
    }

    public function loadCategoryList()
    {
        $selectQuery = $this->DBH->query("SELECT pcat_id, pcat_name FROM product_category ORDER BY pcat_name ASC");


        echo "<table>";
        echo "<tr>";
        echo "<th>#</th>";
        echo "<th>Název kategorie</th>";
        echo "</tr>";

        $i = 1;
        while($r = mysql_fetch_assoc($selectQuery))
        {
            ?>

            <tr>
                <td><?php echo $i; ?></td>
                <td><?php echo $this->FILTER->prepareText($r["pcat_name"]); ?></td>
                <td><form method="post" action=""><input type="hidden" name="deleteCategory" value="<?php echo $this->FILTER->prepareInputForSQL($r["pcat_id"]); ?>"><input onclick="return confirm('Opravdu chcete smazat tuto kategorii?');" type="submit" value="Smazat"></form></td>
                <td><a href="Admin.php?action=Show&amp;edittype=category&amp;edit=<?php echo $this->FILTER->prepareText($r["pcat_id"]); ?>">Editovat</a></td>
            </tr>

            <?php
            $i++;
        }
        echo "</table>";
    }

    public function deleteCategory($id)
    {
        $id = $this->FILTER->prepareInputForSQL($id);

        $categorySubcategories = $this->DBH->fetch("SELECT psub_id FROM product_subcategory WHERE psub_category = '$id'");
            if(!empty($categorySubcategories))
            {
                $this->postBackInfo = "Kategorii není možné smazat. Stále existují podkategorie náležící do této kategorie!";
                return false;
            }

        $deleteCategoryResult = $this->deleteCategoryQuery($id);
            if(!$deleteCategoryResult)
            {
                $this->postBackInfo = "Kategorii se nepodařilo smazat (vnitřní chyba).";
            }

        return $deleteCategoryResult;
    }

    private function deleteCategoryQuery($id)
    {
        $deleteQuery = $this->DBH->query("DELETE FROM product_category WHERE pcat_id = '$id'");

        if($deleteQuery === -1)
        {
            return false;
        }
        else
        {
            return true;
        }
    }


    public function loadSupplierList()
    {

        $selectQuery = $this->DBH->query("SELECT sup_id, sup_name, sup_ico, sup_enabled FROM supplier ORDER BY sup_name ASC");

        echo "<table>";
        echo "<tr>";
        echo "<th>#</th>";
        echo "<th>Jméno dodavatele</th>";
        echo "<th>IČO</th>";
        echo "<th>Aktivní</th>";
        echo "</tr>";

        $i = 1;
        while($r = mysql_fetch_assoc($selectQuery))
        {
            ?>
            <tr>
                <td><?php echo $i;                  ?></td>
                <td><?php echo $this->FILTER->prepareText($r["sup_name"]);      ?></td>
                <td><?php echo $this->FILTER->prepareText($r["sup_ico"]);       ?></td>
                <td><?php echo $this->FILTER->prepareText($r["sup_enabled"]);   ?></td>
                <td><form method="post" action=""><input type="hidden" name="deleteSupplier" value="<?php echo $this->FILTER->prepareInputForSQL($r["sup_id"]); ?>"><input onclick="return confirm('Opravdu chcete tohoto dodavatele smazat?');" type="submit" value="Smazat"></form></td>
                <td><a href="Admin.php?action=Show&amp;edittype=supplier&amp;edit=<?php echo $this->FILTER->prepareText($r["sup_id"]); ?>">Editovat</a></td>
            </tr>
            <?php
            $i++;
        }

        echo "</table>";

    }

    public function deleteSupplier($id)
    {
        $id = $this->FILTER->prepareInputForSQL($id);

        // Check whether supplier is still supplying with some products
        $selectSupplierProducts = $this->DBH->fetch("SELECT pr_id FROM product WHERE pr_supplier = '$id'");
            if(!empty($selectSupplierProducts))
            {
                $this->postBackInfo = "Dodavatele nebylo možné smazat - stále existují výrobky, které jsou tímto dodavatelem dodávány.";
                return false;
            }

        $deleteSupplierResult =  $this->deleteSupplierQuery($id);
            if(!$deleteSupplierResult)
            {
                $this->postBackInfo = "Nepodařilo se smazat dodavatele z databáze (vnitřní chyba).";
            }

        return $deleteSupplierResult;
    }

    private function deleteSupplierQuery($id)
    {
        $deleteQuery = $this->DBH->query("DELETE FROM supplier WHERE sup_id = '$id'");

        if($deleteQuery === -1)
        {
            return false;
        }
        else
        {
            return true;
        }
    }

    public function loadSubcategoryList()
    {
        $selectQuery = $this->DBH->query("SELECT psub_id, psub_name, psub_category, pcat_name FROM product_category JOIN product_subcategory ON pcat_id=psub_category ORDER BY psub_name ASC");

        echo "<table>";
        echo "<tr>";
        echo "<th>#</th>";
        echo "<th>Název podkategorie</th>";
        echo "<th>Název nadřazené kategorie</th>";
        echo "</tr>";

        $i = 1;
        while($r = mysql_fetch_assoc($selectQuery))
        {
            ?>
            <tr>
                <td><?php echo $i; ?></td>
                <td><?php echo $this->FILTER->prepareText($r["psub_name"]); ?></td>
                <td><?php echo $this->FILTER->prepareText($r["pcat_name"]); ?></td>
                <td><form method="post" action=""><input type="hidden" name="deleteSubcategory" value="<?php echo $this->FILTER->prepareInputForSQL($r["psub_id"]); ?>"><input onclick="return confirm('Opravdu chcete tuto podkategorii smazat?\n \n');" type="submit" value="Smazat"></form></td>
                <td><a href="Admin.php?action=Show&amp;edittype=subcategory&amp;edit=<?php echo $this->FILTER->prepareText($r["psub_id"]); ?>">Editovat</a></td>
            </tr>
            <?php
            $i++;
        }

        echo "</table>";
    }

    public function deleteSubcategory($id)
    {
        $id = $this->FILTER->prepareInputForSQL($id);

        $selectSubcategoryProducts = $this->DBH->fetch("SELECT pr_id FROM product WHERE pr_subcategory = '$id'");
            if(!empty($selectSubcategoryProducts))
            {
                $this->postBackInfo = "Podkategorie nemůže být smazána. Stále existují produkty, které do ní spadají.";
                return false;
            }

        $deleteSubcategoryResult = $this->deleteSubcategoryQuery($id);
            if(!$deleteSubcategoryResult)
            {
                $this->postBackInfo  = "Podkategorie nemohla být smazána (vnitřní chyba).";
            }

        return $deleteSubcategoryResult;
    }

    private function deleteSubcategoryQuery($id)
    {
        $deleteQuery = $this->DBH->query("DELETE FROM product_subcategory WHERE psub_id = '$id' ");

        if($deleteQuery === -1)
        {
            return false;
        }
        else
        {
            return true;
        }
    }

    /// TODO: Allow user to view only products in some specific (sub)category

    public function loadProductList()
    {
        $selectQuery = $this->DBH->query("SELECT pr_name, pr_id, pcat_name, psub_name FROM product JOIN product_subcategory ON psub_id = pr_subcategory JOIN product_category ON psub_category = pcat_id");

        echo "<table>";
        echo "<tr>";
        echo "<th>#</th>";
        echo "<th>Název produktu</th>";
        echo "<th>Název kategorie</th>";
        echo "<th>Název podkategorie</th>";

        $i = 1;
        while($r = mysql_fetch_assoc($selectQuery))
        {
            ?>

            <tr>
                <td><?php echo $i;?></td>
                <td><?php echo $this->FILTER->prepareText($r["pr_name"]);?></td>
                <td><?php echo $this->FILTER->prepareText($r["pcat_name"]);?></td>
                <td><?php echo $this->FILTER->prepareText($r["psub_name"]);?></td>
                <td><form method="post" action=""><input type="hidden" name="deleteProduct" value="<?php echo $this->FILTER->prepareInputForSQL($r["pr_id"]); ?>"><input onclick="return confirm('Opravdu chcete smazat tento produkt?');" type="submit" value="Smazat"></form></td>
                <td><a href="Admin.php?action=Show&amp;edittype=product&amp;edit=<?php echo $this->FILTER->prepareText($r["pr_id"]); ?>">Editovat</a></td>
            </tr>

            <?php
            $i++;
        }

        echo "</table>";

    }

    public function deleteProduct($id)
    {
        $id = $this->FILTER->prepareInputForSQL($id);

        $deleteProductResult = $this->deleteProductQuery($id);
            if(!$deleteProductResult)
            {
                $this->postBackInfo = "Produkt se nepodařilo smazat (vnitřní chyba).";
            }
        return $deleteProductResult;
    }

    private function deleteProductQuery($id)
    {
        $deleteQuery = $this->DBH->query("DELETE FROM product WHERE pr_id = '$id' ");

        if($deleteQuery === -1)
        {
            return false;
        }
        else
        {
            return true;
        }
    }



    // Data for edited record are stored here
    private $temporaryDataBuffer;

    private function returnEditPostbackValue($index, $columnName)
    {
        if(isset($_POST[$index]))
        {
            echo " value='" . $_POST[$index] . "' ";
        }
        else
        {
            // Use data for the edited record
            echo " value='" . $this->temporaryDataBuffer[$columnName] . "' ";
        }
    }

    private function returnEditPostbackValuePlain($index, $columnName)
    {
        if(isset($_POST[$index]))
        {
            echo $_POST[$index];
        }
        else
        {
            // Use data for the edited record
            echo $this->temporaryDataBuffer[$columnName];
        }
    }







    #region "Editation"

    public function editCategory()
    {
        // Reseting postbackinformation
        $this->postBackInfo = "";

        // Simplication of $_POST
        $p = $_POST;

        // Simplification of $_GET["edit"]
        $catId = $_GET["edit"];

        // Basic variables init
        $editRecord = true;
        $errorMessage = "";

        // Data initialization & securing
        if(isset($p["categoryName"], $p["categoryDescription"]))
        {
            // Secure data
            $categoryName = $this->FILTER->prepareInputForSQL($p["categoryName"]);
            $categoryDescription = $this->FILTER->prepareInputForSQL($p["categoryDescription"]);
            $catId = $this->FILTER->prepareInputForSQL($catId);

            if(empty($categoryName))
            {
                $editRecord = false;
                $errorMessage .= "Jméno kategorie nesmí být prázdné! <br>";
            }

            if(empty($categoryDescription))
            {
                $editRecord = false;
                $errorMessage .= "Popisek kategorie nesmí být prázdný! <br>";
            }

            if($editRecord)
            {
                return $this->editCategoryQuery($categoryName, $categoryDescription, $catId);
            }
            else
            {
                $this->postBackInfo = $errorMessage;
                return false;
            }
        }
        else
        {
            $this->postBackInfo = "Obdržena nedostatečná POST data!";
            return false;
        }
    }

    public function editProduct()
    {
        // Reseting postbackinformation
        $this->postBackInfo = "";

        // Simplication of $_POST
        $p = $_POST;

        // Simplification of $_GET["edit"]
        $prId = $_GET["edit"];

        // Basic variables init
        $editRecord = true;
        $errorMessage = "";

        // Data initialization & securing
        if(isset($p["productName"], $p["productUrl"], $p["productPrice"], $p["productInitialStock"], $p["productSupplier"], $p["productSubcategory"], $p["productDescription"]))
        {
            // Secure data
            $productName = $this->FILTER->prepareInputForSQL($p["productName"]);
            $productUrl = $this->FILTER->prepareInputForSQL($p["productUrl"]);
            $productPrice = $this->FILTER->prepareInputForSQL($p["productPrice"]);
            $productInitialStock = $this->FILTER->prepareInputForSQL($p["productInitialStock"]);
            $productSupplier = $this->FILTER->prepareInputForSQL($p["productSupplier"]);
            $productCategory = $this->FILTER->prepareInputForSQL($p["productSubcategory"]);
            $productDescription = $this->FILTER->prepareInputForSQL($p["productDescription"]);
            $prId        =  $this->FILTER->prepareInputForSQL($prId);

            if(empty($productName))
            {
                $editRecord = false;
                $errorMessage .= "Jméno produktu nesmí být prázdné! <br>";
            }

            if(empty($productSupplier))
            {
                $editRecord = false;
                $errorMessage .= "Není možné přidat produkt bez dodavatele! <br>";
            }

            if(empty($productDescription))
            {
                $editRecord = false;
                $errorMessage .= "Produkt musí mít popisek! <br>";
            }

            if(empty($productInitialStock))
            {
                $productInitialStock = 0;
            }

            if(empty($productPrice))
            {
                $editRecord = false;
                $errorMessage .= "Produkt musí mít nastavenou cenu! <br>";
            }

            if(empty($productUrl))
            {
                $productUrl = self::PRODUCT_DEFAULT_URL;
            }

            if($editRecord)
            {
                return $this->editProductQuery($productName, $productUrl, $productPrice, $productInitialStock, $productSupplier, $productCategory, $productDescription, $prId);
            }
            else
            {
                $this->postBackInfo = $errorMessage;
                return false;
            }

        }
        else
        {
            $errorMessage = "Obdržena nedostatečná POST data!";
            $this->postBackInfo = $errorMessage;
            return false;
        }
    }

    public function editSubcategory()
    {
        // Reseting postbackinformation
        $this->postBackInfo = "";

        // Simplication of $_POST
        $p = $_POST;

        // Simplification of $_GET["edit"]
        $psubId = $_GET["edit"];

        // Basic variables init
        $editRecord = true;
        $errorMessage = "";

        // Data initialization & securing
        if(isset($p["subcategoryName"], $p["subcategoryCategory"], $p["subcategoryDescription"]))
        {
            // Secure data
            $subcategoryName = $this->FILTER->prepareInputForSQL($p["subcategoryName"]);
            $subcategoryCategory = $this->FILTER->prepareInputForSQL($p["subcategoryCategory"]);
            $subcategoryDescription = $this->FILTER->prepareInputForSQL($p["subcategoryDescription"]);
            $psubId = $this->FILTER->prepareInputForSQL($psubId);

            if(empty($subcategoryName))
            {
                $errorMessage .= "Název podkategorie musí být vyplněn!";
                $editRecord = false;
            }

            if(empty($subcategoryCategory))
            {
                $errorMessage .= "Nadřazená kategorie musí být vyplněna!";
                $editRecord = false;
            }

            if(empty($subcategoryDescription))
            {
                $errorMessage .= "Popisek podkategorie musí být vyplněn!";
                $editRecord = false;
            }

            if($editRecord)
            {
                return $this->editSubcategoryQuery($subcategoryName, $subcategoryCategory, $subcategoryDescription, $psubId);
            }
            else
            {
                $this->postBackInfo = $errorMessage;
                return false;
            }

        }
        else
        {
            $errorMessage = "Obdržena nedostatečná POST data!";
            $this->postBackInfo = $errorMessage;
            return false;
        }
    }

    public function editSupplier()
    {
        // Reseting postbackinformation
        $this->postBackInfo = "";

        // Simplication of $_POST
        $p = $_POST;

        // Simplification of $_GET["edit"]
        $supId = $_GET["edit"];

        // Basic variables init
        $editRecord = true;
        $errorMessage = "";

        // Data initialization & securing
        if(isset($p["supplierName"], $p["supplierMail"], $p["supplierPhone"], $p["supplierResupplytime"], $p["supplierICO"], $p["supplierAddress"]))
        {

            $supplierName = $this->FILTER->prepareInputForSQL($p["supplierName"]);
            $supplierMail = $this->FILTER->prepareInputForSQL($p["supplierMail"]);
            $supplierPhone = $this->FILTER->prepareInputForSQL($p["supplierPhone"]);
            $supplierResupplytime = $this->FILTER->prepareInputForSQL($p["supplierResupplytime"]);
            $supplierICO = $this->FILTER->prepareInputForSQL($p["supplierICO"]);
            $supplierAddress = $this->FILTER->prepareInputForSQL($p["supplierAddress"]);
            $supId = $this->FILTER->prepareInputForSQL($supId);

            if(empty($supplierName))
            {
                $errorMessage .= "Název dodavatele musí být vyplněn!";
                $editRecord = false;
            }

            if(empty($supplierMail))
            {
                $errorMessage .= "Email dodavatele musí být vyplněn!";
                $editRecord = false;
            }

            if(empty($supplierPhone))
            {
                $errorMessage .= "Telefon dodavatele musí být vyplněn!";
                $editRecord = false;
            }

            if(!is_numeric($supplierResupplytime))
            {
                $errorMessage .= "Doba znovuobnovení musí být číslo!";
                $editRecord = false;
            }

            if(empty($supplierICO))
            {
                $errorMessage .= "IČO dodavatele musí být vyplněn!";
                $editRecord = false;
            }

            if(empty($supplierAddress))
            {
                $errorMessage .= "Adresa dodavatele musí být vyplněn!";
                $editRecord = false;
            }


            if($editRecord)
            {
                $editRecordResult = $this->editSupplierQuery($supplierName, $supplierMail, $supplierPhone, $supplierResupplytime, $supplierICO, $supplierAddress, $supId);
                if(!$editRecordResult)
                {
                    $this->postBackInfo = "Nepodařilo se upravit dodavatele! (vnitřní chyba)";
                }

                return $editRecordResult;
            }
            else
            {
                $this->postBackInfo = $errorMessage;
                return false;
            }

        }
        else
        {
            $errorMessage .= "Obdržena nedostatečná POST data!";
            $this->postBackInfo = $errorMessage;
            return false;
        }
    }

    private function editCategoryQuery($categoryName, $categoryDescription, $catId)
    {
        $updateQuery = $this->DBH
                            ->query("UPDATE product_category SET pcat_name='$categoryName', pcat_description='$categoryDescription' WHERE pcat_id='$catId'");

        if($updateQuery === -1)
        {
            return false;
        }
        else
        {
            return true;
        }
    }

    private function editProductQuery($productName, $productUrl, $productPrice, $productInitialStock, $productSupplier, $productCategory, $productDescription, $prId)
    {

        $updateQueryAddition = "";
        if($productInitialStock == 0)
        {
            $updateQueryAddition = "pr_available = 'false',";
        }
        else
        {
            $updateQueryAddition = "pr_available = 'true',";
        }

        $updateQuery = $this->DBH->query("UPDATE product SET
                        pr_name = '$productName',
                        pr_imageurl = '$productUrl',
                        pr_price = '$productPrice',
                        pr_quantity = '$productInitialStock',
                        pr_supplier = '$productSupplier',
                        pr_subcategory = '$productCategory',
                        $updateQueryAddition
                        pr_description = '$productDescription'
                        WHERE pr_id = '$prId'");

        if($updateQuery === -1)
        {
            return false;
        }
        else
        {
            return true;
        }

    }

    private function editSubcategoryQuery($subcategoryName, $subcategoryCategory, $subcategoryDescription, $psubId)
    {
        $updateQuery = $this->DBH->query("UPDATE product_subcategory SET
                                          psub_name = '$subcategoryName',
                                          psub_category = '$subcategoryCategory',
                                          psub_description = '$subcategoryDescription'
                                          WHERE psub_id = '$psubId'");

        if($updateQuery === -1)
        {
            return false;
        }
        else
        {
            return true;
        }
    }

    private function editSupplierQuery($supplierName, $supplierMail, $supplierPhone, $supplierResupplytime, $supplierICO, $supplierAddress, $supId)
    {
        $updateQuery = $this->DBH->query("UPDATE supplier SET
                                          sup_name='$supplierName',
                                          sup_mail='$supplierMail',
                                          sup_phone='$supplierPhone',
                                          sup_resupplytime='$supplierResupplytime',
                                          sup_ico='$supplierICO',
                                          sup_address='$supplierAddress'
                                          WHERE sup_id='$supId'");

        if($updateQuery === -1)
        {
            return false;
        }
        else
        {
            return true;
        }
    }

    public function loadCategoryEditForm($categoryId)
    {
        $timeStamp = time();
        $_SESSION["formGenerationStamp"] = $timeStamp;

        $categoryId = $this->FILTER->prepareInputForSQL($categoryId);
        $this->temporaryDataBuffer = $this->DBH->fetch("SELECT * FROM product_category WHERE pcat_id = '$categoryId'");
        ?>
<div class="form">
    <div class="form_content">
        <form action="" method="post">
            <table>

                    <tr>
                        <td>Jméno kategorie:</td>
                        <td><input type="text" class="text" name="categoryName"<?php $this->returnEditPostBackValue("categoryName", "pcat_name"); ?>></td>
                        <td><input type="hidden" value="<?php echo $timeStamp;?>" name="formGenerationStamp"></td>
                    </tr>

                    <tr>
                        <td>Popisek kategorie:</td>
                        <td></td>
                    </tr>

                    <tr>
                        <td colspan="2"><textarea name="categoryDescription"><?php $this->returnEditPostBackValuePlain("categoryDescription", "pcat_description"); ?></textarea></td>
                    </tr>

                    <tr>
                        <td></td>
                        <td><input type="submit" value="Upravit kategorii"></td>
                    </tr>
            </table>
        </form>
    </div>
</div>
        <?php
    }

    public function loadProductEditForm($productId)
    {
        $productId = $this->FILTER->prepareInputForSQL($productId);
        $this->temporaryDataBuffer = $this->DBH->fetch("SELECT * FROM product WHERE pr_id = '$productId'");

        // Double postback prevention
        $timeStamp = time();
        $_SESSION["formGenerationStamp"] = $timeStamp;
        ?>

        <div class="form">
            <div class="form_content">
                <form action="" method="post">
                    <table>
                        <tr>
                            <td>Název nového produktu</td>
                            <td><input class="text" type="text" name="productName"<?php $this->returnEditPostBackValue("productName", "pr_name"); ?>></td>
                            <td><input type="hidden" name="formGenerationStamp" value="<?php echo $timeStamp; ?>"></td>
                        </tr>

                        <tr>
                            <td>Ceseta k náhledovému obrázku</td>
                            <td><input class="text" type="text" name="productUrl"<?php $this->returnEditPostBackValue("productUrl", "pr_imageurl");?>></td>
                        </tr>

                        <tr>
                            <td>Cena</td>
                            <td><input class="text" type="text" name="productPrice"<?php $this->returnEditPostBackValue("productPrice", "pr_price");?>></td>
                        </tr>

                        <tr>
                            <td>Počáteční počet:</td>
                            <td><input class="text" type="text" name="productInitialStock"<?php $this->returnEditPostBackValue("productInitialStock", "pr_quantity");?>></td>
                        </tr>

                        <tr>
                            <td>Dodavatel</td>
                            <td>
                                <select name="productSupplier">
                                    <?php
                                    $this->loadSupplierSelectionOptions();
                                    ?>
                                </select>
                            </td>
                        </tr>

                        <tr>
                            <td>Kategorie produktu</td>
                            <td>
                                <select name="productSubcategory">
                                    <?php
                                    $this->loadProductCategoryOptions();
                                    ?>
                                </select>
                            </td>
                        </tr>

                        <tr>
                            <td>Popis produktu</td>
                            <td></td>
                        </tr>

                        <tr>
                            <td colspan="2">
                                <textarea name="productDescription" rows="3"><?php echo $this->returnEditPostbackValuePlain("productDescription", "pr_description"); ?></textarea>
                            </td>
                        </tr>

                        <tr>
                            <td></td>
                            <td><input type="submit" value="Upravit produkt"></td>
                        </tr>
                    </table>
                </form>
            </div>

        </div>

        <?php
    }

    public function loadSubcategoryEditForm($subcategoryId)
    {
        $subcategoryId = $this->FILTER->prepareInputForSQL($subcategoryId);

        $this->temporaryDataBuffer = $this->DBH->fetch("SELECT * FROM product_subcategory WHERE psub_id = '$subcategoryId'");

        // Double postback prevention
        $timeStamp = time();
        $_SESSION["formGenerationStamp"] = $timeStamp;
        ?>
        <div class="form">
            <div class="form_content">
                <form action="" method="post">
                    <table>

                        <tr>
                            <td>Jméno podkategorie:</td>
                            <td><input type="text" class="text" name="subcategoryName" <?php $this->returnEditPostbackValue("subcategoryName", "psub_name"); ?>></td>
                            <td><input type="hidden" value="<?php echo $timeStamp;?>" name="formGenerationStamp"></td>
                        </tr>

                        <tr>
                            <td>Nadřazená kategorie:</td>
                            <td><select name="subcategoryCategory">
                                    <?php
                                    $this->loadSubcategoryCategoryOptions();
                                    ?>
                                </select>
                            </td>
                        </tr>

                        <tr>
                            <td>Popisek podkategorie:</td>
                            <td></td>
                        </tr>

                        <tr>
                            <td colspan="2"><textarea name="subcategoryDescription"><?php $this->returnEditPostbackValuePlain("subcategoryDescription", "psub_description"); ?></textarea></td>
                        </tr>

                        <tr>
                            <td></td>
                            <td><input type="submit" value="Přidat kategorii"></td>
                        </tr>
                    </table>
                </form>
            </div>
        </div>

        <?php
    }

    public function loadSupplierEditForm($supplierId)
    {
        $supplierId = $this->FILTER->prepareInputForSQL($supplierId);

        $this->temporaryDataBuffer = $this->DBH->fetch("SELECT * FROM supplier WHERE sup_id = '$supplierId'");

        // Double postback prevention
        $timeStamp = time();
        $_SESSION["formGenerationStamp"] = $timeStamp;
        ?>
        <div class="form">
            <div class="form_content">
                <form action="" method="post">
                    <table>
                        <tr>
                            <td>Název dodavatele</td>
                            <td><input class="text" type="text" name="supplierName" <?php $this->returnEditPostbackValue("supplierName", "sup_name"); ?>></td>
                            <td><input type="hidden" value="<?php echo $timeStamp;?>" name="formGenerationStamp"></td>
                        </tr>

                        <tr>
                            <td>Mail dodavatele:</td>
                            <td><input class="text" type="text" name="supplierMail" <?php $this->returnEditPostbackValue("supplierMail", "sup_mail"); ?>></td>
                        </tr>

                        <tr>
                            <td>Telefon:</td>
                            <td><input class="text" type="text" name="supplierPhone" <?php $this->returnEditPostbackValue("supplierPhone", "sup_phone"); ?>></td>
                        </tr>

                        <tr>
                            <td>Dostupnost zboží</td>
                            <td><input class="text" type="text" name="supplierResupplytime" <?php $this->returnEditPostbackValue("supplierResupplytime", "sup_resupplytime"); ?>></td>
                            <td>(doba ve dnech)</td>
                        </tr>

                        <tr>
                            <td>IČO</td>
                            <td><input class="text" type="text" name="supplierICO" <?php $this->returnEditPostbackValue("supplierICO", "sup_ico"); ?>></td>
                        </tr>

                        <tr>
                            <td>Adresa</td>
                            <td></td>
                        </tr>

                        <tr>
                            <td colspan="2">
                                <textarea name="supplierAddress" rows="3"><?php $this->returnEditPostbackValuePlain("supplierAddress", "sup_address"); ?></textarea>
                            </td>
                        </tr>

                        <tr>
                            <td></td>
                            <td><input type="submit" value="Upravit dodavatele"></td>
                        </tr>
                    </table>
                </form>
            </div>

        </div>

        <?php
    }



    // Selection options load methods

    private function loadProductCategoryOptions()
    {
        $selectCategoryQueries = $this->DBH->query("SELECT pcat_name, pcat_id FROM product_category ORDER BY pcat_name");

        while($r = mysql_fetch_assoc($selectCategoryQueries))
        {

            echo "<option disabled='disabled' value='$r[pcat_id]'>$r[pcat_name]</option>";

            $selectSubcategories = $this->DBH->query("SELECT psub_name, psub_id FROM product_subcategory WHERE psub_category = '".$r["pcat_id"]."'");
            while($sc = mysql_fetch_assoc($selectSubcategories))
            {
                echo "<option value='$sc[psub_id]'>&nbsp;&nbsp;$sc[psub_name]</option>";
            }
        }
    }

    private function loadSubcategoryCategoryOptions()
    {
        $selectQuery = $this->DBH->query("SELECT pcat_id, pcat_name FROM product_category ORDER BY pcat_name ASC");

        while($r = mysql_fetch_assoc($selectQuery))
        {
            echo "<option value='$r[pcat_id]'>" . $this->FILTER->prepareText($r["pcat_name"]) . "</option>" . PHP_EOL;
        }
    }

    private function loadSupplierSelectionOptions()
    {
        $selectQuery = $this->DBH->query("SELECT sup_name, sup_id FROM supplier ORDER BY sup_name ASC");

        while($r = mysql_fetch_assoc($selectQuery))
        {
            echo "<option value='$r[sup_id]'>" . $this->FILTER->prepareText($r["sup_name"]) . "</option>" . PHP_EOL;
        }
    }

}